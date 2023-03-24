<?php

require('connect.php');

// sql command written
$products = "SELECT * FROM products  LIMIT 5";

// preparring sql for executoin
$statement = $db->prepare($products);

//executing sql
$statement->execute();

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
    <!-- have a llist of available products--->
    <?php while($row =$statement->fetch()): ?>
    <!--image, title, company, price, condition -->
    <div id= "product_div">
        <img src=<?= $row['image'] ?> alt="image here">    
        <div>
            <a href="#"><h3> <?= $row['name'] ?> </h3></a> 
            <h2><?= $row['company'] ?></h2>
            <h2><?= $row['item_condition'] ?></h2>
            <h3><?= $row['price'] ?></h3>

        </div>
    </div>
    <?php endwhile ?>
</body>
</html>