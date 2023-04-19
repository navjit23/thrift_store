<?php 

//require('../scripts/connect.php');
include_once '../scripts/functions.inc.php';

session_start();
if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
}
// Uploading an image

/*
// file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
// Default upload path is an 'uploads' sub-folder in the current folder.
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);
    
    // Build an array of paths segment names to be joins using OS specific slashes.
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    
    // The DIRECTORY_SEPARATOR constant is OS specific.
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

// file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
function file_is_an_image($temporary_path, $new_path) {
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
    
    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);

    $mime_type_is_valid = false;
    if($file_extension_is_valid){
        $actual_mime_type        = getimagesize($temporary_path)['mime'];
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
    }
    
    
    return $file_extension_is_valid && $mime_type_is_valid;
}
        


// upload_new_item() - gets the values from the form and uploads data to database on success
function upload_new_item($image_file){

    global $db;
    // sanitize the data
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rarity = filter_input(INPUT_POST, 'rarity', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    // build a sql query with placeholders
    $query = "INSERT INTO products( name, item_condition,company, rarity, price, color, description, image, category_id) VALUES ( :name, :item_condition, :company, :rarity, :price, :color, :description, :image, :category) ";
    $statement= $db->prepare($query);

    // bind values to placeholders
    $statement->bindValue(':name', $productName);
    $statement->bindValue(':item_condition', $condition);
    $statement->bindValue(':company', $company);
    $statement->bindValue(':rarity', $rarity);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':color', $color);
    $statement->bindValue(':category', $category);
    $statement->bindValue(':image', $image_file);
    $statement->bindValue(':description', $description);

    //  Execute the INSERT.
    $statement->execute();

}
*/

// loading the categories
/*
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
*/

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
            echo"<script> alert('The file was not uploaded because it was not an image. ') </script>";
            header("location: ../index.php");
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
        
        <label for="productName" class="form-label">Name</label>
        <input type="text" name="productName" class="form-control">

        <label for="company" class="form-label">Brand</label>
        <input type="text" name="company" class="form-control">

        <label for="condition" class="form-label">Condition</label>
        <input type="int" name="condition" class="form-control">

        <label for="rarity" class="form-label">Rarity</label>
        <input type="int" name="rarity" class="form-control">

        <br>
        <div class="input-group container">
            <label for="price" class="form-label">Price</label>
            <div class="input-group-text">$</div>
            <input type="int" name="price" class="form-control">
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
</body>
</html>