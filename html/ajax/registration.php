<?php
require '../../vendor/autoload.php';
include '../../lib/App/database.php';
include '../../lib/Helper/validator.php';
include '../../lib/Response/navigate.php';

//here we are getting the values from the POST that the user has submitted
$errors = [];
$data = [];

if(empty($_POST['firstName'])){
    $errors['firstName'] = 'First Name is required.';
}else{
    $firstName = $_POST['firstName'];
}

if(empty($_POST['lastName'])){
    $errors['lastName'] = 'Last Name is required.';
}else{
    $lastName = $_POST['lastName'];
}

if(empty($_POST['email'])){
    $errors['email'] = 'Email is required.';
}else{
    $email = $_POST['email'];
}

if(empty($_POST['confirm_email'])){
    $errors['confirm_email'] = 'Email Confirmation is required.';
}else{
    $email_confirm = $_POST['confirm_email'];
}

if(empty($_POST['g_recaptcha'])){
    $errors['g_recaptcha'] = 'Google Recaptcha is required.';
}else{
    $g_recaptcha = $_POST['g_recaptcha'];
}

if (isset($_POST['optin'])) {
    $optIn = $_POST['optin'];
} 

//add the current date
$date = date("Y-m-d");

//if the initial values given are not empty, continue foward
if (empty($errors)) {

        //create database and validator objects
        $database = new Database();
        $validator = new Validator();

        //create user info array
        $info = [
            'entry_date' => $date,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'email_confirm' => $email_confirm,
            'optIn' => $optIn
        ];

        //validate info
        $errors = $validator->validate($info);

        //if validation passed, continue submission process
        if (empty($errors)) {
            // check daily limit using query
            $query = [
                'IndexName' => 'email-entry_date-index',
                'KeyConditionExpression' => 'email = :email and entry_date = :entry_date',
                'ExpressionAttributeValues' => [
                    ':email' => $info['email'],
                    ':entry_date' => $info['entry_date']
                ]
            ];

            //if the query returns a value greater than 0 for the day, go to already entered page
            if ($database->queryDB($query) > 0) {
                new Navigate("alreadyentered.php");

            }

            //if no errors or redirects up until this point, add the item to db and go to thank you page
            if($database->addItem($info)){
                new Navigate("thankyou.php");
            }
        }
}

//send any errors that occured at any point during the submission process
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Success!';
}

echo json_encode($data);
?>