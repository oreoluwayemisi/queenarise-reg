<?php
ini_set('display_errors', 1);

// Pull in the Database Configuration file
require 'dbconfig.php';

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

        
    }

} catch (PDOException $e){
    //report the error message
    echo $e->getMessage();
}