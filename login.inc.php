<?php

require_once 'user_functions.inc.php';

if(isset($_POST['login']))
{
    $user_name = $_POST['user_name'];
    $pass = $_POST['pwd'];

    if(emptyInputLogin($user_name, $pass) ){
        header("location: login_page.php?error='empty_fields'");
        exit();
    }

    login_user($user_name, $pass);

}
else{
    header("location: login_page.php");
    exit();
}