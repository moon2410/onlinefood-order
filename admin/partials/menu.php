<?php 
    include('../config/constants.php'); 
    include('login-check.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <!-- Important to make website responsive -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Food Order</title>
        <link rel="stylesheet" href="../css/admin.css">

        <style>
            /* Menu Section Styles */
            .menu {
                background-color: #333;
                color: #fff;
                position: fixed;
                width: 100%;
                top: 0;
                left: 0;
                z-index: 1000;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .menu .wrapper {
                display: flex;
                justify-content: space-around;
                align-items: center;
                padding: 15px 0;
                max-width: 1200px;
                margin: 0 auto;
            }

            .menu ul {
                list-style-type: none;
                display: flex;
                justify-content: space-between;
                padding: 0;
                margin: 0;
                width: 100%;
                align-items: center;
            }

            .menu ul li {
                position: relative;
                padding: 10px 20px;
                text-align: center;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .menu ul li a {
                text-decoration: none;
                color: #fff;
                font-size: 16px;
                font-weight: 600;
                display: block;
                padding: 5px 10px;
            }

            .menu ul li:hover {
                background-color: #ff6600;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            }

            /* Hover Effect */
            .menu ul li a:hover {
                color: #fff;
                transform: scale(1.05);
                transition: transform 0.3s ease;
            }

            /* Active menu item */
            .menu ul li.active {
                background-color: #ff6600;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            }

            /* Responsive Design */
            @media only screen and (max-width: 768px) {
                .menu .wrapper {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .menu ul {
                    flex-direction: column;
                    width: 100%;
                }

                .menu ul li {
                    width: 100%;
                    text-align: left;
                    padding: 15px;
                    font-size: 18px;
                }

                .menu ul li:hover {
                    background-color: #ff6600;
                }
            }

            /* Animation Effect for Menu */
            @keyframes slideIn {
                from {
                    transform: translateY(-100%);
                }
                to {
                    transform: translateY(0);
                }
            }

            .menu {
                animation: slideIn 0.5s ease-in-out;
            }
        </style>

    </head>

    <body>
        <!-- Menu Section Starts -->
        <div class="menu text-center">
            <div class="wrapper">
                <ul>
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="manage-blog.php">Blog</a></li>
                    <li><a href="manage-category.php">Category</a></li>
                    <li><a href="manage-food.php">Food Items</a></li>
                   <!-- <li><a href="manage-offers.php">Offers Food</a></li> -->
                    <li><a href="manage-order.php">Received Order Section</a></li>
                    <li><a href="order-management.php">Order Management</a></li>
                    <li><a href="manage-user.php">Manage Users</a></li>
                    <li><a href="manage-admin.php">Manage Admin</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        <!-- Menu Section Ends -->
        
        <!-- Add some content here -->
        <div style="padding: 80px 20px;">

            <h2><br>Welcome to Admin Dashboard</h2>
        </div>
        
        <script>
            // Make the menu item active when clicked
            const menuItems = document.querySelectorAll('.menu ul li a');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(link => link.parentElement.classList.remove('active'));
                    item.parentElement.classList.add('active');
                });
            });
        </script>

