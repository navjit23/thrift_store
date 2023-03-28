<?php
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
    <title>product name here</title>
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
    <?php if($id): ?>
        <div>
        <h1><?= $row['name'] ?></h1>
        <h3><?= $row['brand'] ?></h3>
        <h2><?= $row['price'] ?></h2>
        </div>
        <div>
            <p><?= $row['description'] ?></p>
        </div>
        <!-- future comments / reviews here -->


    <?php else : ?>
        <?php header("Location: index.php"); ?>
    <?php endif ?>
</body>
</html>