<?php
session_start();
require('connect.php');
require('authenticate.php');

$comments = loading_comments();
//sanitize the get aswell
$edit_mode = false;
if($_GET){
if ($_GET['edit'] == true){
    $edit_mode = true;
}
}

if($edit_mode){

    if(isset($_POST['edit'])){
        editing_comment();
    }
    if(isset($_POST['delete'])){
        delete_comment();
    }
}
//needs connection with products 
//loading comment
function loading_comments(){
    global $db;


    $load_comments = "SELECT * FROM comments;";
        // preparring sql for executoin
    $statement = $db->prepare($load_comments);
        
        //executing sql
    $statement->execute();

    $comments = [];
    while ($x = $statement->fetch() ){
        $comments[] = $x;
        
    }
    
    return $comments;
}



//editing comment
function editing_comment(){
    global $db;
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
    $edit_comment = "UPDATE comments SET comment= :comment WHERE comment_id = :comment_id ;";

    $statement= $db->prepare($edit_comment);
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
    $statement->bindValue(':comment', $comment);

    if($statement->execute()){
        echo("Success");
        header("Location: comments.php");
    }


}
//deleting comment
function delete_comment(){
    global $db;

    $query = "DELETE FROM comments WHERE comment_id= :comment_id";
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
    $statement= $db->prepare($query);
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
    if($statement->execute()){
        echo("Success");
        header("Location: comments.php");
    } // add an alert to confirm delete
    
}

// in the views also need the email, rating and user when applied
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
<?php if(! $edit_mode): ?>

    <h4><a href="comments.php?edit='true'">Edit</a></h4>
    <div id="comment_box_view_only" style="border: 1px solid black">
                
        <?php if(count($comments) > 0):
        foreach ($comments as $commentData): ?>
            <div>
        
            <h2><?= $commentData['user_name'] ?></h2>
            <h4><?= $commentData['user_email'] ?></h4>

            <?php if($commentData['rating']!=0): ?>
            <h3>Rating: <?= $commentData['rating'] ?> </h3>
            <?php endif ?>


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

<?php else: ?>
    <div id="comment_box_edit">

        <?php if(count($comments) > 0):
        foreach ($comments as $commentData): ?>
            
            <form action="" method="post">
                <h2><?= $commentData['user_name'] ?></h2>
                <h6>date here**********</h6>

                <input type="hidden" name="comment_id" value="<?= $commentData['comment_id'] ?>" >
                
                <textarea name="comment"  cols="30" rows="10" ><?= $commentData['comment'] ?></textarea>


                <input type="submit" value="Edit" name="edit">
                <input type="submit" value="Delete" name="delete">
            </form>

        <?php endforeach;
        else: ?>
            <div>
                <h2>No comments here</h2>
            </div>
        <?php endif ?>

    </div>
    <?php endif ?>
    <?php include_once 'footer.php'; ?>
</body>


</html>