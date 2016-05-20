<?php

class Estimate extends Invoice{

    public $approval_is_rejected;
    public $approval_is_approved;


    function base_select_query()
    {
        return "SELECT estimates.*,
                  clients.name AS client_name,
                  projects.name AS project_name,
                  approvals.is_approved AS approval_is_approved,
                  approvals.is_rejected AS approval_is_rejected
                FROM estimates
                LEFT JOIN clients ON estimates.client_id = clients.id
                LEFT JOIN projects ON estimates.project_id = projects.id
                LEFT JOIN approvals ON estimates.id = approvals.object_id AND approvals.object_type = 'estimate'";
    }

    function sql_to_get_max_invoice_number(){
        return "SELECT MAX(CAST(number AS signed))FROM estimates";
    }

    function where_clause_sql_for_individual_entity($criteria)
    {
        return ' WHERE estimates.id = ' . $criteria;
    }

    function get_items($id){
        return $this->load('EstimateItem')->get($id);
    }

    function get_details_for_item_taxes(){

        foreach($this->invoice_items as &$item){

            $tax_ids = $item->tax_ids;
            $invoice_item = new InvoiceItem($item);

            $invoice_item->process_taxes($tax_ids);

            $item = $invoice_item->to_array();

        }
    }

    function get($criteria = null)
    {
        $sql = $this->base_select_query();

        if (is_numeric($criteria)) {
            $sql .= $this->where_clause_sql_for_individual_entity($criteria);

            $estimate = parent::get_one($sql);


            global $CONFIG;
            $estimate['company'] = $CONFIG['company'];


            $estimate['client'] = $this->load('Client')->get($estimate['client_id']);
            $estimate['invoice_items'] = $this->get_items($estimate['id']); //todo: if i were really using oop I would use invoice->id;
            $estimate['taxes'] = $this->get_taxes($estimate['id']);

//
//            $this->set('id', $estimate['id']);
//            $approval = new Approval;
//            $approval = $approval->create($this);
//            $estimate['approval'] = $approval;

            $this->import_parameters($estimate);
            $this->update_status();


            return $estimate;

        } else {
            $sql .= isset($criteria) ? " $criteria" : '';
            $sql = $this->modify_sql_for_user_type($sql);

            $estimates = Model::get($sql);

            //we currently have an array of invoices where each invoice is an array itself. We need to update the status
            //of each invoice, which means creating an invoice object for each invoice array item
            foreach ($estimates as &$estimate) {
                //create the invoice
                $estimate_object = new Estimate($estimate);
//todo: i don't think this makes any sense because the invoice status will already be updated when I create the invoice using new Invoice

                //update the invoice status
                $estimate_object->update_status();

                //we need to add the status text back the invoice array, since we're sending the array to the client,
                //not the object
                $estimate['status_text'] = $estimate_object->status_text;
            }

            return $estimates;
        }
    }



    function update_status($save_status = true)
    {
        //store the current invoice status as it exists in the database
        $old_status = $this->status_text;


        if ($this->approval_is_approved == 1) {
            $this->set('status_text', Language::get('estimate.status_approved'));
        }
        else if ($this->approval_is_rejected == 1) {
            $this->set('status_text', Language::get('estimate.status_rejected'));
        }
        else if (isset($this->date_sent)) {
            $this->set('status_text', Language::get('estimate.status_sent'));

        } else {
            $this->set('status_text', Language::get('estimate.status_draft'));

        }
//if the old status and the new status do not match, then we need to save this invoice back to the database.
        if (($save_status == true) && ($old_status != (string)$this->status_text)) {
            $this->unset_params_not_saved_to_db();
            call_user_func(array('Model', 'save'));
        }
    }

    function delete_taxes()
    {

        $sql = "DELETE FROM estimate_tax_totals WHERE invoice_id = $this->id";
        $this->execute($sql);
    }

    function get_taxes($id)
    {
        $sql = "SELECT * FROM estimate_tax_totals WHERE invoice_id = $id";
        return $this->select($sql);
    }

    function save_taxes($taxes)
    {
        $this->delete_taxes();

        if(is_array($taxes)){
            foreach ($taxes as &$tax) {
                $tax['invoice_id'] = $this->id;
                $this->insert($tax, 'estimate_tax_totals');
            }
        }

    }

    function save($record_activity = true)
    {
        //we need to import the parameter that we're trying to save, in some cases this will be all of the invoice parameters,
        //but in others it will just be the project id. If it's just the project id, we will need to set the other
        //required parameters to their defaults
        $this->import_parameters();
        $this->set_client_id();
        $this->set_invoice_number();
        $this->set_invoice_dates();

        $this->validate();
        $item_result = true;

        //if there were no errors saving the base invoice, save each of the line items
        if (!$this->has_errors && is_array($this->invoice_items)) {

            foreach ($this->invoice_items as &$item) {
                //make sure the invoice item is tied to this invoice (just in case the invoice id isn't sent by the client)
                $invoice_item = new EstimateItem($item);

                //let's just make sure the invoice item is correct, since we can't trust any of this client side data
                $invoice_item->set('estimate_id', $this->id);




                $item_result = $invoice_item->save();

                //calculate total is going to need the object
                $item = $invoice_item;
                //stop saving line items if there was an error saving this one
                if ($invoice_item->has_errors)
                    break;
            }
        }

        //let's store is_new status in a variable because it will change once we call save
        $is_new = $this->is_new();

        //there is no reason to calculate the total if this is a new invoice because there won't be any invoice items
        //yet
        if (!$is_new) {
            $this->calculate_total();
        }

        $this->update_status(false);


        $this->unset_params_not_saved_to_db();

        $taxes = $this->calculated_taxes;

        $result = call_user_func(array('Model', 'save'));
        $this->save_taxes($taxes);


        if ($record_activity) {
            if ($is_new) {
                ActivityManager::invoice_created($this);
            } else ActivityManager::invoice_updated($this);


        }

        //return the result, true or false
        return $is_new ? $result : ($item_result != false);
    }

    function unset_params_not_saved_to_db(){
        parent::unset_params_not_saved_to_db();
        $this->unset_param('approval');
        $this->unset_param('estimate_id');
        $this->unset_param('approval_is_approved');
        $this->unset_param('approval_is_rejected');
    }

    function convert_to_invoice(){
        $this->get_details_for_item_taxes();

        $invoice = new Invoice();
        $invoice->is_auto_generated();

        $invoice->set('client_id', $this->client_id);

        //manually set params imported to true otherwise the invoice will attempt to import the Estimate properties. NOT
        //what we want, becaue the id is among those properties. We'll handle the import manually
        $invoice->params_imported = true;

        //we need ot save the invoice before we import any of the items, because it needs to have an id before we
        //save the items
        $invoice->save();

        //prepare the parameter to be imported
        $invoice_parameters = $this->to_array();

        unset($invoice_parameters['id']);

        //the invoice needs to generate it's own number
        unset($invoice_parameters['number']);

        $invoice->params_imported = false;
        $invoice->import_parameters($invoice_parameters);

        $invoice->set('estimate_id', $this->id);
        $invoice->is_auto_generated();
        $invoice->save();


        return $invoice;

    }



    function has_access_via_link_slug($link_slug){
        $link = new Link();
        $link->asset($link_slug);

        if($link->validation_passed()){
            return true;
        }
        else return false;
    }

    function response($type){
        $approval = new Approval();
        $approval->create($this);


       if($type == 'accept'){
           $approval->approve();
           return $this->post_approve_processing();

       }
       else{
           $approval->reject();
           $email = new AppEmail();
           $email->send_estimate_rejection($this);
           return true;
       }
    }

    function post_approve_processing(){
        $estimate_post_processing = get_config('estimates.post_processing');


        if($estimate_post_processing == 'generate_invoice'){
            $invoice = $this->convert_to_invoice();
        }

        if($estimate_post_processing == 'generate_and_send_invoice'){
            $invoice = $this->convert_to_invoice();
            $email = new AppEmail();
            $email->send_invoice($invoice);
        }

        if (!isset($invoice))
            $invoice = null;

        $email = new AppEmail();
        $email->send_estimate_approval($this, $invoice);

        return $invoice;

    }




}
 
