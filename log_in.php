<?php

/* File di Configurazione */
require_once("config/config.php");

session_start(); // Inizializzazione della Sessione

// Importazione Sicurezza della Sessione
require_once("inclusioni/session_security.php");

// Importo strumenti e Dati dal JSON
require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["log_in"];
$flag = 0;

/* Validazione Lato Server */
if (!empty($_POST)) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $test_username = preg_match($data['regex_username'], $username);
    $test_password = preg_match($data['regex_password'], $password);
    if ($test_username === 1 && $test_password === 1) {
        // Check presence of Account
        $connection = strumenti::create_connection(EXTENSION_MYSQLI, "localhost", "portfolio", "root");
        $result = strumenti::check_credentials($connection, $username, $password);
        if ($result) {
            // Account Found
            $flag = 201; // Flag di Successo

            /* Rigenerazione di Sicurezza dell'ID della Sessione */
            session_regenerate_id(true); // Cancellazione della Sessione Precedente

            /* Variabili di Sessione */
            $_SESSION['username'] = $username;
            $_SESSION['is_auth'] = true;
            
            /* Ridirezionamento nella pagina di Backend */
            header("Location: backend.php");
            exit(); // Termine anticipato dello sviluppo di codice
        } else {
            // Account Not Found
            $flag = 400;
        }
    } elseif ($test_username === 0 || $test_password === 0) {
        $flag = 400;
    } else {
        $flag = 500;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head incluso da inclusioni/head.php -->
    <?php require_once("inclusioni/head.php") ?> 
    <body>
        <!-- Barra di navigazione inclusa da navbar.php -->
        <?php require_once('inclusioni/navbar.php') ?>
        <main>
            <!-- Contenitore Form -->
            <div id="contactsWrapper">
                <div id="contactsBg">
                    <?php if ($flag === 400) { ?>
                        <h1 id="formSentLogin" class="error"><?php echo $data['login_error_client'] ?></h1>
                    <?php } elseif ($flag === 500) { ?>
                        <h1 id="formSentLogin" class="error"><?php echo $data['login_error_server'] ?></h1>
                    <?php } else { ?>
                        <!-- Form vero e proprio -->
                        <form action="log_in.php" method="post" id="contactForm" novalidate>
                            <!-- Titolo Form -->
                            <h2 id="title">Log In</h2>
                            <!-- Username Input -->
                            <label for="username">Username:</label>
                            <input type="text" class="input-credential" name="username" id="username" placeholder="Username">
                            <!-- Username Error Message -->
                            <ul class="errors-container" id="usernameErrors" role="alert"><li></li></ul>

                            <!-- Password Input -->
                            <label for="password">Password:</label>
                            <div class="password-container">
                                <input type="password" class="input-credential input-password" name="password" id="password" placeholder="Password">
                                <span class="iconShowPassword"><i class="password-toggle" class="fa-solid fa-eye show"></i></span>
                            </div>
                            
                            <!-- Password Error Message -->
                            <ul class="errors-container" id="passwordErrors" role="alert"><li></li></ul>

                            <!-- Submit Button -->
                            <button type="submit" name="button_login" value="login" class="button-submit-credentials">
                                <span id="buttonText">Log In</span>
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </main>
        <script src="js/check_login_signup.js"></script>
    </body>
</html>