<?php

require('connect.php');

//warnings if hit search and nothing to sort
//maybe use session to savve  and load the radio selection
//no items founds remaining
if($_POST){
$search_value = $_POST['searchText'];

    // sql command written
    if($_POST['sort'] == 'byName'){
        $products = "SELECT * FROM products WHERE `name` LIKE '%$search_value%' ORDER BY `name` DESC LIMIT 10  ";

    }
    else if($_POST['sort'] == 'byCost'){
        $products = "SELECT * FROM products WHERE `name` LIKE '%$search_value%'  ORDER BY `price` DESC LIMIT 10";
    }
    else if($_POST['sort'] == 'byDate'){
        $products = "SELECT * FROM products WHERE `name` LIKE '%$search_value%'  LIMIT 10";
    }
    else{
        $products = "SELECT * FROM products WHERE `name` LIKE '%$search_value%' LIMIT 10";
    }

}
else{
    $products = "SELECT * FROM products LIMIT 10";
} 

    $statement = $db->prepare($products);
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
    <form action="" method="post">
        <label for="byName">byName</label>
        <input type="radio" value="byName" name="sort">

        <label for="byCost">byCost</label>
        <input type="radio" value="byCost" name="sort">

        <label for="byDate">byDate</label>
        <input type="radio" value="byDate" name="sort">
        <input type="text" name="searchText" id="" placeholder="Type here to search">
        <input type="submit" value="Search" name="search">
    </form>
    <?php while($row =$statement->fetch()): ?>
    <!--image, title, company, price, condition -->
    <div id= "product_div" style="border:solid 1px black; margin:5px">
         
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