<?php
require_once "../php/functions.php";

header("Access-Control-Allow-Origin: *");

// Security -- Check request method
checkMethod(OUTSIDE_TO_INDEX_PATH);

// Security -- Validate required POST fields
$requiredFields = ['firstname', 'lastname', 'email', 'password', 'selectCurrency'];
checkPOSTFields($requiredFields);

// Sanitize inputs
$fname = htmlspecialchars(filter_input(INPUT_POST, "firstname", FILTER_UNSAFE_RAW));
$lname = htmlspecialchars(filter_input(INPUT_POST, "lastname", FILTER_UNSAFE_RAW));
$currency = htmlspecialchars(filter_input(INPUT_POST, "selectCurrency", FILTER_UNSAFE_RAW));
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$pwd = $_POST["password"];
$userOK = false;
$ecoOK = false;

// Check if user already exists
if (checkIfUserExist($email) != null) {
    header("Location: ".OUTSIDE_TO_INDEX_PATH."?".ERROR_GET_KEY."=user_already_exists");
    exit();
}

// First DB transaction: create user
try {
    DataBase::begin();

    $token = createToken(); // Custom function
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);

    createUser($email, $fname, $lname, $token, $hashedPwd, $currency);
    $userOK = true;

    DataBase::commit();
} catch (Throwable $e) {
    DataBase::rollback();
    internalServerErrorHandling();
}

// Second DB transaction: create default economy
try {
    DataBase::begin();

    $user = checkIfUserExist($email);
    if (!$user || !isset($user["idUser"])) {
        header("Location: ".OUTSIDE_TO_INDEX_PATH."?".ERROR_GET_KEY."=internal_server_error");
        exit();
    }

    $id = $user["idUser"];
    createEconomy("0", "0", "0", $id);
    $ecoOK = true;

    DataBase::commit();
} catch (Throwable $e) {
    DataBase::rollback();
    internalServerErrorHandling();
}

// Final check and redirect
if ($userOK && $ecoOK) {
    session_start();
    $_SESSION[SESSION_TOKEN_KEY] = null;
    $_SESSION[SESSION_TOKEN_KEY] = $token;
    header("Location: ../home/");
    exit();
} else {
    internalServerErrorHandling();
}
