<?php

require_once 'user_functions.inc.php';

if(isset($_POST['login']))
{
    $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(emptyInputLogin($user_name, $pass) ){
        header("location: ../login_page.php?error='empty_fields'");
        exit();
    }

    login_user($user_name, $pass);

}
else{
    header("location: ../login_page.php");
    exit();
}