<?php

require('scripts/connect.php');
session_start();

// result end = rStart * whatever the no.of results
//try saving search results in sessions
// when click on hyperlinks also clear the cookie
// have a bla bla bla results found

// LOADING THE PAGE
// for search pagination
$result_start = (int)$_GET['result_start'];
$no_of_results = 5 ;// make a dropdown or something so the user can change it later
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
    echo"post";
}
//loading a page with cookies for paginated results
else if(array_key_exists( 'row_results', $_COOKIE) ){
    $row = json_decode( $_COOKIE['row_results'] , true);
    echo"sesso";
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
    echo"else";
} 

$no_of_pages = count($row) % $no_of_results ;

$now = time();

//	Set visit_count cookie. Expires in 2 hours
setcookie('row_results', json_encode($row), $now + 60 * 60 *2);


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
    <title>Welcome to my Blog!</title>
</head>
<body>
<?php
    include_once 'header.php';
?>
    
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

        <!-- only loads the category dropdown if there is no category already selected -->
        <?php if(!array_key_exists( 'category_id', $_GET)): ?>
        <label for="category">Category</label> 
        <select name="category">
            <option value="all_categories"> ---All Categories--- </option>
            <?php foreach($categories1 as $category_type): ?>
                <option value="<?= $category_type['category_id'] ?>"> <?= $category_type['category_name'] ?> </option>
            <?php endforeach ?>
        </select>
        <?php endif ?>

        <input type="text" name="searchText" id="" placeholder="Type here to search">
        <input type="submit" value="Search" name="search">
    </form>

    <div id="category_bar">
        <ul>
            <li><a href="shop.php?result_start=0">All categories</a></li>
            <?php foreach ($categories1 as $category) : ?>
                <li> <a href="shop.php?category_id=<?= $category['category_id']?>&result_start=0 "> <?= $category['category_name'] ?> </a> </li>
            <?php endforeach ?>
        </ul>

    </div>

    <div id="products">

    <?php if($row):
    $products = array_slice($row, $result_start, $result_start+ $no_of_results);
    foreach ($products as $product): ?>

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
    <?php endforeach ;

    else: ?>
    <h2> No Results Found</h2>
    <a href="shop.php?result_start=0">Click here to view all products</a>
    <?php endif ?>

    </div>

    <ul>
    <?php if($row):

        if($result_start>0){ ?>
            <li><a href="shop.php?result_start=<?= $result_start - $no_of_results ?>"> <<< </a></li>
        <?php } 

        for ($page_number = 1; $page_number <=$no_of_pages+1; $page_number ++ ) :?>
            <li><a href="shop.php?result_start=<?= $page_number * $no_of_results ?>"><?=$page_number ?></a></li>
        <?php endfor ;

        if($result_start< count($row)-$no_of_results ){ ?>
            <li><a href="shop.php?result_start=<?= $result_start + $no_of_results ?>"> >>></a></li>
        <?php } 
    endif ?>
    </ul>

    <?php include_once 'footer.php'; ?>
</body>

</html>