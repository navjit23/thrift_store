<?php 

require('connect.php');

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
    $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
    $statement->bindValue(':image', $image);
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
    
   


    <form action="new_item.php" method="post">
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

        <label for="image">Image URL </label> <!--- create a dropbox or something to uplload images--->
        <input type="text" name="image">

        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>

        <input type="submit">
        
    </form>
    
</body>
</html>