<?php
class Email{

    public function sendEmail($to,$subject,$message){
        //Email information
        $from = "noreply@worklife.com.ar";
    //$to = "panella.dante@gmail.com";
    //$subject = "Simple test for mail function";
    //$message = "This is a test to check if php mail function sends out the email";
    $headers = "From:" . $from;
 
    /*
     * Test php mail function to see if it returns "true" or "false"
     * Remember that if mail returns true does not guarantee
     * that you will also receive the email
     */
        if(mail($to,$subject,$message, $headers))
        {
            header("Location: http://www.worklife.com.ar");
            exit();
        } 
        else 
        {
            echo "Failed to send.";
        }
    }

}

$obj = new Email();

$obj->sendEmail($_POST['to'], $_POST['subject'],$_POST['message']);



?>