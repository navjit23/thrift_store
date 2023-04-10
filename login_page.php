<?php
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
<header>
    <!--- nav bar and other stuff on top-->
    <!-- put a logo and social handles-->
    <!-- nav bar-->
    <div id= "main_nav">
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="new_item.php">Sell / Donate</a></li>
        <li><a href="contact_us.php">Contact Us</a></li>
        <li><a href="edit.php">Store Location</a></li>
    </ul>
    </div>

</header>

<form action="" method="post">
    <input type="text" name="userName">
    <input type="password" name="pass">
    <input type="password" name="repeatPass">
    <input type="email" name="userEmail">
    <input type="submit" value="Sign Up" name="signup"> 
</form>
    
<form action="" method="post">
    <input type="text" name="userName">
    <input type="password" name="pass">
    <input type="submit" value="Log In" name="login"> 
</form>
</body>
</html>