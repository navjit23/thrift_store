<?php
require ('scripts/connect.php');
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Home</title>
</head>

<body>

<?php
    include_once 'header.php';
?>

<div class="container">
    <!-- main content--->
    <div id="main_page">
        <h2 class="orange">Kildonan MCC Thrift Store</h2>
        <div>
            <p>Come and visit us Monday - Saturday, 10am - 5pm</p>
            <p>Donations will be gladly accepted Monday - Saturday 10am - 5pm</p>
            <p>We are a Non Profit Organization Part of MCC Canada. We have been part of the East Kildonan Community since 1972 and offer gently used products at an affordable price</p>
        </div>
    </div>

    <div>
        <h1>About Us</h1>
        <h2>Reduce Reuse Donate</h2>

        <div>
            <h3>Support Local Community</h3>
        </div>
        <div>
            <h3>Shop Environment Friendly</h3>
        </div>
    </div>

    <div>
        <h1>Support Programs</h1>
        <div>
            <h2>Hands of Hope</h2>
            <h2>Elmwood High School</h2>
            <h2>Riverwood House</h2>
        </div>
    </div>


</div>
<?php include_once 'footer.php'; ?>
<?php
if( array_key_exists('error', $_GET)){
  
        echo " <script> alert('The file was not uploaded because it was not an image. ') </script>";
}

?>
</body>
</html>