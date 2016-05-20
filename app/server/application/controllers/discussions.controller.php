<?php


class DiscussionController extends Controller{
    function load($reference_object, $reference_id){


        $entity = new $reference_object($reference_id);

        $this->check_authorization('read', $entity);

        $message = new Message();
        $messages = $message->get($reference_object, $reference_id);

        Response(array(
            'entity' => $entity,
            'messages'=>$messages
        ));
    }

    function clean_old(){
        if(!current_user()->is('admin')){
            return false;
        }

        $message = new Message();
        $result = $message->remove_html_tags_from_messages();

        Response($result);
    }

    function remove_special_characters(){
        if(!current_user()->is('admin')){
            return false;
        }

        $message = new Message();
        $result = $message->remove_special_characters_from_messages();

        Response($result);
    }
}