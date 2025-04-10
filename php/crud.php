<?php
/*
Fichier crud pour chaque classe de la base de donnée
*/

require_once "database.php";

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
    return $statement->fetch(PDO::FETCH_ASSOC);
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

///
/// Récupère toutes les données d'un seul utilisateur de la base de donnée
/// <param> string $token <param> : Paramètre en STRING, Token de l'utilisateur, grace a cela on peut savoir quel utilisateur est connecté
function ReadOneUserByToken(string $token)
{
    $sql = "SELECT idUser, email, Firstname, Lastname, Token, Password, currency FROM user WHERE Token = :token";
    $params = [":token" => $token];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
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
    $sql = "UPDATE INTO user SET Token = :token WHERE idUser = :id";
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
        ":minput" => $monthlyInput,
        ":mlimit" => $monthlyLimit,
        ":spaim" => $spendAim,
        ":bmoney" => $BaseMoney
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
    $sql = "UPDATE economy 
            SET monthlyInput = :minput, monthlyLimit = :mlimit, spendAim = :spaim, BaseMoney = :bmoney 
            WHERE idUser = :id";
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
/// Créer une économie dédiée a un utilisateur
/// <param> string $email       <param> : Paramètre en STRING, Email de l'utilisateur
/// <param> string $firstname   <param> : Paramètre en STRING, Prénom de l'utilisateur
/// <param> string $lastname    <param> : Paramètre en STRING, Nom de famille de l'utilisateur
/// <param> string $token       <param> : Paramètre en STRING, Le token de de l'utilisateur qui permet de savoir qui est si on est connecté
/// <param> string $password    <param> : Paramètre en STRING, Mot de pass de l'utilisateur ( préférablement hashé en BCrypt)
/// <param> string $currency    <param> : Paramètre en STRING, Currency utilisé et préférée de l'utilisateur
function createSpending(int $monthlyInput, int $monthlyLimit, string $spendAim, string $BaseMoney, int $id)
{
    $sql = "INSERT INTO user (idUser, monthlyInput, monthlyLimit, spendAim, BaseMoney) VALUES (:id,:email, :fname, :lname, :token, :password, :currency)";
    $params = [
        ":id" => $id,
        ":minput" => $monthlyInput,
        ":mlimit" => $monthlyLimit,
        ":spaim" => $spendAim,
        ":bmoney" => $BaseMoney

    ];
    $statement = DataBase::dbRun($sql, $params);
    return $statement->fetch(PDO::FETCH_ASSOC);
}