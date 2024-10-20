<?php
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
        $flag = 201;
    } elseif ($test_username === 0 || $test_password === 0) {
        $flag = 400;
    } else {
        $flag = 500;
    }

    $connection = strumenti::create_connection(EXTENSION_MYSQLI, "localhost", "portfolio", "root");
    $result = strumenti::check_credentials($connection, $username, $password);
    if ($result) {

    } else {
        $flag = 400;
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
                    <?php if ($flag === 201) { ?>
                        <h1 id="formSentLogin" class="success"><?php echo $data['login_success'] ?></h1>
                    <?php } elseif ($flag === 400) { ?>
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
                            <input type="text" class="login-credential" name="username" id="username" placeholder="Username">
                            <!-- Username Error Message -->
                            <ul class="errors-container" id="usernameErrors" role="alert"><li></li></ul>

                            <!-- Password Input -->
                            <label for="password">Password:</label>
                            <div class="password-container">
                                <input type="password" class="login-credential" name="password" id="password" placeholder="Password">
                                <span class="iconShowPassword"><i id="passwordToggle" class="fa-solid fa-eye show"></i></span>
                            </div>
                            
                            <!-- Password Error Message -->
                            <ul class="errors-container" id="passwordErrors" role="alert"><li></li></ul>

                            <!-- Submit Button -->
                            <button type="submit" name="button_login" value="login" id="buttonLogIn">
                                <span id="buttonText">Log In</span>
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </main>
        <script src="js/log_in.js"></script>
    </body>
</html>