<?php
ini_set('display_errors', 1);

// Pull in the Database Configuration file
require 'dbconfig.php';
require 'sendpulse-rest-api-php/ApiInterface.php';
require 'sendpulse-rest-api-php/ApiClient.php';
require 'sendpulse-rest-api-php/Storage/TokenStorageInterface.php';
require 'sendpulse-rest-api-php/Storage/FileStorage.php';
require 'sendpulse-rest-api-php/Storage/SessionStorage.php';
require 'sendpulse-rest-api-php/Storage/MemcachedStorage.php';
require 'sendpulse-rest-api-php/Storage/MemcacheStorage.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// API credentials from https://login.sendpulse.com/settngs/#api
define('API_USER_ID', 'e4d11114a5f946adfcc9874a92fd4a42');
define('API_SECRET', '09005b4f24d9f7a4c1c71fabbf55746a');
define('PATH_TO_ATTACH_FILE', __FILE__);

$SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

// Capture Post Data
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$city = $_POST['city'];
$firstName = $_POST['firstName'];
$ticketType = $_POST['ticketType'];
$ticketId;
if($_POST['ticketType'] == 'Duchess') {
    $ticketId = 1;
} elseif ($_POST['ticketType'] == 'Empress') {
    $ticketId = 2;
} elseif($_POST['ticketType'] == 'Queen') {
    $ticketId = 3;
}



$dsn = "mysql:host=$host;dbname=$db";

try{
    // Create a PDO Connection with the configuration data
    $conn = new PDO($dsn, $username, $password);

    //display a message if connected to database successfully
    // if($conn){
    //     echo "Connected to the <strong>$db</strong> database successfully!";
    // }
    
    // enter the data into the database
    $enteruser = "INSERT into participants (firstName, lastName, email, phone, city, ticket_id) VALUES (:firstName, :lastName, :email, :phone, :city, :ticket_id)";

    //Prepare Query
    $enteruserquery = $conn->prepare($enteruser);

    // Execute the Query
    $enteruserquery->execute(
        array(
            "firstName"         =>  $firstName,
            "lastName"          =>  $lastName,
            "email"             =>  $email,
            "phone"             =>  $phone,
            "city"              =>  $city,
            "ticket_id"         =>  $ticketId
        )
    );


        
    // Check to see if the query executed successfully
    if($enteruserquery->rowCount() > 0) {
        //Send SMS
        // prepare the parameters
        $url = 'https://www.bulksmsnigeria.com/api/v1/sms/create';
        $from = 'Queen Arise';
        $body = "Dear ".$ticketType. " " .$firstName. ", Thank you for registering for Queen Arise Conference! We are so excited already. Guess what! There is so much in store for you! Kindly look out for updates on all our social media platforms and check your mail for your ticket. For further enquiries call 08022473972 or send a mail to info@cgeee.org.";
        $token = '3bzWyASahw61zV0s3TT2oYQnbiJ1EcM9mm5g6QArpKm8Ubv7w8aMf7iHbkh8';
        $myvars = 'api_token=' . $token . '&from=' . $from . '&to=' . $phone . '&body=' . $body;
        //start CURL
        // create curl resource
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_exec($ch);

        /**
         * Add User to the SendPule mailing List
         */
        $bookID = 2063694;
        $emails = array(
                array(
                    'email'         =>  $email,
                    'variables'     =>  array(
                    'phone'         =>  $phone,
                    'name'          =>  $firstName,
                    'lastName'      =>  $lastName,
                    'city'          =>  $city,
                    'ticketType'    =>  $ticketType,
                )
            )
        );
        // Without Confirmation
        var_dump($SPApiClient->addEmails($bookID, $emails));


        // Send Ticket via Email - PHPMailer
        $mail = new PHPMailer(true); // enable exceptions
        // server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'mail.cgeee.org';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@cgeee.org';
        $mail->Password = '1nf0m@1l';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $emailBody = '<table style="background-color: #d5d5d5;" border="0" width="100%" cellspacing="0">
                        <tbody>
                        <tr>
                        <td>
                        <table style="font-family: Helvetica,Arial,sans-serif; background-color: #fff; margin-top: 40px; margin-bottom: 40px;  border-radius: 20px;" border="0" width="600" cellspacing="0" cellpadding="0" align="center">
                        <tbody>
                        <tr>
                        <td style="padding-top: 40px; padding-right: 40px; padding-bottom: 15px;" colspan="2">
                        <p style="text-align: right;"><a href="http://cgeee.org/queenarise/"><img src="https://cgeee.org/email/qalogo.png" alt="Queen Arise" width="30%" border="0" /></a></p>
                        </td>
                        </tr>
                        <tr>
                        <td style="padding-right: 40px; text-align: right;" colspan="2"><span style="font-size: 12pt;"></span></td>
                        </tr>
                        <tr>
                        <td style="color: #000; font-size: 12pt; font-weight: normal; line-height: 15pt; padding: 40px 40px 80px 40px;" colspan="2" valign="top">Hi ' . $ticketType . ' ' . $firstName . ',
                            <p>Thank you for registering for Queen Arise Conference.</p>
                            <p>We are ready for you on November 23rd to 24th 2018. Are you ready? There&#39;s so much we have for you, we can&#39;t wait.</p>
                            <p>This mail serves as a ticket to the event. Please come with it to the venue and present it at the verification stand. Note that <strong>this ticket is not transferrable.</strong></p>
                            <p>Kindly visit {site name} and all our social media platforms for more information and updates. Don&#39;t hesitate to call <strong>08022473972</strong> for further enquiries.</p>
                            <p>We look forward to receiving you and making great memories together</p>
                            <p>Have a beautiful day!</p>
                        </td>
                        </tr>
                        <tr>
                        <td style="border-top: 5px solid #ec008b; height: 10px; font-size: 7pt;" colspan="2" valign="top"><span>&nbsp;</span></td>
                        </tr>
                        <tr style="text-align: center;">
                        <td id="s1" style="padding-left: 20px;" valign="top"><span style="text-align: center; color: #333; font-size: 12pt;"><strong>Powered by:</strong></span></td>
                        </tr>
                        <tr style="text-align: center; padding-left: 20px; padding-right: 20px; padding-bottom: 0;">
                        <td colspan="2" valign="top"><span style="color: #333; font-size: 8pt; font-weight: normal; line-height: 17pt;"><span style="font-size: 12pt;color: #4298f4;">Centre for Gender Equality Education & Empowerment</span><br /> TFN House, 7A ECOWAS Estate, Diplomatic Zone, Katampe Extension, Abuja, Nigeria<br />tel: +2349023725244 &nbsp;<br /><strong>email:&nbsp;</strong>info@cgeee.org &nbsp;|&nbsp; <strong>www.cgeee.org</strong></span>
                        <p><a href="https://twitter.com/CGEEE2"><img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/twitter_circle_color-20.png" width="20px" height="20px" /></a><a href="https://www.facebook.com/CGEEEAFRICA"><img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/facebook_circle_color-20.png" width="20px" height="20px" /></a></p>
                        </td>
                        </tr>
                        <tr>
                        <td id="s3" style="padding-left: 20px; padding-right: 20px;" colspan="2" valign="bottom">
                        <p style="font-family: Helvetica, sans-serif; text-align: center; font-size: 12px; line-height: 21px; color: #333;"><span style="margin-left: 4px;"><span style="opacity: 0.4; color: #333; font-size: 9px;">Disclaimer: This message and any files transmitted with it are confidential and privileged. If you have received it in error, please notify the sender by return e-mail and delete this message from your system. If you are not the intended recipient you are hereby notified that any dissemination, copy or disclosure of this e-mail is strictly prohibited.</span></span></p>
                        </td>
                        </tr>
                        <tr>
                        <td style="border-bottom: 5px solid #7e3f98; height: 5px; font-size: 7pt;border-radius: 20px;" colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        </tbody>
                        </table>
                        </td>
                        </tr>
                        </tbody>
                        </table>';


        //Recipients
        $mail->setFrom('info@cgeee.org', 'Queen Arise');
        $mail->addAddress($email, $firstName.' '.$lastName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Ticket for the Queen Arise Conference is Here';
        $mail->Body = $emailBody;
        
        $mail->send();


        
    }

} catch (PDOException $e){
    //report the error message
    echo $e->getMessage();
}