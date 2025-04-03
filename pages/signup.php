<?php

header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];

// Security
if($method !== "POST"){
    http_response_code(HTTP_STATUS_METHOD_NOT_ALLOWED);
    header("Location: ../index.php");
    die();
}

