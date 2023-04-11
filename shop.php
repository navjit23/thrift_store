<?php

require('connect.php');


// sb sort and categories


// search Bar only search
function search_bar_filter($search_value, $sortBy, $sortType){
    global $db;
    $products = "SELECT * FROM products WHERE `name` LIKE '%$search_value%' ORDER BY $sortBy $sortType ;";

    $statement = $db->prepare($products);
    //$statement->bindValue(':search_value', $search_value);
    
    $statement->execute();

    $results = [];
    while ($x = $statement->fetch() ){
        $results[] = $x;
        
    }
    
    return $results;

}

// maybe create hyperlinks for categories and they filter automatically 





//maybe use session to savve  and load the radio selection
//no items founds remaining
if($_POST){
$search_value = $_POST['searchText'];
$sortBy = $_POST['sort_by'];
$sortType = $_POST['sort_type'];


$row = search_bar_filter($search_value, $sortBy, $sortType);


}
else{
    $products = "SELECT * FROM products LIMIT 10";
    $statement = $db->prepare($products);
    $statement->execute();
    $results = [];
    while ($x = $statement->fetch() ){
        $results[] = $x;
        
    }
    
    $row = $results;
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
        <label for="sort_by">Sort By</label>
        <select name="sort_by" >
            <option value="date">By Date</option>
            <option value="name">By Name</option>
            <option value="price">By Cost</option>
            <option value="rarity">By Rarity</option>
        </select>

        <label for="sort_type"></label>
        <select name="sort_type">
            <option value="ASC">asc </option>
            <option value="DESC">desc</option>
        </select>

        <input type="text" name="searchText" id="" placeholder="Type here to search">
        <input type="submit" value="Search" name="search">
    </form>
    <?php foreach ($row as $product): ?>
    <!--image, title, company, price, condition -->
    <div id= "product_div" style="border:solid 1px black; margin:5px">
         
        <div>
            <a href="view_item.php?id=<?=$product['product_id']?>"><h3> <?= $product['name'] ?> </h3></a> 
            <h2><?= $product['company'] ?></h2>
            <h2><?= $product['item_condition'] ?></h2>
            <h3><?= $product['price'] ?></h3>
            <?php 
            $folder = "./uploads/". $product['image'];
            ?>
            <img src="<?= $folder ?>" alt="iamge here">
        </div>
    </div>
    <?php endforeach ?>
</body>
</html>