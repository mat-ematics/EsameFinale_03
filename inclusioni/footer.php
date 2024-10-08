<?php
require_once("strumenti.php");
use assets\strumenti;
$footer = strumenti::leggiJSON("json/data.json", true)["footer"];
?>

<!-- Piè di pagina -->
<footer>
    <!-- Link ai Social -->
    <?php foreach ($footer['socials'] as $social) { ?>
        <div class="social">
            <a href="<?php echo $social['link'] ?>" target="_blank" title="<?php echo $social['title'] ?>">
                <i class="fa-brands fa-<?php echo $social['icon'] ?> <?php echo $footer['icon_style'] ?>"></i>
            </a>
        </div>
    <?php } ?>
</footer>