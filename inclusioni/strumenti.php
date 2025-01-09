<?php 

namespace assets;

use Exception;
use InvalidArgumentException;
use mysqli;
use PDO;
use PDOException;

define('EXTENSION_MYSQLI', 'MYSQLI');
define('EXTENSION_PDO', 'PDO');
define('CHECK_LOGIN', 0);
define('CHECK_REGISTER', 1);

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

    /**
     * Creates a new Connection with a Database 
     * 
     * @param string $extension Determine what extension to use using an Extension Costant
     * @param string $host Defines the name of the host of the database
     * @param string $database Defines the name of the database
     * @param string|null $user [optional] Defines the User for the database. Defaults to null
     * @param string|null $password [optional] Defines the Password for the Database's User. Defaults to null
     * 
     * @return class Returns a Connection of the Selected Type
     */
    static public function create_connection(string $extension = EXTENSION_PDO, string $host, string $database, string $user = null, string $password = null) {
        /**
       * Public variable that Stores the Connection 
       */
        $connection = null;
        if ($extension == EXTENSION_MYSQLI) {
            $connection = new \mysqli($host, $user, $password, $database);
            if ($connection->connect_error) {
                throw new \Exception("MySQLi Connection failed: " . $connection->connect_error);
            }
        } elseif ($extension == EXTENSION_PDO) {
            try {
                $connection = new \PDO("mysql:host=".$host.";dbname=".$database, $user, $password);
                $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                throw new \Exception("PDO Connection Failed: " . $e->getMessage());
            }
        } else {
            throw new \Error("ERROR: select a correct Extension Constant");
        }
        return $connection;
    }

    /**
     * Checks if given username and password are present on a given Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param string $username The Username to check
     * @param string $database The Password to check
     * 
     * @return int|bool On success, the ID of the Account matched if found (CHECK_REGISTER) or true (CHECK_LOGIN), false otherwise
     */

    static public function check_credentials(object $connection, string $username, string $password, int $type = CHECK_LOGIN) {

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            // SQL Statement with MySQLi parameters that performs the match
            $sql_check_login = "SELECT `password`, idAdmin FROM admins WHERE username = ?";
            // Execution of the statement
            $query_check_login = $connection->prepare($sql_check_login);
            $query_check_login->bind_param("s", $username);
            $query_check_login->execute();

           
            // Store the Login Result
            $query_check_login->store_result();

            /* Result Binding */
            $query_check_login->bind_result($hash_password, $idUser);
            $query_check_login->fetch();

            // Check if it is empty
            if ($query_check_login->num_rows() > 0) {
                // Determine Credentials Check Type
                if ($type === CHECK_REGISTER) {
                    /* Register Case */
                    return $idUser; //Return the ID of the user
                }
            } else {
                return false;
            }

        } elseif ($connection instanceof PDO) {
            // SQL Statement with PDO parameters
            $sql_check_login = "SELECT `password` FROM admins WHERE username = :inputUsername";
            // Execution of the statement
            $query_check_login = $connection->prepare($sql_check_login);
            $query_check_login->bindParam(":inputUsername", $username, PDO::PARAM_STR); // Username Binding
            $query_check_login->execute();
            // Binding of the Result
            if ($query_check_login->rowCount() > 0) {
                // Determine Credentials Check Type
                if ($type === CHECK_REGISTER) {
                    /* Register Case */
                    $existing_account = $query_check_login->fetch(PDO::FETCH_NUM);
                    return $existing_account;
                } else {
                    /* Login Case */
                    // Binding of the Result
                    $hash_password = $query_check_login->fetch()[0];
                }
            } else {
                return false;
            }
        } else {
            throw new InvalidArgumentException("ERROR: Invalid Connection Type");
        }
        // Boolean Return for Login Verification
        if ($type === CHECK_LOGIN) {
            if (password_verify($password, $hash_password)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Retrieves Admins from the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * 
     * @return array|string Users if successful, otherwise the failure message is returned
     */
    static public function get_admins(object $connection) {
        //Query
        $sql_get_users = "SELECT idAdmin, username, `password` FROM admins";

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            /* Creation of Account */
            try {
                // Query of the statement
                $query_get_users = $connection->query($sql_get_users);
                
                $result = $query_get_users->fetch_all(MYSQLI_ASSOC);
                
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            try {
                // Query of the statement
                $query_get_users = $connection->query($sql_get_users);
                
                $result = $query_get_users->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                /* Failure Handling */
                return $e->getMessage(); 
            }
        }
    }

    /**
     * Creates a New Account in the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param string $username The Username of the new account
     * @param string $password The Password of the new account
     * 
     * @return true|string True if successful, otherwise the failure message is returned
     */
    static public function create_account(object $connection, string $username, string $password) {
        /* Password Hashing */
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Try with MySQLi
        if ($connection instanceof mysqli) {
            // SQL Statement with MySQLi parameters that creates the Account
            $sql_create_account = "INSERT INTO admins (username, `password`) VALUES (?, ?)";
            
            $connection->begin_transaction(); // Transaction For Security
            /* Creation of Account */
            try {
                // Preparation of the statement
                $query_create_account = $connection->prepare($sql_create_account);
                // Parameter Binding and Query Execution
                $query_create_account->bind_param("ss", $username, $password_hash);
                $query_create_account->execute();

                /* Commit Changes and Return True on Success */
                $connection->commit();
                return true;               
            } catch (Exception $e) {
                /* Failure Handling */
                $connection->rollback(); // Rollback of changes
                return $e->getMessage(); 
            }
        } elseif ($connection instanceof PDO) {
            $sql_create_account = "INSERT INTO admins (username, `password`) VALUES (:usr, :psw)";
            $connection->beginTransaction();
            try {
                // Preparation of the statement
                $query_create_account = $connection->prepare($sql_create_account);
                // Parameter Binding and Query Execution
                $query_create_account->bindParam(":usr", $username);
                $query_create_account->bindParam(":psw", $hash_password);
                $query_create_account->execute();

                /* Commit Changes and Return True on Success */
                $connection->commit();
                return true;               
            } catch (PDOException $e) {
                /* Failure Handling */
                $connection->rollback(); // Rollback of changes
                return $e->getMessage(); 
            }
        }
    }

    /**
     * Modifies the username and/or password of an existing account
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $idAdmin The ID number of the account
     * @param string $username (optional) The new Username 
     * @param string $password (optional) The new Password 
     * 
     * @return true|string True if successful, otherwise the failure message is returned
     */
    static public function edit_user(object $connection, int $idAdmin, string $username = '', string $password = '') {
        /* Arrays for Database Update */
        $updates = [];
        $params = [];
        $types = "";
        
        /* Query Creation */
        $sql_update_users = "UPDATE admins SET "; //Query start
        //Joining of Username (if present)
        if ($username != '') {
            $updates[] = "username = ?"; //Username field SQL update
            $params[] = $username; //Add username to parameters array
            $types .= 's';
        }
        //Joining of Password (if present)
        if ($password != '') {
            $updates[] = "password = ?"; //Password field SQL update
            $params[] = password_hash($password, PASSWORD_DEFAULT); //Add password to parameters array
            $types .= 's';
        }
        //Adjoining of SQL togheter
        $sql_update_users .= implode(', ', $updates); //Joins the parameter array togheter
        $sql_update_users .= " WHERE idAdmin = ?"; //Username field SQL search
        $params[] = $idAdmin;
        $types .= 'i';

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            /* Modification Start */
            /* Modification of Account */
            $connection->begin_transaction(); //Start of Transaction
            try {
                // Query of the statement
                $query_update_users = $connection->prepare($sql_update_users); //Prepare the statement

                /* The following steps allows to pass the array into the query's method (otherwise impossible, it requires individual variables) */
                // Combine types and parameters into a single array with references
                $bind_names = [$types];
                foreach ($params as $key => $value) {
                    $bind_names[] = &$params[$key]; // & -> Pass by reference
                }
                call_user_func_array([$query_update_users, "bind_param"], $bind_names); //Use this function to bypass array parameter limitation

                $result = $query_update_users->execute(); //Statement Execution

                /* Return of the Result and Commit Changes */
                $connection->commit();
                return $result;
            } catch (Exception $e) {
                /* Return Error Message and Rollback changes */
                $connection->rollback();
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            /* Modification Start */
            //Transaction Start
            $connection->beginTransaction();
            try {
                // Query of the statement
                $query_update_users = $connection->prepare($sql_update_users); //Prepare the statement

                /* Parameter Binding */
                $i = 1; //Iterator (for ? placeholders)
                foreach ($params as $value) {
                    //Type Indentification
                    $type = PDO::PARAM_STR; 
                    if (gettype($value) == 'integer') {
                        $type = PDO::PARAM_INT;
                    }
                    $query_update_users->bindParam($i, $value, $type); //Binding
                    $i++; //Iterator Progression
                }
                $result = $query_update_users->execute(); //Statement Execution

                /* Return of the Result and Commit Changes */
                $connection->commit();
                return $result;
            } catch (PDOException $e) {
                /* Failure Handling and Rollback */
                $connection->rollBack();
                return $e->getMessage(); 
            }
        }
    }

    /**
     * Deletes an existing account
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $idAdmin The ID number of the account
     * 
     * @return bool|string Either True if successful, the failure message, or a non-exception-caught failure is returned
     */
    static public function delete_user(object $connection, int $idAdmin) {
        
        // Try with MySQLi
        if ($connection instanceof mysqli) {
            //SQL statement
            $sql_delete_user = "DELETE FROM admins WHERE idAdmin = ?";
            
            /* Deletion */
            $connection->begin_transaction(); //Start of Transaction
            try {
                // Query of the statement
                $query_delete_user = $connection->prepare($sql_delete_user); //Prepare the statement
                $query_delete_user->bind_param('i', $idAdmin);
                $result = $query_delete_user->execute(); //Statement Execution

                /* Return of the Result and Commit Changes */
                $connection->commit();
                return $result;
            } catch (Exception $e) {
                /* Return Error Message and Rollback changes */
                $connection->rollback();
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            //SQL statement
            $sql_delete_user = "DELETE FROM admins WHERE idAdmin = :id";

            /* Deletion */
            $connection->beginTransaction();
            try {
                // Query of the statement
                $query_delete_user = $connection->prepare($sql_delete_user); //Prepare the statement
                $query_delete_user->bindParam(':id', $idAdmin, PDO::PARAM_INT); // Parameter Binding
                $result = $query_delete_user->execute(); //Statement Execution

                /* Return of the Result and Commit Changes */
                $connection->commit();
                return $result;
            } catch (PDOException $e) {
                /* Failure Handling and Rollback */
                $connection->rollBack();
                return $e->getMessage(); 
            }
        }
    }

    /**
     * Checks if given category name is present on a given Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param string $category The Name of the Category to check
     * @param bool $return_id If true, returns the ID matched on success, otherwise it returns true
     * 
     * @return int|bool On success, the ID of the Account matched if found or true , false otherwise
     */
     static public function check_category(object $connection, string $category, bool $return_id = false) {

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            // SQL Statement with MySQLi parameters that performs the match
            $sql_check_login = "SELECT idCategory FROM categories WHERE `name` = ?";
            // Execution of the statement
            $query_check_login = $connection->prepare($sql_check_login);
            $query_check_login->bind_param("s", $category);
            $query_check_login->execute();

           
            // Store the Login Result
            $query_check_login->store_result();

            /* Result Binding */
            $query_check_login->bind_result($idCategory);
            $query_check_login->fetch();

            // Check if it is empty
            if ($query_check_login->num_rows() > 0) {
                // Determine Credentials Check Type
                if ($return_id) {
                    /* Register Case */
                    return $idCategory; //Return the ID of the user
                } else {
                    return true; //Return true
                }
            } else {
                return false;
            }

        } elseif ($connection instanceof PDO) {
            // SQL Statement with PDO parameters
            $sql_check_login = "SELECT idCategory FROM categories WHERE `name` = :inputName";
            // Execution of the statement
            $query_check_login = $connection->prepare($sql_check_login);
            $query_check_login->bindParam(":inputName", $category, PDO::PARAM_STR); // Name Binding
            $query_check_login->execute();
            // Binding of the Result
            if ($query_check_login->rowCount() > 0) {
                // Determine Credentials Check Type
                if ($return_id) {
                    /* ID case */
                    $idCategory = $query_check_login->fetch(PDO::FETCH_NUM);
                    return $idCategory; //Returns the ID
                } else {
                    /* Boolean Case */
                    return true; // Return true
                }
            } else {
                return false;
            }
        } else {
            throw new InvalidArgumentException("ERROR: Invalid Connection Type");
        }
    }

    /**
     * Creates a New Category in the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param string $category The Name of the new category
     * 
     * @return true|string True if successful, otherwise the failure message is returned
     */
    static public function create_category(object $connection, string $category) {
        
        // Try with MySQLi
        if ($connection instanceof mysqli) {
            // SQL Statement with MySQLi parameters that creates the Account
            $sql_create_category = "INSERT INTO categories (`name`) VALUES (?)";
            
            $connection->begin_transaction(); // Transaction For Security
            /* Creation of Account */
            try {
                // Preparation of the statement
                $query_create_category = $connection->prepare($sql_create_category);
                // Parameter Binding and Query Execution
                $query_create_category->bind_param("s", $category);
                $query_create_category->execute();

                /* Commit Changes and Return True on Success */
                $connection->commit();
                return true;               
            } catch (Exception $e) {
                /* Failure Handling */
                $connection->rollback(); // Rollback of changes
                return $e->getMessage(); 
            }
        } elseif ($connection instanceof PDO) {
            $sql_create_category = "INSERT INTO categories (`name`) VALUES (:catname)";
            $connection->beginTransaction();
            try {
                // Preparation of the statement
                $query_create_category = $connection->prepare($sql_create_category);
                // Parameter Binding and Query Execution
                $query_create_category->bindParam(":catname", $category);
                $query_create_category->execute();

                /* Commit Changes and Return True on Success */
                $connection->commit();
                return true;               
            } catch (PDOException $e) {
                /* Failure Handling */
                $connection->rollback(); // Rollback of changes
                return $e->getMessage(); 
            }
        }
    }

    /**
     * Retrieves Categories from the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * 
     * @return array|string Categories if successful, otherwise the failure message is returned
     */
    static public function get_categories(object $connection) {
        //Query
        $sql_get_cat = "SELECT idCategory, `name` FROM categories";

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            /* Creation of Account */
            try {
                // Query of the statement
                $query_get_cat = $connection->query($sql_get_cat);
                
                $result = $query_get_cat->fetch_all(MYSQLI_ASSOC);
                
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            try {
                // Query of the statement
                $query_get_cat = $connection->query($sql_get_cat);
                
                $result = $query_get_cat->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                /* Failure Handling */
                return $e->getMessage(); 
            }
        }
    }
}