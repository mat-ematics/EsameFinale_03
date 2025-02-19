<?php
require_once('inclusioni/strumenti.php');
use assets\strumenti;

$connection = strumenti::connect_database(PUBLIC_USER);

$data = strumenti::leggiJSON("json/data.json", true)["index"];
$projects = strumenti::leggiJSON("json/data.json", true)['projects']['project_list'];

$work_list = strumenti::get_last_n_works($connection, $data['work_section']['project_count']);

/* strumenti::stampaArray($work_list);
exit; */
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Head incluso da inclusioni/head.php -->
        <?php require_once("inclusioni/head.php") ?> 
    </head>
    <body>
        <!-- Barra di navigazione inclusa da navbar.php -->
        <?php require_once('inclusioni/navbar.php') ?>
        <main>
            <!-- Introduzione di me stesso -->
            <div id="columnWrapper">
                <div id="title" class="mainColumns">
                    <h1>
                        <span>I'm Cringy,</span>
                        <span>but my code isn't</span>
                    </h1>
                    <div class="subtitle">
                        <p>
                            Certified <span class="barred">Copy Paster
                        </p>
                        <p>
                            Full Stack Developer
                        </p>
                    </div>
                </div>
                <div id="image" class="mainColumns">
                    <!-- Logo -->
                    <img src="images/logo.png" alt="Logo">
                </div>
            </div>
            <!-- Bottone per scorrere sotto -->
            <div id="button"> 
                <button type="button" id="down" onclick="location.href='<?php echo $data['main']['button_down']['location'] ?>'">
                        <span><?php echo $data['main']['button_down']['content']['text'] ?></span>
                        <i class="<?php echo $data['main']['button_down']['content']['icon']['style'] . " " . $data['main']['button_down']['content']['icon']['symbol'] ?>" id="arrowDown"></i>
                </button>
            </div>
        </main>
        <!-- Sezione Progetti Recenti -->
        <h1 class="title" id="workSection"><?php echo $data['work_section']['section_title'] ?></h1>
        <div id="worksWrapper">
            <div class="works">
                <?php foreach ($work_list as $project) { ?>
                    <!-- Singola Card progetto -->
                    <?php $project_name_url = str_replace(' ', '-', $project['name']) ?>
                    <a href="single_project.php?work=<?php echo $project_name_url ?>">
                        <div class="cards">
                            <img src="<?php echo $project['image_path'] ?>" alt="<?php echo $project['name'] ?>'s Image" title="Go to the  page of '<?php echo $project['image']['title'] ?>'">
                            <!-- Overlay-on-hover -->
                            <div class="overlay">
                                <h2><?php echo $project['name'] ?></h2>
                                <p><?php echo $project['description'] ?></p>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
        <!-- Footer importato da footer.php -->
        <?php require_once("inclusioni/footer.php") ?>
    </body>
</html>    