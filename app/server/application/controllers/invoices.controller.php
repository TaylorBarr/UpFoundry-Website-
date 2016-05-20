<?php

Class InvoicesController extends Controller{
    function pdf(){

        $invoice = new Invoice();

        $result = $invoice->pdf();

        Response($result);
        //we check access in the upload function

    }

    function filter($type){
        $invoice = new Invoice();
        Response($invoice->get($type));
    }

    function download($name){
        $invoice = new Invoice();

        $result = $invoice->download($name);
    }


    function force_delete(){
        if(!current_user()->is('admin'))
            Response()->not_authorized();

        $invoice = new Invoice();
        $result = $invoice->force_delete(Request::get('invoice_number'));

        Response($result);
    }

    function get_defaults(){
        $invoice = new Invoice();

        if(!current_user()->can('update', $invoice)){
            Response()->not_authorized();
        }
        else{
            Response($invoice->get_defaults());
        }
    }

    function send($id){
        if(!current_user()->is('admin')){
            Response()->not_authorized();
        }
        else{
            $invoice = new Invoice();
            $result = $invoice->send();
            Response($result);
        }
    }




}

 
