

<header>
<!--- nav bar and other stuff on top-->
<!-- put a logo and social handles-->
<!-- nav bar-->
<div id= "main_nav">
<ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="shop.php?result_start=0">Shop</a></li>
    <li><a href="contact_us.php">Contact Us</a></li>

    <?php
        if( isset($_SESSION['user_id'])): ?>
            <li><a href="scripts/logout.inc.php">Log Out</a></li>

            <?php if($_SESSION['user_id'] == 1): ?>
                <li><a href="admin/admin.php">Admin !</a></li>
            <?php else: ?>
            <li><?= $_SESSION['user_name'] ?></li>
        <?php endif;
        else: ?>
            <li><a href="signup_page.php">Sign Up</a></li>
            <li><a href="login_page.php">Login</a></li>
    <?php endif ?>
</ul>
</div>

</header>
