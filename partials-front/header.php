<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Food Order System</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">




    <style>



/* Navbar Section */
.navbar {
background-color:rgb(255, 247, 221);
padding: 10px 0;
position: fixed;
width: 100%;
z-index: 1000;
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.navbar:hover {
background-color: rgb(252, 239, 198);
box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.navbar .container {
display: flex;
justify-content: space-between;
align-items: center;
max-width: 1200px; /* Increased width for a more expansive navbar */
margin: 0 auto;
padding: 0 20px;
}

.navbar .logo img {
width: 150px;
height: auto;
transition: transform 0.3s ease;
}

.navbar .logo img:hover {
transform: scale(1.1);
}

.navbar .menu {
list-style: none;
margin: 0;
padding: 0;
display: flex; /* Display menu items in a row */
gap: 20px; /* Add space between the menu items */
}

.navbar .menu ul {
display: flex;
margin: 0;
padding: 0;
}

.navbar .menu ul li {
display: inline-block;
}

.navbar .menu ul li a {
text-decoration: none;
color: black;
font-size: 20px;
font-weight: 600;
padding: 10px 20px;
border-radius: 5px;
display: inline-block;
transition: background-color 0.3s ease, transform 0.3s ease;
}

.navbar .menu ul li a:hover {
background-color:rgb(235, 115, 88);
color: #fff;
transform: translateY(-2px);
}

.navbar .menu ul li a:active {
background-color:rgb(240, 113, 104);
}

/* Cart Count Styling */
#cart-count {
font-weight: bold;
color:rgb(230, 52, 12);
font-size: 18px;
}

/* Mobile Responsive Styling */
@media (max-width: 768px) {
.navbar .container {
    flex-direction: column;
    align-items: flex-start;
}

.navbar .menu {
    flex-direction: column;
    gap: 15px;
    padding-left: 0;
}

.navbar .menu ul li {
    display: block;
    width: 100%;
}

.navbar .menu ul li a {
    padding: 15px;
    text-align: left;
}
}

</style>

    
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="user.php" title="Logo">
                    <img src="images/logo.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li>
                        <a href="<?php echo SITEURL; ?>user.php">Home</a>
                    </li>

                    <li>
                        <a href="<?php echo SITEURL; ?>foods.php">FoodMenu</a>
                    </li>
                    <li>
    <a href="<?php echo SITEURL; ?>cart.php">
        Cart (<span id="cart-count">
    <?php 
        // Get the cart count
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; 
    ?>
</span>
)
    </a>
</li>




                    <li>
                        <a href="<?php echo SITEURL; ?>myorders.php">MyOrders</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>profile.php">Profile</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>logout.php">Logout</a>
                    </li>
                    
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->
     <br><br><br><br><br><br><br><br><br><br>