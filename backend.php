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

$users = strumenti::get_admins($connection);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head Import -->
    <?php require_once("inclusioni/head.php") ?>
    <!-- Form Content Style -->
    <link rel="stylesheet" href="css/account_form.min.css">
</head>
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
    <!-- Form Response -->
    <?php if($flagUsers != 200) { ?>
        <div class="response"></div>
    <?php } ?>

    <!-- Users -->
    <div id="areaUsers">
        <!-- Create Account Form -->
        <form action="backend.php" method="post" class="form-users">
            <!-- Form Title -->
            <h3 class="form-title">Create Account</h3>

            <!-- Input Username -->
            <label>Insert Username:
                <input type="text" 
                    class="input-credential" 
                    name="username" 
                    data-type="username" 
                    placeholder="Username">
            </label>
            <!-- Username Error Message -->
            <ul class="errors-container username-errors" data-type="username" role="alert"><li></li></ul>

            <!-- Input Password -->
            <label>Password:
            <!-- Password Container -->
                <div class="password-container">
                    <!-- Password Input -->
                    <input type="password" 
                        class="input-credential" 
                        name="password" 
                        data-type="password" 
                        placeholder="Password">
                    <!-- "Show Password" Icon -->
                    <span class="iconShowPassword">
                        <i class="password-toggle fa-solid fa-eye show"></i>
                    </span>
                </div>
            </label>
            <!-- Password Error Message -->
            <ul class="errors-container password-errors" data-type="password" role="alert"><li></li></ul>

            <!-- Input Repeat Password -->
            <label>Repeat Password:
                <!-- Repeat Password Container -->
                <div class="password-container">
                    <!-- Repeat Password Input -->
                    <input type="password" 
                        class="input-credential" 
                        name="repeat_password" 
                        class="input-repeat-password" 
                        data-type="repeat-password" 
                        placeholder="Repeat Password">
                    <!-- "Show Password" Icon -->
                    <span class="iconShowPassword">
                        <i class="repeat-password password-toggle fa-solid fa-eye show"></i>
                    </span>
                </div>
            </label>
            <!-- Repeat Password Error Message -->
            <ul class="errors-container repeat-password-errors" data-type="repeat-password" role="alert"><li></li></ul>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_create_account" 
                    value="create_user" 
                    class="button-submit">
                <span class="buttonText">Create User</span>
            </button>
        </form>

        <!-- Edit Account form -->
        <form action="backend.php" method="post" class="form-users">
            <!-- Form Title -->
            <h3 class="form-title">Edit Account</h3>

            <label class="label-select-user">User:
                <select name="selected_user" class="select-user">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user['idAdmin'] ?>"><?php echo $user['username'] ?></option>
                    <?php } ?>
                </select>
            </label>

            <!-- Input Username -->
            <label>Insert Username:
                <input type="text" 
                    class="input-credential" 
                    name="username" 
                    data-type="username" 
                    placeholder="Username">
            </label>
            <!-- Username Error Message -->
            <ul class="errors-container username-errors" data-type="username" role="alert"><li></li></ul>

            <!-- Input Password -->
            <label>Password:
            <!-- Password Container -->
                <div class="password-container">
                    <!-- Password Input -->
                    <input type="password" 
                        class="input-credential" 
                        name="password" 
                        data-type="password" 
                        placeholder="Password">
                    <!-- "Show Password" Icon -->
                    <span class="iconShowPassword">
                        <i class="password-toggle fa-solid fa-eye show"></i>
                    </span>
                </div>
            </label>
            <!-- Password Error Message -->
            <ul class="errors-container password-errors" data-type="password" role="alert"><li></li></ul>

            <!-- Input Repeat Password -->
            <label>Repeat Password:
                <!-- Repeat Password Container -->
                <div class="password-container">
                    <!-- Repeat Password Input -->
                    <input type="password" 
                        class="input-credential" 
                        name="repeat_password" 
                        class="input-repeat-password" 
                        data-type="repeat-password" 
                        placeholder="Repeat Password">
                    <!-- "Show Password" Icon -->
                    <span class="iconShowPassword">
                        <i class="repeat-password password-toggle fa-solid fa-eye show"></i>
                    </span>
                </div>
            </label>
            <!-- Repeat Password Error Message -->
            <ul class="errors-container repeat-password-errors" data-type="repeat-password" role="alert"><li></li></ul>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_create_account" 
                    value="create_user" 
                    class="button-submit">
                <span class="buttonText">Create User</span>
            </button>
        </form>

        <!-- Delete Account form -->
        <form action="backend.php" method="post" class="users-form">
            
        </form>
    </div>
    <script src="js/backend.js"></script>
    <script src="js/check_login_signup.js"></script>
</body>
</html>