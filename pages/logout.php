<?php

require_once './dbConfig.php';
require_once './DataBase.php';

// Fonction pour se déconnecter et supprimer le token
function logout(string $token): void
{
    // Supprimer le token de la base de données
    $sql = "UPDATE users SET token = NULL WHERE token = :token";
    DataBase::dbRun($sql, ['token' => $token]);
}