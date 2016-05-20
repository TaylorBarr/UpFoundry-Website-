<?php


class ClientsController extends Controller{
    function set_tax_rate(){
        if(!current_user()->is('admin'))
            Response()->not_authorized();

       $client = new Client(Request::get('client_id'));
        $result = $client->set_tax_rate();

        Response($result);
    }
}
 
