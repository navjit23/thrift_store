<?php

require('scripts/connect.php');
session_start();

//user login check
$login_user = false;
$admin_access = false;
if(array_key_exists('user_id', $_SESSION ) ){
    $login_user =true;

    if($_SESSION['user_id'] == 1){
        $admin_access = true;
    }
}


// have a bla bla bla results found

// LOADING THE PAGE

$categories1 = loading_categories();

// check for GET value, if true category is filtered from here
if(array_key_exists( 'category_id', $_GET) ){
    $categoryID = $_GET['category_id'];
}

// gets executed when user clicks search 
if($_POST){
    $result_start=0;
    $search_value = $_POST['searchText'];
    $sortBy = $_POST['sort_by'];
    $sortType = $_POST['sort_type'];

    if(! array_key_exists( 'category_id', $_GET)){
    $categoryID = $_POST['category'];
    }

    $row = search_bar_filter($search_value, $sortBy, $sortType, $categoryID);
}

// default page loadup with/without categories filtered
else{
    if(array_key_exists( 'category_id', $_GET)){
        $products = "SELECT * FROM products WHERE category_id = :category_id "; 
        $statement = $db->prepare($products);
        $statement->bindValue(':category_id', $categoryID);
    }
    else{
        $products = "SELECT * FROM products ";
        $statement = $db->prepare($products);
    }
    $statement->execute();
    $results = [];
    while ($x = $statement->fetch() ){
        $results[] = $x;    
    }
    $row = $results;
} 


/* search Bar function takes 4 inputs to filter data and return the query result in an array
* @param $search_value contains the string that needs to be searched
* @param $sortBy contains the value to order the query by
* @param $sortType should be asc or desc
* @param $category_id cotain the category_id to filter the query in WHERE clause
*/
function search_bar_filter($search_value, $sortBy, $sortType, $category_id){
    global $db;
    
    $products = "SELECT * FROM products 
        WHERE `name` LIKE '%$search_value%' AND category_id LIKE '$category_id' ORDER BY $sortBy $sortType   ;";

    if($category_id == "all_categories"){
        $products = "SELECT * FROM products 
            WHERE `name` LIKE '%$search_value%' 
            ORDER BY $sortBy $sortType" ;
    }
    $statement = $db->prepare($products);
    
    $statement->execute();

    $results = [];
    while ($x = $statement->fetch() ){
        $results[] = $x;
        
    }
    
    return $results;

}


// loading the categories
function loading_categories(){
    global $db;

    $query = "SELECT * FROM categories ;";
        // preparring sql for executoin
    $statement = $db->prepare($query);
    
        //executing sql
    $statement->execute();
    $categories = [];
    while ($x = $statement->fetch() ){
        $categories[] = $x;
        
    }
    
    return $categories;
}

//maybe use session to savve  and load the radio selection



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
    include_once 'header.php'; ?>

<div class="container">
    <?php
    if ($login_user): ?> 
    <div id="search_box" class="row">
        <form action="" method="post">
            <label for="sort_by" class="form-label">Sort By</label>
            <select name="sort_by" class="form-select-sm" >
                <option value="date">By Date</option>
                <option value="name">By Name</option>
                <option value="price">By Cost</option>
                <option value="rarity">By Rarity</option>
            </select>

            <label for="sort_type"></label>
            <select name="sort_type" class="form-select-sm">
                <option value="ASC">asc </option>
                <option value="DESC">desc</option>
            </select>

            <!-- only loads the category dropdown if there is no category already selected -->
            <?php if(!array_key_exists( 'category_id', $_GET)): ?>
            <label for="category">Category</label> 
            <select class="form-select-sm" name="category">
                <option value="all_categories"> ---All Categories--- </option>
                <?php foreach($categories1 as $category_type): ?>
                    <option value="<?= $category_type['category_id'] ?>"> <?= $category_type['category_name'] ?> </option>
                <?php endforeach ?>
            </select>
            <?php endif ?>

            <input type="text" class="form-control" name="searchText" id="" placeholder="Type here to search" >
            <input type="submit" value="Search" name="search">
        </form>
        <?php endif ?>

        <div id="category_bar">
        <a class="col" href="shop.php"><div class="col box">All categories</div></a>
            <?php foreach ($categories1 as $category) : ?>
                <a class="col" href="shop.php?category_id=<?= $category['category_id']?>&name=<?= $category['category_name'] ?> "><div class="col box"> <?= $category['category_name'] ?> </div></a>
            <?php endforeach ?>
        </div>
    </div> 


    <div id="products">

        <?php if($row): ?>
        <h3> <?= count($row)?> Results Found !!!</h3>
        <?php foreach ($row as $product): ?>

        <div class=" border border-dark border-1 border-opacity-50 m-2 p-3 rounded">
            
            <div class="product_div">
                <a href="view_item/<?=$product['product_id']?>/<?= str_replace(" ", '-', $product['name']) ?>">
                    <?php 
                    if($product['image']):
                        $folder = "uploads/". $product['image'];
                        ?>
                        <img src="<?= $folder ?>" alt="image here">

                        <div id="with_img">
                            <h3> <?= $product['name'] ?> </h3></a> 
                            <h6><?= date('F d/Y g:i a', strtotime( $product['date']) ) ?> </h6>
                            <?php if ($product['rarity'] > 0): ?>
                                <h4>Rarity: <?= $product['rarity'] ?></h4>
                            <?php endif ?>
                            <h4> <?= $product['company'] ?></h4>
                            <h3> $<?=$product['price'] ?></h3>
                            <?php if(strlen($product['description']) > 200): ?>
                                <p><?= substr($product['description'], 0, 200) ?>.... </p>
                            <?php else: ?>
                            <p><?= $product['description'] ?></p>
                            <?php endif ?>
                        </div>
                    <?php else:?>

                    <div id="no_img">
                        <h3> <?= $product['name'] ?> </h3></a> 
                        <h6><?= date('F d/Y g:i a', strtotime( $product['date']) ) ?> </h6>
                        <?php if ($product['rarity'] > 0): ?>
                            <h4>Rarity: <?= $product['rarity'] ?></h4>
                        <?php endif ?>
                        <h4> <?= $product['company'] ?></h4>
                        <h3> $<?=$product['price'] ?></h3>
                        <?php if(strlen($product['description']) > 200): ?>
                            <p><?= substr($product['description'], 0, 200) ?>.... </p>
                        <?php else: ?>
                        <p><?= $product['description'] ?></p>
                        <?php endif ?>
                    </div>
                    
                    <?php endif ?>
                
            </div>
        </div>
        <?php endforeach ; ?>
    </div>


        <?php    else: ?>
    <div id="no_result">
        <h2> No Results Found</h2>
        <a href="shop.php">Click here to view all products</a>
        <?php endif ?>

    </div>

    <ul>
    

    <?php include_once 'footer.php'; ?>
    
</body>

</html>



