<?php

use phpSweetPDO\SQLHelpers\Basic as Helpers;

Class Invoice extends Model
{
    public $client_id; //todo: resolve with client object
    public $project_id;
    public $recurring_invoice_id;
    public $estimate_id;
    public $number;
    public $date;
    public $subtotal;
    public $tax;
    public $total;
    public $payments;
    public $balance;
    public $due_date;
    public $date_sent;
    public $num_times_sent;
    public $status_text;
    public $is_overdue;
    public $is_paid;

    //params not saved to the db

    public $recurring_next_invoice_date;
    public $recurring_profile_name;
    public $recurring_start_date;
    public $recurring_end_date;
    public $recurring_every;
    public $recurring_frequency;
    public $recurring_payment_terms;

    //the client side will calculate taxes when an invoice is being dynamically updated. We don't want to save that info
    public $taxes;

    //the server will re calculate all taxes based on the invoice_items data
    public $calculated_taxes;

    //for display
    public $formatted_total;
    public $formatted_due_date;

    //the company object
    public $company;

    //the client object
    public $client;

    //array of InvoiceItems
    public $invoice_items;

    protected $is_auto_generated;

    function validate(){
        $this->validator_tests = array(
            'client_id' => 'required',
            'number' => 'required',
            'date' => 'required',
            'due_date' => 'required'
        );

        return parent::validate();
    }

    function is_auto_generated(){
        //this function needs to be called immediately before EVERY save, because it will be cleared by each call to SAVE
        //since it's not stored in the dabase. We set this flag to let the save funciton know it doesn't need to worry about
        //whether the user is logged in
        $this->set('is_auto_generated', true);
    }

    function get_is_auto_generated(){
        return $this->is_auto_generated;
    }

    function base_select_query(){
        return "SELECT invoices.*, clients.name AS client_name, projects.name AS project_name,
                  recurring_invoices.id AS recurring_invoice_id,
                  recurring_invoices.next_invoice_date AS recurring_next_invoice_date,
                  recurring_invoices.profile_name AS recurring_profile_name,
                  recurring_invoices.start_date AS recurring_start_date,
                  recurring_invoices.end_date AS recurring_end_date,
                  recurring_invoices.every AS recurring_every,
                  recurring_invoices.unit_of_time AS recurring_interval,
                  recurring_invoices.payment_terms AS recurring_payment_terms
                FROM invoices
                LEFT JOIN clients ON invoices.client_id = clients.id
                LEFT JOIN projects ON invoices.project_id = projects.id
                LEFT JOIN recurring_invoices ON invoices.recurring_invoice_id = recurring_invoices.id";
    }

    function where_clause_sql_for_individual_entity($criteria){
        return ' WHERE invoices.id = ' . $criteria;
    }

    function get_items($id){
        return $this->load('InvoiceItem')->get($id);
    }

    function get_taxes($id){
        $sql = "SELECT * FROM invoice_tax_totals WHERE invoice_id = $id";
        return $this->select($sql);
    }

    function get($criteria = null){
        $sql = $this->base_select_query();

        if (is_numeric($criteria)) {
            $sql .= $this->where_clause_sql_for_individual_entity($criteria);

            $invoice = parent::get_one($sql);

            global $CONFIG;
            $invoice['company'] = $CONFIG['company'];


            $invoice['client'] = $this->load('Client')->get($invoice['client_id']);
            $invoice['invoice_items'] = $this->get_items($invoice['id']); //todo: if i were really using oop I would use invoice->id;
            $invoice['taxes'] = $this->get_taxes($invoice['id']);

            $this->import_parameters($invoice);
            $this->update_status();
            //$invoice['status'] = $this->status;

            return $invoice;
        } else {
            $sql .= isset($criteria) ? " $criteria" : '';
            $sql = $this->modify_sql_for_user_type($sql);

            $invoices = parent::get($sql);

            //we currently have an array of invoices where each invoice is an array itself. We need to update the status
            //of each invoice, which means creating an invoice object for each invoice array item
            foreach($invoices as &$invoice){
                //create the invoice
                $invoice_object = new Invoice($invoice);
//todo: i don't think this makes any sense because the invoice status will already be updated when I create the invoice using new Invoice

               //update the invoice status
                $invoice_object->update_status();

                //we need to add the status text back the invoice array, since we're sending the array to the client,
                //not the object
                $invoice['status_text'] = $invoice_object->status_text;
            }

            return $invoices;
        }
    }

    function set_client_id(){
        if (!isset($this->client_id)) {
            $project = new Project($this->project_id);
            $this->set('client_id', $project->client_id);
        }
    }

    function set_invoice_number(){
        //todo:if this is a new invoice, we can't rely on the invoice number set by the client because there could be conflicts if two people are creating an invoice at the same time
        if (!isset($this->number)) {
            $this->set('number', $this->get_default_invoice_number());
        }
    }

    function sql_to_get_max_invoice_number(){
        return "SELECT MAX(CAST(number AS signed))FROM invoices";
    }

    function get_default_invoice_number(){
        $number = null;
        $result = $this->select($this->sql_to_get_max_invoice_number());

        if (is_null($result[0]['MAX(CAST(number AS signed))']))
            $number = $GLOBALS['CONFIG']['invoice']['base_invoice_number']; //todo:this isn't in the config
        else $number = $result[0]['MAX(CAST(number AS signed))'] + 1;

        return $number;
    }

    function set_invoice_dates(){
        $dates = $this->get_default_dates();

        if(!isset($this->date))
            $this->set('date', $dates['date']);

        if(!isset($this->due_date))
            $this->set('due_date', $dates['due_date']);
    }

    function get_default_dates(){
        $hour = 12;
        $today = strtotime($hour . ':00:00');

        $date = $today;
        $due_date = $today;

        return array(
            'date' => $date,
            'due_date' => $due_date
        );
    }

    function get_defaults(){
        $clients = array();

        $number = $this->get_default_invoice_number();
        $dates = $this->get_default_dates();

        $project_id = Request::get('project_id');

        if(isset($project_id)){
            $project = new Project($project_id);
            $client = new Client($project->client_id);
        }
        else{
            $client = null;
            $clients = $this->load('Client')->get();
        }

        return array(
            'number' => $number,
            'date' => $dates['date'],
            'due_date' => $dates['due_date'],
            'status_text' => Language::get('invoice.status_inactive'),
            'client' => $client,
            'clients' => $clients
        );
    }

    function save($record_activity = true){

        //if the invoice is auto generated, then we don't need to check the current user, because there will be no
        //current user. We allow auto genrated invoices to save regardless of whether there's a logged in user
        //this is for recurring invoices that are generated by the system
        //todo: why do we care about current_user access in the model? access should have already been verified by the controller?
        $current_user = current_user();

        if($current_user !== false && !$current_user->is('admin')){
            return false;
        }
//        if($this->is_auto_generated !== true && Request::is_public()){
//            if(!current_user()->is('admin'))
//                return false;
//        }


        //we need to import the parameter that we're trying to save, in some cases this will be all of the invoice parameters,
        //but in others it will just be the project id. If it's just the project id, we will need to set the other
        //required parameters to their defaults
        $this->import_parameters();
        $this->set_client_id();
        $this->set_invoice_number();
        $this->set_invoice_dates();

        $this->validate();
        $item_result = true;


        $this->unset_params_not_saved_to_db();

        //let's store is_new status in a variable because it will change once we call save
        $is_new = $this->is_new();

        if($is_new == true){
            parent::save();
        }
        //todo:currently, if you p
        //if there were no errors saving the base invoice, save each of the line items
        if (!$this->has_errors && is_array($this->invoice_items)) {

            foreach ($this->invoice_items as &$item) {
                //make sure the invoice item is tied to this invoice (just in case the invoice id isn't sent by the client)
                $invoice_item = new InvoiceItem($item);

                //let's just make sure the invoice item is correct, since we can't trust any of this client side data
                $invoice_item->set('invoice_id', $this->id);


                if (isset($invoice_item->task)) {
                    if(!$invoice_item->task instanceof Task){
                        $invoice_item->task = new Task($invoice_item->task);
                    }
                    //todo:does this still work?
                    $invoice_item->task->set('invoice_id', $this->id);
                    $invoice_item->task->save();
                }

                $item_result = $invoice_item->save();

                //calculate total is going to need the object
                $item = $invoice_item;
                //stop saving line items if there was an error saving this one
                if ($invoice_item->has_errors)
                    break;
            }
        }



        $this->calculate_total();


        $this->update_status(false);

        $taxes = $this->calculated_taxes;

        parent::save();

        $this->save_taxes($taxes);

        if($record_activity && $this->is_auto_generated !== true){
            if ($is_new){
                ActivityManager::invoice_created($this);
            }
            else ActivityManager::invoice_updated($this);


        }

        //return the result, true or false
        return $is_new ? $this->to_array() : ($item_result != false);
    }

    function unset_params_not_saved_to_db(){
        $this->unset_param('recurring_next_invoice_date');
        $this->unset_param('recurring_profile_name');
        $this->unset_param('recurring_start_date');
        $this->unset_param('recurring_end_date');
        $this->unset_param('recurring_every');
        $this->unset_param('recurring_frequency');
        $this->unset_param('recurring_payment_terms');
        $this->unset_param('formatted_total');
        $this->unset_param('formatted_due_date');
        $this->unset_param('recurring_profile_name');
        $this->unset_param('is_auto_generated');
        $this->unset_param('taxes');
        $this->unset_param('calculated_taxes');
        $this->unset_param('formatted_total');
    }

    function set_recurring_invoice_id($id){
        $this->unset_params_not_saved_to_db();
        $this->set('recurring_invoice_id', $id);
        parent::save();
    }

    function is_recurring_invoice(){
        return isset($this->recurring_invoice_id) && !empty($this->recurring_invoice_id) && $this->recurring_invoice_id !== 0;
    }

    function send(){
        $email = new AppEmail();

        //todo:This is a gross hack, but we need the status to say Sent, so the generated pdf has a status of sent.
        //there are two problems preventing this from working without the hack 1) THe status isn't actually
        //update until AFTER the invoice is sent. 2) The pdf function gets it's data from the POST variable for
        //some reason. Need to investigate if this is still the best way to generate PDFs
        if($_POST['statusText'] == 'Draft')
            $_POST['statusText'] = 'Sent';

        if(get_config('invoice.attach_pdf_to_emails') === true){
            $pdf = $this->pdf();
            $pdf_path = $this->pdf_path($pdf);
            $email->add_attachment($pdf_path, $pdf);
        }

        if($email->send_invoice($this)){
            //update the invoice with the most recent send date
            $this->clear_params(); //todo:I have no idea why the params array is even populated at this point, but
            $this->set('date_sent', time());
            $this->set('num_times_sent', intval($this->num_times_sent) + 1);
            $this->params_imported = true; //todo: i need to do something about this.

            call_user_func(array('Model', 'save'));
            return true;
        }
        else return $this->set_error('id', 'Error sending invoice');
    }

    function has_balance(){
        return $this->balance > 0;
    }

    function link(){
        $link = new Link();
        $link->get($this);
        return $link->url();
    }

    function valid_payment_amount($amount){
        $old_balance = $this->balance;
        //recalculate the invoice total, which will update the balance
        $this->calculate_total();


        //using parent::save, because there is no need for us to save the invoice items in this case
        if($old_balance != $this->balance){
            $this->unset_params_not_saved_to_db();
            parent::save();
        }

        if($this->has_balance() && (float)$this->balance >= (float)$amount)
            return true;
        else return false;
    }

    function process_item_taxes($item, $tax_total){
        foreach ($item->taxes as $id => $tax) {

            if (!isset($this->calculated_taxes[$tax['id']])) {
                $this->calculated_taxes[$tax['id']] = array(
                    'tax_name' => $tax['name'],
                    'tax_rate' => $tax['rate'],
                    'tax_id' => $tax['id'],
                    'amount' => 0
                );
            }

            $amount = (float)$tax['amount'];

            $this->calculated_taxes[$tax['id']]['amount'] += $amount;
            $tax_total += $amount;
        }

        return $tax_total;
    }

    function calculate_total($save_invoice = false){
        $subtotal = 0;
        $tax = 0;
        $total = 0;
        $payments = 0;
        $balance = 0;
        $tax_total = 0;

        $taxes = array();

        if(is_array($this->invoice_items)){
            //calculate the subtotal

            foreach ($this->invoice_items as $item) {

                $subtotal += $item->quantity * $item->rate;

                if(!($item instanceof InvoiceItem)){
                    $tax_ids = $item->tax_ids;
                    $item = new InvoiceItem($item);
                    $item->process_taxes($tax_ids);
                }
                if(is_array($item->taxes)){
                   $tax_total = $this->process_item_taxes($item, $tax_total);
                }
            }
        }

        $total = round($subtotal + $tax_total, 2);

        //calculate the total value of all payments made on this invoice
        $payment_transactions = $this->get_payments();

        foreach($payment_transactions as $payment){
            $payments += $payment->amount;
        }

        //calculate balance
        $balance = $total - $payments;

        //set all total related values
        $this->set('subtotal', $subtotal);
        $this->set('tax', $tax);
        $this->set('total', $total);
        $this->set('payments', $payments);
        $this->set('balance', $balance);


        if($save_invoice)
        {
            $this->unset_params_not_saved_to_db();
            parent::save();

        }
    }

    function delete_taxes(){
        $sql = "DELETE FROM invoice_tax_totals WHERE invoice_id = $this->id";
        $this->execute($sql);
    }

    function save_taxes($taxes){
        $this->delete_taxes();

        if (is_array($taxes)) {
            foreach ($taxes as &$tax) {
                $tax['invoice_id'] = $this->id;
                $this->insert($tax, 'invoice_tax_totals');
            }
        }

    }



    function pdf($data = null)
    {
        $this->load_library('fpdf/fpdf');
        $pdf = new InvoicePDF;


        if($data === null)
            $data = Utils::decode($_POST);


       //pre($data);
        $pdf->SetInvoiceData($data);

        $filename = $this->pdf_path($data['number'] . ".pdf");
        $pdf->Output($filename);

        return $data['number'] . ".pdf";
    }

    function pdf_path($file_name){
        return ROOT . "/tmp/" . $file_name;
    }

    static function is_active($invoice){
        return $invoice['status_text'] != Language::get('invoice.status_inactive');
    }

    static function is_overdue($invoice){
        return $invoice['status_text'] == Language::get('invoice.status_overdue');
    }

    static function is_paid($invoice){
        return $invoice['is_paid'] == 1;

    }

    function download($file_path)
    {
        $file_path = $this->pdf_path($file_path);

        if (!file_exists($file_path))
            return false;

        //todo:if headers are the same for downloading other files, they should be stored in one place App:download_headers(); or better yet File::init_download($file_path) and it handles the whole thing
        //turn off error reporting to prevent the document from being corrupted
        error_reporting(0);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_path));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        ob_clean();
        flush();
        readfile($file_path);
        unlink($file_path);
        exit;
    }


    function update_status($save_status = true){

        //store the current invoice status as it exists in the database
        $old_status = $this->status_text;

        if($this->total <= 0 || !isset($this->date_sent) || $this->date_sent == 0){
            $this->set('status_text', Language::get('invoice.status_inactive'));
            $this->set('is_overdue', false);
            $this->set('is_paid', false);
        }
        else{
            if($this->balance <= 0)
            {
                $this->set('status_text', Language::get('invoice.status_paid'));
                $this->set('is_overdue', false);
                $this->set('is_paid', true);
            }

            else if ($this->due_date < time())
            {
                $this->set('status_text', Language::get('invoice.status_overdue'));
                $this->set('is_overdue', true);
                $this->set('is_paid', false);
            }
            else if(isset($this->date_sent) && $this->date_sent > 0) {

                $this->set('status_text', Language::get('invoice.status_pending'));
                $this->set('is_overdue', false);
                $this->set('is_paid', false);
            }
            else{
                $this->set('status_text', Language::get('invoice.status_inactive'));
                $this->set('is_overdue', false);
                $this->set('is_paid', false);
            }
        }

        //if the old status and the new status do not match, then we need to save this invoice back to the database.
        if(($save_status == true) && ($old_status != (string)$this->status_text)){
          $this->unset_params_not_saved_to_db();
           parent::save();
        }
    }

    function set_date_sent(){


        if($this->id){
            $date_sent = time();
            $sql = "UPDATE invoices SET date_sent = $date_sent WHERE id = $this->id";
            $this->execute($sql);
        }

    }

    function get_payments(){
        $payment_class = new Payment();
        $payments = $payment_class->get("WHERE invoice_id = $this->id");

        return is_array($payments) ? $payments : array();
    }

    function prep_for_display(){
        $this->formatted_total = number_format($this->total, 2);
        $this->formatted_due_date = date('F d, Y', $this->due_date);

        if(is_array($this->invoice_items)){
            foreach ($this->invoice_items as &$item) {
                if (!$item instanceof InvoiceItem)
                    $item = new InvoiceItem($item);
                $item->prep_for_display();
            }
        }

    }


    function delete(){
        $result = parent::delete();

        ActivityManager::invoice_deleted($this);

        return $result;
    }

    function user_can_access(User $user = null){
        //todo:permission needs to be more fine grained. Clients can view but not create, edit, or delete invoices
        if(!isset($user))
            $user = current_user();


        if($user->is('admin') || $user->client_id == $this->client_id)
            return true;
        else if($user->is('agent')){
            //agent can only access this invoice if the agent is assigned to the project for this invoice
            $project = new Project($this->project_id);
            return $project->is_assigned_to($user);
        }
        else return false;
    }

    function force_delete($invoice_number){
        $sql = "SELECT id FROM invoices WHERE number = '$invoice_number'";
        $id = $this->select($sql);
        if(isset($id[0]))
            $id = $id[0]['id'];
        else $id = false;

        if($id){
            $this->set('id', $id);
            $this->delete();
        }

        return true;
    }

    function get_overdue_count(){
        $sql = "SELECT COUNT(id) FROM invoices WHERE due_date < " . time() . " AND balance > 0 AND total > 0";
        $count = $this->select($sql);

        return $count[0]['COUNT(id)'];
    }
}
 
