<?php
// Importo strumenti e Dati dal JSON
require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["log_in"];
$flag = 0;
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head incluso da inclusioni/head.php -->
    <?php require_once("inclusioni/head.php") ?> 
    <body>
        <!-- Barra di navigazione inclusa da navbar.php -->
        <?php require_once('inclusioni/navbar.php') ?>
        <main style="<?php if ($flag === 201) { ?> height: calc(100% - 60px); <?php } ?>">
            <!-- Contenitore Form -->
            <div id="contactsWrapper">
                <div id="contactsBg">
                    <?php if ($flag === 201) { ?>
                        <h1 id="formSentMessage"><?php echo $data['form_sent_message'] ?></h1>
                    <?php } else { ?>
                        <!-- Form vero e proprio -->
                        <form action="log_in.php" method="post" id="contactForm" novalidate>
                            <!-- Titolo Form -->
                            <h2 id="title">Log In</h2>
                            <!-- Username Input -->
                            <label for="username">Username:</label>
                            <input type="text" class="login-credential" name="username" id="username" placeholder="Username" minlength="6" maxlength="32">
                            <!-- Password Input -->
                            <label for="password">Password:</label>
                            <input type="password" class="login-credential" name="password" id="password" placeholder="Password" minlength="8" maxlength="32">
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