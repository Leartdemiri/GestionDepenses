<?php
session_start();
require_once "database.php";

// Always declare JSON content type for a REST API
header('Content-Type: application/json');

// === UTILITY FUNCTIONS FOR THE DATABASE ===

/**
 * Retrieves user information based on their token.
 *
 * @param string $token Session token
 * @return array|null User data or null if not found or on error
 */
function getUserByToken(string $token): ?array
{
    try {
        $sql = "SELECT * FROM user WHERE Token = :token";
        $stmt = DataBase::dbRun($sql, [':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (Throwable $e) {
        error_log("Error in getUserByToken: " . $e->getMessage());
        return null;
    }
}

/**
 * Retrieves total monthly expenses of the user over the year.
 *
 * @param int $userId User ID
 * @return array An array with 12 entries (one per month), containing totals
 */
function getMonthlyExpenses(int $userId): array
{
    try {
        $sql = "
            SELECT MONTH(sp.dateCreated) AS month, SUM(sp.amount) AS total
            FROM spending sp
            JOIN economy e ON sp.idEconomy = e.idEconomy
            WHERE e." . USER_TABLE_ID . " = :arg1
            GROUP BY MONTH(sp.dateCreated)
            ORDER BY MONTH(sp.dateCreated)
        ";
        $stmt = DataBase::dbRun($sql, [':arg1' => $userId]);

        // Initialize 12 months to 0
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
        error_log("Error in getMonthlyExpenses: " . $e->getMessage());
        return array_fill(0, 12, 0); // Return 12 months with 0 on error
    }
}

/**
 * Calculates the total expenses grouped by type (e.g., Food, Transport, etc.).
 *
 * @param int $userId User ID
 * @return array List of expense types with their totals
 */
function getExpensesByType(int $userId): array
{
    try {
        $sql = "
            SELECT st.Type, SUM(sp.amount) AS total
            FROM spending sp
            JOIN spendTypes st ON sp.idSpendType = st.idSpendingType
            JOIN economy e ON sp.idEconomy = e.idEconomy
            WHERE e." . USER_TABLE_ID . " = :userId
            GROUP BY st.Type
            ORDER BY total DESC
        ";
        $stmt = DataBase::dbRun($sql, [':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        error_log("Error in getExpensesByType: " . $e->getMessage());
        return [];
    }
}

/**
 * Retrieves the user's latest expenses (default last 10).
 *
 * @param int $userId User ID
 * @param int $limit Number of rows to return (default 10)
 * @return array List of recent expenses
 */
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
        JOIN user u ON e." . USER_TABLE_ID . " = u." . USER_TABLE_ID . "
        WHERE e." . USER_TABLE_ID . " = :userId
        ORDER BY sp.dateCreated DESC
        LIMIT :limit
    ";

    // Avoid PDO::bindValue with LIMIT (manual replacement)
    $sql = str_replace(':limit', (int) $limit, $sql);

    $stmt = DataBase::dbRun($sql, [':userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// === SESSION AUTHENTICATION ===

if (!isset($_SESSION['token'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user = getUserByToken($_SESSION['token']);
if (!$user) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Invalid user']);
    exit;
}

// === CONSTRUCTING THE JSON RESPONSE ===

try {
    $userId = (int) $user[USER_TABLE_ID];

    $response = [
        'monthlyExpenses' => getMonthlyExpenses($userId), // Data for monthly charts
        'expenseTypes'    => getExpensesByType($userId),  // Data for pie charts (types)
        'latestExpenses'  => getLatestExpenses($userId)   // List of latest expenses
    ];

    echo json_encode($response, JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    error_log("JSON error: " . $e->getMessage());
    exit;
}
