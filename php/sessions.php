<?php

require_once "database.php";

session_start();

if (!isset($_SESSION['token'])) {
    http_response_code(HTTP_STATUS_UNAUTHORIZED);
    die("Accès non autorisé.");
}

$token = $_SESSION['token'];
$sql = "SELECT idUser FROM user WHERE Token = :token";
$statement = DataBase::dbRun($sql, [':token' => $token]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    http_response_code(HTTP_STATUS_UNAUTHORIZED);
    die("Session invalide.");
}