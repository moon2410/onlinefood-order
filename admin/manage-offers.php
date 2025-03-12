<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Offer Foods</h1>

        <br /><br />

        <!-- Button to Add Offer Food -->
        <a href="<?php echo SITEURL; ?>admin/add-offer-food.php" class="btn-primary">Add Offer Food</a>
        <a href="<?php echo SITEURL; ?>admin/manage-offer-settings.php" class="btn-primary">Offer Settings</a>

        <br /><br /><br />

        <?php 
            if(isset($_SESSION['add']))
            {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }

            if(isset($_SESSION['delete']))
            {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }

            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }

            if(isset($_SESSION['unauthorize']))
            {
                echo $_SESSION['unauthorize'];
                unset($_SESSION['unauthorize']);
            }

            if(isset($_SESSION['update']))
            {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
        ?>

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Original Price</th>
                <th>Offer Price</th>
                <th>Image</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>

            <?php 
                // Create a SQL Query to Get all the Offer Foods
                $sql = "SELECT * FROM tbl_offer WHERE offer_price > 0";  // Only fetching foods with an offer price

                // Execute the query
                $res = mysqli_query($conn, $sql);

                // Count rows to check whether we have foods with offer or not
                $count = mysqli_num_rows($res);

                // Create Serial Number Variable and Set Default Value as 1
                $sn = 1;

                if($count > 0)
                {
                    // We have foods with offers in the Database
                    // Get the Offer Foods from Database and Display
                    while($row = mysqli_fetch_assoc($res))
                    {
                        // Get values from individual columns
                        $id = $row['id'];
                        $title = $row['title'];
                        $original_price = $row['original_price'];
                        $offer_price = $row['offer_price'];
                        $image_name = $row['image_name'];
                        $featured = $row['featured'];
                        $active = $row['active'];
                        ?>

                        <tr>
                            <td><?php echo $sn++; ?>. </td>
                            <td><?php echo $title; ?></td>
                            <td><strike>$<?php echo $original_price; ?></strike></td> <!-- Original Price with Strike-through -->
                            <td>$<?php echo $offer_price; ?></td> <!-- Offer Price -->
                            <td>
                                <?php  
                                    // Check whether we have an image or not
                                    if($image_name == "")
                                    {
                                        // Image not available, display error message
                                        echo "<div class='error'>Image not Added.</div>";
                                    }
                                    else
                                    {
                                        // We have image, display it
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/offer/<?php echo $image_name; ?>" width="100px">
                                        <?php
                                    }
                                ?>
                            </td>
                            <td><?php echo $featured; ?></td>
                            <td><?php echo $active; ?></td>
                            <td>
                                <a href="<?php echo SITEURL; ?>admin/update-offer-food.php?id=<?php echo $id; ?>" class="btn-secondary">Update Offer</a>
                                <a href="<?php echo SITEURL; ?>admin/delete-offer-food.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-danger">Delete Offer</a>
                            </td>
                        </tr>

                        <?php
                    }
                }
                else
                {
                    // No Offer Foods Added
                    echo "<tr> <td colspan='8' class='error'> No Offer Foods Added Yet. </td> </tr>";
                }
            ?>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
