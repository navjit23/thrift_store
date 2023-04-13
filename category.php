<?php
session_start();
require('connect.php');
require('authenticate.php');

$row = loading_categories();
if(isset($_POST)){

    if(isset($_POST['add_category']) && trim($_POST['new_category']) != '' ){
        add_category();
        header("location: shop.php"); // should go to user page once formed
    }

    if(isset($_POST['rename_category']) && trim($_POST['rename']) != '' ){
        rename_category();
        header("location: shop.php"); // should go to user page once formed
    }

    if(isset($_POST['delete_category'])){
        remove_category();
        header("location: shop.php"); // should go to user page once formed
    }
}

//add category
function add_category(){

    global $db;
    //sanitize the data
    $category = filter_input(INPUT_POST, 'new_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO categories(category_name) VALUES (:category_name);";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_name', $category);

    if($statement->execute()){
        echo "Success";
    }
}

//removve catgory
function remove_category(){
    global $db;

    $category_id = filter_input(INPUT_POST, 'loaded_category', FILTER_SANITIZE_NUMBER_INT);

    $query = "DELETE FROM categories WHERE category_id= :id";

    $statement= $db->prepare($query);
    $statement->bindValue(':id', $category_id, PDO::PARAM_INT);
    if($statement->execute()){
        echo("Success");

    } // add an alert to confirm delete
}

// rename category
function rename_category(){
    global $db;
    //sanitize the data
    $category = filter_input(INPUT_POST, 'rename', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_input(INPUT_POST, 'loaded_category', FILTER_SANITIZE_NUMBER_INT);

    $query = "UPDATE categories SET category_name = :category_name WHERE category_id= :category_id;";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_name', $category);
    $statement->bindValue(':category_id', $category_id);

    if($statement->execute()){
        echo "Success";
    }

}

//loading comment
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>My Blog Post!</title>
</head>
<body>
    <!-- loading the categories--->
    <form action="category.php" method="post">

    <label for="loaded_category">Category</label>
    <select name="loaded_category" >

    <?php foreach($row as $category_type): ?>    
    <option value="<?= $category_type['category_id'] ?>"> <?= $category_type['category_name'] ?> </option>
    <?php endforeach ?>

    </select>
   

    <label for="rename">Rename</label>
    <input type="text" name="rename">

    <input type="submit" value="Rename Category" name="rename_category">
    <input type="submit" value="Delete Category" name="delete_category">
    </form>


    <!-- adding new categories--->
    <form action="category.php" method="post">
        <label for="new_category">Category</label>
        <input type="text" name="new_category">

        <input type="submit" value="Add Category" name="add_category">
        
    </form>
</body>
</html>