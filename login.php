<?php
require_once("inclusioni/strumenti.php"); // Tools
use assets\strumenti;

/* Session */
session_start(); // Session Start
require_once("inclusioni/security/session_security.php"); // Session Check

$connection = strumenti::connect_database(PUBLIC_USER);

$data = strumenti::leggiJSON("json/data.json", true)["login"];

require_once('inclusioni/security/login_validation.php');
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head incluso da inclusioni/head.php -->
    <?php require_once("inclusioni/head.php") ?> 
    <body>
        <head>
            <!-- Barra di navigazione inclusa da navbar.php -->
            <?php require_once('inclusioni/navbar.php') ?>
            <!-- Form Content Style -->
            <link rel="stylesheet" href="css/account_form.min.css">
        </head>
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
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="formLogin" novalidate>
                            <!-- Titolo Form -->
                            <h2 id="title">Log In</h2>

                            <!-- Username Input -->
                            <label for="username">Username:</label>
                            <input type="text" 
                                class="input-credential" 
                                name="username" 
                                id="username"
                                data-type="username" 
                                placeholder="Username">
                            <!-- Username Error Message -->
                            <ul class="errors-container username-errors" data-type="username" role="alert"><li></li></ul>

                            <!-- Password Input -->
                            <label for="password">Password:</label>
                            <div class="password-container">
                                <input type="password" 
                                    class="input-credential input-password" 
                                    name="password" 
                                    id="password"
                                    data-type="password" 
                                    placeholder="Password">
                                <span class="iconShowPassword">
                                    <i class="password-toggle show fa-solid fa-eye"></i>
                                </span>
                            </div>
                            <!-- Password Error Message -->
                            <ul class="errors-container password-errors" data-type="password" role="alert"><li></li></ul>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    name="button_login" 
                                    value="login" 
                                    class="button-submit">
                                <span class="buttonText">Log In</span>
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </main>
        <script type="module" src="js/login.js"></script>
    </body>
</html>