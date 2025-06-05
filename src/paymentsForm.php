<?php
require_once "functions.php";

session_start();
    $user = checkIfUnlogged(OUTSIDE_TO_INDEX_PATH);

if (isset($_POST['action']) && $_POST['action'] === 'deleteExpense') {
    $expenseId = filter_input(INPUT_POST, 'expenseId', FILTER_VALIDATE_INT);

    if ($expenseId && deleteExpense($expenseId, $user[USER_TABLE_ID])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Impossible de supprimer la dépense.']);
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'updateExpense') {
    
    $expenseId = filter_input(INPUT_POST, 'expenseId', FILTER_VALIDATE_INT);
    $newAmount = filter_input(INPUT_POST, 'newAmount', FILTER_VALIDATE_FLOAT);

    if ($expenseId && $newAmount && $newAmount > 0) {
        try {
            $db = DataBase::db();

            // Vérifiez si la dépense appartient à l'utilisateur
            $sql = "
                 SELECT sp.amount, e.BaseMoney, e.idEconomy
                 FROM spending sp
                 JOIN economy e ON sp.idEconomy = e.idEconomy
                 WHERE sp.idSpending = :arg1 AND e." . USER_TABLE_ID . " = :arg2 
             ";
            $params = [':arg1' => $expenseId, ":arg2" => $user[USER_TABLE_ID]];
            $stmt = DataBase::dbRun($sql, $params);
            $expense = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$expense) {
                echo json_encode(['success' => false, 'error' => 'Dépense non trouvée ou non autorisée.']);
                exit();
            }

            $currentAmount = (float) $expense['amount'];
            $currentBalance = (float) $expense['BaseMoney'];
            $economyId = (int) $expense['idEconomy'];

            // Calculez le nouveau solde
            $newBalance = $currentBalance + $currentAmount - $newAmount;

            // Vérifiez si le nouveau solde est négatif
            if ($newBalance < 0) {
                echo json_encode(['success' => false, 'error' => 'Solde insuffisant pour cette modification.']);
                exit();
            }

            // Mettre à jour la dépense
            updateSpendingAmount($expenseId, $newAmount);

            // Mettre à jour le solde
            updateBaseMoney($newBalance, $economyId);

            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            echo json_encode(['success' => false, 'error' => 'Erreur serveur.' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Données invalides.']);
    }
    exit();
}