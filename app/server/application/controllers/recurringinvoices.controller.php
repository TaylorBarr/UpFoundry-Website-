<?php

class RecurringInvoicesController extends Controller{
    function create(){
        $recurring_invoice = new RecurringInvoice();

        $result = $recurring_invoice->create();

        //todo:this exact pattern is repeated so many times. DRY it out.
        if($recurring_invoice->validation_passed())
            Response($result);
        else Response()->error($recurring_invoice->errors());
    }



    function stop(){
        if(!current_user()->is('admin'))
            Response()->not_authorized();

        $id = Request::get('id');
        $recurring_invoice = new RecurringInvoice($id);

        Response($recurring_invoice->stop());
    }

    function start(){
        if (!current_user()->is('admin'))
            Response()->not_authorized();

        $id = Request::get('id');
        $recurring_invoice = new RecurringInvoice($id);

        Response($recurring_invoice->start());
    }
}