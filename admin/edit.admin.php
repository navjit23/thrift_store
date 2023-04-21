<?php

/*******w******** 
    
    Name:
    Date:
    Description:

****************/

//require('../scripts/connect.php');
include_once '../scripts/functions.inc.php';

session_start();

if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
}

// To DELETE
if(isset($_POST['delete'])){
    $query = "DELETE FROM products WHERE product_id= :id";
    $current_image_path = filter_input(INPUT_POST, 'current_image_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement= $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    if($statement->execute()){

        //check if the image is unique
        $unique_image = "SELECT COUNT(product_id) FROM products WHERE image LIKE :image ;";
        $statement= $db->prepare($unique_image);
        $statement->bindValue(':image', $current_image_path);
        $statement->execute();
        $product_count = $statement->fetch();

        if( $product_count == 1){
            unlink($current_image_path);
        }
        header("Location: ../index.php");
        exit();
    } 
}

$categories = loading_categories();


// TO LOAD A BLOG


if(isset($_GET['id'])){
    $blogs = "SELECT * FROM products WHERE product_id=:id "; 
    $id= filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // preparring sql for executoin
    $statement = $db->prepare($blogs);

    //bind
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    //executing sql
    $statement->execute();
    $row = $statement->fetch();

    $folder = "../uploads/". $row['image'];
    }
    else{
        $id= false;
    }


    

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
    $current_image_path = filter_input(INPUT_POST, 'current_image_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $filename = $_FILES['new_image']['name'];
    $file_filename        = $_FILES['new_image']['name'];
    $temporary_file_path  = $_FILES['new_image']['tmp_name']; 
    

    // build a sql query with placeholders
    $query = "UPDATE products SET name = :name, company= :company, item_condition= :condition, rarity= :rarity, price = :price, `description` = :description, category_id = :category  WHERE product_id= :id;";

    if(isset($_POST['del_image'])){
        $query .= "UPDATE products SET image = NULL WHERE product_id=:id;";
        //  delete the image from db yet to ccheck if its unique
        //unlink($current_image_path);
    }

    if(trim($filename) != '' &&  file_is_an_image($temporary_file_path, $file_filename) ){
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

    if(trim($filename) !== '' && file_is_an_image($temporary_file_path, $file_filename)){
        $statement->bindValue(':image', $filename);
    }

     //  Execute the INSERT.
        //  execute() will check for possible SQL injection and remove if necessary
        $statement->execute();

        if(isset($_POST['del_image'])){
            //check if the image is unique
            $unique_image = "SELECT COUNT(product_id) FROM products WHERE image LIKE :image ;";
            $statement= $db->prepare($unique_image);
            $statement->bindValue(':image', $current_image_path);
            $statement->execute();
            $product_count = $statement->fetch();

            if( $product_count == 1){
                unlink($current_image_path);
            }
        }
        
            
            $file_filename        = $_FILES['new_image']['name'];
            $temporary_file_path  = $_FILES['new_image']['tmp_name'];    
            $file_upload_detected = isset($_FILES['new_image']) && ($_FILES['new_image']['error'] === 0);
            $upload_error_detected = isset($_FILES['new_image']) && ($_FILES['new_image']['error'] > 0);

            if (trim($filename) != '' ) { 

                if(file_is_an_image($temporary_file_path, $file_filename)){
                    
                    $new_file_path  = "../uploads/".$file_filename;
                    move_uploaded_file($temporary_file_path, $new_file_path);
                   

                    $filetype = pathinfo($file_filename, PATHINFO_EXTENSION);
                    header("Location: ../scripts/fileresize.inc.php?path=$new_file_path&filetype=$filetype");
                }
                else{
                    header ("location: ../index.php?error='img_no_upload");
                    exit();
                    
                }
            }
            else{
           header("Location: ../index.php");
            }
           exit();
        
        
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main.css">
    <title>Edit this Post!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<?php
    include_once 'header.admin.php';
?>
    




    <?php if($id): ?>
    <div class="container">

    <form action="edit.admin.php" method="post" enctype="multipart/form-data"> 

        <input type="hidden" name="id" value="<?= $row['product_id'] ?>">

        <label for="productName" class="form-label">Name</label>
        <input type="text" class="form-control" name="productName" value="<?= $row['name'] ?>">

        <label for="company" class="form-label">Brand</label>
        <input type="text" class="form-control" name="company" value="<?= $row['company'] ?>">

        <label for="condition" class="form-label">Condition</label>
        <input type="int" class="form-control" name="condition" value="<?= $row['item_condition'] ?>">

        <label for="rarity" class="form-label">Rarity</label>
        <input type="int" class="form-control" name="rarity" value="<?= $row['rarity'] ?>">

        <br>
        <div class="input-group container">
        <label for="price" class="form-label">Price :</label>
        <div class="input-group-text">$</div>
        <input type="int" class="form-control" name="price" value="<?= $row['price'] ?>">
        </div>
        <br>
        <select class="form-control" name="category" >

        <?php foreach($categories as $category_type):
            
            if($category_type['category_id'] == $row['category_id']) :?>
                <option class="form-control" value="<?= $category_type['category_id'] ?>" selected > <?= $category_type['category_name'] ?> </option>
                
            <?php else : ?>
                <option class="form-control" value="<?= $category_type['category_id'] ?>"> <?= $category_type['category_name'] ?> </option>
        
            <?php endif;
        endforeach ?>
        </select>

        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-control" name="color" value="<?= $row['color'] ?>">
    
        <?php if( $row['image']): ?>
            <img src="<?= $folder ?>" alt="image here">
            
            <label for="del_image" class="form-label">Check Here to Delete Image: </label>
            <input type="checkbox" name="del_image" id="del_image" value="delete_image">
            <br>
            <input type="hidden" name="current_image_path" value="<?= $folder ?>">
        <?php endif ?>

        <label for="new_image">Click Here to Change the Image</label>
        <input type="file" class="form-control" name="new_image" id="new_image">

        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" rows="10" value="<?= $row['description'] ?>"><?= $row['description'] ?></textarea>
        

        
        <input type="submit" class="btn btn-primary" onclick="validate()" value="Update" name="update">
        <input type="submit" class="btn btn-outline-secondary" onclick="checker()" name='delete' value="Delete">

    </form>
    </div>

    <?php  else : 
        header("Location: ../index.php"); 
        exit();

     endif ?>
<?php
    include_once 'header.admin.php';
?>
<script>
    function checker(){
        var result = confirm('Do you want to delete the item');
        if(result == false){
            event.preventDefault();
        }
    }

    function validate(){
        var del_image = document.getElementById('del_image');
        if(del_image.checked){
            var update_no_image = confirm("Image will be deleted");
            if(update_no_image == false){
            event.preventDefault();
        }   
        }

    }


</script>
</body>
</html>