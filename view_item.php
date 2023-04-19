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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>product name here</title>
</head>
<body>
    
<?php
    include_once 'header.php';
?>
<div class="container view_item">

    <?php if($_GET['id']): ?>
        <div>
        <?php if($admin_access): ?>
            <a class="btn btn-primary" href="admin/edit.admin.php?id=<?=$row['product_id']?>">edit</a>
        <?php endif ?>

        <h1><?= $row['name'] ?></h1>
        <h6><?= date('F d/Y g:i a', strtotime( $row['date']) ) ?> </h6>
        <h3><?= $row['company'] ?></h3>
        <h2>Price: $<?= $row['price'] ?></h2>
        <?php if ($row['rarity'] > 0): ?>
            <h4>Rarity: <?= $row['rarity'] ?></h4>
        <?php endif ;
        
        if ($row['item_condition'] > 0): ?>
            <h4>Condition: <?= $row['item_condition'] ?></h4>
        <?php endif ?>
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
        <div id="comment_box" class="container border border-3 grey mb-3 p-4">
            
            <?php if(count($row3) > 0):
            foreach ($row3 as $commentData): ?>
                <div>
            
                <h2><?= $commentData['user_name'] ?></h2>
                <h6><?= date('F d/Y g:i a', strtotime( $commentData['date']) ) ?></h6>   
                <?php if($commentData['rating']!=0): ?>
                <h4>Rating: <?= $commentData['rating'] ?> </h4>
                <?php endif ?>
                <p><?= $commentData['comment'] ?></p>
                
                </div>
            <?php endforeach;
            else: ?>
                <div>
                    <h2>No comments here</h2>
                </div>
            <?php endif ?>

        </div>
        
        <!--ADD COMMENTS (for future, js should load this box when user click on add a comment also have an option for image upload-->
        <div class="container grey">
            <form  method="post">
                <h2>Add a Comment/ Write a review</h2>

                <?php if($login_user): ?>
                    <input type="hidden" name="user_name" value="<?= $_SESSION['user_name'] ?>">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                <?php else: ?>
                    <label for="user_name" class="form-label">User Name </label>
                    <input type="text" class="form-control" name="user_name" >

                    <label for="user_email" class="form-label">email </label>
                    <input type="email" class="form-control" name="user_email" >
                <?php endif ?>
            
                <label for="rating" class="form-label">Rating</label>
                <input type="int" class="form-control" name="rating">

                <label for="user_comment" class="form-label">Comment</label>
                <textarea name="user_comment" class="form-control" rows="10"></textarea>

                <input type="hidden" name="product_id"  value=<?= $row['product_id']?> >
                
                <input type="submit" class="btn btn-outline-dark" value="Add Comment" name="add_comment">

            </form>
        </div>
</div>

    <?php else : ?>
        
        <?php header("Location: index.php"); ?>
    <?php endif ?>
</body>
</html>


