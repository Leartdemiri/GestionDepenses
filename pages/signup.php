<?php
require_once "../php/crud.php";
require_once "../php/functions.php";

header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
// Security
if ($method !== "POST") {
    http_response_code(HTTP_STATUS_METHOD_NOT_ALLOWED);
    header("Location: ../index.php");
    die();
}

// Security -- Est-ce que on a recu les bonnes données?
$requiredFields = ['firstname', 'lastname', 'email', 'password', 'selectCurrency'];
checkPOSTFields($requiredFields);

$fname = htmlspecialchars(filter_input(INPUT_POST, "firstname", FILTER_UNSAFE_RAW));
$lname = htmlspecialchars(filter_input(INPUT_POST, "lastname", FILTER_UNSAFE_RAW));
$currency = htmlspecialchars(filter_input(INPUT_POST, "selectCurrency", FILTER_UNSAFE_RAW));
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$pwd = $_POST["password"];
$userOK = false;
$ecoOK = false;

try {   
    DataBase::begin();

    // Création utilisateur
    $token = createToken(); // Fonction personnalisée à toi
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);
    createUser($email, $fname, $lname, $token, $hashedPwd, $currency);
    $userOK = true;
    DataBase::commit();
} catch (Throwable $e) {
    DataBase::rollback();
    http_response_code(HTTP_STATUS_INTERNAL_SERVER_ERROR);
    // header("Location: ../index.php?error=user_creation_failed");
    
    echo "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
    
    // Facultatif : afficher plus d'informations pour déboguer
    echo "<pre>";
    var_dump($email, $fname, $lname, $token, $hashedPwd, $currency);
    echo "</pre>";

    exit();
}

try {
    DataBase::begin();

    // Get the new created user
    $user = checkIfUserExist($email);
    $id = $user["idUser"];

    // DefaultValue economy
    createEconomy("0", "0", "0", "0", $id);
    $ecoOK = true;
    DataBase::commit();
} catch (Throwable $e) {
    DataBase::rollback();
    http_response_code(HTTP_STATUS_INTERNAL_SERVER_ERROR);
    header("Location: ../index.php?error=economy_creation_failed");
    exit();
}

if ($userOK == true || $ecoOK == true) {
    session_start();
    $_SESSION['token'] = $token;
    header("Location: home/");
} else {
    http_response_code(HTTP_STATUS_BAD_REQUEST);
    header("Location: ../index.php");
}