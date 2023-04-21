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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Welcome to my Blog!</title>
</head>
<body>
<?php
    include_once 'header.php';
?>
<div class="container mt-5">
<form action="scripts/signup.inc.php" method="post">

    <label for="name" class="form-label">Full Name</label>
    <input type="text" class="form-control" name="name" placeholder="Full name ..." required>

    <label for="user_name" class="form-label">User Name</label>
    <input type="text" class="form-control" name="user_name" placeholder="User name ..." required>

    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" name="email" placeholder="Email ..." required>

    <label for="pwd" class="form-label">Password</label>
    <input type="password" class="form-control" name="pwd" placeholder="Password ..." required>

    <label for="pwd_repeat" class="form-label">Repeat Password</label>
    <input type="password" class="form-control" name="pwd_repeat" placeholder=" Repeat Password ..." required>

    <input type="hidden" name="by_admin" value= '0' >
    

    <input type="submit" class="btn btn-primary" value="Sign Up" name="sign_up">
</form>


<?php if($_GET): ?>
    <h4 class="alert alert-danger"><?= $error_message ?></h4>
<?php endif ?>
</div>

<?php include_once 'footer.php'; ?>

</body>

</html>