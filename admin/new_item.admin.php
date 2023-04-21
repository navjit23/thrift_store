<?php 

//require('../scripts/connect.php');
include_once '../scripts/functions.inc.php';

session_start();
if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
}


$row = loading_categories();

if($_POST && trim($_POST['productName']) != '' && trim($_POST['price']) != '' ){
    
    // if uploaded file is not an image, make an alert that says that the uploaded file was not an image
    //condition for image upload
    $file_filename        = $_FILES['image']['name'];
    $temporary_file_path  = $_FILES['image']['tmp_name'];    
    $file_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

    if ($file_upload_detected) { 

        if(file_is_an_image($temporary_file_path, $file_filename)){
            
            $new_file_path  = "../uploads/".$file_filename;
            move_uploaded_file($temporary_file_path, $new_file_path);
            upload_new_item($file_filename);

            $filetype = pathinfo($file_filename, PATHINFO_EXTENSION);
            header("Location: ../scripts/fileresize.inc.php?path=$new_file_path&filetype=$filetype");
        }
        else{
            //here should be a prompt and after wards normal file uploaded
            upload_new_item(`NULL`);
            
            header("location: ../index.php?error='img_no_upload' ");
            exit();
        }
    }
    else{
        upload_new_item(`NULL`);
        header("location: ../index.php");
            exit();
    }

    
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>My Blog Post!</title>
</head>
<body>
<?php
    include_once 'header.admin.php';
?>
    
   

<div class="container">
    <form action="" method="post" enctype="multipart/form-data"> 
        
        <label for="productName" class="form-label">Name*</label>
        <input type="text" name="productName" required class="form-control">

        <label for="company" class="form-label">Brand</label>
        <input type="text" name="company" class="form-control">

        <label for="condition" class="form-label">Condition</label>
        <input type="int" name="condition" class="form-control">

        <label for="rarity" class="form-label">Rarity</label>
        <input type="int" name="rarity" class="form-control">

        <br>
        <div class="input-group container">
            <label for="price" class="form-label">Price*</label>
            <div class="input-group-text">$</div>
            <input type="int" name="price" required  class="form-control">
            <div class="input-group-text">.00</div>
        </div>
        <br>

        <label for="category" class="form-label">Category</label>
        <select class="form-control" name="category">
            <?php foreach($row as $category_type): ?>
                <option class="form-control" value="<?= $category_type['category_id'] ?>"> <?= $category_type['category_name'] ?> </option>
            <?php endforeach ?>
        </select>

        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-control" name="color">

        <label for="image" class="form-label">Image </label> <!---- needs validation and rn cannot be empty----->
        <input type="file" class="form-control" name="image">

        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control"  rows="10"></textarea>

        <input type="submit" class="btn btn-primary" value="Add Product">
        
    </form>
</div>
<?php include_once 'footer.admin.php' ?>

</body>
</html>