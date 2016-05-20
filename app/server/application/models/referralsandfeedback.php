<?php

class ReferralsAndFeedback extends Model {

    const HEADER = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                    <html xmlns='http://www.w3.org/1999/xhtml'>
                    <head>
                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                    </head>
                    <body>
                    <table cellpadding='0' border='0'
                           style='-webkit-text-size-adjust: none; -ms-text-size-adjust: none; line-height: 1.5em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; border-spacing: 0px; font-size: 100%; border: 0; margin: 0; padding: 0; height: 100%;'
                           cellspacing='0' width='100%' class='container'>
                        <tr>
                            <td width='100%'>";

    const FOOTER = "</td></tr></table></body></html>";

    function save() {

        $type = Request::get('type');

        if ($type == 'referral')
            return $this->send_referral();

        if ($type == 'feedback')
            return $this->send_feedback();

        return false;
    }

    function send_referral() {
        $email = new Email();

        $recipient = Request::get('email');
        $subject = Request::get('subject');
        $message = Request::get('message');


        $email->set_recipient($recipient);
        $email->subject = $subject;
        $email->message =  self::HEADER . nl2br($message) . self::FOOTER;

        $result =  $email->send(true);

        if(get_config('referrals.log') === true){
            //This is so I can track performance of referrals sent through the app. No one you refer will ever be contacted directly
            $email->set_recipient('duet-referral-analytics@23andwalnut.com');
            $email->send(true);
        }

        return $result;
    }

    function send_feedback() {
        $email = new Email();

        $recipient = 'duet-in-app-feedback@23andwalnut.com';
        $subject = 'Duet In App Feedback';
        $message = Request::get('message');

        $email->set_recipient($recipient);
        $email->subject = $subject;
        $email->message =  self::HEADER . nl2br($message) . self::FOOTER;

        $result =  $email->send(true);

        return $result;
    }

    function get($criteria = null) {
    }
}