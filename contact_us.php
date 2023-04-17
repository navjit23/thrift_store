<?php
session_start();
require('scripts/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Contact Us</title>
</head>

<body>
<?php
    include_once 'header.php';
?>
    <!--- timing, google map, cell, mail, address, form(for suggestions)--->
<div class="container">
    <div>
        <h1>Contact Us</h1>
        <ul>
            <!-- email icon here--><li>abc@gmail.com</li>
            <!-- phone icon here--><li>(431)-123-1234</li>
            <!-- location icon --> <li>whatever the location is</li>
        </ul>
    </div>

    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2569.3199342787325!2d-97.10408378428806!3d49.91156977940463!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x52ea711d9cddc76f%3A0x8a66c115599feac0!2sKildonan%20MCC%20Thrift%20Shop!5e0!3m2!1sen!2sca!4v1679624726032!5m2!1sen!2sca" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>


    <!-- timing-->
    <div>
        <h1>Store Timing</h1>
        <p>Monday to Saturday 10am - 5pm</p>
        <h1>Donation Timing</h1>
        <p>Monday to Saturday 10am - 5pm</p>
    </div>
</div>
    <?php include_once 'footer.php'; ?>
</body>
</html>