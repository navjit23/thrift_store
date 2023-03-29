<?php

/*******w******** 
    
    Name:
    Date:
    Description:

****************/

require('connect.php');
require('authenticate.php');

// To DELETE
if(isset($_POST['delete'])){
    $query = "DELETE FROM products WHERE product_id= :id";
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement= $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    if($statement->execute()){
        echo("Success");
        header("Location: index.php");
    } // add an alert to confirm delete
}




//TO SAVE CHANGES TO BLOG
if($_POST && trim($_POST['productName']) != '' && trim($_POST['price']) != '' ){
    
    // sanitize the data
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $conditon = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rarity = filter_input(INPUT_POST, 'rarity', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $filename = $_FILES['new_image']['name'];
    

    // build a sql query with placeholders
    $query = "UPDATE products SET name = :name, company= :company, item_condition= :condition, rarity= :rarity, price = :price, `description` = :description  WHERE product_id= :id;";

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
            //header("Location: index.php");
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
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <header>
    <!--- nav bar and other stuff on top-->
    <!-- put a logo and social handles-->
    <!-- nav bar-->
    <div id= "main_nav">
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="new_item.php">Sell / Donate</a></li>
        <li><a href="contact_us.php">Contact Us</a></li>
        <li><a href="edit.php">Store Location</a></li>
    </ul>
    </div>

</header>
    




    <?php if($id): ?>
        <form action="edit.php" method="post" enctype="multipart/form-data"> 

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

        <label for="category">Category</label> <!-- maybe createa   a dropdown-->
        <input type="text" name="category" value="<?= $row['category'] ?>">

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

    <?php /* else : ?>
        <?php header("Location: index.php"); */?>

    <?php endif ?>

</body>
</html>