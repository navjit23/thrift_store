<?php
session_start();
require('../scripts/connect.php');
if($_SESSION['user_id'] != 1){
    header(" location: ../index.php");
    exit();
}

$row = loading_categories();
if(isset($_POST)){

    if(isset($_POST['add_category']) && trim($_POST['new_category']) != '' ){
        add_category();
        header("location: admin.php"); 
        exit();
    }

    if(isset($_POST['rename_category']) && trim($_POST['rename']) != '' ){
        rename_category();
        header("location: admin.php"); 
        exit();
    }

    if(isset($_POST['delete_category'])){
        remove_category();
        header("location: admin.php"); 
        exit();
    }
}

//add category
function add_category(){

    global $db;
    //sanitize the data
    $category = filter_input(INPUT_POST, 'new_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO categories(category_name) VALUES (:category_name);";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_name', $category);

    if($statement->execute()){
        echo "Success";
    }
}

//removve catgory
function remove_category(){
    global $db;

    $category_id = filter_input(INPUT_POST, 'loaded_category', FILTER_SANITIZE_NUMBER_INT);

    $query = "DELETE FROM categories WHERE category_id= :id";

    $statement= $db->prepare($query);
    $statement->bindValue(':id', $category_id, PDO::PARAM_INT);
    if($statement->execute()){
        echo("Success");

    } // add an alert to confirm delete
}

// rename category
function rename_category(){
    global $db;
    //sanitize the data
    $category = filter_input(INPUT_POST, 'rename', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_input(INPUT_POST, 'loaded_category', FILTER_SANITIZE_NUMBER_INT);

    $query = "UPDATE categories SET category_name = :category_name WHERE category_id= :category_id;";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_name', $category);
    $statement->bindValue(':category_id', $category_id);

    if($statement->execute()){
        echo "Success";
    }

}

//loading comment
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
    <!-- loading the categories--->
    <form action="new_category.admin.php" method="post" class="border border-1">
    <h3>Edit a Category</h3>

    <select class="form-control"name="loaded_category" >

    <?php foreach($row as $category_type): ?>    
    <option class="form-control" value="<?= $category_type['category_id'] ?>"> <?= $category_type['category_name'] ?> </option>
    <?php endforeach ?>

    </select>
   

    <label for="rename" class="form-label">Rename</label>
    <input type="text" class="form-control" name="rename">

    <input type="submit" class="btn btn-primary" value="Rename Category" name="rename_category">
    <input type="submit" class="btn btn-outline-secondary" onclick="checker()" value="Delete Category" name="delete_category">
    </form>


    <!-- adding new categories--->
    <div class="container mt-5 p-3">
    <form action="new_category.admin.php" method="post" class="border border-1">
        <label for="new_category" >Category</label>
        <input type="text" name="new_category" required>

        <input type="submit" class="btn btn-primary"  value="Add Category" name="add_category">
        
    </form>
    </div>

    <div>
        <ul>
            <?php foreach($row as $category_name): ?>
                <li><?= $category_name['category_name'] ?> </li>
            <?php endforeach ?>
        </ul>
    </div>

</div>


<?php include_once 'footer.admin.php' ?>
<script>
    function checker(){
        var result = confirm('Do you want to delete this category?');
        if(result == false){
            event.preventDefault();
        }
    }
</script>
</body>
</html>