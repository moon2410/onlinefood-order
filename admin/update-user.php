<link rel="stylesheet" href="../css/admin.css">
<?php 
include('../config/constants.php'); 
//include('partials/menu.php'); 
?>
<?php 


    // Start Session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the ID is provided
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch user data from database
        $sql = "SELECT * FROM tbl_user WHERE id=$id";
        $res = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($res);

        // Check if the user exists
        if (!$user) {
            $_SESSION['user-not-found'] = "<div class='error'>User not found.</div>";
            header('location: manage-user.php');
            exit();
        }
    } else {
        header('location: manage-user.php');
        exit();
    }

    // Handle the update form submission
    if (isset($_POST['update'])) {
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        // Update user data in the database
        $sql = "UPDATE tbl_user SET 
                name = '$full_name',
                phone = '$phone',
                email = '$email',
                address = '$address'
                WHERE id = $id";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['update'] = "<div class='success'>User updated successfully.</div>";
            header('location: manage-user.php');
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to update user. Try again.</div>";
        }
    }
?>

<!-- Update User Form -->
<div class="main-content">
    <div class="wrapper">
        <h1>Update User</h1>
        <br><br>

        <form action="" method="POST">
            <table class="tbl-full">
                <tr>
                    <td>Full Name:</td>
                    <td><input type="text" name="full_name" value="<?php echo $user['name']; ?>" required /></td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td><input type="text" name="phone" value="<?php echo $user['phone']; ?>" required /></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" value="<?php echo $user['email']; ?>" /></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address" value="<?php echo $user['address']; ?>" required /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="update" value="Update User" class="btn-primary" />
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <br><br>
<a href="manage-user.php" class="btn btn-primary">Back to Manage Users</a>
<br><br><br>
</div>

<?php include('partials/footer.php'); ?>
