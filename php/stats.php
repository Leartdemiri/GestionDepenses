<?php
session_start();
require_once "../php/database.php";

// Toujours déclarer le type de contenu JSON
header('Content-Type: application/json');

// === FONCTIONS ===

function getUserByToken(string $token): ?array
{
    try {
        $sql = "SELECT * FROM user WHERE Token = :token";
        $stmt = DataBase::dbRun($sql, [':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (Throwable $e) {
        error_log("Erreur getUserByToken : " . $e->getMessage());
        return null;
    }
}

function getMonthlyExpenses(int $userId): array
{
    try {
        $sql = "
            SELECT MONTH(sp.dateCreated) AS month, SUM(sp.amount) AS total
            FROM spending sp
            JOIN economy e ON sp.idEconomy = e.idEconomy
            WHERE e.idUser = :userId
            GROUP BY MONTH(sp.dateCreated)
            ORDER BY MONTH(sp.dateCreated)
        ";
        $stmt = DataBase::dbRun($sql, [':userId' => $userId]);

        $monthly = array_fill(1, 12, 0);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $mois = (int) $row['month'];
            $total = (float) $row['total'];
            if ($mois >= 1 && $mois <= 12) {
                $monthly[$mois] = $total;
            }
        }

        return array_values($monthly);
    } catch (Throwable $e) {
        error_log("Erreur getMonthlyExpenses : " . $e->getMessage());
        return array_fill(0, 12, 0); // renvoie 12 mois à 0 en cas d’erreur
    }
}

function getExpensesByType(int $userId): array
{
    try {
        $sql = "
            SELECT st.Type, SUM(sp.amount) AS total
            FROM spending sp
            JOIN spendTypes st ON sp.idSpendType = st.idSpendingType
            JOIN economy e ON sp.idEconomy = e.idEconomy
            WHERE e.idUser = :userId
            GROUP BY st.Type
            ORDER BY total DESC
        ";
        $stmt = DataBase::dbRun($sql, [':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        error_log("Erreur getExpensesByType : " . $e->getMessage());
        return [];
    }
}

function getLatestExpenses(int $userId, int $limit = 10): array
{
    $sql = "
        SELECT 
            sp.idSpending AS expenseId,
            st.Type AS title,
            sp.amount,
            u.currency,
            DATE_FORMAT(sp.dateCreated, '%d %M %Y') AS date
        FROM spending sp
        JOIN spendTypes st ON sp.idSpendType = st.idSpendingType
        JOIN economy e ON sp.idEconomy = e.idEconomy
        JOIN user u ON e.idUser = u.idUser
        WHERE e.idUser = :userId
        ORDER BY sp.dateCreated DESC
        LIMIT :limit
    ";

    $sql = str_replace(':limit', (int) $limit, $sql);

    $stmt = DataBase::dbRun($sql, [':userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// === AUTHENTIFICATION ===

if (!isset($_SESSION['token'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$user = getUserByToken($_SESSION['token']);
if (!$user) {
    http_response_code(403);
    echo json_encode(['error' => 'Utilisateur invalide']);
    exit;
}

// === RÉPONSE JSON ===

try {
    $response = [
        'monthlyExpenses' => getMonthlyExpenses((int) $user['idUser']),
        'expenseTypes' => getExpensesByType((int) $user['idUser']),
        'latestExpenses' => getLatestExpenses((int) $user['idUser'])
    ];
    echo json_encode($response, JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
    error_log("Erreur JSON : " . $e->getMessage());
    exit;
}
