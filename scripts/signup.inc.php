<?php
session_start();
require_once 'user_functions.inc.php';

if(isset($_POST['sign_up']))
{
    // grabbing the data
    $full_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $repeat_pass = filter_input(INPUT_POST, 'pwd_repeat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $by_admin = filter_input(INPUT_POST, 'by_admin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(emptyInputSignup($full_name, $user_name, $pass, $repeat_pass, $email) ){
        header("location: ../signup_page.php?error='empty_fields'");
        exit();
    }

    if(invaid_user_name($user_name)){
        header("location: ../signup_page.php?error='invalid_user_name'");
        exit();
    }

    if(invaid_email($email)){
        header("location: ../signup_page.php?error='invalid_email'");
        exit();
    }

    if(user_name_exists($user_name, $email)){
        header("location: ../signup_page.php?error='user_name_exists'");
        exit();
    }

    if(!pass_match($pass, $repeat_pass)){
        header("location: ../signup_page.php?error='password_does_not_match'");
        exit();
    }

    createUser($full_name, $user_name, $pass, $email, $by_admin);
}

else{
    header ("../signup_page.php");
    exit();
}