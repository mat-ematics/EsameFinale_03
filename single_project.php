<?php
// Importo Strumenti e dati dal JSON

require_once("inclusioni/strumenti.php");
use assets\strumenti;

$connection = strumenti::connect_database(PUBLIC_USER);

$project_list = strumenti::get_works($connection);

$data = strumenti::leggiJSON("json/data.json", true)["single_project"];

// Controllo dell'id del lavoro

/* strumenti::stampaArray($project_list); */
if (isset($_GET['work']) || !empty($_GET['work'])) {
    $selected_work = str_replace( "-", " ", $_GET['work']);
    foreach ($project_list as $work) {
        if ($work['name'] == $selected_work) {
            $project = $work;
        }
    };
}


// Page Not Found    
if (!isset($project) || empty($project)) {
    http_response_code(404);
    include 'error_page.php';
    exit;
}

/* strumenti::stampaArray($langs);
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
            <!-- Immagine Progetto Principale -->
            <div class="preview">
                    <!-- Overlay dell'immagine - bottone -->
                    <div class="overlay">
                        <button id="visitProject">Visit <?php echo $project["name"] ?></button>
                    </div>
                    <!-- Immagine progetto placeholder -->
                    <img src="<?php echo $project["image_path"] ?>" alt="Image of Work: <?php echo $project["name"] ?>">
                </div>
                <!-- Info Progetto -->
                <div class="projectInfo">
                    <!-- Titolo -->
                    <h1><?php echo $project['name'] ?></h1>
                    <!-- Data del Progetto -->
                    <h2>Project Date: <?php echo $project["date"] ?></h2>
                    <!-- Descrizione -->
                    <p><?php echo $project["description"] ?></p>
                    <!-- Linguaggi usati -->
                    <?php $langs = strumenti::json_implode($project['languages']); ?>
                    <h1>Langs: <?php echo $langs ?></h1>
                </div>
            <!-- Footer -->
            <?php require_once("inclusioni/footer.php") ?>
        </main>
    </body>
</html>