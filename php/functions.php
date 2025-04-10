<?php
require_once "constants.php";
require_once "database.php";


function createToken(){
    return bin2hex(random_bytes(16));
}