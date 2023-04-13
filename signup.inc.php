<?php
session_start();
require_once 'user_functions.inc.php';

if(isset($_POST['sign_up']))
{
    // grabbing the data
    $full_name = $_POST['name'];
    $user_name = $_POST['user_name'];
    $pass = $_POST['pwd'];
    $repeat_pass = $_POST['pwd_repeat'];
    $email = $_POST['email'];

    if (in_array('is_admin', $_POST)){
        $is_admin = 1;
    }
    else{
        $is_admin= `NULL`;
    }

    if(emptyInputSignup($full_name, $user_name, $pass, $repeat_pass, $email) ){
        header("location: signup_page.php?error='empty_fields'");
        exit();
    }

    if(invaid_user_name($user_name)){
        header("location: signup_page.php?error='invalid_user_name'");
        exit();
    }

    if(invaid_email($email)){
        header("location: signup_page.php?error='invalid_email'");
        exit();
    }

    if(user_name_exists($user_name, $email)){
        header("location: signup_page.php?error='user_name_exists'");
        exit();
    }

    if(!pass_match($pass, $repeat_pass)){
        header("location: signup_page.php?error='password_does_not_match'");
        exit();
    }

    createUser($full_name, $user_name, $pass, $email, $is_admin);
}

else{
    header ("signup_page.php");
    exit();
}