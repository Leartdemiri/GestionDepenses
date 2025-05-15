<?php

require_once "../php/functions.php";

header("Access-Control-Allow-Origin: *");




// --- Gestion connexion Google ---
if (isset($_POST['google_credential'])) {
    $token = $_POST['google_credential'];
    $client_id = '139570543794-sf77h7hiah3l8q3l2m0u8r2r29ftu3a7.apps.googleusercontent.com';
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($token);
    $data = json_decode(file_get_contents($url), true);

    if ($data && isset($data['email']) && $data['aud'] === $client_id) {
        $email = $data['email'];
        $user = checkIfUserExist($email);
        if (!$user) {
            header("Location: ../index.php?error=login_unexistant_user");
            exit();
        }
        $tokenSession = createToken();
        updateUserToken($user["idUser"], $tokenSession);
        session_start();
        $_SESSION[SESSION_TOKEN_KEY] = $tokenSession;
        header("Location: ../home/");
        exit();
    } else {
        header("Location: ../index.php?error=google_login_failed");
        exit();
    }
}

//Security -- On est bien en POST?
checkMethod(OUTSIDE_TO_INDEX_PATH);

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
    header("Location: " . OUTSIDE_TO_INDEX_PATH . "?" . ERROR_GET_KEY . "=login_unexistant_user");
    die("NoUser");
} else {
    if (!password_verify($password, $user['Password'])) {
        http_response_code(HTTP_STATUS_UNAUTHORIZED);
        header("Location: " . OUTSIDE_TO_INDEX_PATH . "?" . ERROR_GET_KEY . "=wrong_login_password");
        die("WrongPwd");
    }
}

try {
    // Générer un nouveau token pour la session
    $token = createToken();
    updateUserToken($user["idUser"], $token);

    // Démarrer une session et stocker le token
    session_start();
    $_SESSION[SESSION_TOKEN_KEY] = $token;

    // Rediriger vers la page d'accueil
    http_response_code(HTTP_STATUS_OK);
    header("Location: ./home/");
} catch (Throwable $th) {
    http_response_code(HTTP_STATUS_INTERNAL_SERVER_ERROR);
    header("Location: ." . OUTSIDE_TO_INDEX_PATH . "?" . ERROR_GET_KEY . "=login_failed");
    die("Erreur lors de la connexion.");
}
