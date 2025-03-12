<link rel="stylesheet" href="../css/admin.css">

<?php 


// Start session

include('../config/constants.php'); 

?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Offer Section Visibility</h1>

        <br /><br />

        <?php 
            if(isset($_SESSION['update'])) {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
        ?>

        <?php 
            // Get the offer settings from the database
            $sql = "SELECT * FROM tbl_offer_settings WHERE id=1"; // Assuming there's only one settings row
            $res = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($res);

            // Check if settings were found
            if ($row) {
                $is_visible = $row['is_visible'];
                $header_text = $row['header_text'];
            } else {
                // If no settings were found, set defaults
                $is_visible = 'No';
                $header_text = 'Special Offers';
            }
        ?>

        <!-- Offer Settings Form -->
        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Header Text:</td>
                    <td><input type="text" name="header_text" value="<?php echo $header_text; ?>" required></td>
                </tr>

                <tr>
                    <td>Show Offer Section:</td>
                    <td>
                        <input type="radio" name="is_visible" value="Yes" <?php if($is_visible == 'Yes') echo "checked"; ?>> Yes
                        <input type="radio" name="is_visible" value="No" <?php if($is_visible == 'No') echo "checked"; ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Settings" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>

<?php
// Handle form submission
if (isset($_POST['submit'])) {
    // Get the values from the form
    $header_text = mysqli_real_escape_string($conn, $_POST['header_text']);
    $is_visible = $_POST['is_visible'];

    // Update the settings in the database
    $sql = "UPDATE tbl_offer_settings SET header_text='$header_text', is_visible='$is_visible' WHERE id=1";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['update'] = "Offer section visibility updated successfully!";
        header('location: manage-offer-settings.php');
    } else {
        $_SESSION['update'] = "Failed to update offer section visibility.";
        header('location: manage-offer-settings.php');
    }
}
?>
