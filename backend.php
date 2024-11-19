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
$connection = strumenti::create_connection(EXTENSION_MYSQLI, 'localhost', 'portfolio', 'root');

$data = strumenti::leggiJSON("json/data.json", true)["backend"];

//Importazione Validazione Server del Backend
require_once('inclusioni/backend_validation.php');

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
    <form action="backend.php" method="post" class="users">
        <!-- Area Title -->
        <h2 class="area-title">Users</h2>

        <!-- Input Username -->
        <label for="username">Insert Username:</label>
        <input type="text" class="input-credential" name="username" id="username" placeholder="Username">
        <!-- Username Error Message -->
        <ul class="errors-container" id="usernameErrors" role="alert"><li></li></ul>

        <!-- Input Password -->
        <label for="password">Password:</label>
        <!-- Password Container -->
        <div class="password-container">
            <!-- Password Input -->
            <input type="password" class="input-credential" name="password" id="password" placeholder="Password">
            <!-- "Show Password" Icon -->
            <span class="iconShowPassword"><i class="password-toggle fa-solid fa-eye show"></i></span>
        </div>
         <!-- Password Error Message -->
         <ul class="errors-container" id="passwordErrors" role="alert"><li></li></ul>

        <!-- Input Repeat Password -->
        <label for="repeatPassword">Repeat Password:</label> 
        <!-- Repeat Password Container -->
        <div class="password-container">
            <!-- Password Input -->
            <input type="password"  class="input-credential" name="repeat_password" id="repeatPassword" placeholder="Password">
            <!-- "Show Password" Icon -->
            <span class="iconShowPassword"><i class="repeat-password password-toggle fa-solid fa-eye show"></i></span>
        </div>
         <!-- Repeat Password Error Message -->
         <ul class="errors-container" id="repeatPasswordErrors" role="alert"><li></li></ul>

         <!-- Submit Button -->
         <button type="submit" name="button_submit" value="create_user" class="button-submit-credentials">
            <span id="buttonText">Create User</span>
        </button>
    </form>
    <script src="js/backend.js"></script>
    <script src="js/check_login_signup.js"></script>
</body>
</html>