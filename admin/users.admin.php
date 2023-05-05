<?php
require '../scripts/connect.php';
session_start();
if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
}

$editmode = false;
if($_GET){
    $edit = filter_input(INPUT_GET, 'edit',FILTER_SANITIZE_FULL_SPECIAL_CHARS );
    if ($edit == true){
        $editmode = true;
    }
}

// view/add/delete users here
$users = loading_users();
if(isset($_POST['delete'])){
    delete_user();
}
if($editmode){
    if(isset($_POST['edit'])){
        editing_users();
    }
}

//loading users
function loading_users(){
    global $db;


    $load_users = "SELECT * FROM users;";
        // preparring sql for executoin
    $statement = $db->prepare($load_users);
        
        //executing sql
    $statement->execute();

    $users = [];
    while ($x = $statement->fetch() ){
        $users[] = $x;   
    }
    return $users;
}

function editing_users(){
    global $db;
    $username = filter_input(INPUT_POST, 'edit_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $fullname = filter_input(INPUT_POST, 'edit_fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $edit_user_id = filter_input(INPUT_POST, 'edit_user_id', FILTER_SANITIZE_NUMBER_INT);
    $new_pass = filter_input(INPUT_POST, 'new_pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
    $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
    $edit_user = "UPDATE users SET name= :username , full_name = :fullname WHERE user_id = :user_id ;";
    if(isset($_POST['change_pass'])){
        $edit_user= "UPDATE users SET name= :username , full_name = :fullname, password= :password WHERE user_id = :user_id ;";
    }

    $statement= $db->prepare($edit_user);
    $statement->bindValue(':user_id', $edit_user_id, PDO::PARAM_INT);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':fullname', $fullname);
    if(isset($_POST['change_pass'])){
        $statement->bindValue(':password', $hashed_pass); 
    }

    if($statement->execute()){
        echo("Success");
        header("Location: admin.php");
    }
}

///delete users
function delete_user(){
    global $db;

    $query = "DELETE FROM users WHERE user_id= :user_id";
    $edit_user_id = filter_input(INPUT_POST, 'edit_user_id', FILTER_SANITIZE_NUMBER_INT);

    if($edit_user_id == 1){
        header("location: admin.php");
        exit();
    }

    $statement= $db->prepare($query);
    $statement->bindValue(':user_id', $edit_user_id, PDO::PARAM_INT);
    if($statement->execute()){
        echo("Success");
        header("Location: users.admin.php");
    } // add an alert to confirm delete
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Welcome to my Blog!</title>
</head>
<body>
<?php
    include_once 'header.admin.php';
?>
<div class="container">
    <form class="border m-3 p-2" action="../scripts/signup.inc.php" method="post">
        <h2>Add a new user</h2>

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

        <input type="hidden" name="by_admin" value= '1'>

        <input type="submit" class="btn btn-primary" value="Create User" name="sign_up">
    </form>


    <div>
        <a href="users.admin.php?edit=true" class="btn btn-outline-secondary">Edit</a>
        <?php foreach ($users as $user): ?>
            <form action="" method="post" class="border border-1 m-3 p-3">
                <input type="hidden" name="edit_user_id" value="<?= $user['user_id']?>">

                <?php if($editmode): ?>
                    <div class="form-floating">
                    <input type="text" class="form-control" name="edit_username" value="<?= $user['name']?>" placeholder="User name">
                    <label for="username" class="form-label">UserName</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="edit_fullname" value="<?= $user['full_name']?>" placeholder="Full name">
                        <label for="fullname" class="form-label">FullName</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="new_pass" >
                        <label for="new_pass">New Password</label>
                    </div>
                    <label for="change_pass">Click Here if you want to Create a New Password</label>
                    <input type="checkbox" name="change_pass" >
                    <input type="submit" value="edit" class="btn btn-outline-secondary" name="edit">
                <?php else: ?>
                    <h2><?= $user['name']?></h2>
                    <h3><?= $user['full_name']?></h3>
                    <h3><?php $user['password'] ?></h3>
                <?php endif ?>
                <h6><?= $user['email'] ?></h6>

                <input type="submit" value="Delete" onclick="checker()" class="btn btn-danger" name="delete">
            </form>

        <?php endforeach ?>
    </div>
</div>
<?php include_once 'footer.admin.php' ?>
<script>
    function checker(){
        var result = confirm('Do you want to delete this User?');
        if(result == false){
            event.preventDefault();
        }
    }
</script>

</body>