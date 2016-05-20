<?php


class ScheduledTasksController extends Controller{



    function daily($code = null){
       $this->validate_request($code);

        $recurring_invoice = new RecurringInvoice();
        $recurring_invoice->process_invoices();
    }

    function frequently($code = null){
        $this->validate_request($code);

        $email = new IncomingEmail();
        $email->fetch();
    }

    function validate_request($code){

        if(empty($code) || $code != get_config('scheduled_tasks.code'))
            Response()->not_authorized();

    }

    function urls(){

    }
}