<?php 

namespace assets;

/**
 * Classe contente strumenti utili
 * 
 * @author Matteo
 * @copyright Copyright (c) 2024
 * @license GNU AGPLv3
 * @version 1.0.0
 */
class strumenti {
    /**
     * Stampa un array dato con identazione
     * @param array l'array da stampare
     */
    public static function stampaArray($array)
    {
        echo "<pre>" . print_r($array, true) . "</pre>";
    }

    /**
     * Legge un dato file JSON
     * @param string $file Il percorso del File JSON
     * @param boolean $array [opzionale] Se vero, restituisce il file come array associativo anziché come oggetto. Il valore predefinito è falso
     * @param boolean $print [opzionale] Se vero, stampa i dati JSON
     * @return object|array I dati
     */
     public static function leggiJSON($file, $array = false, $print = false)
     {
         $json = file_get_contents($file);
         $json_data = json_decode($json, $array);
         if ($print) {
            strumenti::stampaArray($json_data);
         }
         return $json_data;
     }

     /**
     * Valida un dato testo
     * @param string $text Il testo da validare
     * @param string $format [facoltativo] Il formato che il testo NON DEVE AVERE. Il valore predefinito è "qualsiasi carattere non alfabetico e non costituito da spazi bianchi". Scrivi "default" per utilizzare il formato predefinito
     * @param int $maxlength [facoltativo] La lunghezza massima del testo. Il valore predefinito è 32
     * @param int $minlength [facoltativo] La lunghezza minima del testo. Il valore predefinito è 1
     * @param boolean $print [opzionale] Se vero, stampa il valore del parametro. Il valore predefinito è falso
     * @param string $field [facoltativo] Il nome del campo da visualizzare durante la stampa, per impostazione predefinita è "Testo"
     * @return boolean Se valido restituisce vero, altrimenti falso
     */
    public static function validaTesto($text, $format = "/[^a-zA-Z\s]/", $maxlength = 32, $minlength = 1, $print = false, $field = 'Text')
    {

        if ($format === "default") {
            $format = "/[^a-zA-Z\s]/";
        }

        $text = trim($text);
        if ($text == null || $text == "") {
            if ($print) echo "<br>Insert $field<br>";
            return false;
        }


        if (strlen($text) < $minlength || strlen($text) > $maxlength) {
            if ($print) echo "<br>The $field must be between $minlength and $maxlength characters<br>";
            return false;
        }

        if ($format !== "") {
            if (preg_match_all($format, $text) !== 0) {
                if ($print) echo "<br>The $field must follow the given regex<br>";
                return false;
            }
        }
        if ($print) echo "<br>$field: '$text' is valid<br>";
        return true;
    }

    /**
     * Validates a given email
     * @param string $key The email to validate
     * @param boolean $print If true, it prints the value of the parameter. Defaults as false
     * @param int $minlength [optional] The min length of the email. If omitted, it is equal 1
     * @param int $maxlength [optional] The max length of the email. If omitted, no bound is set
     * @return boolean If invalid, returns false, otherwise true
     */
    public static function validateEmail($email, $print = false, $minlength = 1, $maxlength = null)
    {
        $email = trim($email);
        if ($email == null) {
            if ($print) echo "<br>Insert E-mail<br>";
            return false;
        }
        $validate = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($validate === false) {
            if ($print) echo "<br>'$email' is invalid: please insert a correct E-mail<br>";
            return false;
        }

        if ($minlength !== 1) {
            if (strlen($email) < $minlength) {
                if ($print) echo "<br>The Email must be at least $minlength characters long<br>";
                return false;
            }
        } elseif ($maxlength !== null) {
            if (strlen($email) > $maxlength) {
                if ($print) echo "<br>The Email must be less than $maxlength characters long<br>";
                return false;
            }
        }

        if ($print) echo "<br>E-mail '$email' is valid<br>";
        return true;
    }

    /**
     * Validates a given phone number
     * @param string $key The phone number to validate
     * @param boolean $print If true, it prints the value of the parameter. Defaults as false
     * @return boolean If invalid, returns false, otherwise true
     */
    public static function validatePhone($phone, $print = false)
    {
        $phone = trim($phone);
        if ($phone == null) {
            if ($print) echo "<br>Insert Phone Number<br>";
            return false;
        }

        if (str_starts_with($phone, "+")) {
            if (strlen($phone) < 8 || strlen($phone) > 16) {
                if ($print) echo "<br>The Phone Number must be between 7 and 15 digits<br>";
                return false;
            }
        } else {
            if (strlen($phone) < 7 || strlen($phone) > 15) {
                if ($print) echo "<br>The Phone Number must be between 7 and 15 digits<br>";
                return false;
            }
        }

        if (preg_match_all("/^(?!(\+)?\d+$).*$/", $phone) !== 0) {
            if ($print) echo "<br>The Phone Number must contain only Digits and (optionally) a starting + Symbol<br>";
            return false;
        }
        if ($print) echo "<br>Phone Number '$phone' is valid<br>";
        return true;
    }

    /**
     * Checks wether a given file exists
     * @param string $file The path of the file to be checked
     * @param boolean $print If true, it prints a statement on the existance of the file. Defaults as false
     * @return boolean If it exists, returns true, otherwise false
     */
    public static function fileExistance($file, $print = false)
    {
        if (file_exists($file)) {
            if (is_file($file)) {
                if ($print) {
                    echo "<br>'$file' exists<br>";
                }
                return true;
            } elseif ($print) {
                echo "<br>'$file' is not a file<br>";
            }
        } elseif ($print) {
            echo "<br>'$file' does not exist<br>";
        }
        return false;
    }

    /**
     * Writes an associative array of contents in a file in a given format per line
     * @param string $file The path of the file to be written. If it doesn't exist, a new one is created
     * @param array $contents The array of contents to be written
     * @param string $format The format in which each key-value pair is written
     * @param boolean $print [optional] If true, it prints each line. Defaults as false
     * @return boolean True if it was successful, otherwise false
     */
    public static function writeArrInFile($file, array $contents, $format, $print = false)
    {
        /* File existance check */
        if (strumenti::fileExistance($file, $print) === false && $print) {
            echo "<br>Creating new file<br>";
        }

        /* File opening */
        $handle = fopen($file, "a");
        if ($handle == false) {
            echo "Could not open file";
            return false;
        }

        /* File writing */

        foreach ($contents as $key => $value) {
            if (array_is_list($contents)) {
                // Sequential Array
                $line = sprintf($format, $value);
            } else {
                // Associative Array
                $line = sprintf($format, $key, $value);
            }
            if (fwrite($handle, $line) == false) {
                echo "Could not write in file";
                return false;
            }

            if ($print) {
                echo "<br>$line<br>";
            }
        }

        fwrite($handle, "\n\n");

        // Resource closing
        fclose($handle);
        return true;
    }
}