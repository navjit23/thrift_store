<?php 

require('connect.php');
require('authenticate.php');

if($_POST && trim($_POST['productName']) != '' && trim($_POST['price']) != '' ){
    
    // sanitize the data
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $conditon = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rarity = filter_input(INPUT_POST, 'rarity', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
   

    // Uploading an image
 

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
         $actual_mime_type        = getimagesize($temporary_path)['mime'];
         
         $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
         $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
         
         return $file_extension_is_valid && $mime_type_is_valid;
     }
     
     $file_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
     $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
 
     if ($file_upload_detected) { 
         $file_filename        = $_FILES['image']['name'];
         $temporary_file_path  = $_FILES['image']['tmp_name'];
         $new_file_path        = file_upload_path($file_filename);

        
        // file is an image
        if (file_is_an_image($temporary_file_path, $new_file_path)) {
             move_uploaded_file($temporary_file_path, $new_file_path);
             echo"it is an image";
         }
         else{
            echo" Filetype not valid";
         }


     }
     else{
        echo" Filetype not valid";
     }


// yet to implement a logic file is not an image the query should stop and error pop on screen

    

    // build a sql query with placeholders
    $query = "INSERT INTO products( name, item_condition,company, rarity, price, color, description, image) VALUES ( :name, :item_condition, :company, :rarity, :price, :color, :description, :image) ";
    $statement= $db->prepare($query);

    // bind values to placeholders
    $statement->bindValue(':name', $productName);
    $statement->bindValue(':item_condition', $condition);
    $statement->bindValue(':company', $company);
    $statement->bindValue(':rarity', $rarity);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':color', $color);
    $statement->bindValue(':image', $file_filename);
    $statement->bindValue(':description', $description);

     //  Execute the INSERT.
        //  execute() will check for possible SQL injection and remove if necessary
        if($statement->execute()){
            echo "Success";
        }
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
    
   


    <form action="new_item.php" method="post" enctype="multipart/form-data"> 
        <label for="productName">Name</label>
        <input type="text" name="productName">

        <label for="company">Brand</label>
        <input type="text" name="company">

        <label for="condition">Condition</label>
        <input type="int" name="condition">

        <label for="rarity">Rarity</label>
        <input type="int" name="rarity">

        <label for="price">Price</label>
        <input type="int" name="price">

        <label for="category">Category</label> <!-- maybe createa   a dropdown-->
        <input type="text" name="category">

        <label for="color">Color</label>
        <input type="text" name="color">

        <label for="image">Image </label> <!---- needs validation and rn cannot be empty----->
        <input type="file" name="image">

        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>

        <input type="submit" value="Add Product">
        
    </form>
    
</body>
</html>