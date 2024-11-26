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
    if ($_POST['button_submit'] == 'create_user') {
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
                $flag = 406;
            } else {
                $create_result = strumenti::create_account($connection, $username, $password); 
                if ($create_result === true) {
                    /* Account Successfully Created */
                    $flag = 201;
                } else {
                    /* Failed to Create Account */
                    $flag = 500;
                    echo $create_result;
                }
            }
        } elseif ($test_username === 0 || $test_password === 0) {
            /* Incorrect Username or Password */
            $flag = 400;
        } else {
            /* Server Error */
            $flag = 500;
        }
    }
}