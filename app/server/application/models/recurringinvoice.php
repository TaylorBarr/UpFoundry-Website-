<?php


class RecurringInvoice extends Model
{


    public $client_id;
    public $project_id;
    public $profile_name;
    public $status;
    public $unit_of_time;
    public $every;
    public $start_date;
    public $end_date;
    public $last_sent_date;
    public $next_invoice_date;
    public $payment_terms;
    public $payment_terms_label;
    public $subtotal;
    public $total;
    public $created;
    public $last_modified;
    public $is_archived;
    public $auto_send;
    public $is_stopped;

    //params not saved to db
    public $invoices;
    public $company;
    public $client;
    public $invoice_items;
    public $calculated_taxes;
    public $taxes;


    function unset_params_not_saved_to_db()
    {
        $this->unset_param('invoices');
        $this->unset_param('company');
        $this->unset_param('client');
        $this->unset_param('invoice_items');
        $this->unset_param('calculated_taxes');
    }

    function process_item_taxes($item, $all_taxes)
    {
        $taxes = explode(',', $item->tax_ids);

        if (is_array($taxes) && count($taxes) > 0) {
            foreach ($taxes as $tax_id) {
                if (!empty($tax_id)) {

                    //get the details of this tax
                    $tax = $all_taxes[$tax_id];

                    //if this tax isn't already in the array, add it
                    if (!isset($this->calculated_taxes[$tax_id])) {
                        $this->calculated_taxes[$tax['id']] = array(
                            'tax_name' => $tax['name'],
                            'tax_rate' => $tax['rate'],
                            'tax_id' => $tax['id'],
                            'amount' => 0
                        );
                    }


                    //add the amount from this item/tax, to the tax total array
                    $amount = $item->subtotal * $tax->rate;
                    $this->calculated_taxes[$tax['id']]['amount'] += $amount;

                }

            }
        }
    }

    function get($criteria = null)
    {
        if (is_numeric($criteria)) {

            $recurring_invoice = parent::get_one($criteria);


            global $CONFIG;
            $recurring_invoice['company'] = $CONFIG['company'];


            $recurring_invoice['client'] = $this->load('Client')->get($recurring_invoice['client_id']);
            $recurring_invoice['invoice_items'] = $this->get_items($recurring_invoice['id']); //todo: if i were really using oop I would use invoice->id;

            $invoice = new Invoice();
            $recurring_invoice['invoices'] = $invoice->get("WHERE recurring_invoice_id = $criteria");



            $recurring_invoice['taxes'] = $this->process_taxes($recurring_invoice);

            // $recurring_invoice['taxes'] = $this->calculated_taxes;

            return $recurring_invoice;

        } else return parent::get($criteria);
    }

    function process_taxes($recurring_invoice){
        $taxes = $this->select("SELECT * FROM tax_rates");
        $all_taxes = array();

        foreach ($taxes as $tax) {
            $all_taxes[$tax->id] = $tax;
        }


        if (is_array($recurring_invoice['invoice_items'])) {
            foreach ($recurring_invoice['invoice_items'] as $item) {
                $this->process_item_taxes($item, $all_taxes);
            }
        }

        //we currently have an associative array in $this->calculated_taxes, we need an indexed array
        $the_taxes = array();

        if(is_array($this->calculated_taxes)){
            foreach ($this->calculated_taxes as $tax) {
                $the_taxes[] = $tax;
            }
        }

        $this->unset_param('calculated_taxes');

        return $the_taxes;
    }

    function get_items($id)
    {
        $sql = "SELECT recurring_invoice_items. * , GROUP_CONCAT( recurring_invoice_items_tax_rates.tax_id ) AS tax_ids
                FROM recurring_invoice_items
                LEFT JOIN recurring_invoice_items_tax_rates ON recurring_invoice_items.id = recurring_invoice_items_tax_rates.recurring_invoice_item_id
                WHERE recurring_invoice_items.recurring_invoice_id = $id
                GROUP BY recurring_invoice_items.id";

        return $this->select($sql);
    }

    function create()
    {
        $items = Request::get('invoiceItems');

        if (!isset($items) || !is_array($items)) {
            $this->set_error('invoice_items', 'There must be at least one item on the invoice'); //todo:lang
            return false;
        }

        $this->import_parameters();

        if (!isset($this->client_id) || empty($this->client_id)) {
            $this->set_error('client_id', 'Please select a client'); //todo:lang
            return false;
        }

        //$start_date =  strtotime(Request::get('start_date') + ' midnight');
        $start_timestamp = Request::get('start_date');
        $start_date = new DateTime();//todo:this needs to take into account
        $start_date->setTimestamp($start_timestamp);

        $start_date = $start_date->getTimestamp();


        $today = $this->today();

        //start date must be in the future
        if ($start_date <= $today && $this->is_new()) {
            $this->set_error('start_date', 'Start date must be in the future'); //todo:lang
            return false;
        }


        $this->set('profile_name', Request::get('profile_name'));
        $this->set('unit_of_time', Request::get('unit_of_time'));
        $this->set('every', Request::get('every'));
        $this->set('start_date', $start_date);
        //the first time this invoice will be sent is the start date
        $this->set('next_invoice_date', $start_date);
        $this->set('end_date', Request::get('end_date'));

        $this->unset_params_not_saved_to_db();

        $result = parent::save();

        //the params passed were for the invoice. We need to clear them otherwise every object created after this will
        //attempt to also import those params
        Request::clear_params();


        foreach ($items as $item) {
//            //we do not want to import the id from the original invoice item. The recurring_invoice_item will
//            //generate it's own id
//            unset($item['id']); // = null;
//todo: if we're converting a regular invoice to a recurring invoice, then we need to unset the original id
            $item = new RecurringInvoiceItem($item);
            $item->set('recurring_invoice_id', $this->id);
            $item->save();
        }


        return $result;
    }

    function today()
    {
        $today = new DateTime('today midnight');
        $today->setTime(1, 0);
        $today = $today->getTimestamp();

        $today = new DateTime();
        $today = $today->getTimestamp();

        return $today;
    }

    function add_month_to_datetime(DateTime &$date, $months = 1)
    {
        //http://php.net/manual/en/datetime.add.php
        $date_string = $date->format('Y-n-j');
        list($y, $m, $d) = explode('-', $date_string);

        $m += $months;
        while ($m > 12) {
            $m -= 12;
            $y++;
        }

        $last_day = date('t', strtotime("$y-$m-1"));
        if ($d > $last_day) {
            $d = $last_day;
        }

        $date->setDate($y, $m, $d);
    }

    function get_next_invoice_date()
    {

        //TODo: use the timezone in the config file?
        date_default_timezone_set('UTC');

        $frequency = $this->unit_of_time;
        $every = $this->every;

        $current_next = isset($this->next_invoice_date) ? $this->next_invoice_date : $this->start_date;


        $today = $this->today();


        //if the current value for next_invoice date is in the future. There is no reason to calculate another date,
        //because we haven't create the next invoice yet
        if ($today <= $current_next)
            return false;

        //previously we were basing the next_invoice_date calculation on the previous value of next_invoice_date ($current_next),
        //but this presents problems if a recurring invoice is stopped. The script will send out all 'missed' invoices,
        //which isn't desired behavior. The script should only send future invoices, therefore the next_invoice_date
        //will be calculated based on today, so all next_invoice_dates are in the future. We are essentially eliminating
        //the possibility that next_invoice_date can have a value in the past.
        $date = new DateTime();
        //$date->setTimestamp($current_next);

        if ($frequency == 'months') {
            $this->add_month_to_datetime($date, $every);
        } else {

            $frequency_interval_map = array(
                'weeks' => 'W',
                'months' => 'M',
                'years' => 'Y'
            );

            //todo:delete or comment out
            $frequency_interval_map['days'] = 'D';
            $frequency_interval_map['minutes'] = 'M';

            if ($frequency !== 'minutes')
                $interval_string = 'P' . $every;
            else $interval_string = 'PT' . $every;


            $interval_string .= $frequency_interval_map[$frequency];

            $date->add(new DateInterval($interval_string));
        }

        $this->set('next_invoice_date', $date->getTimestamp());

        return parent::save();
    }



    function generate_invoice()
    {
        $params = $this->to_array();

        //we need the invoice to generate it's own numbers
        unset($params['id']);
        unset($params['number']);

        //the params that aren't saved to the db, neeed to be unset as well
        unset($params['invoices']);
        unset($params['company']);
        unset($params['client']);
        unset($params['invoice_items']);
        unset($params['calculated_taxes']);
        unset($params['params']);


        $invoice = new Invoice($params);

        $today = new DateTime();




        //create a blank invoice, then immediately save it so the invoice will get an id
        $invoice->set('recurring_invoice_id', $this->id);
        $invoice->set('date', $today->getTimestamp());

        $today->modify('+' . $params['payment_terms'] . ' days');
        $invoice->set('due_date', $today->getTimestamp());
        $invoice->is_auto_generated();

        $invoice->save();

        //clear all the params sent with the request because we're about to create invoice items and if we don't
        // clear those params, they will get imported into the new invoice items, which is incorrect. The invoice
        //items will be populated with the data from the recurring invoice record
        //items will be populated with the data from the recurring invoice record
        Request::clear_params();

        $sql = $this->get_invoice_items_sql($this->id);
        $items = $this->select($sql);

        foreach($items as &$item){
            $tax_ids = $item->tax_ids;

            $invoice_item = new InvoiceItem($item);

            $invoice_item->process_taxes($tax_ids);

            $item = $invoice_item->to_array();

            unset($item['id']);// = null;

            $invoice->invoice_items[] = $item;

        }



        //save the invoice again to calculate the total
        $invoice->is_auto_generated();
        $invoice->save();


        return $invoice;
    }

    function get_invoice_items_sql($recurring_invoice_id)
    {
        return "SELECT recurring_invoice_items. * , GROUP_CONCAT( recurring_invoice_items_tax_rates.tax_id ) AS tax_ids
                FROM recurring_invoice_items
                LEFT JOIN recurring_invoice_items_tax_rates ON recurring_invoice_items.id = recurring_invoice_items_tax_rates.recurring_invoice_item_id
                WHERE recurring_invoice_items.recurring_invoice_id = $recurring_invoice_id
                GROUP BY recurring_invoice_items.id";
    }

    function process_invoices()
    {

        $today = $this->today();

        $sql = "SELECT * FROM recurring_invoices WHERE next_invoice_date <= $today AND (end_date IS NULL OR end_date >= $today) AND is_stopped != 1";
        $recurring_invoices = $this->select($sql);
        $ids = array();

        foreach ($recurring_invoices as $recurring_invoice) {
            $ids[] = $recurring_invoice->id;
            $recurring_invoice = new RecurringInvoice($recurring_invoice);
            $invoice = $recurring_invoice->generate_invoice();
            $recurring_invoice->get_next_invoice_date();

            if ($invoice->validation_passed()) {

                //todo:this is obviously supposed to be a config option
                $recurring_invoice->auto_send = 1;

                if ($recurring_invoice->auto_send == 1) {
                    $app_email = new AppEmail();
                    $app_email->send_invoice($invoice);

                    $invoice->set_date_sent();
                }

                ActivityManager::invoice_generated_from_recurring_series($recurring_invoice, $invoice);
            } else {
                //todo: need to log the errors. Dispatch a notice to admins? A recurring invoice not generating is a big deal

            }
        }

        Log::debug('Recurring Invoices Processed', $ids);

    }

    function stop(){
        $this->set('is_stopped', 1);
        return $this->save();
    }

    function start(){
        $this->set('is_stopped', 0);
        return $this->save();
    }

}