<?php
require('scripts/connect.php');


$error_message = "";
if($_GET){
    if(isset($_GET['error'])){

        $error = $_GET['error'];

        if($error == "'empty_fields'"){
            $error_message = "You have empty fields";
        }
        else if($error == "'doesnot_exist'"){
            $error_message = "User does not exist";
        }
        else if($error == "'wrong_pass'"){
            $error_message = "Username/Password do not match";
        }  
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>product name here</title>
</head>
<body>
<?php
    include_once 'header.php';
?>


    
<form action="scripts/login.inc.php" method="post">
    <input type="text" name="user_name" placeholder="Username/email">
    <input type="password" name="pwd" placeholder= "Password">
    <input type="submit" value="Log In" name="login"> 
</form>

<?php if($_GET): ?>
    <h3><?= $error_message ?></h3>
<?php endif ?>

<?php include_once 'footer.php'; ?>
</body>
</html>