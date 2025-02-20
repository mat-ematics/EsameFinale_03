<?php
require_once("strumenti.php");

use assets\strumenti;

$header = strumenti::leggiJSON("json/data.json", true)["header"];
?>

<header>
    <!-- Barra di navigazione -->
    <nav>
        <ul id="navBar">
            <li class="navSides">
                <!-- Logo -->
                <div id="navLogo">
                    <a href="<?php echo $header["logo"]['link']  ?>"><?php echo $header['logo']['image'] ?></a>
                </div>
            </li>
            <li id="navSections" class="sections">
                <div id="navMenu">
                    <?php foreach ($header['navbar'] as $item => $link) { ?>
                        <div class="navItems <?php if ($item == $data['page_title']) echo "selected-page" ?>">
                            <a href="<?php echo $link ?>" title="<?php echo $item ?>"><?php echo $item ?></a>
                        </div>
                    <?php } ?>
                </div>
            </li>
            <li class="navSides">
                <!-- Log In -->
                <div id="loginButton" class="<?php if ($data['page_title'] == "Log In") echo "selected-page" ?>">
                    <a href="<?php echo $header['login']['link'] ?>" title="<?php echo $header['login']['title'] ?>">
                        <?php echo $header['login']['text'] ?>
                    </a>
                </div>
                <!-- Menu -->
                <div id="menuIcon">
                    <a href="javascript:void(0)" onclick="menu()"><i class="fa-solid fa-bars"></i></a>
                </div>
            </li>
        </ul>
    </nav>
</header>
<script>
    function menu() {
        let navSections = document.getElementById("navSections");
        if (navSections.className === "sections") {
            navSections.className += " responsive";
        } else {
            navSections.className = "sections";
        }
    }
</script>