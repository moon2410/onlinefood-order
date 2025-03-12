<?php
// Start session
session_start();
include('partials-front/header.php');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('location: login.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user']['id'];
$success_message = '';
$error_message = '';

// Handle form submission for changing password
if (isset($_POST['update_password'])) {
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if new password matches confirm password
    if ($new_password != $confirm_password) {
        $error_message = "New password and confirm password do not match.";
    } else {
        // Fetch current password from the database
        $sql = "SELECT password FROM tbl_user WHERE id='$user_id' LIMIT 1";
        $res = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($res);

        // Check if old password is correct
        if (password_verify($old_password, $user['password'])) {
            // Encrypt the new password
            $new_password_encrypted = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in the database
            $update_sql = "UPDATE tbl_user SET password='$new_password_encrypted' WHERE id='$user_id'";
            if (mysqli_query($conn, $update_sql)) {
                $success_message = "Password updated successfully!";
            } else {
                $error_message = "Failed to update password. Try again.";
            }
        } else {
            $error_message = "Incorrect old password.";
        }
    }
}
?>

<!-- HTML for Change Password Page -->
<div class="main-content">
    <div class="wrapper">
        <h1>Change Password</h1>

        <!-- Display success or error message -->
        <?php if ($success_message) { ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if ($error_message) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Password Change Form -->
        <form action="change-password.php" method="POST">
            <div>
                <label for="old_password">Old Password:</label>
                <input type="password" name="old_password" required>
            </div>
            <div>
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div>
                <input type="submit" name="update_password" value="Update Password" class="btn btn-success">
            </div>
        </form>

        <a href="profile.php" class="btn btn-primary">Back to Profile</a>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>

<!-- CSS for Password Change Page -->
<style>
    .main-content {
        margin: 20px;
    }

    .wrapper {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn:hover {
        background-color: #45a049;
    }

    .btn-primary {
        background-color: #007BFF;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .success, .error {
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
    }

    .success {
        background-color: #4CAF50;
        color: white;
    }

    .error {
        background-color: #f44336;
        color: white;
    }
</style>
