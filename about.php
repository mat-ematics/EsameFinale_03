<?php
// Importo strumenti e dati dal JSON

require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["about"];
/* strumenti::stampaArray($data);
exit; */
?>

<!DOCTYPE html>
<html lang="en">
    <?php require_once("inclusioni/head.php") ?>
    <body>
        <?php require_once("inclusioni/navbar.php") ?>
        <main>
            <!-- Sezione primo piano chi sono -->
            <div id="columnWrapper">
                <div id="image" class="mainColumns">
                    <!-- Immagine placeholder -->
                    <img src="<?php echo $data['main']['image']['link'] ?>" alt="<?php echo $data['main']['image']['alt_text'] ?>">
                </div>
                <div id="title" class="mainColumns">
                    <h1><?php echo $data['main']['title'] ?></h1>
                    <p><?php echo $data['main']['subtitle'] ?></p>
                </div>
            </div>
        </main>
        <div id="lowerHalf">
            <!-- Linguaggi e strumenti da me utilizzati con barre di utilizzo -->
            <div id="langCards">
                <?php foreach ($data['lang_cards']['langs'] as $lang) { ?>
                    <div class="lang">
                        <div class="hexagon">
                            <p class="hexText"><?php echo $lang['name'] ?></p>
                            <i class="fa-brands <?php echo $lang['icon'] ?> <?php echo $data['lang_cards']['icon_style'] ?>" style="color: <?php echo $data['lang_cards']['icon_color'] ?>;"></i>
                        </div>
                        <div class="useBar">
                            <div class="blueBar <?php echo strtolower($lang['name']) ?>"></div>
                            <div class="grayBar <?php echo strtolower($lang['name']) ?>"></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div id="indepthMe">
                <h1><?php echo $data['indepth']['title'] ?></h1>
                <!-- Caratteristiche delle mie pagine -->
                <div id="icons">
                    <?php $row_counter = 0 ?>
                    <?php foreach ($data['indepth']['icons'] as $icon) { ?>
                        <?php if ($row_counter % 2 == 0) { ?>
                            <div class="iconRow">
                        <?php } ?>
                        <div class="icon">
                            <i class="<?php echo $icon['icon_supp_style'] ?> <?php echo $icon['icon'] ?>"></i>
                            <p><?php echo $icon['title']?></p>
                        </div>
                        <?php if ($row_counter % 2 == 1) { ?>
                            </div>
                        <?php } ?>
                        <?php $row_counter++; ?>    
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php require_once("inclusioni/footer.php") ?>
    </body>
</html>