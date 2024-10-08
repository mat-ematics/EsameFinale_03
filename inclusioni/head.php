<?php
require_once("strumenti.php");
use assets\strumenti;
?> 
    
<head>
    <!-- Titolo pagina Home -->
    <title><?php echo $data['page_title'] ?></title>
    <!-- Font e icone varie -->
    <link rel="stylesheet" type="text/css" href="css/global.min.css">
    <link rel="stylesheet" type="text/css" href="css/<?php echo $data['style_sheet'] ?>">
    <script src="https://kit.fontawesome.com/81cee0c499.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&family=Source+Code+Pro:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/logo.ico">
    <!-- Adattatore schermo -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- Meta Tag -->
    <meta name="keywords" content="Cringe.devs, C.devs, c.devs, cringe.devs, Matt.devs, M.devs, m.devs, matt.devs">
    <!-- Descrizione del Sito -->
    <meta name="description" content="Matt's Portfolio - <?php echo $data['page_title'] ?>">
    <!-- Autore del Sito -->
    <meta name="author" content="Matt">

    <style>
        div#<?php echo strtolower($data['page_title']) ?> {
        color: white;
        border-bottom: 2px solid rgb(0, 162, 255);
    }
    </style>
</head>