<?php
// Importo Strumenti e dati dal JSON

require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["single_project"];
$project_list = strumenti::leggiJSON("json/data.json", true)['projects']['project_list'];

// Controllo dell'id del lavoro

/* strumenti::stampaArray($project_list); */
if (isset($_GET['id']) || !empty($_GET['id'])) {
    foreach ($project_list as $item => $details) {
        if ($details['project_id'] == $_GET['id']) {
            $project = $details;
        }
    };
}

// Impostazione di un Placeholder in caso di id mancante/errato

if (!isset($project) || empty($project)) $project = $data;

/* strumenti::stampaArray($project);
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
            <!-- Immagine Progetto Principale -->
            <div class="preview">
                    <!-- Overlay dell'immagine - bottone -->
                    <div class="overlay">
                        <button id="visitProject">Visit <?php echo $project["project_title"] ?></button>
                    </div>
                    <!-- Immagine progetto placeholder -->
                    <img src="<?php echo $project["image"]['link'] ?>" alt="<?php echo $project["image"]['alt_text'] ?>">
                </div>
                <!-- Info Progetto -->
                <div class="projectInfo">
                    <!-- Titolo -->
                    <h1><?php echo $project['project_title'] ?></h1>
                    <!-- Descrizione -->
                    <p><?php echo $project["project_description_long"] ?></p>
                    <!-- Linguaggi usati -->
                    <p>Languages: <?php echo $project["langs"] ?></p>
                    <!-- Data del Progetto -->
                    <h1>Project Date: <?php echo $project["project_date"] ?></h1>
                </div>
            <!-- Footer -->
            <?php require_once("inclusioni/footer.php") ?>
        </main>
    </body>
</html>