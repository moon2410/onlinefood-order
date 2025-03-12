<?php
include('config/constants.php');

// Get search query from the URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch food items based on search
$sql = "SELECT * FROM tbl_food WHERE title LIKE '%$search%' AND active='Yes'";
$res = mysqli_query($conn, $sql);
$count = mysqli_num_rows($res);

if ($count > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $id = $row['id'];
        $title = $row['title'];
        $price = $row['price'];
        $description = $row['description'];
        $image_name = $row['image_name'];
        ?>
        <div class="food-menu-box">
            <div class="food-menu-img">
                <?php 
                    if ($image_name == "") {
                        echo "<div class='error'>Image not Available.</div>";
                    } else {
                        echo "<img src='".SITEURL."images/food/".$image_name."' alt='$title' class='img-responsive img-curve'>";
                    }
                ?>
            </div>
            <div class="food-menu-desc">
                <h4><?php echo $title; ?></h4>
                <p class="food-price">$<?php echo $price; ?></p>
                <p class="food-detail"><?php echo $description; ?></p>
                <br>
                <a href="javascript:void(0);" class="btn btn-primary add-to-cart" data-food-id="<?php echo $id; ?>">Add to Cart</a>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='error'>No food found.</div>";
}
?>
