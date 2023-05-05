

<header>
<!--- nav bar and other stuff on top-->
<!-- put a logo and social handles-->
<!-- nav bar-->
<div class="bg_nav">
        <h1>MCC Thrift Store</h1>
        <div class="flex logo">
        <a href="https://www.facebook.com/KildonanMCCThriftShop/"><img src="image/fb.jpeg" alt=""></a>
        <a href="https://www.instagram.com/kmtswinnipeg/?hl=en"><img src="image/ig.jpg" alt=""></a>
        <a href="https://youtu.be/dQw4w9WgXcQ"><img src="image/twitter.png" alt=""></a>
        </div>
        
    </div>
<div  class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">

        <a href="index.php" class="navbar-brand">MCC Thrift Store</a>
    
    <button class="navbar-toggler" data-bs-toggle="collapse"
    data-bs-target="#nav">
        <div class="navbar-toggler-icon"></div>
        
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav">
                <li class="nav-item"><a href="index.php" class="nav-link" >Home</a></li>
                <li class="nav-item"><a href="shop.php" class="nav-link" >Shop</a></li>
                <li class="nav-item"><a href="contact_us.php" class="nav-link" >Contact Us</a></li>

                <?php
                    if( isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a href="scripts/logout.inc.php" class="nav-link" >Log Out</a></li>

                        <?php if($_SESSION['user_id'] == 1): ?>
                            <li class="nav-item"><a href="admin/admin.php" class="nav-link fw-bolder" >Admin !</a></li>
                        <?php else: ?>
                        <li class="nav-item"><a href="#" class="nav-link fw-bolder"><?= $_SESSION['user_name'] ?></a></li>
                    <?php endif;
                    else: ?>
                        <li class="nav-item"><a href="signup_page.php" class="nav-link" >Sign Up</a></li>
                        <li class="nav-item"><a href="login_page.php" class="nav-link" >Login</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>

</header>
