<?php
/* Included Tools */
require_once('inclusioni/strumenti.php');
use assets\strumenti;

/* Regex for Usernames and Passwords for User Creation */
$regex_create_username = "/^[a-zA-Z_]{6,32}$/";
$regex_create_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z0-9@$!%*?&]{8,}$/";

$regex_category_name = "/^[a-zA-Z]{3,32}$/";

$flag_response = 200;
$form_response = '';

/* Check if a Form was Submitted */
if (isset($_POST) && !empty($_POST)) {

    /* Switch of Type of Form` */
    switch ($_POST['button_submit']) {

        /* User Creation Server Validation */
        case 'user_create':
            /* Form Submission to Respond */
            $form_response = 'user_creation'; 

            /* Get Username, Password and Repeat Password */
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $repeat_password = trim($_POST['repeat_password']);
            /* Tests of Credentials */
            $test_username = preg_match($regex_create_username, $username);
            $test_password = preg_match($regex_create_password, $password);
            $test_repeat_password = $password == $repeat_password;

            /* Check Credentials Correctedness */
            if ($test_username === 1 && $test_password === 1 && $test_repeat_password) {
                // Case of Correct Credentials 
                $account_exists = strumenti::check_credentials($connection, $username, $password, CHECK_REGISTER);
                if ($account_exists !== false) {
                    /* User Already Exists */
                    $flag_response = 406;
                } else {
                    $create_result = strumenti::create_account($connection, $username, $password); 
                    if ($create_result === true) {
                        /* Account Successfully Created */
                        $flag_response = 201;
                    } else {
                        /* Failed to Create Account */
                        $flag_response = 500;
                    }
                }
            } elseif ($test_username === 0 || $test_password === 0) {
                /* Incorrect Username or Password */
                $flag_response = 400;
            } else {
                /* Server Error */
                $flag_response = 500.1;
            }

            /* Breaking of the Case */
            break;



        /* User Editing Server Validation */
        case 'user_edit':
            /* Form Submission to Respond */
            $form_response = 'user_edit'; 

            /* Get Username, Password and Repeat Password */
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $repeat_password = trim($_POST['repeat_password']);
            $idUser = $_POST['selected_user'];
            /* Tests of Credentials */
            $test_username = preg_match($regex_create_username, $username) || $username == '';
            $test_password = preg_match($regex_create_password, $password) || $password == '';
            $test_repeat_password = $password == $repeat_password;

            /* Check Validity of Inputs */
            if ($test_username == true && $test_password == true && $test_repeat_password == true) {
                //Valid Inputs
                $account_exists = strumenti::check_credentials($connection, $username, $password, CHECK_REGISTER);
                
                /* Check if username already exists */
                if ($account_exists != false && $account_exists != $idUser) {
                    //User already Exists
                    $flag_response = 406;
                } else {
                    /* User Modification */
                    $edit_result = strumenti::edit_user($connection, $idUser, $username, $password); 
                    
                    /* Check if operation was successful */
                    if ($edit_result === true) {
                        //Account Successfully Modified
                        $flag_response = 201;
                    } else {
                        //Account Modification Failure
                        $flag_response = 500;
                    }
                }
            } else {
                //Invalid Inputs
                $flag_response = 400;
            }

            /* Breaking of the Case */
            break;


        /* User Deletion Server Validation */
        case 'user_delete': 
            /* Form Submission to Respond */
            $form_response = 'user_deletion';
            /* ID of the User to delete */
            $idUser = $_POST['selected_user'];
            /* User Deletion */
            $isDeleted = strumenti::delete_user($connection, $idUser);
            if ($isDeleted === true) {
                $flag_response = 201;
            } else {
                $flag_response = 500;
            }

            /* Breaking of the Case */
            break;



        /* Category Forms Validation */
        /* Category Creation */
        case 'category_create':
            $form_response = 'category_creation';

            $category_name = trim($_POST['category_name']);

            $test_category_name = preg_match($regex_category_name, $category_name);

            if ($test_category_name === 1) {
                // Case of Correct Category Name
                $category_exists = strumenti::check_category($connection, $category_name);
                if ($category_exists) {
                    /* Category Already Exists */
                    $flag_response = 406;
                } else {
                    $create_result = strumenti::create_category($connection, $category_name); 
                    if ($create_result === true) {
                        /* Category Successfully Created */
                        $flag_response = 201;
                    } else {
                        /* Failed to Create Category */
                        $flag_response = 500;
                    }
                }
            } elseif ($test_category_name === 0) {
                /* Incorrect Category Name */
                $flag_response = 400;
            } else {
                /* Server Error */
                $flag_response = 500.1;
            }

            /* Breaking of the Case */
            break;

        default:
            throw new Exception("Error!", 1);
            
            break;
    }
}