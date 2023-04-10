<?php
require('connect.php');

// TO LOAD A BLOG
function loading_page(){
    global $db;

    $id= filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $blogs = "SELECT * FROM products WHERE product_id=:id ;";
        // preparring sql for executoin
    $statement = $db->prepare($blogs);
    
        //bind
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
        //executing sql
    $statement->execute();
    $row1 = $statement->fetch();
    return $row1;

}

function loading_comments(){
    global $db;

    $id= filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $load_comments = "SELECT * FROM comments WHERE  product_id= :id ORDER BY date DESC;";
        // preparring sql for executoin
    $statement = $db->prepare($load_comments);
    
        //bind
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
        //executing sql
    $statement->execute();
    //$row2 = $statement->fetch();
    $comments = [];
    while ($x = $statement->fetch() ){
        $comments[] = $x;
        

    }
    
    return $comments;
    
}


function adding_comment(){
    global $db;

    //check if user is logged in
    //check for required info
  if ($_POST['user_comment'] && $_POST['user_name'] && $_POST['user_email']){

        //sanitizing all the informatiom
         
        $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $comment1 = filter_input(INPUT_POST, 'user_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

        //if all good, add the comment inn db
        // productt id doesnt work code broken

        $comment = "INSERT INTO comments(comment, rating, user_name, user_email, product_id) VALUES (:comment1, :rating, :user_name, :user_email, :product_id); ";
        $statement1 = $db->prepare($comment);

        $statement1->bindValue(':comment1', $comment1);
        $statement1->bindValue(':rating', $rating);
        $statement1->bindValue(':user_name', $user_name);
        $statement1->bindValue(':user_email', $user_email);
        $statement1->bindValue(':product_id', $product_id);

        if($statement1->execute()){
            echo "Success";
            header("Location: view_item.php?id=$product_id");
        }
    }
    
}

if(isset($_GET['id'])){
    $row = loading_page();
    $row3 = loading_comments();    
}

if(isset($_POST['add_comment'])){
   adding_comment();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
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
    <?php if($_GET['id']): ?>
        <div>
        <a href="edit.php?id=<?=$row['product_id']?>"><p>edit</p></a>
        <h1><?= $row['name'] ?></h1>
        <h3><?= $row['company'] ?></h3>
        <h2><?= $row['price'] ?></h2>
    
        <?php $folder = "./uploads/". $row['image']; ?>
        <img src="<?= $folder ?>" alt="iamge here">
        </div>
        <div>
            <p><?= $row['description'] ?></p>
        </div>
        

        <!--VIEW COMMENTS -->
        <!--- COMMENT BOX--->
        <div id="comment_box" style="border: 1px solid black">
            
            <?php if(count($row3) > 0):
            foreach ($row3 as $commentData): ?>
                <div>
            
                <h2><?= $commentData['user_name'] ?></h2>
                <p><?= $commentData['comment'] ?></p>
                <h6>date here**********</h6>

                </div>
            <?php endforeach;
            else: ?>
                <div>
                    <h2>No comments here</h2>
                </div>
            <?php endif ?>

        </div>
        
        <!--ADD COMMENTS (for future, js should load this box when user click on add a comment also have an option for image upload-->
        <form  method="post">
            <h2>Add a Comment/ Write a review</h2>

            <label for="user_name">User Name *</label>
            <input type="text" name="user_name" >

            <label for="user_email">email *</label>
            <input type="email" name="user_email" >
        
            <label for="rating">Rating</label>
            <input type="int" name="rating">

            <label for="user_comment">Comment</label>
            <textarea name="user_comment" cols="30" rows="10"></textarea>

            <input type="hidden" name="product_id"  value=<?= $row['product_id']?> >
            <input type="hidden" name="user_id">
            <input type="submit" value="Add Comment" name="add_comment">

        </form>


    <?php else : ?>
        
        <?php header("Location: index.php"); ?>
    <?php endif ?>
</body>
</html>


