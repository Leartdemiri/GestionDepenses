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
    $sql = "INSERT INTO user (email, Firstname, Lastname, Token, Password, currency) VALUES (:email, :fname, :lname, :token, :password, :currency)";
    $params = [
        ":email" => $email,
        ":fname" => $firstname,
        ":lname" => $lastname,
        ":token" => $token,
        ":password" => $password,
        ":currency" => $currency
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Récupère toutes les données de tout les utilisateurs de la base de donnée
function readAllUsers()
{
    $sql = "SELECT idUser, email, Firstname, Lastname, Token, Password, currency FROM user";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/// Récupère toutes les données d'un seul utilisateur de la base de donnée
/// <param> int $id <param> : Paramètre en INT, id de l'utilisateur que l'ont veux récupérer
function readOneUserByID(int $id)
{
    $sql = "SELECT idUser, email, Firstname, Lastname, Token, Password, currency FROM user WHERE idUser = :id";
    $params = [":id" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/// Récupère toutes les données d'un seul utilisateur de la base de donnée
/// <param> string $token <param> : Paramètre en STRING, Token de l'utilisateur que l'ont veux récupérer
function readOneUserByToken(string $token)
{
    $sql = "SELECT idUser, email, Firstname, Lastname, Token, Password, currency FROM user WHERE Token = :token";
    $params = [":token" => $token];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/// Est-ce que un utilisateur éxiste?
/// <param> string $email <param> : Paramètre en STRING, email de l'utilisateur que l'ont veux récupérer
function checkIfUserExist(string $email){
    $sql = "SELECT idUser, Password, Token FROM user WHERE email = :email";
    $param = [':email' => $email];
    $statement = DataBase::dbRun($sql, $param);
    return $statement->fetch(PDO::FETCH_ASSOC) ?? null;
}

///
/// Créer un utilisateur dans la base de donnée et le login immédiatement en lui créant un token
/// <param> int     $id          <param> : Paramètre en INT, id de l'utilisateur que l'ont veux modifier
/// <param> string  $email       <param> : Paramètre en STRING, Email de l'utilisateur
/// <param> string  $firstname   <param> : Paramètre en STRING, Prénom de l'utilisateur
/// <param> string  $lastname    <param> : Paramètre en STRING, Nom de famille de l'utilisateur
/// <param> string  $token       <param> : Paramètre en STRING, Le token de de l'utilisateur qui permet de savoir qui est si on est connecté
/// <param> string  $password    <param> : Paramètre en STRING, Mot de pass de l'utilisateur ( préférablement hashé en BCrypt)
/// <param> string  $currency    <param> : Paramètre en STRING, Currency utilisé et préférée de l'utilisateur
function updateUser(int $id, string $email, string $firstname, string $lastname, string $token, string $password, string $currency)
{
    $sql = "UPDATE INTO user SET email = :email, Firstname = :fname,Lastname = :lname,Token = :token,Password = :password,currency = :currency WHERE idUser = :id";
    $params = [
        ":email" => $email,
        ":fname" => $firstname,
        ":lname" => $lastname,
        ":token" => $token,
        ":password" => $password,
        ":currency" => $currency,
        ":id" => $id
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
    $sql = "UPDATE user SET Token = :token WHERE idUser = :id";
    $params = [
        ":token" => $token,
        ":id" => $id
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Supprime un utilisateur de la base de donnée
/// <param> int $id <param> : Paramètre en INT, id de l'utilisateur que l'ont veux supprimer
function deleteUser(int $id)
{
    $sql = "DELETE FROM user WHERE idUser = :id";
    $params = [":id" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/* ======================================================================================================================================================================*/
/* ==== | ECONOMY | ==== */
/* ======================================================================================================================================================================*/

///
/// Créer une économie dédiée à un utilisateur
/// <param> string $monthlyInput <param> :  Entrée mensuelle de l'utilisateur
/// <param> string $monthlyLimit <param> :  Limite mensuelle fixée
/// <param> string $spendAim     <param> :  Objectif de dépense
/// <param> string $BaseMoney    <param> :  Argent de base
/// <param> int    $id           <param> :  ID de l'utilisateur
function createEconomy(string $monthlyInput, string $monthlyLimit, string $spendAim, string $BaseMoney, int $id)
{
    $sql = "INSERT INTO economy (idUser, monthlyInput, monthlyLimit, spendAim, BaseMoney) 
            VALUES (:id, :minput, :mlimit, :spaim, :bmoney)";
    $params = [
        ":id" => $id,
        ":minput" => floatval($monthlyInput),
        ":mlimit" => floatval($monthlyLimit),
        ":spaim" => floatval($spendAim),
        ":bmoney" => floatval($BaseMoney)
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement ? true : false;
}

///
/// Lire toutes les économies
function readEconomies()
{
    $sql = "SELECT idEconomy, idUser, monthlyInput, monthlyLimit, spendAim, BaseMoney FROM economy";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

///
/// Lire une économie dédiée à un utilisateur
/// <param> int $id <param> : ID de l'utilisateur
function readOneEconomy(int $id)
{
    $sql = "SELECT idEconomy, idUser, monthlyInput, monthlyLimit, spendAim, BaseMoney 
            FROM economy WHERE idUser = :id";
    $params = [":id" => $id];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Modifier une économie dédiée à un utilisateur
/// <param> string $monthlyInput    <param>
/// <param> string $monthlyLimit    <param>
/// <param> string $spendAim        <param>
/// <param> string $BaseMoney       <param>
/// <param> int    $id              <param>
function updateEconomy(string $monthlyInput, string $monthlyLimit, string $spendAim, string $BaseMoney, int $id)
{
    $sql = "UPDATE economy SET monthlyInput = :minput, monthlyLimit = :mlimit, spendAim = :spaim, BaseMoney = :bmoney WHERE idUser = :id";
    $params = [
        ":id" => $id,
        ":minput" => $monthlyInput,
        ":mlimit" => $monthlyLimit,
        ":spaim" => $spendAim,
        ":bmoney" => $BaseMoney
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement ? true : false;
}

///
/// Supprimer une économie liée à un utilisateur
/// <param> int $id <param> : ID de l'utilisateur
function deleteEconomy(int $id)
{
    $sql = "DELETE FROM economy WHERE idUser = :id";
    $params = [":id" => $id];
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
    $sql = "INSERT INTO spending (idEconomy, idSpendType, amount) VALUES (:ide, :idst, :amo)";
    $params = [
        ":ide" => $idEconomy,
        ":idst" => $idSpendType,
        ":amo" => $amount
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Créer une dépense dans l'économie de l'utilisateur
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
function readOneSpending(int $idSpending)
{
    $sql = "SELECT idSpending, idEconomy, idSpendType, amount FROM spending WHERE idSpending = :ids";
    $params = [
        ":ids" => $idSpending
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Créer une dépense dans l'économie de l'utilisateur
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
function readAllSpendingOfEconomy(int $idEconomy)
{
    $sql = "SELECT idSpending, idEconomy, idSpendType, amount FROM spending WHERE idEconomy = :ide";
    $params = [
        ":ide" => $idEconomy
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

///
/// Créer une dépense dans l'économie de l'utilisateur
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
/// <param> int $idEconomy          <param> : Paramètre en INT, ID de l'économie de l'utilisateur
/// <param> int $idSpendType        <param> : Paramètre en INT, ID du type de dépense (bouffe, loisir etc.. )
/// <param> string $amount          <param> : Paramètre en STRING, Combien on a dépensé
function updateSpending(int $idSpending, int $idEconomy, int $idSpendType, string $amount)
{
    $sql = "UPDATE INTO spending SET idEconomy = :ide, idSpendType = :idst, amount = :amo  WHERE idSpending = :ids";
    $params = [
        ":ids" => $idSpending,
        ":ide" => $idEconomy,
        ":idst" => $idSpendType,
        ":amo" => $amount
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

///
/// Créer une dépense dans l'économie de l'utilisateur
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
function deleteSpending(int $idSpending)
{
    $sql = "DELETE FROM spending WHERE idSpending = :ids";
    $params = [
        ":ids" => $idSpending
    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}


///
/// Récupérer tous les types de dépenses
/// <param> int $idSpending         <param> : Paramètre en INT, ID de la dépense
function readAllSpendTypes()
{
    $sql = "SELECT idSpendingType, Type FROM spendTypes";
    $params = [];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


function getMonthlyExpenses(int $userId): array {
    $sql = "
        SELECT MONTH(sp.dateCreated) AS month, SUM(sp.amount) AS total
        FROM spending sp
        JOIN economy e ON sp.idEconomy = e.idEconomy
        WHERE e.idUser = :userId
        GROUP BY MONTH(sp.dateCreated)
        ORDER BY MONTH(sp.dateCreated);
    ";

    $stmt = DataBase::dbRun($sql, [':userId' => $userId]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $monthlyExpenses = array_fill(1, 12, 0);
    foreach ($result as $row) {
        $monthlyExpenses[(int)$row['month']] = (float)$row['total'];
    }

    return $monthlyExpenses;
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

