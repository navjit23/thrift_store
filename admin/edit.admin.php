<?php

/*******w******** 
    
    Name:
    Date:
    Description:

****************/

require('../scripts/connect.php');
session_start();

if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
}

// To DELETE
if(isset($_POST['delete'])){
    $query = "DELETE FROM products WHERE product_id= :id";
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement= $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    if($statement->execute()){
        echo("Success");
        header("Location: ../index.php");
    } // add an alert to confirm delete
}

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
$categories = loading_categories();



//TO SAVE CHANGES TO BLOG
if($_POST && trim($_POST['productName']) != '' && trim($_POST['price']) != '' ){
    
    // sanitize the data
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rarity = filter_input(INPUT_POST, 'rarity', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $filename = $_FILES['new_image']['name'];
    

    // build a sql query with placeholders
    $query = "UPDATE products SET name = :name, company= :company, item_condition= :condition, rarity= :rarity, price = :price, `description` = :description, category_id = :category  WHERE product_id= :id;";

    if(isset($_POST['del_image'])){
        $query .= "UPDATE products SET image = NULL WHERE product_id=:id;";
        // for future delete the image from db
        
    }

    if(trim($filename) != '' ){
        // for future delete the image from db if its UNIQUE 
        $query .= "UPDATE products SET image= :image WHERE product_id= :id;";
    }

    

    
    
    $statement= $db->prepare($query);

    // bind values to placeholders
    $statement->bindValue(':name', $productName);
    $statement->bindValue(':condition', $condition);
    $statement->bindValue(':company', $company);
    $statement->bindValue(':category', $category);
    $statement->bindValue(':rarity', $rarity);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    if(trim($filename) != '' ){
        $statement->bindValue(':image', $filename);
    }

     //  Execute the INSERT.
        //  execute() will check for possible SQL injection and remove if necessary
        if($statement->execute()){
            echo("Success");
            header("Location: ../index.php");
        }
        
}

// TO LOAD A BLOG


elseif(isset($_GET['id'])){
    $blogs = "SELECT * FROM products WHERE product_id=:id "; 
    $id= filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // preparring sql for executoin
    $statement = $db->prepare($blogs);

    //bind
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    //executing sql
    $statement->execute();
    $row = $statement->fetch();
    }
    else{
        $id= false;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit this Post!</title>
</head>
<body>
<?php
    include_once '../header.php';
?>
    




    <?php if($id): ?>
        <form action="edit.admin.php" method="post" enctype="multipart/form-data"> 

        <input type="hidden" name="id" value="<?= $row['product_id'] ?>">

        <label for="productName">Name</label>
        <input type="text" name="productName" value="<?= $row['name'] ?>">

        <label for="company">Brand</label>
        <input type="text" name="company" value="<?= $row['company'] ?>">

        <label for="condition">Condition</label>
        <input type="int" name="condition" value="<?= $row['item_condition'] ?>">

        <label for="rarity">Rarity</label>
        <input type="int" name="rarity" value="<?= $row['rarity'] ?>">

        <label for="price">Price</label>
        <input type="int" name="price" value="<?= $row['price'] ?>">

        <select name="category" >

        <?php foreach($categories as $category_type):
            
            if($category_type['category_id'] == $row['category_id']) :?>
                <option value="<?= $category_type['category_id'] ?>" selected > <?= $category_type['category_name'] ?> </option>
                
            <?php else : ?>
                <option value="<?= $category_type['category_id'] ?>"> <?= $category_type['category_name'] ?> </option>
        
            <?php endif;
        endforeach ?>
        </select>

        <label for="color">Color</label>
        <input type="text" name="color" value="<?= $row['color'] ?>">
    
        <?php $folder = "./uploads/". $row['image']; ?>
        <img src="<?= $folder ?>" alt="iamge here">

        <label for="new_image">Click Here to Change the Image</label>
        <input type="file" name="new_image" id="new_image">

        <label for="del_image">Check Here to Delete Image: </label>
        <input type="checkbox" name="del_image" id="del_image" value="delete_image">;


        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10" value="<?= $row['description'] ?>"></textarea>
        

        <input type="submit" value="Update" name="update">
        <input type="submit" name='delete' value="Delete">

    </form>

    <?php  else : 
        header("Location: ../index.php"); 
        exit();

     endif ?>

</body>
</html>