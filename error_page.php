<?php
require_once("inclusioni/strumenti.php"); // Tools
use assets\strumenti;

/* Session */
session_start(); // Session Start
require_once("inclusioni/security/session_security.php"); // Session Check

$connection = strumenti::connect_database(PUBLIC_USER);

$data = strumenti::leggiJSON("json/data.json", true)["error_page"];

$error_code = http_response_code();
if ($error_code == 200) {
    $error_code = 404;
}

$error_message = $data['errors'][$error_code];

?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head incluso da inclusioni/head.php -->
    <?php require_once("inclusioni/head.php") ?> 
    <body>
        <head>
            <!-- Barra di navigazione inclusa da navbar.php -->
            <?php require_once('inclusioni/navbar.php') ?>
        </head>
        <main>
            <!-- Contenitore Form -->
            <div id="errorWrapper">
                <div id="errorBg">
                    <h1 id="errorCode" class="error">
                        Error <?php echo $error_code ?>: <!-- Error Code -->
                    </h1>
                    <p id="errorMsg">
                        <?php echo $error_message ?> <!-- Error Message -->
                    </p>
                </div>
            </div>
        </main>
    </body>
</html>