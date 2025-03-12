<?php include('partials-front/header.php'); ?>

<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <?php 
            // Check if search form is submitted
            if(isset($_POST['submit'])) {
                // Get the search keyword from the form
                $search = mysqli_real_escape_string($conn, $_POST['search']);
            }
        ?>

        <h2><a href="#" class="text-white">Foods on Your Search "<?php echo $search; ?>"</a></h2>
    </div>
</section>
<!-- fOOD sEARCH Section Ends Here -->

<!-- fOOD MEnu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 
            // SQL Query to get food items based on the search keyword
            $sql = "SELECT * FROM tbl_food WHERE title LIKE '%$search%' OR description LIKE '%$search%' AND active='Yes'";

            // Execute the query
            $res = mysqli_query($conn, $sql);

            // Count rows to check whether any food items were found
            $count = mysqli_num_rows($res);

            // Check if any food items were found
            if($count > 0) {
                // Food items found
                while($row = mysqli_fetch_assoc($res)) {
                    // Get the food details
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $description = $row['description'];
                    $image_name = $row['image_name'];
        ?>

        <div class="food-menu-box">
            <div class="food-menu-img">
                <?php 
                    // Check if image exists
                    if($image_name == "") {
                        // If image not available
                        echo "<div class='error'>Image not Available.</div>";
                    } else {
                        // Display image
                        ?>
                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Food Image" class="img-responsive img-curve">
                        <?php 
                    }
                ?>
            </div>

            <div class="food-menu-desc">
                <h4><?php echo $title; ?></h4>
                <p class="food-price">$<?php echo $price; ?></p>
                <p class="food-detail">
                    <?php echo $description; ?>
                </p>
                <br>

                <!-- If user is logged in, show "Add to Cart", otherwise show "Order Now" -->
                <?php if (isset($_SESSION['user'])) { ?>
                    <a href="<?php echo SITEURL; ?>cart.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Add to Cart</a>
                <?php } else { ?>
                    <!-- Redirect to login page if not logged in -->
                    <a href="<?php echo SITEURL; ?>login.php" class="btn btn-primary">Order Now</a>
                <?php } ?>
            </div>
        </div>

        <?php
                }
            } else {
                // If no food items found
                echo "<div class='error'>No food found matching your search.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- fOOD Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>
