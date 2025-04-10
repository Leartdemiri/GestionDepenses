<?php

require_once '../php/functions.php';


session_start();

$user = readOneUserByToken($_SESSION['token']);

logout($user["idUser"], $_SESSION['token']);

header('Location: ../index.php');