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
    <title>Welcome to my Blog!</title>
</head>
<body>
<?php
    include_once 'header.php';
?>
<form action="scripts/signup.inc.php" method="post">

    <label for="name">Full Name</label>
    <input type="text" name="name" placeholder="Full name ...">

    <label for="user_name">User Name</label>
    <input type="text" name="user_name" placeholder="User name ...">

    <label for="email">Email</label>
    <input type="email" name="email" placeholder="Email ...">

    <label for="pwd">Password</label>
    <input type="password" name="pwd" placeholder="Password ...">

    <label for="pwd_repeat">Repeat Password</label>
    <input type="password" name="pwd_repeat" placeholder=" Repeat Password ...">
    
    <label for="is_admin">Admin!</label>
    <input type="checkbox" name="is_admin" >

    <input type="submit" value="Sign Up" name="sign_up">
</form>


<?php include_once 'footer.php'; ?>

</body>

</html>