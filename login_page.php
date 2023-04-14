<?php
require('scripts/connect.php');
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
<?php include_once 'footer.php'; ?>
</body>
</html>