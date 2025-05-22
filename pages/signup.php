<?php
require_once "../php/functions.php";

header("Access-Control-Allow-Origin: *");



// --- Gestion inscription Google ---
if (isset($_POST['google_credential'])) {
    $token = $_POST['google_credential'];
    $client_id = '139570543794-sf77h7hiah3l8q3l2m0u8r2r29ftu3a7.apps.googleusercontent.com';
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($token);
    $data = json_decode(file_get_contents($url), true);

    $currency = $_POST['currency'];

    if ($data && isset($data['email']) && $data['aud'] === $client_id) {
        $email = $data['email'];
        $fname = $data['given_name'] ?? '';
        $lname = $data['family_name'] ?? '';
        $pwd = bin2hex(random_bytes(8)); // Mot de passe aléatoire
        $tokenSession = createToken();
        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);

        if (checkIfUserExist($email) == null) {
            try {
                DataBase::begin();
                createUser($email, $fname, $lname, $tokenSession, $hashedPwd, $currency);
                DataBase::commit();
            } catch (Throwable $e) {
                DataBase::rollback();
                header("Location: ../index.php?error=google_signup_failed");
                exit();
            }
            try {
                DataBase::begin();
                $user = checkIfUserExist($email);
                createEconomy("0", "0", "0", $user[USER_TABLE_ID]);
                DataBase::commit();
            } catch (Throwable $e) {
                DataBase::rollback();
                header("Location: ../index.php?error=google_signup_failed");
                exit();
            }
        } else {
            $user = checkIfUserExist($email);
            $tokenSession = createToken();
            updateUserToken($user[USER_TABLE_ID], $tokenSession);
        }

        session_start();
        $_SESSION[SESSION_TOKEN_KEY] = $tokenSession;
        header("Location: ../home/");
        exit();
    } else {
        header("Location: ../index.php?error=google_signup_failed");
        exit();
    }
}

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
    header("Location: " . OUTSIDE_TO_INDEX_PATH . "?" . ERROR_GET_KEY . "=user_already_exists");
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
    if (!$user || !isset($user[USER_TABLE_ID])) {
        internalServerErrorHandling();
    }

    $id = $user[USER_TABLE_ID];
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
    $_SESSION[SESSION_TOKEN_KEY] = $token;
    header("Location: ../home/");
    exit();
} else {
    internalServerErrorHandling();
}
