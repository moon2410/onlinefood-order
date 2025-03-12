<link rel="stylesheet" href="../css/admin.css">
<?php 
include('../config/constants.php'); 
//include('partials/menu.php'); 
?>
<div class="main-content">
    <div class="wrapper">
        <h1>Add Offer Food</h1>

        <br><br>

        <?php 
            if (isset($_SESSION['upload'])) {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td><input type="text" name="title" placeholder="Enter Food Title" required></td>
                </tr>
                <tr>
                    <td>Original Price: </td>
                    <td><input type="number" name="original_price" placeholder="Enter Original Price" required></td>
                </tr>
                <tr>
                    <td>Offer Price: </td>
                    <td><input type="number" name="offer_price" placeholder="Enter Offer Price" required></td>
                </tr>
                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes"> Yes
                        <input type="radio" name="featured" value="No" checked> No
                    </td>
                </tr>
                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes
                        <input type="radio" name="active" value="No" checked> No
                    </td>
                </tr>
                <tr>
                    <td>Image: </td>
                    <td><input type="file" name="image" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Offer Food" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

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
                header('location:' . SITEURL . 'admin/add-offer-food.php');
                die();
            }
        }
    }

    // SQL Query to insert the offer food into database
    $sql = "INSERT INTO tbl_offer SET 
            title='$title', 
            original_price='$original_price', 
            offer_price='$offer_price', 
            image_name='$image_name', 
            featured='$featured', 
            active='$active'";

    $res = mysqli_query($conn, $sql);

    // Check if the data was inserted
    if ($res == true) {
        $_SESSION['add'] = "Offer Food Added Successfully";
        header('location:' . SITEURL . 'admin/manage-offer-food.php');
    } else {
        $_SESSION['add'] = "Failed to Add Offer Food";
        header('location:' . SITEURL . 'admin/add-offer-food.php');
    }
}
?>

<?php include('partials/footer.php'); ?>
