<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Offer Food</h1>

        <br><br>

        <?php
            // Get the food ID from the URL
            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                // Fetch the current data of the food item
                $sql = "SELECT * FROM tbl_offer WHERE id=$id";
                $res = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($res);

                // Get individual details
                $title = $row['title'];
                $original_price = $row['original_price'];
                $offer_price = $row['offer_price'];
                $featured = $row['featured'];
                $active = $row['active'];
                $image_name = $row['image_name'];
            } else {
                header('location:' . SITEURL . 'admin/manage-offer-food.php');
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td><input type="text" name="title" value="<?php echo $title; ?>" required></td>
                </tr>
                <tr>
                    <td>Original Price: </td>
                    <td><input type="number" name="original_price" value="<?php echo $original_price; ?>" required></td>
                </tr>
                <tr>
                    <td>Offer Price: </td>
                    <td><input type="number" name="offer_price" value="<?php echo $offer_price; ?>" required></td>
                </tr>
                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?php if ($featured == "Yes") { echo "checked"; } ?>> Yes
                        <input type="radio" name="featured" value="No" <?php if ($featured == "No") { echo "checked"; } ?>> No
                    </td>
                </tr>
                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if ($active == "Yes") { echo "checked"; } ?>> Yes
                        <input type="radio" name="active" value="No" <?php if ($active == "No") { echo "checked"; } ?>> No
                    </td>
                </tr>
                <tr>
                    <td>Image: </td>
                    <td>
                        <?php if ($image_name != "") { ?>
                            <img src="<?php echo SITEURL; ?>images/offer/<?php echo $image_name; ?>" width="100px"><br><br>
                            <input type="file" name="image">
                        <?php } else { ?>
                            <input type="file" name="image" required>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Offer Food" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
            // Check if the form is submitted
            if (isset($_POST['submit'])) {
                // Get form data
                $title = $_POST['title'];
                $original_price = $_POST['original_price'];
                $offer_price = $_POST['offer_price'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                // Handle image upload
                if (isset($_FILES['image']['name'])) {
                    $image_name = $_FILES['image']['name'];
                    if ($image_name != "") {
                        $ext = end(explode('.', $image_name));
                        $image_name = "Offer_Food_" . rand(0000, 9999) . '.' . $ext;
                        $source_path = $_FILES['image']['tmp_name'];
                        $destination_path = "../images/offer/" . $image_name;
                        $upload = move_uploaded_file($source_path, $destination_path);
                        if ($upload == false) {
                            $_SESSION['upload'] = "Failed to upload image";
                            header('location:' . SITEURL . 'admin/update-offer-food.php?id=' . $id);
                            die();
                        }
                    }
                } else {
                    $image_name = $row['image_name']; // Keep the old image if no new image is uploaded
                }

                // Update food details in the database
                $sql2 = "UPDATE tbl_offer SET 
                            title='$title',
                            original_price='$original_price',
                            offer_price='$offer_price',
                            image_name='$image_name',
                            featured='$featured',
                            active='$active'
                          WHERE id=$id";

                $res2 = mysqli_query($conn, $sql2);

                if ($res2 == true) {
                    $_SESSION['update'] = "Offer Food Updated Successfully";
                    header('location:' . SITEURL . 'admin/manage-offer-food.php');
                } else {
                    $_SESSION['update'] = "Failed to Update Offer Food";
                    header('location:' . SITEURL . 'admin/update-offer-food.php?id=' . $id);
                }
            }
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>
