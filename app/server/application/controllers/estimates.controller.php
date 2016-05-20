<?php


class EstimatesController extends Controller{
    function pdf()
    {

        $estimate = new Estimate();

        $result = $estimate->pdf();

        Response($result);
        //we check access in the upload function

    }

    function download($name)
    {
        $estimate = new Estimate();

        $result = $estimate->download($name);
    }


    function convert_to_invoice(){
        $id = Request::get('id');
        $estimate = new Estimate($id);
        $invoice = $estimate->convert_to_invoice();

        if($invoice->is_valid()){
            Response($invoice->id);
        }
        else Response()->error($invoice->errors());
    }

    function response(){
        $current_user = current_user();
        $id = Request::get('id');
        $estimate = new Estimate($id);
        //todo:controller;

        //there are two scenarios where the user has access to this function
        $logged_in_and_has_access = $current_user != false && $current_user->can('read', $estimate);
        $external_but_has_link = $current_user == false && $estimate->has_access_via_link_slug(Request::get('slug'));

        if (!$logged_in_and_has_access && !$external_but_has_link){
            Response()->not_authorized();
        }
        else{
            $result = $estimate->response(Request::get('type'));
            Response($result);
        }
    }

    function link(){

    }
}
 
