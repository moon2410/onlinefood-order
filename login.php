<?php
include('partials-front/menu.php');
$error = ''; // Error message variable

// Check if form is submitted
if (isset($_POST['submit'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // SQL Query to fetch user by phone number
    $sql = "SELECT * FROM tbl_user WHERE phone='$phone'";
    $res = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($res) == 1) {
        // User found
        $row = mysqli_fetch_assoc($res);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session for logged-in user
            $_SESSION['user'] = $row;
            header('location: user.php');
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Phone number not found.";
    }
}
?>

<!-- HTML Form for Login -->
<div class="login-container">
    <form action="" method="POST" onsubmit="return validateLoginForm()">
        <h2>Login</h2>

        <div class="form-group">
            <label for="login-phone">Phone:</label>
            <input type="text" name="phone" id="login-phone" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="login-password">Password:</label>
            <input type="password" name="password" id="login-password" class="form-control" required>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" value="Login" class="btn btn-primary">
        </div>

        <div class="error-message" style="color: red;"><?php echo $error; ?></div>
        
        <div class="link">
            Don't Have an Account? <a href="register.php">Click Register now</a>
        </div>

        <div class="link">
            For Admin? <a href="admin/login.php">Click Login now</a>
        </div>
    </form>
</div>

<script>
    // JS Validation for Login Form
    function validateLoginForm() {
        var phone = document.getElementById('login-phone').value;
        var password = document.getElementById('login-password').value;
        
        // Validate phone number (ensure it's a valid phone format)
        var phonePattern = /^[0-9]{11}$/; // Assuming 10 digits for phone number
        if (!phonePattern.test(phone)) {
            alert("Please enter a valid 11-digit phone number.");
            return false;
        }

        // Ensure password is not empty
        if (password === "") {
            alert("Password is required.");
            return false;
        }
        
        return true;
    }
</script>

<!-- CSS for the login page -->
<style>


    h2 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-size: 14px;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .form-control:focus {
        outline: none;
        border-color: #4CAF50;
    }

    .btn {
        width: 100%;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #45a049;
    }

    .error-message {
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }

    .link {
        text-align: center;
        font-size: 14px;
        margin-top: 15px;
    }

    .link a {
        color: #4CAF50;
        text-decoration: none;
    }

    .link a:hover {
        text-decoration: underline;
    }
</style>

<?php include('partials-front/footer.php'); ?>
