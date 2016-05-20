<?php

use EmailReplyParser\Parser\EmailParser;

class IncomingEmail extends Model{

    function fetch(){





        $this->load_library('EmailReplyParser/src/EmailReplyParser/Email');
        $this->load_library('EmailReplyParser/src/EmailReplyParser/EmailReplyParser');
        $this->load_library('EmailReplyParser/src/EmailReplyParser/Fragment');
        $this->load_library('EmailReplyParser/src/EmailReplyParser/Parser/EmailParser');
        $this->load_library('EmailReplyParser/src/EmailReplyParser/Parser/FragmentDTO');


        $this->load_library('PhpImap/Mailbox');
        $this->load_library('PhpImap/IncomingMail');


        try {
            //todo:these need to be config parameters
            $email_address = get_config('incoming_email.email_address');
            $host = get_config('incoming_email.host');
            $port = get_config('incoming_email.port');
            $password = get_config('incoming_email.password');

            $connection_string = '{' . $host . ':' . $port . '/imap/ssl}INBOX';

            $mailbox = new PhpImap\Mailbox($connection_string, $email_address, $password, __DIR__);
            $mailsIds = $mailbox->searchMailBox('ALL');


            if (!$mailsIds) {
                Log::debug('Mailbox is empty');
                die('Mailbox is empty');

            }
        }
        catch(Exception $e){
            Log::debug('Unable to connect', $e);
            die('Unable to connect');
            //unable to connect
        }





        foreach($mailsIds as $mailId){
            try{
                $mail = $mailbox->getMail($mailId);
            }catch(Exception $e){
                //error processing mail
            }
            echo $mailId . "<br>";

            $recipients = explode(',', $mail->toString);
            foreach($recipients as $recipient){
                echo $recipient;
                $email = explode('@', $recipient);
                $email = $email[0];
                $entity_details =  explode('+', $email);

                //make sure we have a valid email
                if(!isset($entity_details[1])){
                    //this message is not being sent to a valid entity, because the 'to' email address isn't in the format
                    //address+entity-entityId
                    $mailbox->deleteMail($mail->id);

                }
                else {
                    $entity_details = $entity_details[1];
                    $entity_parts = explode('-', $entity_details);
                    $entity_type = ucFirst($entity_parts[0]);
                    $entity_id = $entity_parts[1];

                    if (class_exists($entity_type)) {
                        $entity = new $entity_type($entity_id);

                        if (!isset($entity->id)) {
                            //this entity is invalid
                            $this->complete_email_processing($mail);
                        }
                        else{
                            $user = new User();
                            $user = $user->load_from_email_address($mail->fromAddress);

                            if ($user !== false && $user->can('read', $entity)) {

                                $result = $this->create_message($user, $entity, $mail->textPlain, $mail->date);
                                if ($result !== false && is_array($result) && isset($result['id'])) {
                                    $mailbox->deleteMail($mail->id);
                                }
                            }
                            else {
                                //email is from an email address not associated with the entity it's attempting to access.
                                //delete it
                                $mailbox->deleteMail($mail->id);
                            }
                        }


                    }
                }


            }
        }

        Log::debug('Incoming email processed', $mailsIds);
    }

    function create_message($user, $entity, $text, $date){
        $message = new Message();
        $message_text = $this->extract_message_and_discard_quoted_portion($text);

        $message->set('message', $message_text);

        $message->set('created_date', strtotime($date));
        $message->set('reference_object', strtolower(get_class($entity)));
        $message->set('reference_id', $entity->id);
        $message->set('user_id', $user->id);
        $message->set('source', 'email');

        return $message->save();
    }

    function extract_message_and_discard_quoted_portion($text){
        //separator might be optional, some people might value ability to have email previews over potential
        //increased accuracy of the text extraction
        $separator = AppEmail::get_quoted_text_separator();
        $text = explode($separator, $text);

        $parser = new EmailParser();

        $text = $parser->parse($text[0]);

        $fragments = $text->getFragments();

        return $fragments[0]->getContent();
    }

    function complete_email_processing(IncomingMail $mail){

    }
}