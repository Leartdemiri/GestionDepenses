<?php

require_once "../php/constants.php";
require_once "../php/database.php";

header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];

// Security 
if($method !== "POST"){
    http_response_code(HTTP_STATUS_METHOD_NOT_ALLOWED);
    header("Location: ../index.php");
    die();
}

if($_POST['email'])


// Récupérer les données du formulaire
$email = htmlspecialchars($_POST['email']) ?? null;
$password = $_POST['password'] ?? null;

if (!$email || !$password) {
    http_response_code(HTTP_STATUS_BAD_REQUEST);
    die("Email et mot de passe requis.");
}

try {
    // Vérifier si l'utilisateur existe
    $sql = "SELECT idUser, Password, Token FROM user WHERE email = :email";
    $statement = DataBase::dbRun($sql, [':email' => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['Password'])) {
        http_response_code(HTTP_STATUS_UNAUTHORIZED);
        die("Identifiants incorrects.");
    }

    // Générer un nouveau token pour la session
    $token = bin2hex(random_bytes(16));
    $updateTokenSql = "UPDATE user SET Token = :token WHERE idUser = :id";
    DataBase::dbRun($updateTokenSql, [':token' => $token, ':id' => $user['idUser']]);

    // Démarrer une session et stocker le token
    session_start();
    $_SESSION['token'] = $token;

    // Rediriger vers la page d'accueil
    http_response_code(HTTP_STATUS_OK);
    header("Location: ../index.php");
} catch (Throwable $th) {
    http_response_code(HTTP_STATUS_INTERNAL_SERVER_ERROR);
    die("Erreur lors de la connexion.");
}