<?php
//Importo Strumenti e dati dal JSON

require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["services"];
/* strumenti::stampaArray($data);
exit; */
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head incluso da inclusioni/head.php -->
    <?php require("inclusioni/head.php") ?>
    <body>
        <!-- Barra di Navigazione incluso da inclusioni/navbar.php -->
        <?php require("inclusioni/navbar.php") ?>
        <div id="background">
            <div class="bg"></div>
            <div class="bg bg2"></div>
            <div class="bg bg3"></div>
        </div>
        <main>
            <!-- Titolo Pagina -->
            <div id="title">
                <h1><?php echo ucfirst($data['page_title']) ?></h1>
            </div>
            <div id="columns">
                <!-- Bolle dei Servizi -->
                <?php foreach ($data['bubbles'] as $type => $bubble) { ?>
                    <div class="column">
                        <div class="bubble" id="<?php echo $type ?>">
                            <!-- Titolo -->
                            <div class="title">
                                <h1><?php echo $bubble['title'] ?></h1>
                            </div>
                            <!-- Descrizione -->
                            <div class="description">
                                <p><?php echo $bubble['description'] ?></p>
                            </div>
                        </div>
                    </div>    
                <?php } ?>
            </div>
        </main>
        <!-- Footer incluso da inclusioni/footer.php -->
        <?php require("inclusioni/footer.php") ?>
    </body>
</html>