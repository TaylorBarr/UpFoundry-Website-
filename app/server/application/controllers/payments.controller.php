<?php


class PaymentsController extends Controller{

    static function allow_link_access(){
        $current_user = current_user();

        //if the user isn't logged in, make sure they're accessing a valid slug.
        $slug = Request::get('slug');

        if($current_user === false){
            if(isset($slug)){
                $link = new Link();
                $is_valid = $link->is_valid_slug($slug);

                if($is_valid == false)
                    Response()->not_authorized();
            }
            else Response()->not_authorized();
        }

    }

    function save(){


        $this->allow_link_access();

        $id = Request::get('id');

        $payment = new Payment($id);

        //WE ARENT CHECKING FOR PERMISSION because this is a public route to allow for users to pay when not logged in
        //we can't use the default save on the base contoller because it requires users to be logged in

        //clear the params that were imported from the database
        $payment->clear_params();

        //import the parameters sent from the client
        $payment->import_parameters($_POST);

        $result = $payment->save();

        if ($payment->validation_passed())
            Response($result);
        else Response()->error($payment->errors());
    }
}
