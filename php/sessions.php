<?php

require_once "database.php";

session_start();

if (!isset($_SESSION[SESSION_TOKEN_KEY])) {
    http_response_code(HTTP_STATUS_UNAUTHORIZED);
    die("Accès non autorisé.");
}

$token = $_SESSION[SESSION_TOKEN_KEY];
$user = readOneUserByToken($token);
if (!$user) {
   
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    http_response_code(HTTP_STATUS_UNAUTHORIZED);
    die("Session invalide.");
}