<?php 

/* File di Configurazione */
require_once("config/config.php");

session_start(); // Inizializzazione della Sessione

// Importazione Sicurezza della Sessione
require_once("inclusioni/session_security.php");

// Importo Strumenti
require_once("inclusioni/strumenti.php");
use assets\strumenti;

/* Controllo dell'Autenticazione */
if (!isset($_SESSION) || !isset($_SESSION['is_auth']) || $_SESSION['is_auth'] != true) {
    session_unset(); // Dissociazione Dati della Sessione Corrente
    session_destroy(); // Eliminazione della Sessione Corrente
    header("Location: log_in.php"); // Reindirizzamento alla pagina di Login
    exit; // Chiusura Caricamento pagina corrente
}

/* Creazione della Connessione con Database */
strumenti::create_connection(EXTENSION_MYSQLI, 'localhost', 'portfolio', 'root');

$data = strumenti::leggiJSON("json/data.json", true)["backend"];

?>

<!DOCTYPE html>
<html lang="en">
<?php require_once("inclusioni/head.php") ?>
<body>
    <?php require_once('inclusioni/navbar.php') ?>
    <main>
        <h1 id="pageTitle">Backend</h1>
        <menu class="area-list">
            <li id="users" class="area">Users</li>
            <li id="categories" class="area">Categories</li>
            <li id="works" class="area">Works</li>
        </menu>
    </main>
    <script src="js/backend.js"></script>
</body>
</html>