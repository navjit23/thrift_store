

<header>
<!--- nav bar and other stuff on top-->
<!-- put a logo and social handles-->
<!-- nav bar-->
<div id= "main_nav">
<ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="shop.php">Shop</a></li>
    <li><a href="contact_us.php">Contact Us</a></li>

    <?php
        if( isset($_SESSION['user_id'])): ?>
            <li><a href="scripts/logout.inc.php">Log Out</a></li>
            <li><?= $_SESSION['user_name'] ?></li>
    <?php 
        else: ?>
            <li><a href="signup_page.php">Sign Up</a></li>
            <li><a href="login_page.php">Login</a></li>
    <?php endif ?>
</ul>
</div>

</header>
