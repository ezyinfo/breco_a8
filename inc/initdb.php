<?php

include "config.php";
include "breco.php";


function initDb()
{
    // Get config variables
    global $db_host, $db_name, $db_user, $db_pass;

    // Create object for the databas
    $brecoDB = new BrecoDB($db_host, $db_name, $db_user, $db_pass);
    $brecoDB->connect();
    $brecoDB->initDb();
}

initDb();
