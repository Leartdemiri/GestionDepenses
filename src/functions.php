<?php
require_once "constants.php";
require_once "database.php";
require_once "crud.php";

/**
 * Generates a secure 32-character hexadecimal token.
 *
 * @return string The generated token
 */
function createToken()
{
    return bin2hex(random_bytes(16));
}

/**
 * Checks that all specified fields in the POST request are present and not empty.
 * Redirects with an error if any field is missing or empty.
 *
 * @param array $fieldsList List of field names to check
 */
function checkPOSTFields($fieldsList)
{
    foreach ($fieldsList as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            http_response_code(HTTP_STATUS_BAD_REQUEST);
            header("Location: " . OUTSIDE_TO_INDEX_PATH . "?" . ERROR_GET_KEY . "=missing_fields");
            exit();
        }
    }
}

/**
 * Checks that the user is not logged in.
 * If they are logged in or the token is invalid, redirects to the specified page.
 *
 * @param string $redirection URL to redirect to if the user is not logged in
 * @return array|null User data if logged in, otherwise script stops
 */
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
        } else {
            return $user;
        }
    }
}

/**
 * Checks if the user is already logged in.
 * If so, automatically redirects (useful to prevent logged-in users from accessing login pages, etc.).
 *
 * @param string $redirection URL to redirect to if already logged in
 */
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

/**
 * Checks that the request method is POST.
 * If not, sends a 405 error and redirects.
 *
 * @param string $redirection URL to redirect to if method is not POST
 */
function checkMethod($redirection)
{
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method !== "POST") {
        http_response_code(HTTP_STATUS_METHOD_NOT_ALLOWED);
        header("Location: $redirection");
        die();
    }
}

/**
 * Properly logs the user out:
 * - Empties the session
 * - Deletes the cookie
 * - Updates the user's token
 * - Destroys the session
 *
 * @param int $idUser ID of the user to log out
 * @param string $token New token to store to invalidate the old one
 */
function logout(int $idUser, string $token): void
{
    session_start();
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    updateUserToken($idUser, $token);
    session_destroy();
}

/**
 * Displays form error messages based on the error code passed in the URL (?error=...).
 */
function displayFormErrors()
{
    if (isset($_GET[ERROR_GET_KEY])) {
        echo '<div class="error-container" style="margin-bottom: 1em;">';
        switch ($_GET["error"]) {
            // Erreurs de connexion
            case "login_unexistant_user":
                echo '<div class="error-msg" style="color: red;">Cet utilisateur n\'existe pas.</div>';
                break;
            case "wrong_login_password":
                echo '<div class="error-msg" style="color: red;">Mot de passe incorrect.</div>';
                break;
            case "login_failed":
                echo '<div class="error-msg" style="color: red;">Une erreur est survenue. Veuillez réessayer.</div>';
                break;

            // Erreurs d'inscription
            case "user_already_exists":
                echo '<div class="error-msg" style="color: red;">Un compte avec cet e-mail existe déjà.</div>';
                break;
            case "error_during_creation":
                echo '<div class="error-msg" style="color: red;">Erreur lors de la création du compte.</div>';
                break;
            case "error_economy":
                echo '<div class="error-msg" style="color: red;">Erreur lors de la création de l\'économie utilisateur.</div>';
                break;
            case "internal_server_error":
                echo '<div class="error-msg" style="color: red;">Erreur serveur interne. Veuillez réessayer.</div>';
                break;

            default:
                echo '<div class="error-msg" style="color: red;">Erreur inconnue.</div>';
        }
        echo '</div>';
    }
}

/**
 * Formats a monetary value based on its size:
 * - "K" for thousands
 * - "Mio" for millions
 * - "Mia" for billions
 *
 * @param int $money Amount to format
 * @return string Formatted amount with unit
 */
function formatMoney(int $money)
{
    if (countDigits($money) >= 10) {
        return number_format($money / 1000000000, 2, ".", " ") . " Mia";
    } else if (countDigits($money) >= 7 && countDigits($money) < 10) {
        return number_format($money / 1000000, 2, ".", " ") . " Mio";
    } else if (countDigits($money) >= 4 && countDigits($money) < 7) {
        return number_format($money / 1000, 2, ".", " ") . " K";
    } else {
        return number_format($money, 2, ".", " ");
    }
}

/**
 * Counts the number of digits in a given number.
 *
 * @param int|float $number The number to analyze
 * @return int Number of digits
 */
function countDigits($number)
{
    $numberStr = (string) abs($number); // Ignore negative sign
    return strlen($numberStr);
}

/**
 * Redirects to the internal server error page with the appropriate GET code.
 */
function internalServerErrorHandling()
{
    http_response_code(HTTP_STATUS_BAD_REQUEST);
    header("Location: " . OUTSIDE_TO_INDEX_PATH . "?" . ERROR_GET_KEY . "=" . ERROR_TYPE_SERVER);
    exit();
}
