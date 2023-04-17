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
        
        <div id="category_bar" class="row row-cols-8 gx=1">
            
            <a class="col" href="shop.php?result_start=0"><div class="col box">All categories</div></a>
            <?php foreach ($categories1 as $category) : ?>
                <a class="col" href="shop.php?category_id=<?= $category['category_id']?>&result_start=0 "><div class="col box"> <?= $category['category_name'] ?> </div></a>
            <?php endforeach ?>
            

        </div>
    </div> 


    <div id="products">

        <?php if($row):
        $products = array_slice($row, $result_start, $result_start+ $no_of_results);
        foreach ($products as $product): ?>

        <div class="product_div border border-dark border-1 border-opacity-50 m-2 p-3">
            
            <div class="info">
                <a href="view_item.php?id=<?=$product['product_id']?>"><h3> <?= $product['name'] ?> </h3></a> 
                <h2><?= $product['company'] ?></h2>
                <h2><?= $product['item_condition'] ?></h2>
                <h3><?= $product['price'] ?></h3>
                
                <?php 
                if($product['image']):
                    $folder = "./uploads/". $product['image'];
                    ?>
                    <img src="<?= $folder ?>" alt="iamge here">
                <?php endif ?>
            </div>
        </div>
        <?php endforeach ; ?>
    </div>


        <?php    else: ?>
    <div id="no_result">
        <h2> No Results Found</h2>
        <a href="shop.php?result_start=0">Click here to view all products</a>
        <?php endif ?>

    </div>

    <ul>
    <?php if($row): ?>

    <div id="page_links">

        <?php    if($result_start>0){ ?>
            <li><a href="shop.php?result_start=<?= $result_start - $no_of_results ?>"> <<< </a></li>
        <?php } 

        for ($page_number = 1; $page_number <=$no_of_pages+1; $page_number ++ ) :?>
            <li><a href="shop.php?result_start=<?= $page_number * $no_of_results ?>"><?=$page_number ?></a></li>
        <?php endfor ;

        if($result_start< count($row)-$no_of_results ){ ?>
            <li><a href="shop.php?result_start=<?= $result_start + $no_of_results ?>"> >>></a></li>
        <?php } 
    endif ?>
    </div>
    </ul>
</div>

    <?php include_once 'footer.php'; ?>
    
</body>

</html>