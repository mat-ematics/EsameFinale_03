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
    header("Location: login.php"); // Reindirizzamento alla pagina di Login
    exit; // Chiusura Caricamento pagina corrente
}

/* Creazione della Connessione con Database */
$connection = strumenti::create_connection(EXTENSION_MYSQLI, 'localhost', 'portfolio', 'root');

$data = strumenti::leggiJSON("json/data.json", true)["backend"];

//Importazione Validazione Server del Backend
require_once('inclusioni/backend_validation.php');

$users = strumenti::get_admins($connection);

/* strumenti::stampaArray($_POST);
exit; */
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
    <?php if($flag_response != 200 && $form_response != '') { ?>
        <div 
            class="response
            <?php
                /* Dynamic Validation Style */
                if (str_starts_with($flag_response, 2)) {
                    echo 'success';
                } else {
                    echo 'failure';
                } 
            ?>"
            >
            <!-- Response Printing -->
            <p>
                <!-- Response Status Code -->
                <?php
                    /* Dynamic Validation Style */
                    if (str_starts_with($flag_response, 2)) {
                        echo 'Success';
                    } else {
                        echo 'Error';
                    } 
                    echo " " . $flag_response . ": ";
                ?>
            </p>
            <p>
                <!-- Response Message -->
                <?php echo $data['responses'][$form_response][$flag_response] ?>
            </p>
        </div>
    <?php } ?>


    <!-- Users Management Area -->
    <div id="areaUsers" class="area-div">
        <!-- Create Account Form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-users" id="formUserCreate">
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
                        name="repeat_password" 
                        class="input-credential" 
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
                    name="button_submit" 
                    value="user_create" 
                    class="button-submit">
                <span class="buttonText">Create User</span>
            </button>
        </form>

        <!-- Edit Account form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-users" id="formUserEdit">
            <!-- Form Title -->
            <h3 class="form-title">Edit Account</h3>

            <label class="label-select">Select User:
                <select name="selected_user" class="select-user">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user['idAdmin'] ?>"><?php echo $user['username'] ?></option>
                    <?php } ?>
                </select>
            </label>

            <!-- Input Username -->
            <label>Insert New Username:
                <input type="text" 
                    class="input-credential" 
                    name="username" 
                    data-type="username" 
                    placeholder="Username">
            </label>
            <!-- Username Error Message -->
            <ul class="errors-container username-errors" data-type="username" role="alert"><li></li></ul>

            <!-- Input Password -->
            <label>Insert New Password:
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
            <label>Repeat New Password:
                <!-- Repeat Password Container -->
                <div class="password-container">
                    <!-- Repeat Password Input -->
                    <input type="password" 
                        class="input-credential" 
                        name="repeat_password" 
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
                    name="button_submit" 
                    value="user_edit" 
                    class="button-submit">
                <span class="buttonText">Edit User</span>
            </button>
        </form>

        <!-- Delete (Admin) User form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-users" id="formUserDelete">
            <!-- Form Title -->
            <h3 class="form-title">Delete User</h3>

            <label class="label-select">Select User:
                <select name="selected_user" class="select-user">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user['idAdmin'] ?>"><?php echo $user['username'] ?></option>
                    <?php } ?>
                </select>
            </label>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="user_delete" 
                    class="button-submit">
                <span class="buttonText">Delete User</span>
            </button>
        </form>
    </div>





    <!-- Category Management Area -->
    <div id="areaCategories" class="area-div">

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-categories" id="formCategoryCreate">
            <!-- Form Title -->
            <h3 class="form-title">Create Category</h3>

            <!-- Input Category Name -->
            <label>Insert Category Name:
                <input type="text" 
                    class="input-category" 
                    name="category_name" 
                    data-type="category-name" 
                    placeholder="Category">
            </label>
            <!-- Category Name Error Message -->
            <ul class="errors-container category-name-errors" data-type="category-name" role="alert"><li></li></ul>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="category_create" 
                    class="button-submit">
                <span class="buttonText">Create Category</span>
            </button>
        </form>

        <!-- Edit Account form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-cateogories" id="formCategoryEdit">
            <!-- Form Title -->
            <h3 class="form-title">Edit Category</h3>

            <!-- Category Selection -->
            <label class="label-select">Select Category:
                <select name="selected_category" class="select-category">
                    <?php foreach ($categories as $cat) { ?>
                        <option value="<?php echo $cat['idAdmin'] ?>"><?php echo $cat['username'] ?></option>
                    <?php } ?>
                </select>
            </label>

           <!-- Input Category Name -->
           <label>Insert New Category Name:
                <input type="text" 
                    class="input-category" 
                    name="category_name" 
                    data-type="category-name" 
                    placeholder="Category">
            </label>
            <!-- Category Name Error Message -->
            <ul class="errors-container category-name-errors" data-type="category-name" role="alert"><li></li></ul>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="category_edit" 
                    class="button-submit">
                <span class="buttonText">Modify Category</span>
            </button>
        </form>

        <!-- Delete (Admin) User form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-users" id="formCategoryDelete">
            <!-- Form Title -->
            <h3 class="form-title">Delete Category</h3>

            <!-- Category Selection -->
            <label class="label-select">Select Category:
                <select name="selected_category" class="select-category">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user['idAdmin'] ?>"><?php echo $user['username'] ?></option>
                    <?php } ?>
                </select>
            </label>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="category_delete" 
                    class="button-submit">
                <span class="buttonText">Modify Category</span>
            </button>
        </form>
    </div>







    <!-- Works Management Area -->
    <div id="areaWorks" class="area-div">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-users" id="formCreateUser">
            <!-- Form Title -->
            <h3 class="form-title">Create Account</h3>

            <!-- Input Username -->
            <label>Insert Work:
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
                        name="repeat_password" 
                        class="input-credential" 
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
                    name="button_submit" 
                    value="user_create" 
                    class="button-submit">
                <span class="buttonText">Create User</span>
            </button>
        </form>

        <!-- Edit Account form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-users" id="formEditUser">
            <!-- Form Title -->
            <h3 class="form-title">Edit Account</h3>

            <label class="label-select">User:
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
                    name="button_submit" 
                    value="user_edit" 
                    class="button-submit">
                <span class="buttonText">Edit User</span>
            </button>
        </form>

        <!-- Delete (Admin) User form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-users" id="formDeleteUser">
            <!-- Form Title -->
            <h3 class="form-title">Delete User</h3>

            <label class="label-select">User:
                <select name="selected_user" class="select-user">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user['idAdmin'] ?>"><?php echo $user['username'] ?></option>
                    <?php } ?>
                </select>
            </label>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="user_delete" 
                    class="button-submit">
                <span class="buttonText">Delete User</span>
            </button>
        </form>
    </div>
    <script type="module" src="js/backend.js"></script>
    <script src="js/global/prevent_resubmission.js"></script>
</body>
</html>