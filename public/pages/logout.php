<?php

require_once '../php/functions.php';

session_start();

$user = readOneUserByToken($_SESSION[SESSION_TOKEN_KEY]);

logout($user[USER_TABLE_ID], $_SESSION[SESSION_TOKEN_KEY]);

header('Location: ../index.php');