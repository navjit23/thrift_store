<?php
require('../scripts/connect.php');
session_start();
if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
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
    <title>product name here</title>
</head>
<body>
    <?php include_once 'header.admin.php' ?>
    <div class="container admin-page ">
        <a href="users.admin.php"><div>Manage Users</div></a>
        <a href="comments.admin.php"><div>View Comments</div></a>
        <a href="new_category.admin.php"><div>Manage categories</div></a>
        <a href="new_item.admin.php"><div>Add Items</div></a>
        <a href="../shop.php?result_start=0"><div>View/ Edit Items</div></a>
    </div>

<?php include_once'footer.admin.php' ?>
</body>
</html>