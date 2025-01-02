<?php
/* Included Tools */
require_once('inclusioni/strumenti.php');
use assets\strumenti;

/* Regex for Usernames and Passwords */
$regex_username = "/^[a-zA-Z_]{6,32}$/";
$regex_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z0-9@$!%*?&]{8,}$/";

$flagUsers = 200;

/* Check if a Form was Submitted */
if (isset($_POST) && !empty($_POST)) {
    /* Create User Validation */
    if ($_POST['button_submit'] == 'user_create') {
        /* Get Username, Password and Repeat Password */
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $repeat_password = trim($_POST['repeat_password']);
        /* Tests of Credentials */
        $test_username = preg_match($regex_username, $username);
        $test_password = preg_match($regex_password, $password);
        $test_repeat_password = $password == $repeat_password;

        /* Check Credentials Correctedness */
        if ($test_username === 1 && $test_password === 1 && $test_repeat_password) {
            // Case of Correct Credentials 
            $result = strumenti::check_credentials($connection, $username, $password, CHECK_REGISTER);
            if ($result) {
                /* User Already Exists */
                $flagUsers = 406;
            } else {
                $create_result = strumenti::create_account($connection, $username, $password); 
                if ($create_result === true) {
                    /* Account Successfully Created */
                    $flagUsers = 201;
                } else {
                    /* Failed to Create Account */
                    $flagUsers = 500;
                }
            }
        } elseif ($test_username === 0 || $test_password === 0) {
            /* Incorrect Username or Password */
            $flagUsers = 400;
        } else {
            /* Server Error */
            $flagUsers = 500.1;
        }
    }
}