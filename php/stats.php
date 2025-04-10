<?php
session_start();
require_once "../php/database.php";

function getUserByToken(string $token): ?array {
    $sql = "SELECT * FROM user WHERE Token = :token";
    $stmt = DataBase::dbRun($sql, [':token' => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function getMonthlyExpenses(int $userId): array {
    $sql = "
        SELECT MONTH(sp.dateCreated) AS month, SUM(sp.amount) AS total
        FROM spending sp
        JOIN economy e ON sp.idEconomy = e.idEconomy
        WHERE e.idUser = :userId
        GROUP BY MONTH(sp.dateCreated)
    ";

    $stmt = DataBase::dbRun($sql, [':userId' => $userId]);
    $monthly = array_fill(1, 12, 0); 

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $monthly[(int)$row['month']] = (float)$row['total'];
    }

    return array_values($monthly);
}

function getExpensesByType(int $userId): array {
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
}

// Exécution
header('Content-Type: application/json');

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

echo json_encode([
    'monthlyExpenses' => getMonthlyExpenses($user['idUser']),
    'expenseTypes' => getExpensesByType($user['idUser'])
]);
