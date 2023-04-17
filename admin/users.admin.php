<?php
require '../scripts/connect.php';
session_start();
if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
}

$editmode = false;
if($_GET){
    if ($_GET['edit'] == true){
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
    $edit_user = "UPDATE users SET name= :username , full_name = :fullname WHERE user_id = :user_id ;";

    $statement= $db->prepare($edit_user);
    $statement->bindValue(':user_id', $edit_user_id, PDO::PARAM_INT);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':fullname', $fullname);

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
    <link rel="stylesheet" href="main.css">
    <title>Welcome to my Blog!</title>
</head>
<body>
<?php
    include_once 'header.admin.php';
?>
    <form action="../scripts/signup.inc.php" method="post">

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

        <input type="hidden" name="by_admin" value= '1'>

        <input type="submit" value="Create User" name="sign_up">
    </form>


    <div>
        <a href="users.admin.php?edit=true">Edit</a>
        <?php foreach ($users as $user): ?>
            <form action="" method="post">
                <input type="hidden" name="edit_user_id" value="<?= $user['user_id']?>">

                <?php if($editmode): ?>
                    <label for="username">UserName</label>
                    <input type="text" name="edit_username" value="<?= $user['name']?>">

                    <label for="fullname">FullName</label>
                    <input type="text" name="edit_fullname" value="<?= $user['full_name']?>">
                    <input type="submit" value="edit" name="edit">
                <?php else: ?>
                    <h2><?= $user['name']?></h2>
                    <h3><?= $user['full_name']?></h3>
                <?php endif ?>
                <h4><?= $user['email'] ?></h4>

                <input type="submit" value="Delete" name="delete">
            </form>

        <?php endforeach ?>
    </div>

</body>