<?php


class LinksController extends Controller{

    function asset($slug){

        $link = new Link();
        $asset = $link->asset($slug);

        if($link->validation_passed()){
            Response($asset);
        }
        else Response()->error($link->errors());
    }
}