<?php
require('scripts/connect.php');


// Error messages
$error_message = "";
if($_GET){
    if(isset($_GET['error'])){

        $error = $_GET['error'];

        if($error == "'empty_fields'"){
            $error_message = "You have empty fields";
        }
        else if($error == "'invalid_user_name'"){
            $error_message = "You have an invalid user name, Please try again";
        }
        else if($error == "'invalid_email'"){
            $error_message = "You have entered an invalid email";
        }
        else if($error == "'user_name_exists'"){
            $error_message = "Username/Email already exists";
        }
        else if($error == "'password_does_not_match'"){
            $error_message = "Passwords do not match";
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

    <input type="hidden" name="by_admin" value= '0' >
    

    <input type="submit" value="Sign Up" name="sign_up">
</form>

<?php if($_GET): ?>
    <h3><?= $error_message ?></h3>
<?php endif ?>


<?php include_once 'footer.php'; ?>

</body>

</html>