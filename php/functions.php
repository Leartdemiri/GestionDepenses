<?php
require_once "constants.php";
require_once "database.php";


function createToken(){
    return bin2hex(random_bytes(16));
}

function checkPOSTFields($fieldsList){
    foreach ($fieldsList as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            http_response_code(HTTP_STATUS_BAD_REQUEST); 
            header("Location: ../index.php?error=missing_fields");
            exit();
        }
    }
}
