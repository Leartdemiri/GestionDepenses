<?php

require_once "../php/functions.php";

header("Access-Control-Allow-Origin: *");

//Security -- On est bien en POST?
checkMethod("../index.php");

// Security -- Est-ce que on est déja loggé?
checkIfLogged("home/");

// Security -- Est-ce que on a recu les bonnes données?
$requiredFields = ['email', 'password'];
checkPOSTFields($requiredFields);

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$password = $_POST["password"];


// Vérifier si l'utilisateur existe
$user = checkIfUserExist($email);
if (!$user) {
    http_response_code(HTTP_STATUS_BAD_REQUEST);
    header("Location: ../index.php?error=login_unexistant_user");
    die("NoUser");
}else{
    if(!password_verify($password, $user['Password'])){
        http_response_code(HTTP_STATUS_UNAUTHORIZED);
        header("Location: ../index.php?error=wrong_login_password");
        die("WrongPwd");
    }
}

try {
    // Générer un nouveau token pour la session
    $token = createToken();
    updateUserToken($user["idUser"],$token);

    // Démarrer une session et stocker le token
    session_start();
    $_SESSION[SESSION_TOKEN_KEY] = $token;

    // Rediriger vers la page d'accueil
    http_response_code(HTTP_STATUS_OK);
    header("Location: ./home/");
} catch (Throwable $th) {
    http_response_code(HTTP_STATUS_INTERNAL_SERVER_ERROR);
    header("Location: ../index.php?error=login_failed");
    die("Erreur lors de la connexion.");
}
