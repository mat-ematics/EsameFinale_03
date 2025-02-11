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
define('IMAGE_DIRECTORY', "uploads/images/");
define('PUBLIC_USER', 0);
define('ADMIN_USER', 1);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
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
    static public function create_connection(
        string $extension = EXTENSION_PDO,
        string $host,
        string $database,
        string $user = null,
        string $password = null
    ) {
        try {
            if ($extension === EXTENSION_MYSQLI) {
                $connection = new \mysqli($host, $user, $password, $database);
                if ($connection->connect_error) {
                    throw new \Exception("MySQLi Connection failed: " . $connection->connect_error);
                }
            } elseif ($extension === EXTENSION_PDO) {
                $connection = new \PDO("mysql:host=$host;dbname=$database", $user, $password, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]);
            } else {
                throw new \InvalidArgumentException("ERROR: Invalid extension type.");
            }
            return $connection;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
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
     * @param bool $idKeys If `true`, the returned array is in the form id => name (defaults to `false`)
     * 
     * @return array|string Categories if successful, otherwise the failure message is returned
     */
    static public function get_categories(object $connection, bool $idKeys = false) {
        //Query
        $sql_get_cat = "SELECT idCategory, `name` FROM categories";

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            /* Creation of Account */
            try {
                // Query of the statement
                $query_get_cat = $connection->query($sql_get_cat);
                
                $result = $query_get_cat->fetch_all(MYSQLI_ASSOC);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            try {
                // Query of the statement
                $query_get_cat = $connection->query($sql_get_cat);
                
                $result = $query_get_cat->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                /* Failure Handling */
                return $e->getMessage(); 
            }
        } else {
            return "Invalid Connection Type.";
        }

        if ($idKeys) {
            foreach ($result as $key => $category) {
                unset($result[$key]);
                $result[$category['idCategory']] = $category['name'];
            }
        }

        return $result;
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
     * @param string $specificName The name with which to save the image. If it is null, a unique one is generated
     * @param string $targetDir [optional] The target directory of the image. If not present, an attempt to create one is made
     * @param int $maxSize [optional] The Max Size in MegaBytes allowed
     * @return array An Associative Array containing all the image info (name, extension, full path, size, error flag and error message).
     */
    static public function uploadImage(array $file, string $specificName = null, string $targetDir = IMAGE_DIRECTORY, $max_size = 5): array
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
        $uniqueFileName = $specificName ?? uniqid('img_', true) . '.' . $fileExtension;
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
     * Can be used to change the directory of an image by passing the same image.
     * 
     * @param string $currentFilePath The path of the Image to Replace
     * @param array $newFile The Array containing all the New Image Info ($_FILES-style)
     * @param string $targetDir [optional] The Target Directory of the new Replaced Image
     * 
     * @return array An Associative Array Containing all the Response Info (new file name, extension, full path, size, error flag and error message)
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
            $response['error_flag'] = true;
            $response['error_message'] = 'The file to replace does not exist.';
            return $response;
        }

        // Backup the current file in case of rollback
        $backupPath = $currentFilePath . '.backup';
        if (!copy($currentFilePath, $backupPath)) {
            $response['error_flag'] = true;
            $response['error_message'] = 'Failed to create a backup of the current file.';
            return $response;
        }

        // Attempt to delete the current file
        if (!unlink($currentFilePath)) {
            $response['error_flag'] = true;
            $response['error_message'] = 'Failed to delete the existing file.';
            return $response;
        }

        // Attempt to upload the new file
        $newFileResult = self::uploadImage($newFile, $targetDir);
        if ($newFileResult['error_flag']) {
            // Rollback: Restore the backup if upload fails
            rename($backupPath, $currentFilePath);
            $response['error_flag'] = true;
            $response['error_message'] = "Replacement failed: " . $newFileResult['error_message'];
            return $response;
        }

        // Cleanup: Delete the backup after a successful replacement
        unlink($backupPath);
        /* Uses upload image response */
        return $newFileResult;
    }

    /**
     * Deletes an existing image.
     * Includes rollback in case of failure.
     * 
     * @param string $filePath The path of the Image to Delete
     * 
     * @return array An Associative Array Containing all the Response Info (error flag and error message)
     */
    static public function deleteImage(string $filePath): array
    {
        /* Response Array */
        $response = [
            'error_flag' => true,
            'error_message' => 'None.',
        ];

        // Validate that the file exists
        if (!file_exists($filePath)) {
            $response['error_message'] = "The file does not exist.";
            return $response;
        }

        // Backup the file in case of rollback
        $backupPath = $filePath . '.backup';
        if (!copy($filePath, $backupPath)) {
            $response['error_message'] = "Failed to create a backup before deletion.";
            return $response;
        }

        // Attempt to delete the file
        if (!unlink($filePath)) {
            $response['error_message'] = "Failed to delete the file.";
            return $response;
        }

        // Cleanup: Remove the backup after successful deletion
        unlink($backupPath);

        /* Toggle Error Flag off */
        $response["error_flag"] = false;
        return $response;
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
     * Retrieves Works from the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * 
     * @return array|string An associative array with the Works if successful, otherwise the failure message is returned
     */
    static public function get_works(object $connection) :array {
        //Query
        $sql_get_works = "SELECT idWork, idCategory, `name`, `date`, image_path, languages, `description` FROM works";

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            /* Creation of Account */
            try {
                // Query of the statement
                $query_get_works = $connection->query($sql_get_works);
                
                $result = $query_get_works->fetch_all(MYSQLI_ASSOC);
                
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            try {
                // Query of the statement
                $query_get_works = $connection->query($sql_get_works);
                
                $result = $query_get_works->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                /* Failure Handling */
                return $e->getMessage(); 
            }
        }
    }

    /**
     * Retrieves the Work with the matching given ID from the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $idWork The ID of the Work to retrieve
     * 
     * @return array|string An associative array with the Works if successful, otherwise the failure message is returned
     */
    static public function get_single_work(object $connection, int $idWork) :array|bool {
        $result = [];

        // Try with MySQLi
        if ($connection instanceof mysqli) {

            //Query
            $sql_get_spec_work = "SELECT idWork, idCategory, `name`, `date`, image_path, languages, `description` FROM works WHERE idWork = ?";

            /* Creation of Account */
            try {
                // Query of the statement
                $query_get_spec_work = $connection->prepare($sql_get_spec_work);
                $query_get_spec_work->bind_param('i', $idWork);

                /* Execute */
                $query_get_spec_work->execute();

                /* Result Retrieval */
                $work_data = $query_get_spec_work->get_result();
                $result = $work_data->fetch_assoc();

                /* Return the Result */
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {

            //Query
            $sql_get_spec_work = "SELECT idWork, idCategory, `name`, `date`, image_path, languages, `description` FROM works WHERE idWork = :id";

            try {
                // Query of the statement
                $query_get_spec_work = $connection->prepare($sql_get_spec_work);
                $query_get_spec_work->bindParam(":id", $idWork);
                
                $result = $query_get_spec_work->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                /* Failure Handling */
                return $e->getMessage(); 
            }
        }
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

    /**
     * Modifies an existing work
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $idWork The ID of the work to modify
     * @param int $idCat The ID of the New Category to assign
     * @param string $name The New Name of the Work
     * @param string $desc The New Description of the Work
     * @param string $date The New Date of the Work
     * @param string $path The Full Path of the New Image
     * @param array $languages The New Languages used in the Work
     * 
     * @return true|string True if successful, otherwise the failure message is returned
     */
    static public function edit_work(object $connection, int $idWork, int $idCat = null, string $name = '', string $desc = '', string $date = '', string $imagePath = '', array $languages = null) :true|string {
        /* Arrays for Database Update */
        $updates = [];
        $params = [];
        $types = "";
        
        /* Query Creation */
        $sql_update_work = "UPDATE works SET "; // Query start
        
        // Assign fields dynamically
        if ($idCat !== null && $idCat !== '') {
            $updates[] = "idCategory = ?";
            $params[] = $idCat;
            $types .= 'i';
        }
        if ($name !== '') {
            $updates[] = "`name` = ?";
            $params[] = $name;
            $types .= 's';
        }
        if ($desc !== '') {
            $updates[] = "`description` = ?";
            $params[] = $desc;
            $types .= 's';
        }
        if ($date !== '') {
            $updates[] = "`date` = ?";
            $params[] = $date;
            $types .= 's';
        }
        if ($imagePath !== '') {
            $updates[] = "image_path = ?";
            $params[] = $imagePath;
            $types .= 's';
        }
        if ($languages !== null && !empty($languages)) {
            $updates[] = "languages = ?";
            $params[] = json_encode($languages);
            $types .= 's';
        }
        
        // Ensure there are updates to make
        if (empty($updates)) {
            return "No fields to update.";
        }
        
        // Build final query
        $sql_update_work .= implode(', ', $updates) . " WHERE idWork = ?";
        $params[] = $idWork;
        $types .= 'i';
        
        // Try with MySQLi
        if ($connection instanceof mysqli) {
            $connection->begin_transaction(); //Start Transaction
            try {
                /* Prepare Statement */
                $stmt = $connection->prepare($sql_update_work); 

                /* Dynamic Binding */
                $bind_names = [$types]; // First element: string of data types
                foreach ($params as $key => $value) {
                    $bind_names[] = &$params[$key]; // Pass by reference (IMPORTANT! bind_param ONLY ACCEPTS REFERENCES)
                }
                call_user_func_array([$stmt, "bind_param"], $bind_names); //Call bind_param on the array (not directly supported)

                /* Execute Statement */
                $result = $stmt->execute();

                /* Commit Result */
                $connection->commit();
                return $result; //Return true
            } catch (Exception $e) {
                $connection->rollback();
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            $connection->beginTransaction(); //Start Transaction
            try {
                /* Prepare the Statement */
                $stmt = $connection->prepare($sql_update_work);

                /* Bind Parameters Dynamically */
                $i = 1; //Param Counter
                foreach ($params as $value) {
                    $type = PDO::PARAM_STR; //Parameter String is default
                    if (is_int($value)) { //Only other Type is int
                        $type = PDO::PARAM_INT;
                    }
                    $stmt->bindParam($i, $value, $type); //Parameter Binding
                    $i++; //Counter Update
                }

                /* Execution */
                $result = $stmt->execute();

                /* Commit */
                $connection->commit();
                return $result; //Return True
            } catch (PDOException $e) {
                $connection->rollBack();
                return $e->getMessage();
            }
        }
    }

    /**
     * Checks if given work name is present on a given Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param string $work The Name of the Category to check
     * @param bool $return_id If `true`, returns the ID matched on success (otherwise a booelan)
     * 
     * @return int|bool On success, the ID of the Account matched if found or true , false otherwise
     */
    static public function check_work(object $connection, string $work, bool $return_id = false) {

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            // SQL Statement with MySQLi parameters that performs the match
            $sql_check_work = "SELECT idWork FROM works WHERE `name` = ?";
            // Execution of the statement
            $query_check_work = $connection->prepare($sql_check_work);
            $query_check_work->bind_param("s", $work);
            $query_check_work->execute();
           
            // Store the Login Result
            $query_check_work->store_result();

            /* Result Binding */
            $query_check_work->bind_result($idWork);
            $query_check_work->fetch();

            // Check if it is empty
            if ($query_check_work->num_rows() > 0) {
                // Determine Credentials Check Type
                if ($return_id) {
                    /* Register Case */
                    return $idWork; //Return the ID of the user
                } else {
                    return true; //Return true
                }
            } else {
                return false;
            }

        } elseif ($connection instanceof PDO) {
            // SQL Statement with PDO parameters
            $sql_check_work = "SELECT idCategory FROM categories WHERE `name` = :inputName";
            // Execution of the statement
            $query_check_work = $connection->prepare($sql_check_work);
            $query_check_work->bindParam(":inputName", $work, PDO::PARAM_STR); // Name Binding
            $query_check_work->execute();
            // Binding of the Result
            if ($query_check_work->rowCount() > 0) {
                // Determine Credentials Check Type
                if ($return_id) {
                    /* ID case */
                    $idWork = $query_check_work->fetch(PDO::FETCH_NUM);
                    return $idWork; //Returns the ID
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
     * Deletes an existing Work
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $idWork The ID number of the Work to delete
     * 
     * @return bool|string Either True if successful, the failure message, or a non-exception-caught failure is returned
     */
    static public function delete_work(object $connection, int $idWork) {
        
        // Try with MySQLi
        if ($connection instanceof mysqli) {
            //SQL statement
            $sql_delete_work = "DELETE FROM works WHERE idWork = ?";
            
            /* Deletion */
            $connection->begin_transaction(); //Start of Transaction
            try {
                // Query of the statement
                $query_delete_work = $connection->prepare($sql_delete_work); //Prepare the statement
                $query_delete_work->bind_param('i', $idWork);
                $result = $query_delete_work->execute(); //Statement Execution

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
            $sql_delete_work = "DELETE FROM works WHERE idWork = :id";

            /* Deletion */
            $connection->beginTransaction();
            try {
                // Query of the statement
                $query_delete_work = $connection->prepare($sql_delete_work); //Prepare the statement
                $query_delete_work->bindParam(':id', $idCategory, PDO::PARAM_INT); // Parameter Binding
                $result = $query_delete_work->execute(); //Statement Execution

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
     * Retrieves a specified amount of Works in decreasing order of date from the provided Database
     * 
     * @param object $connection The Connection Object with the Database
     * @param int $n The number of last works to retrieve
     * 
     * @return array|string An associative array with the Works if successful, otherwise the failure message is returned
     */
    static public function get_last_n_works(object $connection, int $n) :array|string {
        //Query
        $sql_get_works = "SELECT idWork, idCategory, `name`, `date`, image_path, languages, `description`
                            FROM works
                            ORDER BY `date` DESC
                            LIMIT ?";

        // Try with MySQLi
        if ($connection instanceof mysqli) {
            /* Creation of Account */
            try {
                // Query of the statement
                $query_get_works = $connection->prepare($sql_get_works);
                $query_get_works->bind_param('i', $n);
                $query_get_works->execute();

                $works_data = $query_get_works->get_result();
                
                $result = $works_data->fetch_all(MYSQLI_ASSOC);
                
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } elseif ($connection instanceof PDO) {
            try {
                // Query of the statement
                $query_get_works = $connection->prepare($sql_get_works);
                $query_get_works->bindParam(1, $n, pDO::PARAM_INT);
                
                $result = $query_get_works->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                /* Failure Handling */
                return $e->getMessage(); 
            }
        }
    }

    /**
     * Deletes an ongoing session and redirects to the specified page amd stops execution of the latter
     * @param string $dest The Destination of the redirect
     * @return bool `false` on error (redirects on success)
     */
    static public function delete_session(string $dest) :bool {
        try {
            session_unset(); // Data Dissociation
            session_destroy(); // Current Session Deletion
            header('Location: ' . $dest); // Redirecting
            exit;
        } catch (\Throwable $error) {
            return false;
        }
    }

    /**
     * Connect to a Database through a general user
     * @param int $user_type The User Type to establish connection with
     * @return object|bool The connection on success, `false` on error
     */
    static public function connect_database(int $user_type) :object|bool {
        $config = include ROOT . '/config/database.php';

        switch ($user_type) {
            case PUBLIC_USER:
                $user = $config['public_user'];
                break;
            case ADMIN_USER:
                $user = $config['admin_user'];
                break;
            default:
                return "Wrong User Type.";
        }

        $connection = strumenti::create_connection(EXTENSION_MYSQLI, $user['host'], $user['database'], $user['username'], $user['password']);
        return $connection;
    }

    /**
     * Converts a JSON string value into an imploded single string with given glue separated values (default: comma+space-separated)
     * @param string $json The JSON encoded String to implode
     * @param string $glue [optional] The Separator of the Values in the final string (defaults to comma with space ", ")
     * @return string The Imploded string with values separated with the given glue
     */
    static public function json_implode(string $json, $glue = ', ') :string {
        $json_decoded = json_decode($json);
        $imploded_string = implode($glue, $json_decoded);
        return $imploded_string;
    }
}