<?php 
/* Rigenerazione Periodica del Session ID */
$regeneration_interval = 300; // Intervallo di Rigenerazione
$dest_file = 'login.php';

/* Controllo Rigenerazione */
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time(); // Impostazione del tempo iniziale
} elseif (time() - $_SESSION['CREATED'] > $regeneration_interval) {
    session_regenerate_id(true); // Rigenerazione dell'ID con eliminazione del precedente
    $_SESSION['CREATED'] = time(); // Re-impostazione del Tempo Iniziale
}

/* Timeout della Sessione */
$timeout_interval = 600; // Intervallo di Timeout

/* Controllo Timeout */
if (!isset($_SESSION['LAST_ACTIVITY'])) {
    $_SESSION['LAST_ACTIVITY'] = time(); // Impostazione del tempo iniziale
} elseif (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_interval) {
    session_unset(); // Dissociazione di tutti i dati di sessione
    session_destroy(); // Eliminazione della Sessione
    header("Location: {$dest_file}"); // Reindirizzamento alla pagina di Login
    exit(); // Chiusura caricamento pagina corrente
}