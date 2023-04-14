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
    <link rel="stylesheet" href="main.css">
    <title>product name here</title>
</head>
<body>
    <a href="users.admin.php"><div>Manage Users</div></a>
    <a href="comments.admin.php"><div>View Comments</div></a>
    <a href="new_category.admin.php"><div>Manage categories</div></a>
    <a href="new_item.admin.php"><div>Add Items</div></a>
    <a href="../shop.php"><div>View/ Edit Items</div></a>

</body>
</html>