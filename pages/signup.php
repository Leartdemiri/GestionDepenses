<?php

header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];

// Security
if($method !== "POST"){
    http_response_code(HTTP_STATUS_METHOD_NOT_ALLOWED);
    header("Location: ../index.php");
    die();
}

// Security -- Est-ce que on a recu les bonnes données?
if(!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) ||!isset($_POST['password'])){
    http_response_code(HTTP_STATUS_BAD_REQUEST);
    header("Location: ../index.php");
    die();
}


$fname  =   htmlspecialchars(filter_input(INPUT_POST, "firstname", FILTER_UNSAFE_RAW));
$lname  =   htmlspecialchars(filter_input(INPUT_POST, "lastname", FILTER_UNSAFE_RAW));
$currency  =   htmlspecialchars(filter_input(INPUT_POST, "currency", FILTER_UNSAFE_RAW));
$email  =   filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$pwd    =   $_POST["password"]; 

if (!$fname || !$lname || !$email || !$pwd) {
    http_response_code(HTTP_STATUS_BAD_REQUEST);
    header("Location: ../index.php");
    die();
}

try {
    $token = createToken();
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);
    createUser($email, $fname, $lname, $token, $hashedPwd, $currency);

    
    createEconomy()

} catch (\Throwable $th) {
    //throw $th;
}