<?php
require('scripts/connect.php');
session_start();


//user login check
$login_user = false;
$admin_access = false;
if(array_key_exists('user_id', $_SESSION ) ){
    $login_user =true;

    if($_SESSION['user_id'] == 1){
        $admin_access = true;
    }
}


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
  if ($_POST['user_comment']){

        //sanitizing all the informatiom
         
        $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(trim($user_name =='')){
            $user_name = "anonymous";
        }
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
    
<?php
    include_once 'header.php';
?>

    <?php if($_GET['id']): ?>
        <div>
        <?php if($admin_access): ?>
            <a href="admin/edit.admin.php?id=<?=$row['product_id']?>"><p>edit</p></a>
        <?php endif ?>

        <h1><?= $row['name'] ?></h1>
        <h3><?= $row['company'] ?></h3>
        <h2><?= $row['price'] ?></h2>
    
        <?php 
        if($row['image']):
            $folder = "./uploads/". $row['image']; ?>
            <img src="<?= $folder ?>" alt="iamge here">
        <?php endif ?>
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

                <?php if($commentData['rating']!=0): ?>
                <h3>Rating: <?= $commentData['rating'] ?> </h3>
                <?php endif ?>
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

            <?php if($login_user): ?>
                <input type="hidden" name="user_name" value="<?= $_SESSION['user_name'] ?>">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
            <?php else: ?>
                <label for="user_name">User Name </label>
                <input type="text" name="user_name" >

                <label for="user_email">email </label>
                <input type="email" name="user_email" >
            <?php endif ?>
        
            <label for="rating">Rating</label>
            <input type="int" name="rating">

            <label for="user_comment">Comment</label>
            <textarea name="user_comment" cols="30" rows="10"></textarea>

            <input type="hidden" name="product_id"  value=<?= $row['product_id']?> >
            
            <input type="submit" value="Add Comment" name="add_comment">

        </form>


    <?php else : ?>
        
        <?php header("Location: index.php"); ?>
    <?php endif ?>
</body>
</html>


