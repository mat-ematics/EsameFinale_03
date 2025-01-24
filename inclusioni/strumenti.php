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
define('IMAGE_DIRECTORY', "images/");

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

    /**
     * Modifies an existing Category
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $idCategory The ID number of the Category to Modify
     * @param string $category The New name of the Category
     * 
     * @return true|string True if successful, otherwise the failure message is returned
     */
    static public function edit_category(object $connection, int $idCategory, string $category) {
                
        // Try with MySQLi
        if ($connection instanceof mysqli) {
            /* Query Creation */
            $sql_update_category = "UPDATE categories SET `name` = ? WHERE idCategory = ?"; //Query start

            $connection->begin_transaction(); //Start of Transaction
            try {
                // Query of the statement
                $query_update_category = $connection->prepare($sql_update_category); //Prepare the statement
                $query_update_category->bind_param("si", $category, $idCategory); //Bind the Parameter
                $result = $query_update_category->execute(); //Statement Execution

                /* Return of the Result and Commit Changes */
                $connection->commit();
                return $result;
            } catch (Exception $e) {
                /* Return Error Message and Rollback changes */
                $connection->rollback();
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {

            $sql_update_category = "UPDATE categories SET `name` = :newCatName WHERE idCategory = :idCat"; //Query start

            //Transaction Start
            $connection->beginTransaction();
            try {
                // Query of the statement
                $query_update_category = $connection->prepare($sql_update_category); //Prepare the statement

                /* Parameter Binding */
                $query_update_category->bindParam(":newCatName", $category);
                $query_update_category->bindParam(":idCat", $idCategory, PDO::PARAM_INT);

                $result = $query_update_category->execute(); //Statement Execution

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
     * Deletes an existing Category
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $idCategory The ID number of the Category to delete
     * 
     * @return bool|string Either True if successful, the failure message, or a non-exception-caught failure is returned
     */
    static public function delete_category(object $connection, int $idCategory) {
        
        // Try with MySQLi
        if ($connection instanceof mysqli) {
            //SQL statement
            $sql_delete_category = "DELETE FROM categories WHERE idCategory = ?";
            
            /* Deletion */
            $connection->begin_transaction(); //Start of Transaction
            try {
                // Query of the statement
                $query_delete_category = $connection->prepare($sql_delete_category); //Prepare the statement
                $query_delete_category->bind_param('i', $idCategory);
                $result = $query_delete_category->execute(); //Statement Execution

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
            $sql_delete_category = "DELETE FROM categories WHERE idCategory = :id";

            /* Deletion */
            $connection->beginTransaction();
            try {
                // Query of the statement
                $query_delete_category = $connection->prepare($sql_delete_category); //Prepare the statement
                $query_delete_category->bindParam(':id', $idCategory, PDO::PARAM_INT); // Parameter Binding
                $result = $query_delete_category->execute(); //Statement Execution

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
     * Creates a Response associative Array (e.g.: for form submission) with given status and message
     * 
     * @param int $status The Status Code of the Response
     * @param string $message The Message of the Response
     * 
     * @return array The newly created array
     */
    static public function createResponse(int $status, string $message) {
        return [
            'status' => $status,
            'message' => $message
        ];
    }

    /**
     * Checks if the provided credentials are correct and match the provided regex
     * 
     * @param string $username The Username to validate
     * @param string $password The Password to validate
     * @param string $regexUsername The Regex of the Username
     * @param string $regexPassword The Regex of the Password
     * @param string $repeatPassword [optional] The Password inside Repeat Password
     * @param bool $allowEmpty [optional] Whether to allow empty values
     * 
     * @return boolean `True` if valid, `false` if invalid
     */
    /* Credential Validation */
    static public function validateCredentials(string $username, string $password, string $regexUsername, string $regexPassword, string $repeatPassword = null, bool $allowEmpty = false) {
        $flagValid = true;

        if (!preg_match($regexUsername, $username) || !preg_match($regexPassword, $password)) {
            $flagValid = false;
        }

        if ($allowEmpty && ($username == '' || $password == '') && !($username == '' && $password == '')) {
            $flagValid = true;
        }

        if (!is_null($repeatPassword) && $password !== $repeatPassword) {
            $flagValid = false;
        }

        return $flagValid;
    }

    /**
     * Handles image upload, validates that the uploaded file is an image,
     * and saves it in the provided directory.
     * 
     * @param array $file The uploaded file from the `$_FILES` array.
     * @param string $targetDir The target directory of the image. If not present, an attempt to create one is made
     * @param int $maxSize [optional] The Max Size in MegaBytes allowed
     * @return array An Associative Array containing all the image info (name, extension, full path, size, error flag and error message).
     */
    static public function uploadImage(array $file, string $targetDir = IMAGE_DIRECTORY, $max_size = 5): array
    {
        /* Response Array */
        $response = [
            'name' => '',
            'extension' => '',
            'full_path' => '',
            'size' => $file['size'],
            'error_flag' => false,
            'error_message' => 'None.',
        ];

        // Check if the target directory exists; if not, attempt to create it
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                /* Update Error Variables */
                $response['error_flag'] = true;
                $response['error_message'] = "Failed to create the target directory.";
                /* Early Return */
                return $response;
            }
        }

        // Validate file upload
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $response['error_flag'] = true;
            $response['error_message'] = "No file was uploaded.";
            return $response;
        }

        // Validate file size (max 5MB)
        if ($file['size'] > $max_size * 1024 * 1024) {
            $response['error_flag'] = true;
            $response['error_message'] = "File size exceeds the 5MB limit.";
            return $response;
        }

        // Validate file type using MIME type
        $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileMimeType = mime_content_type($file['tmp_name']);
        if (!in_array($fileMimeType, $validMimeTypes)) {
            $response['error_flag'] = true;
            $response['error_message'] = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
            return $response;
        }

        // Generate unique filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid('img_', true) . '.' . $fileExtension;
        $targetFile = $targetDir . $uniqueFileName;

        // Attempt to move the uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            $response['error_flag'] = true;
            $response['error_message'] = "Failed to save the uploaded file.";
            return $response;
        }

        // Ensure rollback for unexpected failure during final validation
        if (!file_exists($targetFile)) {
            unlink($targetFile);
            $response['error_flag'] = true;
            $response['error_message'] = "Uploaded file is missing after save operation. Rollback triggered.";
            return $response;
        }

        /* Update Respnonse for Successful Update */
        $response['name'] = $uniqueFileName;
        $response['extension'] = $fileExtension;
        $response['full_path'] = $targetFile;
        return $response;
    }

    /**
     * Replaces an existing image with a new one.
     * Includes rollback in case of failure.
     */
    static public function replaceImage(string $currentFilePath, array $newFile, string $targetDir = IMAGE_DIRECTORY): array
    {
        /* Response Array */
        $response = [
            'name' => '',
            'extension' => '',
            'full_path' => '',
            'size' => $newFile['size'],
            'error_flag' => false,
            'error_message' => 'None.',
        ];
        $newFileResult = null;

        // Validate current file existence
        if (!file_exists($currentFilePath)) {
            return [
                'error_flag' => true,
                'error_message' => "The file to replace does not exist.",
            ];
        }

        // Backup the current file in case of rollback
        $backupPath = $currentFilePath . '.backup';
        if (!copy($currentFilePath, $backupPath)) {
            return [
                'error_flag' => true,
                'error_message' => "Failed to create a backup of the current file.",
            ];
        }

        // Attempt to delete the current file
        if (!unlink($currentFilePath)) {
            return [
                'error_flag' => true,
                'error_message' => "Failed to delete the existing file.",
            ];
        }

        // Attempt to upload the new file
        $newFileResult = self::uploadImage($newFile, $targetDir);
        if ($newFileResult['error_flag']) {
            // Rollback: Restore the backup if upload fails
            rename($backupPath, $currentFilePath);
            return [
                'error_flag' => true,
                'error_message' => "Replacement failed: " . $newFileResult['error_message'],
            ];
        }

        // Cleanup: Delete the backup after a successful replacement
        unlink($backupPath);

        return $newFileResult;
    }

    /**
     * Deletes an existing image file.
     * Includes rollback in case of failure.
     */
    static public function deleteImage(string $filePath): array
    {
        $flagError = false;
        $errorMessage = 'None.';

        // Validate that the file exists
        if (!file_exists($filePath)) {
            return [
                'error_flag' => true,
                'error_message' => "The file does not exist.",
            ];
        }

        // Backup the file in case of rollback
        $backupPath = $filePath . '.backup';
        if (!copy($filePath, $backupPath)) {
            return [
                'error_flag' => true,
                'error_message' => "Failed to create a backup before deletion.",
            ];
        }

        // Attempt to delete the file
        if (!unlink($filePath)) {
            return [
                'error_flag' => true,
                'error_message' => "Failed to delete the file.",
            ];
        }

        // Cleanup: Remove the backup after successful deletion
        unlink($backupPath);

        return [
            'success' => true,
            'error_flag' => false,
            'error_message' => $errorMessage,
        ];
    }

    /**
     * Validates an Array of text agaisnt a given regex
     * 
     * @param array $array The array to validate
     * @param string $regex [optional] The Regex to check against. It has a default regex of alphabetic and single space, betwen 2 and 30 characters
     * 
     * @return bool `true` if the all elements are valid, `false` otherwise.
     */
    static public function validate_array_text(array $array, string $regex = "/^[a-zA-Z ]{2,30}$/") :bool {
        if (!is_array($array) || empty($array)) {
            return false;
        }
        foreach ($array as $value) {
            if (!preg_match($regex, $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validates a Date with a given format
     * 
     * @param string $date The Date to validate
     * @param string $format [optional] The Format of the date to validate. Defaults to YYYY-MM-DD
     */
    static public function validate_date(string $date, string $format = 'Y-m-d') :bool {
        /* Crates a Date with the specific format and checks if it is correct */
        return \DateTime::createFromFormat($format, $date) !== false;
    }
    
    /**
     * Creates a New Work in the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $category_id The ID of the Category to associate the work with
     * @param string $work_name The Name of the new work
     * @param string $work_description The description of the new work
     * @param string $work_date The Date of the new work
     * @param string $work_image_path The Full Path of the Image of the new work
     * @param array $work_languages The Languages used in the new work
     * @param bool $print_errors [optional] If `true`, prints error messages. Defaults to `false`
     * 
     * @return bool|string `true` if successful, otherwise `false` or the failure message is returned
     */
    static public function create_work(object $connection, int $category_id, string $work_name, string $work_description, string $work_date, string $work_image_path, array $work_languages, bool $print_errors = false) {
        
        $languages_encoded = json_encode($work_languages);

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            // SQL Statement with MySQLi parameters that creates the Account
            $sql_create_work = "INSERT INTO works (idCategory, `name`, `date`, image_path, languages, `description`) VALUES (?, ?, ?, ?, ?, ?)";
            
            $connection->begin_transaction(); // Transaction For Security
            /* Creation of Account */
            try {
                // Preparation of the statement
                $query_create_work = $connection->prepare($sql_create_work);
                // Parameter Binding and Query Execution
                $query_create_work->bind_param("isssss", $category_id, $work_name, $work_date, $work_image_path, $languages_encoded, $work_description);
                $query_create_work->execute();

                /* Commit Changes and Return True on Success */
                $connection->commit();
                return true;               
            } catch (Exception $e) {
                /* Failure Handling */
                $connection->rollback(); // Rollback of changes
                if ($print_errors) {
                    return $e->getMessage(); 
                }
                return false;
            }
        } elseif ($connection instanceof PDO) {
            $sql_create_work = "INSERT INTO works (idCategory, `name`, `date`, image_path, languages, `description`) VALUES (:idCat, :wrk_name, :wrk_date, :img_path, :langs, :wrk_desc)";
            $connection->beginTransaction();
            try {
                // Preparation of the statement
                $query_create_work = $connection->prepare($sql_create_work);
                /* Parameter Binding and Query Execution */
                $query_create_work->bindParam(":idCat", $category_id);
                $query_create_work->bindParam(":wrk_name", $work_name);
                $query_create_work->bindParam(":wrk_date", $work_date);
                $query_create_work->bindParam(":img_path", $work_image_path);
                $query_create_work->bindParam(":langs", $languages_encoded);
                $query_create_work->bindParam(":wrk_desc", $work_description);
                /* Execute Statement */
                $query_create_work->execute();

                /* Commit Changes and Return True on Success */
                $connection->commit();
                return true;               
            } catch (PDOException $e) {
                /* Failure Handling */
                $connection->rollback(); // Rollback of changes
                if ($print_errors) {
                    return $e->getMessage(); 
                }
                return false;            
            }
        }
    }
}