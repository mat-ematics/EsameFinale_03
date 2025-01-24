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
$works = strumenti::leggiJSON("json/data.json", true)['projects'];

//Importazione Validazione Server del Backend
require_once('inclusioni/backend_validation.php');

$users = strumenti::get_admins($connection);
$categories = strumenti::get_categories($connection);

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
    <link rel="stylesheet" href="css/multitag_dropdown.min.css">
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
    <?php if($flag_response['status'] != 200 && $flag_response['status'] != '') { ?>
        <div 
            class="response
            <?php
                /* Dynamic Validation Style */
                if (str_starts_with($flag_response['status'], 2)) {
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
                    if (str_starts_with($flag_response['status'], 2)) {
                        echo 'Success';
                    } else {
                        echo 'Error';
                    } 
                    echo " " . $flag_response['status'] . ": ";
                ?>
            </p>
            <p>
                <!-- Response Message -->
                <?php echo $data['responses'][$form_submitted][$flag_response['status']] ?>
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
                    data-input-type="text"
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
                        data-input-type="text"
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
                        data-input-type="text"
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
                <select 
                    name="selected_user" 
                    class="select-user" 
                    data-type="user-select"
                    data-input-type="select">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user['idAdmin'] ?>"><?php echo $user['username'] ?></option>
                    <?php } ?>
                </select>
            </label>
            <ul class="errors-container user-select-errors" data-type="select-user" role="alert"><li></li></ul>

            <!-- Input Username -->
            <label>Insert New Username:
                <input type="text" 
                    class="input-credential" 
                    name="username" 
                    data-type="username"
                    data-input-type="text"
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
                        data-input-type="text"
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
                        data-input-type="text"
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
                <select 
                    name="selected_user" 
                    class="select-user" 
                    data-type="user-select"
                    data-input-type="select">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php echo $user['idAdmin'] ?>"><?php echo $user['username'] ?></option>
                    <?php } ?>
                </select>
            </label>
            <ul class="errors-container user-select-errors" data-type="select-user" role="alert"><li></li></ul>

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
                    data-input-type="text"
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
                <select 
                    name="selected_category" 
                    class="select-category" 
                    data-type="category-select"
                    data-input-type="select">
                    <?php foreach ($categories as $cat) { ?>
                        <option value="<?php echo $cat['idCategory'] ?>"><?php echo $cat['name'] ?></option>
                    <?php } ?>
                </select>
            </label>
            <ul class="errors-container category-select-errors" data-type="select-category" role="alert"><li></li></ul>

           <!-- Input Category Name -->
           <label>Insert New Category Name:
                <input type="text" 
                    class="input-category" 
                    name="category_name" 
                    data-type="category-name"
                    data-input-type="text" 
                    placeholder="Category">
            </label>
            <!-- Category Name Error Message -->
            <ul class="errors-container category-name-errors" data-type="category-name" role="alert"><li></li></ul>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="category_edit" 
                    class="button-submit">
                <span class="buttonText">Edit Category</span>
            </button>
        </form>

        <!-- Delete Category form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-category" id="formCategoryDelete">
            <!-- Form Title -->
            <h3 class="form-title">Delete Category</h3>

            <!-- Category Selection -->
            <label class="label-select">Select Category:
                <select 
                    name="selected_category" 
                    class="select-category" 
                    data-type="category-select"
                    data-input-type="select">
                    <?php foreach ($categories as $cat) { ?>
                        <option value="<?php echo $cat['idCategory'] ?>"><?php echo $cat['name'] ?></option>
                    <?php } ?>
                </select>
            </label>
            <ul class="errors-container category-select-errors" data-type="select-category" role="alert"><li></li></ul>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="category_delete" 
                    class="button-submit">
                <span class="buttonText">Delete Category</span>
            </button>
        </form>
    </div>




    <!-- Works Management Area -->
    <div id="areaWorks" class="area-div">
        <form 
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
            method="post" 
            class="form-work" 
            id="formWorkCreate"
            enctype="multipart/form-data">
            <!-- Form Title -->
            <h3 class="form-title">Create Work</h3>

            <!-- Category Selection -->
            <label class="label-select">Select Category:
                <select 
                    name="work_category" 
                    class="select-category" 
                    data-type="work-category"
                    data-input-type="select">
                    <?php foreach ($categories as $cat) { ?>
                        <option value="<?php echo $cat['idCategory'] ?>"><?php echo $cat['name'] ?></option>
                    <?php } ?>
                </select>
            </label>
            <ul class="errors-container work-category-errors" role="alert"><li></li></ul>

            <!-- Input Work Name -->
            <label>Insert Work Name:
                <input type="text" 
                    class="input-work" 
                    name="work_name" 
                    data-type="work-name"
                    data-input-type="text"
                    placeholder="Work Name">
            </label>
            <!-- Username Error Message -->
            <ul class="errors-container work-name-errors" role="alert"><li></li></ul>

            <!-- Input Work Date -->
            <label>Insert Work Date:
            <input type="date" 
                class="input-work" 
                name="work_date" 
                data-type="work-date"
                data-input-type="date">
            </label>
            <!-- Username Error Message -->
            <ul class="errors-container work-date-errors" role="alert"><li></li></ul>

            <!-- Input Work Image -->
            <label>Insert Image of the Work:
                <input type="file" 
                    class="input-work" 
                    name="work_image" 
                    data-type="work-image"
                    data-input-type="image"
                    accept="image/png, image/jpeg, image/jpg, image/gif">
            </label>
            <!-- Username Error Message -->
            <ul class="errors-container work-image-errors" role="alert"><li></li></ul>

            <!-- Input Work Languages -->
            
            <div class="global-multitag-dropdown-container">
                <div class="tags-container"></div>
                <input 
                    type="text" 
                    class="input-work tags-input"
                    data-type="work-languages"
                    data-input-type="multitag-select"
                    placeholder="Type or select a language" 
                    autocomplete="off">
                <!-- Dropdown Menu -->
                <ul class="dropdown-menu">
                    <li data-value="Python">Python</li>
                    <li data-value="JavaScript">JavaScript</li>
                    <li data-value="Java">Java</li>
                    <li data-value="C++">C++</li>
                    <li data-value="Ruby">Ruby</li>
                    <li data-value="Go">Go</li>
                    <li data-value="PHP">PHP</li>
                    <li data-value="Swift">Swift</li>
                </ul>
            </div>
            <!-- Languages Error Message -->
            <ul class="errors-container work-languages-errors" role="alert"><li></li></ul>

            <textarea 
                name="work_description" 
                class="input-work"
                data-type="work-description"
                data-input-type="text"
                placeholder="This is Work number 1..."></textarea>
            <!-- Description Error Message -->
            <ul class="errors-container work-description-errors" role="alert"><li></li></ul>

            <!-- Submit Button -->
            <button type="submit" 
                    name="button_submit" 
                    value="work_create" 
                    class="button-submit">
                <span class="buttonText">Create Work</span>
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