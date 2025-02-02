<?php
require_once('inclusioni/strumenti.php');
use assets\strumenti;

$regex_username = "/^[a-zA-Z_]{6,32}$/";
$regex_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z0-9@$!%*?&]{8,}$/";

$flag = 0;

/* Validazione Lato Server */
if (!empty($_POST)) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $test_username = preg_match($regex_username, $username);
    $test_password = preg_match($regex_password, $password);
    if ($test_username === 1 && $test_password === 1) {
        // Check presence of Account

        $result = strumenti::check_credentials($connection, $username, $password);
        if ($result) {
            // Account Found
            $flag = 201; // Flag di Successo

            /* Rigenerazione di Sicurezza dell'ID della Sessione */
            session_regenerate_id(true); // Cancellazione della Sessione Precedente

            /* Variabili di Sessione */
            $_SESSION['role'] = 'admin';
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
