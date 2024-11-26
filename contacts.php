<?php
// Importo strumenti e Dati dal JSON
require_once("inclusioni/strumenti.php");
use assets\strumenti;
$data = strumenti::leggiJSON("json/data.json", true)["contacts"];
/* strumenti::stampaArray($data);
exit; */

// Dichiarazione delle variabili

$print = false; // Variabile per la stampa di errori nelle funzioni
$form = []; // Array contenitore delle informazioni da inserire nel file di testo
$flag = []; // Array contenitore degli errori

if (!empty($_POST)) {
    /* strumenti::stampaArray($_POST); */ // Stampa i valori inviati dal form

    /* Validazione lato server del form */
    strumenti::validaTesto($_POST['fname'], 'default', $data['name']['first_name']['max_length'], $data['name']['first_name']['min_length'], $print , 'Name') ? $form['name'] = trim($_POST['fname']) : $flag['fname'] = $data["name"]['first_name']['error_message'];
    strumenti::validaTesto($_POST['lname'], 'default', $data['name']['last_name']['max_length'], $data['name']['last_name']['min_length'], $print , 'Surname') ? $form['surname'] = trim($_POST['lname']) : $flag['lname'] = $data["name"]['last_name']['error_message'];
    strumenti::validateEmail($_POST['email'], $print) ? $form['email'] = trim($_POST['email']) : $flag['email'] = $data["email"]['error_message'];
    strumenti::validatePhone(preg_replace('/\s+/', "", $_POST['phoneNumber'])) ? $form['phone_number'] = preg_replace('/\s+/', "", $_POST['phoneNumber']) : $flag['phoneNumber'] = $data["phone_number"]['error_message'];
    if ($_POST['subject'] == null) {
        $flag[] = $data["subject"]['error_message'];
    } else {
        $form['subject'] = trim($_POST['subject']);
    }
    strumenti::validaTesto($_POST['object'], "default", $data['object']['max_length'], $data['object']['min_length'], $print, "Object") ? $form['object'] = trim($_POST['object']) : $flag['object'] = $data["object"]['error_message'];
    strumenti::validaTesto($_POST['message'], "", $data['message']['max_length'], $data['message']['min_length'], $print, "Message") ? $form['object'] = trim($_POST['message']) : $flag['message'] = $data["message"]['error_message'];

    /* strumenti::stampaArray($form); */ // Stampa dei Valori che verranno scritti
    /* strumenti::stampaArray($flag); */ // Stampa degli errori

    // Scrittura dei valori inviati e formattati in caso di assenza di errori di compilazione
    
    if (empty($flag)) {
        strumenti::writeArrInFile("contatti/contatti.txt", $form, "%s: %s \n");
        $flag = 201;
    } 
}

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
        <main style="<?php if ($flag === 201) { ?> height: calc(100% - 60px); <?php } ?>">
            <!-- Contenitore Form -->
            <div id="contactsWrapper">
                <div id="contactsBg">
                    <?php if ($flag === 201) { ?>
                        <h1 id="formSentMessage"><?php echo $data['form_sent_message'] ?></h1>
                    <?php } else { ?>
                        <!-- Form vero e proprio -->
                        <form action="<?php echo $data['action'] ?>" method="post" id="contactForm" novalidate>
                            <!-- Titolo Form -->
                            <h2 id="title"><?php echo $data['title'] ?></h2>
                            <!-- Input Nome e Cognome -->
                            <label for="fname"><?php echo $data['name']['label'] ?></label>
                            <div id="name">
                                <input type="text" id="fname" name="fname" placeholder="<?php echo $data['name']['first_name']['placeholder'] ?>" required pattern="<?php echo $data['name']['first_name']['pattern'] ?>" title="<?php echo $data['name']['first_name']['title'] ?>" value="<?php echo isset($_POST["fname"]) ? $_POST["fname"] : "" ?>">
                                <input type="text" id="lname" name="lname" placeholder="<?php echo $data['name']['last_name']['placeholder'] ?>" required pattern="<?php echo $data['name']['last_name']['pattern'] ?>" title="<?php echo $data['name']['last_name']['title'] ?>" value="<?php echo isset($_POST["lname"]) ? $_POST["lname"] : "" ?>">
                            </div>
                            <!-- Input Indirizzo E-mail -->
                            <label for="email"><?php echo $data['email']['label'] ?></label>
                            <input type="email" id="email" name="email" placeholder="<?php echo $data['email']['placeholder'] ?>" required pattern="<?php echo $data['email']['pattern'] ?>" title="<?php echo $data['email']['title'] ?>" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : "" ?>">
                            <!-- Input Telefono -->
                            <label for="phoneNumber"><?php echo $data['phone_number']['label'] ?></label>
                            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="<?php echo $data['phone_number']['placeholder'] ?>" required pattern="<?php echo $data['phone_number']['pattern'] ?>" title="<?php echo $data['phone_number']['title'] ?>" value="<?php echo isset($_POST["phoneNumber"]) ? $_POST["phoneNumber"] : "" ?>">
                            <!-- Motivo del contatto -->
                            <label for="subject"><?php echo $data['subject']['label'] ?></label>
                            <select id="subject" name="subject">
                                <?php foreach ($data['subject']['options'] as $option) { ?>
                                    <option value="<?php echo $option['value'] ?>"><?php echo $option['name'] ?></option>
                                <?php } ?>
                            </select>
                            <!-- Oggetto del messaggio -->
                            <label for="object"><?php echo $data['object']['label'] ?></label>
                            <input type="text" id="object" name="object" placeholder="<?php echo $data['object']['placeholder'] ?>" required pattern="<?php echo $data['object']['pattern'] ?>" value="<?php echo isset($_POST["object"]) ? $_POST["object"] : "" ?>">
                            <!-- Corpo del messaggio -->
                            <label for="message"><?php echo $data['message']['label'] ?></label>
                            <textarea id="message" name="message" placeholder="<?php echo $data['message']['placeholder'] ?>" required minlength="<?php echo $data['message']['min_length'] ?>" maxlength="<?php echo $data['message']['max_length'] ?>"><?php echo isset($_POST["message"]) ? $_POST["message"] : "" ?></textarea>
                            <button type="submit" name="submit" value="submit" id="submit">
                                <span id="buttonText"><?php echo $data['submit_button']['text'] ?></span>
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </main>
        <script>
        /* Cambiamento di Stato del Corpo del Messaggio */
        const textarea = document.getElementById('message');

        textarea.addEventListener('input', function () {
            /* Variabili del Corpo del Messaggio */
            const message = textarea.value; // Testo scritto in tempo reale
            const minLength = parseInt(textarea.getAttribute('minlength')); // Lunghezza minima del messaggio
            const maxLength = parseInt(textarea.getAttribute('maxlength')); // Lunghezza massima del messaggio
            
            /* Invalidità del Testo */
            if (message.length < minLength || message.length > maxLength) {
                textarea.setCustomValidity(`Message must be between ${minLength} and ${maxLength} characters.`);
                textarea.classList.add('invalid');
                textarea.classList.remove('valid');

            /* Validità del testo */
            } else {
                textarea.setCustomValidity('');
                textarea.classList.remove('invalid');
                textarea.classList.add('valid');
            }
        });

        /* Mantenimento dell'opzione scelta nell'input del Motivo del Contatto */
        document.getElementById('subject').value = "<?php echo $_POST['subject'];?>";

        /* Marcamento di Errore in caso di Validazione Sbagliata */
        <?php if (!empty($flag)) {
            foreach ($flag as $input => $value) { 
                if ($value) { ?>
                const <?php echo $input ?> = document.getElementById('<?php echo $input ?>'); // Definito la costante dell'input
                <?php echo $input ?>.classList.add('error'); // Aggiunto lo stile di errore 
                /* Rimosso lo stile di errore al click */
                <?php echo $input ?>.addEventListener("click", function () {
                    <?php echo $input ?>.classList.remove("error");
                });
            <?php  }
            }
        } ?>
    </script>
    </body>
</html>