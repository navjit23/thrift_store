<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Home</title>
</head>

<body>

<?php
    include_once 'header.php';
?>

<main>
    <!-- main content--->
    <div id="main_page">
        <h2>Kildonan MCC Thrift Store</h2>
        <p>Come and visit us Monday - Saturday, 10am - 5pm</p>
        <p>Donations will be gladly accepted Monday - Saturday 10am - 5pm</p>
        <p>We are a Non Profit Organization Part of MCC Canada. We have been part of the East Kildonan Community since 1972 and offer gently used products at an affordable price</p>

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


</main>

<?php include_once 'footer.php'; ?>
</body>
</html>