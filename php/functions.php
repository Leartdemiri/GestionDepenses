<?php
require_once "constants.php";
require_once "database.php";
require_once "crud.php";

function createToken()
{
    return bin2hex(random_bytes(16));
}

function checkPOSTFields($fieldsList)
{
    foreach ($fieldsList as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            http_response_code(HTTP_STATUS_BAD_REQUEST);
            header("Location: ../index.php?error=missing_fields");
            exit();
        }
    }
}

function checkIfUnlogged(string $redirection)
{
    if (!isset($_SESSION[SESSION_TOKEN_KEY])) {
        header("Location: $redirection");
        die("Unlogged");
    } else {
        $token = $_SESSION[SESSION_TOKEN_KEY];
        $user = readOneUserByToken($token);
        if (!$user) {
            header("Location: $redirection");
            die("Unlogged");
        }else{
            return $user;
        }
    }
}


function checkIfLogged(string $redirection)
{
    if (isset($_SESSION[SESSION_TOKEN_KEY])) {
        $token = $_SESSION[SESSION_TOKEN_KEY];
        $user = readOneUserByToken($token);
        if ($user) {
            header("Location: $redirection");
        }
    }
}

function checkMethod($redirection)
{
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method !== "POST") {
        http_response_code(HTTP_STATUS_METHOD_NOT_ALLOWED);
        header("Location: $redirection");
        die();
    }
}



function logout(int $idUser,string $token): void
{
    session_start();
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    updateUserToken($idUser,$token);

    session_destroy();
}






/// MODE EMPLOI ///
// COPIER - COLLER - METTRE DANS CE QUE VOUS VOULEZ DEBBUGGER - REMPLIR LE VARDUMP - UTILISER UN TRYCATCh
function outputDebug()
{
    // echo "Erreur lors de la création de l'utilisateur : " . $e->getMessage();

    // // Facultatif : afficher plus d'informations pour déboguer
    // echo "<pre>";
    // var_dump($email, $fname, $lname, $token, $hashedPwd, $currency);
    // echo "</pre>";
}