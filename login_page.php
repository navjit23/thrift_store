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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>product name here</title>
</head>
<body>
<?php
    include_once 'header.php';
?>


<div class="container mt-5">
<form action="scripts/login.inc.php" method="post">
    <div class="form-floating">
        <input type="text" name="user_name" class="form-control" placeholder="Username/email">
        <label for="user_name">User Name / Email</label>
    </div>
    <div class="form-floating">
        <input type="password" name="pwd" class='form-control' placeholder= "Password">
        <label for="pwd">Password</label>
    </div>
    <input type="submit" class="btn btn-primary" value="Log In" name="login"> 
</form>

<?php if($_GET): ?>
    <h3><?= $error_message ?></h3>
<?php endif ?>
</div>

<?php include_once 'footer.php'; ?>
</body>
</html>