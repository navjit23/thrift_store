<?php

require('connect.php');

// sql command written
$products = "SELECT * FROM "products" ORDER BY date DESC LIMIT 5";

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
    <!--image, title, company, price, condition -->
    <div id= "product_div">
        <img src="#" alt="image here">    
        <div>
            <h3>name</h3>
            <h2>company</h2>
            <h2>conditionn</h2>
            <h3>price</h3>

        </div>
    </div>
</body>
</html>