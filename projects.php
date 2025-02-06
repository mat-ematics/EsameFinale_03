<?php
// Importo strumenti e dati dal JSON
require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["projects"];

$connection = strumenti::connect_database(PUBLIC_USER);

$work_list = strumenti::get_works($connection);
$category_list = strumenti::get_categories($connection, true);

/* strumenti::stampaArray($category_list);
exit; */
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Head incluso da inclusioni/head.php -->
        <?php require_once("inclusioni/head.php") ?>
    </head>
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
                    foreach ($work_list as $project) {
                ?>
                    <!-- Sezione di Un lavoro -->
                    <div class="singleProject">
                        <!-- Immagine -->
                        <div class="cards">
                            <a href="single_project.php?id=<?php echo $project['idWork'] ?>" title="Go to the page of project '<?php echo $project['name'] ?>'">
                                <img src="<?php echo $project['image_path'] ?>" alt="Image of project '<?php echo $project['name'] ?>'">
                            </a>
                        </div>
                        <!-- Titolo del Progetto -->
                        <div class="descriptionTitle">
                            <p class="title">
                                <?php echo $project['name'] ?>
                            </p>
                            <p class="category">
                                <?php echo $category_list[$project['idCategory']] ?>
                            </p>
                        </div>
                    </div>
                <?php
                    } 
                ?>
            </div>
        </main>
        <!-- Footer incluso da footer.php -->
        <?php require_once("inclusioni/footer.php") ?>
    </body>
</html>