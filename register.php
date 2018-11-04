<?php
// Pull in the Database Configuration file
require 'dbconfig.php';

// Capture Post Data
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$city = $_POST['city'];
$firstName = $_POST['firstName'];
$ticketType;
if($_POST['ticketType'] == 'Duchess') {
    $ticketType = 1;
} elseif ($_POST['ticketType'] == 'Empress') {
    $ticketType = 2;
} elseif($_POST['ticketType'] == 'Queen') {
    $ticketType = 3;
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
            "ticket_id"         =>  $ticketType
        )
    );

    

} catch (PDOException $e){
    //report the error message
    echo $e->getMessage();
}