<?php
require 'connect.php';

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