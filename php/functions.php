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
            header("Location: ".OUTSIDE_TO_INDEX_PATH."?".ERROR_GET_KEY."=missing_fields");
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

function displayFormErrors() {
    if (isset($_GET[ERROR_GET_KEY])) {
        echo '<div class="error-container" style="margin-bottom: 1em;">';
        switch ($_GET["error"]) {
            // Login-related errors
            case "login_unexistant_user":
                echo '<div class="error-msg" style="color: red;">Cet utilisateur n\'existe pas.</div>';
                break;
            case "wrong_login_password":
                echo '<div class="error-msg" style="color: red;">Mot de passe incorrect.</div>';
                break;
            case "login_failed":
                echo '<div class="error-msg" style="color: red;">Une erreur est survenue. Veuillez réessayer.</div>';
                break;

            // Sign-up-related errors
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

function formatMoney(int $money){
    if (countDigits($money) >= 10) {
        return number_format($money / 1000000000, 2, ".", " ") . " Mia";
    }else if (countDigits($money) >= 7 && countDigits($money) < 10) {
        return number_format($money / 1000000, 2, ".", " ") . " Mio";
    } else if (countDigits($money) >= 4 && countDigits($money) < 7) {
        return number_format($money / 1000, 2, ".", " ") . " K";
    } else {
        return number_format($money, 2, ".", " ");
    }
}

function countDigits($number) {
    // Convertir le nombre en chaîne de caractères
    $numberStr = (string) abs($number); // Utiliser abs() pour gérer les nombres négatifs
    return strlen($numberStr);
}

function internalServerErrorHandling(){
    http_response_code(HTTP_STATUS_BAD_REQUEST);
    header("Location: ".OUTSIDE_TO_INDEX_PATH."?".ERROR_GET_KEY."=".ERROR_TYPE_SERVER);
    exit();
}


/**
 * Supprime une dépense par son ID.
 *
 * @param int $expenseId L'ID de la dépense à supprimer.
 * @return bool Retourne true si la suppression a réussi, false sinon.
 */
function deleteExpense(int $expenseId, int $userId): bool {
    try {
        $db = DataBase::db();

        // Vérifiez si la dépense appartient à l'utilisateur
        $checkStmt = $db->prepare("
            SELECT sp.idSpending
            FROM spending sp
            JOIN economy e ON sp.idEconomy = e.idEconomy
            WHERE sp.idSpending = :idSpending AND e.idUser = :idUser
        ");
        $checkStmt->execute([':idSpending' => $expenseId, ':idUser' => $userId]);

        // Si aucune dépense n'est trouvée, retournez false
        if (!$checkStmt->fetch()) {
            error_log("Tentative de suppression non autorisée pour l'ID de dépense : $expenseId par l'utilisateur : $userId");
            return false;
        }

        // Supprimez la dépense
        $deleteStmt = $db->prepare("DELETE FROM spending WHERE idSpending = :idSpending");
        $deleteStmt->bindParam(':idSpending', $expenseId, PDO::PARAM_INT);

        // Retournez true si la suppression a réussi
        return $deleteStmt->execute();
    } catch (Throwable $e) {
        error_log("Erreur lors de la suppression de la dépense : " . $e->getMessage());
        return false;
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteExpense') {
    session_start();
    $user = checkIfUnlogged(OUTSIDE_TO_INDEX_PATH);

    $expenseId = filter_input(INPUT_POST, 'expenseId', FILTER_VALIDATE_INT);
    error_log("Reçu pour suppression : expenseId = $expenseId");

    if ($expenseId && deleteExpense($expenseId, $user['idUser'])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Impossible de supprimer la dépense.']);
    }
    exit();
}