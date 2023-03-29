<?php

require('connect.php');

// sql command written
if(isset($_POST['byName'])){
    $products = "SELECT * FROM products ORDER BY `name` DESC LIMIT 5";
    $statement = $db->prepare($products);
    $statement->execute();
}
else if(isset($_POST['byCost'])){
    $products = "SELECT * FROM products ORDER BY `price` DESC LIMIT 5";
    $statement = $db->prepare($products);
    $statement->execute();
}
else if(isset($_POST['byDate'])){
    $products = "SELECT * FROM products LIMIT 5";
    $statement = $db->prepare($products);
    $statement->execute();
}
else{
    $products = "SELECT * FROM products  LIMIT 5";
    $statement = $db->prepare($products);
    $statement->execute();
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
    <!-- have a llist of available products--->
    <form action="" method="post">
        <input type="submit" value="byName" name="byName">
        <input type="submit" value="byCost" name="byCost">
        <input type="submit" value="byDate" name="byDate">
    </form>
    <?php while($row =$statement->fetch()): ?>
    <!--image, title, company, price, condition -->
    <div id= "product_div">
         
        <div>
            <a href="view_item.php?id=<?=$row['product_id']?>"><h3> <?= $row['name'] ?> </h3></a> 
            <h2><?= $row['company'] ?></h2>
            <h2><?= $row['item_condition'] ?></h2>
            <h3><?= $row['price'] ?></h3>
            <?php 
            $folder = "./uploads/". $row['image'];
            ?>
            <img src="<?= $folder ?>" alt="iamge here">
        </div>
    </div>
    <?php endwhile ?>
</body>
</html>