<?php
// Importo strumenti e dati dal JSON
require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["projects"];
/* strumenti::stampaArray($data);
exit; */
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head incluso da inclusioni/head.php -->
    <?php require_once("inclusioni/head.php") ?>
    <body>
        <!-- Barra di Navigazione -->
        <?php require_once("inclusioni/navbar.php") ?>
        <main>
            <h1>Works</h1>
            <!-- Bolle di sfondo -->
            <div class="bubbles">
                <?php for ($i = 0; $i < $data['bubbles']; $i++) { ?>
                    <div class="bubble"></div>    
                <?php } ?>
            </div>
            <!-- Lavori -->
            <div id="works">
                <?php 
                    $loopCounter = 0;
                    foreach ($data['project_list'] as $project) {
                ?>
                <!-- Sezione di Un lavoro -->
                <div class="singleProject">
                    <!-- Immagine -->
                    <div class="cards <?php echo $loopCounter % 2 == 0 ? "left" : "right" ?>">
                        <a href="single_project.php?id=<?php echo $project['project_id'] ?>" title="<?php echo $project['image']['title'] ?>">
                            <img src="<?php echo $project['image']['link'] ?>" alt="<?php echo $project['image']['alt_text'] ?>">
                        </a>
                    </div>
                    <!-- Testo -->
                    <div class="projectDescription">
                        <!-- Titolo del Progetto -->
                        <div class="descriptionTitle">
                            <p><?php echo $project['project_title'] ?></p>
                        </div>
                        <!-- Descrizione del Progetto -->
                        <div class="descriptionText">
                            <p><?php echo $project['project_description_short'] ?></p>
                        </div>
                    </div>
                </div>
                <?php
                    $loopCounter++;
                    } 
                ?>
            </div>
        </main>
        <!-- Footer incluso da footer.php -->
        <?php require_once("inclusioni/footer.php") ?>
    </body>
</html>