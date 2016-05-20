<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class STH_Contact_Form {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'sth_contact_form', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ){
        $output = $email_address = '';

        extract( shortcode_atts( array(
            'email_address'              => ''
        ), $atts ) );

        require_once ABSPATH . WPINC . '/class-phpmailer.php';

        function isEmailwidget($verify_email) {

            return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$verify_email));

        }

        $error_name_widget = false;
        $error_email_widget = false;
        $error_message_widget = false;
        $error_subject_widget = false;

        if (isset($_POST['contact_submit'])) {


            // Initialize the variables
            $name_widget = '';
            $email_widget = '';
            $subject_widget = '';
            $message_widget = '';
            $receiver_email_widget = '';

            // Get the name
            if (trim($_POST['dname']) === '') {
                $error_name_widget = true;
            } else {
                $name_widget = trim($_POST['dname']);
            }

            // Get the email
            if (trim($_POST['demail']) === '' || !isEmailwidget($_POST['demail'])) {
                $error_email_widget = true;
            } else {
                $email_widget = trim($_POST['demail']);
            }

            // Get the message
            if (trim($_POST['dmessage']) === '') {
                $error_message_widget = true;
            } else {
                $message_widget = stripslashes(trim($_POST['dmessage']));
            }

            // Check if we have errors
            if (!$error_name_widget && !$error_email_widget && !$error_message_widget) {

                $receiver_email_widget = $email_address;

                // If none is specified, get the WP admin email
                if (!isset($receiver_email_widget) || $receiver_email_widget == '') {
                    $receiver_email_widget = get_option('admin_email');
                }

                $website = '';

                $mail = new PHPMailer;

                // Construct the email
                $mail->From = $email_widget;
                $mail->FromName = get_bloginfo('name');
                $mail->addAddress($receiver_email_widget);
                $mail->addReplyTo($email_widget, $name_widget);
                $mail->isHTML(false);

                $mail->Subject = __('New message from ', LANGUAGE_ZONE) . $name_widget;

                $mail->Body    = $message_widget;

                if($website != '') {
                    $mail->Body   .= PHP_EOL . PHP_EOL . "{$name_widget}'s website is {$website}";
                }

                if ($mail->send()) {
                    $email_sent_widget = true;
                } else {
                    $email_sent_error_widget = true;
                }

            }
        }

        if( isset($email_sent_error_widget) && $email_sent_error_widget == true ) { // If errors are found
            $output .= '<div class="alert alert-warning fade in">';
            $output .= '<div class="icon-alert"></div>';
            $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
            $output .= __('Please check if you\'ve filled all the fields with valid information and try again. Thank you.', LANGUAGE_ZONE);
            $output .= '</div>';
        }

        if( isset($email_sent_widget) && $email_sent_widget == true ) { // If email is sent
            $output .= '<div class="alert alert-success fade in">';
            $output .= '<div class="icon-alert"></div>';
            $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
            $output .= __('Message Successfully Sent!', LANGUAGE_ZONE);
            $output .= '</div>';
        }


        $output .= '<div class="contact-form">';
        $output .= '<form action="'. esc_url( home_url( '/' ) ) .'" method="post" id="formID" class="comment-form">';
        $output .= '<ul class="comment-form-inputs">';
        $output .= '<li><input id="dauthor" name="dname" type="text" value="'. (isset($_POST['dname']) ? esc_attr($_POST['dname']) : "").'" aria-required="true" required placeholder="'. __('Name', LANGUAGE_ZONE).' *"></li>';
        $output .= '<li><input id="demail" name="demail" type="text" value="'. (isset($_POST['demail']) ? esc_attr($_POST['demail']) : "").'" aria-required="true" required placeholder="'. __('Email', LANGUAGE_ZONE).' *"></li>';
        $output .= '</ul>';
        $output .= '<textarea name="dmessage" placeholder="'. __('Message', LANGUAGE_ZONE).' *" required rows="7">'. (isset($_POST['dmessage']) ? esc_textarea($_POST['dmessage']) : "").'</textarea>';
        $output .= '<p class="form-submit">';
        $output .= '<input type="hidden" id="contact_submit" name="contact_submit" value="true" />';
        $output .= '<input name="submit" type="submit" id="dsubmit" value="'. __('Send', LANGUAGE_ZONE) .'">';
        $output .= '</p>';
        $output .= '</form>';
        $output .= '</div>';


        return $output;
    }

}

STH_Contact_Form::get_instance();