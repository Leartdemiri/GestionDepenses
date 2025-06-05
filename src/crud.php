<?php
/*
Fichier crud pour chaque classe de la base de donnée
*/

/* ======================================================================================================================================================================*/
/* ==== | USER | ==== */
/* ======================================================================================================================================================================*/

/**
 * Creates a user in the database and logs them in immediately by generating a token.
 *
 * @param string $email     Email of the user.
 * @param string $firstname First name of the user.
 * @param string $lastname  Last name of the user.
 * @param string $token     Token used to identify the logged-in user.
 * @param string $password  User's password (preferably hashed with Bcrypt).
 * @param string $currency  Preferred currency of the user.
 * @return array|null       The inserted user data or null.
 */
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

/**
 * Retrieves all users from the database.
 *
 * @return array List of all users.
 */
function readAllUsers()
{
    $sql = "SELECT " . USER_TABLE_ID . ", email, Firstname, Lastname, Token, Password, currency FROM user";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retrieves one user by their ID from the database.
 *
 * @param int $id ID of the user to retrieve.
 * @return array|null User data or null if not found.
 */
function readOneUserByID(int $id)
{
    $sql = "SELECT " . USER_TABLE_ID . ", email, Firstname, Lastname, Token, Password, currency FROM user WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [":arg1" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Retrieves one user by their token from the database.
 *
 * @param string $token Token of the user to retrieve.
 * @return array|null User data or null if not found.
 */
function readOneUserByToken(string $token)
{
    $sql = "SELECT " . USER_TABLE_ID . ", email, Firstname, Lastname, Token, Password, currency FROM user WHERE Token = :arg1";
    $params = [":arg1" => $token];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Checks if a user exists based on their email.
 *
 * @param string $email Email of the user to check.
 * @return array|null User data or null if not found.
 */
function checkIfUserExist(string $email)
{
    $sql = "SELECT " . USER_TABLE_ID . ", Password, Token FROM user WHERE email = :arg1";
    $param = [':arg1' => $email];
    $statement = DataBase::dbRun($sql, $param);
    return $statement->fetch(PDO::FETCH_ASSOC) ?? null;
}

/**
 * Updates a user's data in the database.
 *
 * @param int    $id        ID of the user to update.
 * @param string $email     Email of the user.
 * @param string $firstname First name of the user.
 * @param string $lastname  Last name of the user.
 * @param string $token     Token to associate with the user.
 * @param string $password  User's password (preferably hashed with Bcrypt).
 * @param string $currency  Preferred currency of the user.
 * @return array|null       Updated user data or null.
 */
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

/**
 * Updates the token of a user by their ID.
 *
 * @param int    $id    ID of the user.
 * @param string $token New token to assign (can be null).
 * @return array|null   Updated user data or null.
 */
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

/**
 * Deletes a user from the database by their ID.
 *
 * @param int $id ID of the user to delete.
 * @return array|null Deleted user data or null.
 */
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

/**
 * Create an economy entry for a user.
 *
 * @param string $monthlyLimit Monthly limit set.
 * @param string $spendAim     Spending goal.
 * @param string $BaseMoney    Starting amount of money.
 * @param int    $id           User ID.
 *
 * @return bool True on success, false otherwise.
 */
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

/**
 * Read all economy entries.
 *
 * @return array Array of all economies.
 */
function readEconomies()
{
    $sql = "SELECT idEconomy, " . USER_TABLE_ID . ", monthlyLimit, spendAim, BaseMoney FROM economy";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Read the economy entry of a specific user.
 *
 * @param int $id User ID.
 *
 * @return array|null Economy entry or null if not found.
 */
function readOneEconomy(int $id)
{
    $sql = "SELECT idEconomy, " . USER_TABLE_ID . ", monthlyLimit, spendAim, BaseMoney 
            FROM economy WHERE " . USER_TABLE_ID . " = :arg1";
    $params = [":arg1" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update the economy entry of a specific user.
 *
 * @param string $monthlyLimit Monthly limit.
 * @param string $spendAim     Spending goal.
 * @param string $BaseMoney    Starting amount of money.
 * @param int    $id           User ID.
 *
 * @return bool True on success, false otherwise.
 */
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

/**
 * Update the base money of a specific user's economy.
 *
 * @param string $BaseMoney Base money to update.
 * @param int    $id        Economy ID.
 *
 * @return bool True on success, false otherwise.
 */
function updateBaseMoney(string $BaseMoney, int $id)
{
    $sql = "UPDATE economy SET BaseMoney = :arg2 WHERE idEconomy = :arg1";
    $params = [
        ":arg1" => $id,
        ":arg2" => $BaseMoney
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement ? true : false;
}

/**
 * Delete the economy of a specific user.
 *
 * @param int $id User ID.
 *
 * @return bool True on success, false otherwise.
 */
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
/// Create a spending entry in the user's economy
/// <param> int $idEconomy          <param>: INT parameter, ID of the user's economy
/// <param> int $idSpendType        <param>: INT parameter, ID of the spending type (food, leisure, etc.)
/// <param> string $amount          <param>: STRING parameter, amount spent
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
/// Read a specific spending entry
/// <param> int $idSpending         <param>: INT parameter, ID of the spending
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
/// Read all spending entries of a specific economy
/// <param> int $idEconomy          <param>: INT parameter, ID of the user's economy
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
/// Update a spending entry in the user's economy
/// <param> int $idSpending         <param>: INT parameter, ID of the spending
/// <param> int $idEconomy          <param>: INT parameter, ID of the user's economy
/// <param> int $idSpendType        <param>: INT parameter, ID of the spending type (food, leisure, etc.)
/// <param> string $amount          <param>: STRING parameter, amount spent
function updateSpending(int $idSpending, int $idEconomy, int $idSpendType, string $amount)
{
    $sql = "UPDATE spending SET idEconomy = :arg2, idSpendType = :arg3, amount = :arg4  WHERE idSpending = :arg1";
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
/// Update the amount of a spending record
/// <param> int $idSpending         <param>: INT parameter, ID of the spending
/// <param> string $amount          <param>: STRING parameter, amount spent
function updateSpendingAmount(int $idSpending, string $amount)
{
    // Prepare and execute the SQL update query
    $sql = "UPDATE spending SET amount = :arg2 WHERE idSpending = :arg1";
    $params = [
        ":arg1" => $idSpending,
        ":arg2" => $amount
    ];

    $statement = DataBase::dbRun($sql, $params);

    // Return the result (though UPDATE usually doesn't return data)
    return $statement->fetch(PDO::FETCH_ASSOC);
}


/**
 * Deletes the expense of a user 
 * @param int $idSpending expense ID
 * @return bool True if deleted else false
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

/**
 * Returns all the expenses types
 * @return array
 */
function readAllSpendTypes()
{
    $sql = "SELECT idSpendingType, Type FROM spendTypes";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Returns a user's monthly expenses
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
 * Deletes a user expense and refunds the deleted amount.
 *
 * @param int $expenseId ID of the expense to delete.
 * @param int $userId    ID of the user who owns the expense.
 * @return bool          True if everything went well, false otherwise.
 */
function deleteExpense(int $expenseId, int $userId): bool
{
    try {
        // Retrieve the expense information to verify ownership and get the amounts.
        $sql = "
            SELECT sp.amount, e.BaseMoney, e.idEconomy
            FROM spending sp
            INNER JOIN economy e ON sp.idEconomy = e.idEconomy
            WHERE sp.idSpending = :arg1 AND e." . USER_TABLE_ID . " = :arg2
        ";
        $params = [
            ':arg1' => $expenseId,
            ':arg2' => $userId
        ];

        $stmt = DataBase::dbRun($sql, $params);
        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        // If the expense was not found or doesn't belong to the user
        if (!$expense) {
            error_log("Suppression refusée : dépense $expenseId non trouvée ou n'appartient pas à l'utilisateur $userId.");
            return false;
        }

        $amount = (float) $expense['amount'];
        $currentBalance = (float) $expense['BaseMoney'];
        $economyId = (int) $expense['idEconomy'];

        // Attempt to delete the expense
        if (!deleteSpending($expenseId)) {
            error_log("Échec de la suppression de la dépense ID : $expenseId");
            return false;
        }

        // Refund the deleted amount back to the user's balance
        $newBalance = $currentBalance + $amount;
        if (!updateBaseMoney($newBalance, $economyId)) {
            error_log("Échec de la mise à jour du solde pour idEconomy : $economyId");
            return false;
        }

        return true;
    } catch (Throwable $e) {
        error_log("Exception dans deleteExpense : " . $e->getMessage());
        return false;
    }
}


