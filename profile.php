<?php
// Include database connection
include('partials-front/header.php');

// Check if user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('location: login.php'); // Redirect to login page if not logged in
}

// Get user ID from session
$user_id = $_SESSION['user']['id'];

// Fetch user details from database
$sql = "SELECT * FROM tbl_user WHERE id='$user_id' LIMIT 1";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);

// Update user information when the form is submitted
if (isset($_POST['update'])) {
    // Get form values
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // SQL query to update user data
    $update_sql = "UPDATE tbl_user SET name='$name', phone='$phone', email='$email', address='$address', updated_at=NOW() WHERE id='$user_id'";
    if (mysqli_query($conn, $update_sql)) {
        // Update session data
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['address'] = $address;

        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Failed to update profile. Try again.";
    }
}
?>

<!-- HTML Structure for Profile Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="path_to_your_css_file.css"> <!-- Link your CSS -->
</head>
<body>

<section class="user-profile">
    <div class="container">
        <h2 class="text-center">Welcome, <?php echo $user['name']; ?></h2>

    </div>
</section>
    <div class="container">
        <h2>Profile Details</h2>

        <!-- Display success or error message -->
        <?php if (isset($success_message)) { ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Display User Information -->
        <div class="profile-details">
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email'] ? $user['email'] : 'Not provided'; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
            
            <!-- Edit Button -->
             <br>
            <a href="#editProfile" class="btn btn-primary" id="editBtn">Edit Profile</a>
        </div>

        <!-- Edit Profile Form -->
        <div id="editProfile" style="display: none;">
            <h3>Edit Your Profile</h3>
            <form action="" method="POST">
                <div>
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
                </div>
                <div>
                    <label>Phone:</label>
                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>
                <div>
                    <label>Email (Optional):</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>">
                </div>
                <div>
                    <label>Address:</label>
                    <input type="text" name="address" value="<?php echo $user['address']; ?>" required>
                </div>
                <div>
                    <input type="submit" name="update" value="Update Profile" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
<!-- Add Change Password Button -->
<a href="change-password.php" class="btn btn-warning">Change Password</a>

    <!-- JS to Toggle Edit Profile Form -->
    <script>
        document.getElementById('editBtn').addEventListener('click', function() {
            var editForm = document.getElementById('editProfile');
            if (editForm.style.display === 'none') {
                editForm.style.display = 'block';
            } else {
                editForm.style.display = 'none';
            }
        });
    </script>
</body>
</html>
