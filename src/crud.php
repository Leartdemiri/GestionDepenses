<?php
/*
Fichier crud pour chaque classe de la base de donnée
*/

/* ======================================================================================================================================================================*/
/* ==== | USER | ==== */
/* ======================================================================================================================================================================*/

///
/// Créer un utilisateur dans la base de donnée et le login immédiatement en lui créant un token
/// <param> string $email       <param> : Paramètre en STRING, Email de l'utilisateur
/// <param> string $firstname   <param> : Paramètre en STRING, Prénom de l'utilisateur
/// <param> string $lastname    <param> : Paramètre en STRING, Nom de famille de l'utilisateur
/// <param> string $token       <param> : Paramètre en STRING, Le token de de l'utilisateur qui permet de savoir qui est si on est connecté
/// <param> string $password    <param> : Paramètre en STRING, Mot de pass de l'utilisateur ( préférablement hashé en BCrypt)
/// <param> string $currency    <param> : Paramètre en STRING, Currency utilisé et préférée de l'utilisateur
function createUser(string $email, string $firstname, string $lastname, string $token, string $password, string $currency)
{
    $sql = "INSERT INTO user (email, Firstname, Lastname, Token, Password, currency) VALUES (:arg1, :arg2, :arg3, :arg4, :arg5, :arg6)";
    $params = [
        ":arg1" => $email,
        ":arg2" => $firstname,
        ":arg3" => $lastname,
        ":arg4" => $token,
        ":arg5" => $password,
        ":arg6" => $currency
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Récupère toutes les données de tout les utilisateurs de la base de donnée
function readAllUsers()
{
    $sql = "SELECT " . USER_TABLE_ID . ", email, Firstname, Lastname, Token, Password, currency FROM user";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/// Récupère toutes les données d'un seul utilisateur de la base de donnée
/// <param> int $id <param> : Paramètre en INT, id de l'utilisateur que l'ont veux récupérer
function readOneUserByID(int $id)
{
    $sql = "SELECT " . USER_TABLE_ID . ", email, Firstname, Lastname, Token, Password, currency FROM user WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [":arg1" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/// Récupère toutes les données d'un seul utilisateur de la base de donnée
/// <param> string $token <param> : Paramètre en STRING, Token de l'utilisateur que l'ont veux récupérer
function readOneUserByToken(string $token)
{
    $sql = "SELECT " . USER_TABLE_ID . ", email, Firstname, Lastname, Token, Password, currency FROM user WHERE Token = :arg1";
    $params = [":arg1" => $token];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/// Est-ce que un utilisateur éxiste?
/// <param> string $email <param> : Paramètre en STRING, email de l'utilisateur que l'ont veux récupérer
function checkIfUserExist(string $email)
{
    $sql = "SELECT " . USER_TABLE_ID . ", Password, Token FROM user WHERE email = :arg1";
    $param = [':arg1' => $email];
    $statement = DataBase::dbRun($sql, $param);
    return $statement->fetch(PDO::FETCH_ASSOC) ?? null;
}

///
/// Créer un utilisateur dans la base de donnée et le login immédiatement en lui créant un token
/// <param> int        $id          <param> : Paramètre en INT, id de l'utilisateur que l'ont veux modifier
/// <param> string     $email       <param> : Paramètre en STRING, Email de l'utilisateur
/// <param> string     $firstname   <param> : Paramètre en STRING, Prénom de l'utilisateur
/// <param> string     $lastname    <param> : Paramètre en STRING, Nom de famille de l'utilisateur
/// <param> string     $token       <param> : Paramètre en STRING, Le token de de l'utilisateur qui permet de savoir qui est si on est connecté
/// <param> string     $password    <param> : Paramètre en STRING, Mot de pass de l'utilisateur ( préférablement hashé en BCrypt)
/// <param> string     $currency    <param> : Paramètre en STRING, Currency utilisé et préférée de l'utilisateur
function updateUser(int $id, string $email, string $firstname, string $lastname, string $token, string $password, string $currency)
{
    $sql = "UPDATE INTO user 
    SET email = :arg1, Firstname = :arg2, Lastname = :arg3, Token = :arg4, Password = :arg5, currency = :arg6 
    WHERE " . USER_TABLE_ID . " = :arg7";
    $params = [
        ":arg1" => $email,
        ":arg2" => $firstname,
        ":arg3" => $lastname,
        ":arg4" => $token,
        ":arg5" => $password,
        ":arg6" => $currency,
        ":arg7" => $id
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Mettre a jour le token d'un utilisateur via son ID dans la base de donnée
/// <param> int     $id          <param> : Paramètre en INT, id de l'utilisateur que l'ont veux modifier
/// <param> string  $token       <param> : Paramètre en STRING, Modifier le token de l'utilisateur concerné ( peut etre null )
function updateUserToken(int $id, string $token)
{
    $sql = "UPDATE user SET Token = :arg1 WHERE " . USER_TABLE_ID . " = :arg2";
    $params = [
        ":arg1" => $token,
        ":arg2" => $id
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Supprime un utilisateur de la base de donnée
/// <param> int $id <param> : Paramètre en INT, id de l'utilisateur que l'ont veux supprimer
function deleteUser(int $id)
{
    $sql = "DELETE FROM user WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [":arg1" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/* ======================================================================================================================================================================*/
/* ==== | ECONOMY | ==== */
/* ======================================================================================================================================================================*/

///
/// Créer une économie dédiée à un utilisateur
/// <param> string $monthlyLimit <param> :  Limite mensuelle fixée
/// <param> string $spendAim     <param> :  Objectif de dépense
/// <param> string $BaseMoney    <param> :  Argent de base
/// <param> int    $id           <param> :  ID de l'utilisateur
function createEconomy(string $monthlyLimit, string $spendAim, string $BaseMoney, int $id)
{
    $sql = "INSERT INTO economy (" . USER_TABLE_ID . ", monthlyLimit, spendAim, BaseMoney) 
            VALUES (:arg1, :arg2, :arg3, :arg4)";
    $params = [
        ":arg1" => $id,
        ":arg2" => floatval($monthlyLimit),
        ":arg3" => floatval($spendAim),
        ":arg4" => floatval($BaseMoney)
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement ? true : false;
}

///
/// Lire toutes les économies
function readEconomies()
{
    $sql = "SELECT idEconomy, " . USER_TABLE_ID . ", monthlyLimit, spendAim, BaseMoney FROM economy";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

///
/// Lire une économie dédiée à un utilisateur
/// <param> int $id <param> : ID de l'utilisateur
function readOneEconomy(int $id)
{
    $sql = "SELECT idEconomy, " . USER_TABLE_ID . ", monthlyLimit, spendAim, BaseMoney 
            FROM economy WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [":arg1" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Modifier une économie dédiée à un utilisateur
/// <param> string $monthlyLimit    <param>
/// <param> string $spendAim        <param>
/// <param> string $BaseMoney       <param>
/// <param> int    $id              <param>
function updateEconomy(string $monthlyLimit, string $spendAim, string $BaseMoney, int $id)
{
    $sql = "UPDATE economy SET monthlyLimit = :arg2, spendAim = :arg3, BaseMoney = :arg4 WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [
        ":arg1" => $id,
        ":arg2" => $monthlyLimit,
        ":arg3" => $spendAim,
        ":arg4" => $BaseMoney
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement ? true : false;
}


///
/// Modifier l'argent d'une économie d'un utlisateur spécifique
/// <param> string $BaseMoney       <param>
/// <param> int    $id              <param>
function updateBaseMoney(string $BaseMoney, int $id)
{
    $sql = "UPDATE economy SET BaseMoney = :arg2 WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [
        ":arg1" => $id,
        ":arg2" => $BaseMoney
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement ? true : false;
}


///
/// Supprimer une économie liée à un utilisateur
/// <param> int $id <param> : ID de l'utilisateur
function deleteEconomy(int $id)
{
    $sql = "DELETE FROM economy WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [":arg1" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement ? true : false;
}



/* ======================================================================================================================================================================*/
/* ==== | SPENDING | ==== */
/* ======================================================================================================================================================================*/

///
/// Créer une dépense dans l'économie de l'utilisateur
/// <param> int $idEconomy          <param> : Paramètre en INT, ID de l'économie de l'utilisateur
/// <param> int $idSpendType        <param> : Paramètre en INT, ID du type de dépense (bouffe, loisir etc.. )
/// <param> string $amount          <param> : Paramètre en STRING, Combien on a dépensé
function createSpending(int $idEconomy, int $idSpendType, string $amount)
{
    $sql = "INSERT INTO spending (idEconomy, idSpendType, amount) VALUES (:arg1, :arg2, :arg3)";
    $params = [
        ":arg1" => $idEconomy,
        ":arg2" => $idSpendType,
        ":arg3" => $amount
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Créer une dépense dans l'économie de l'utilisateur
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
function readOneSpending(int $idSpending)
{
    $sql = "SELECT idSpending, idEconomy, idSpendType, amount FROM spending WHERE idSpending = :arg1";
    $params = [
        ":arg1" => $idSpending
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Créer une dépense dans l'économie de l'utilisateur
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
function readAllSpendingOfEconomy(int $idEconomy)
{
    $sql = "SELECT idSpending, idEconomy, idSpendType, amount FROM spending WHERE idEconomy = :arg1";
    $params = [
        ":arg1" => $idEconomy
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

///
/// Mettre a jour une dépense dans l'économie d'un utilisateur spécifique
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
/// <param> int $idEconomy          <param> : Paramètre en INT, ID de l'économie de l'utilisateur
/// <param> int $idSpendType        <param> : Paramètre en INT, ID du type de dépense (bouffe, loisir etc.. )
/// <param> string $amount          <param> : Paramètre en STRING, Combien on a dépensé
function updateSpending(int $idSpending, int $idEconomy, int $idSpendType, string $amount)
{
    $sql = "UPDATE INTO spending SET idEconomy = :arg2, idSpendType = :arg3, amount = :arg4  WHERE idSpending = :arg1";
    $params = [
        ":arg1" => $idSpending,
        ":arg2" => $idEconomy,
        ":arg3" => $idSpendType,
        ":arg4" => $amount
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Mettre a jour l'argent d'une dépense
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
/// <param> string $amount          <param> : Paramètre en STRING, Combien on a dépensé
function updateSpendingAmount(int $idSpending, string $amount)
{
    $sql = "UPDATE INTO spending SET amount = :arg2  WHERE idSpending = :arg1";
    $params = [
        ":arg1" => $idSpending,
        ":arg2" => $amount
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Supprime une dépense de l'économie de l'utilisateur
 * @param int $idSpending ID de la dépense
 * @return bool True si supprimée avec succès, False sinon
 */
function deleteSpending(int $idSpending): bool
{
    $sql = "DELETE FROM spending WHERE idSpending = :arg1";
    $params = [
        ":arg1" => $idSpending
    ];
    $statement = DataBase::dbRun($sql, $params);

    // Vérifie si une ligne a été supprimée
    return $statement->rowCount() > 0;
}

///
/// Récupérer tous les types de dépenses
/// <param> int $idSpending         
/// <param> : Paramètre en INT, ID de la dépense
function readAllSpendTypes()
{
    $sql = "SELECT idSpendingType, Type FROM spendTypes";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retourne un tableau des dépense du mois selon un utilisateur
 * @param int $userId
 * @return array<float|int>
 */
function getMonthlyExpenses(int $userId): array
{
    $sql = "
        SELECT MONTH(sp.dateCreated) AS month, SUM(sp.amount) AS total
        FROM spending sp
        JOIN economy e ON sp.idEconomy = e.idEconomy
        WHERE e." . USER_TABLE_ID . " = :arg1
        GROUP BY MONTH(sp.dateCreated)
        ORDER BY MONTH(sp.dateCreated);
    ";


    $params = [':arg1' => $userId];
    $stmt = DataBase::dbRun($sql, $params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $monthlyExpenses = array_fill(1, 12, 0);
    foreach ($result as $row) {
        $monthlyExpenses[(int) $row['month']] = (float) $row['total'];
    }

    return $monthlyExpenses;
}

/**
 * Returns the type of the expense
 * @param int $userId
 * @return array
 */
function getExpensesByType(int $userId): array
{
    $sql = "
        SELECT st.Type, SUM(sp.amount) AS total
        FROM spending sp
        JOIN spendTypes st ON sp.idSpendType = st.idSpendingType
        JOIN economy e ON sp.idEconomy = e.idEconomy
        WHERE e." . USER_TABLE_ID . " = :arg1
        GROUP BY st.Type
        ORDER BY total DESC
    ";

    $params = [':arg1' => $userId];
    $stmt = DataBase::dbRun($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Supprime une dépense et redonne l'argent a la balance
 * @param int $expenseId - la dépense
 * @param int $userId   - l'utilisateur
 * @return bool         - Tout est bon? ou pas du tout
 */
function deleteExpense(int $expenseId, int $userId): bool
{
    try {
        // Check if the spending is linked to the right user
        $sql = "
             SELECT sp.amount, e.BaseMoney, e.idEconomy
             FROM spending sp
             JOIN economy e ON sp.idEconomy = e.idEconomy
             WHERE sp.idSpending = :arg1 AND e." . USER_TABLE_ID . " = :" . USER_TABLE_ID . "
         ";
        $params = [':arg1' => $expenseId, ":" . USER_TABLE_ID => $userId];
        $checkStmt = DataBase::dbRun($sql, $params);
        $expense = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$expense) {
            error_log("Tentative de suppression non autorisée pour l'ID de dépense : $expenseId par l'utilisateur : $userId");
            return false;
        }

        $amount = (float) $expense['amount'];
        $currentBalance = (float) $expense['BaseMoney'];
        $economyId = (int) $expense['idEconomy'];

        // Delete the spending
        deleteSpending($expenseId);

        // Add the money spent back to the balance
        $newBalance = $currentBalance + $amount;
        updateBaseMoney($newBalance, $economyId);

        return true;
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

    if ($expenseId && deleteExpense($expenseId, $user[USER_TABLE_ID])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Impossible de supprimer la dépense.']);
    }
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'updateExpense') {
    session_start();
    $user = checkIfUnlogged(OUTSIDE_TO_INDEX_PATH);

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
                 WHERE sp.idSpending = :arg1 AND e." . USER_TABLE_ID . " = :" . USER_TABLE_ID . "
             ";
            $params = [':arg1' => $expenseId, ":" . USER_TABLE_ID => $user[USER_TABLE_ID]];
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
            updateSpendingAmount($newAexpenseIdmount, $newAmount);

            // Mettre à jour le solde
            updateBaseMoney($newBalance, $economyId);

            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            echo json_encode(['success' => false, 'error' => 'Erreur serveur.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Données invalides.']);
    }
    exit();
}