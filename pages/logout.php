<?php

require_once '../php/functions.php';

session_start();

$user = readOneUserByToken($_SESSION[SESSION_TOKEN_KEY]);

logout($user["idUser"], $_SESSION[SESSION_TOKEN_KEY]);

header('Location: ../index.php');