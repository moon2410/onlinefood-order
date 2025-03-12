<?php
include('partials-front/menu.php');
$error = ''; // Error message variable

// Check if form is submitted
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate that phone is 11 digits
    if (strlen($phone) != 11) {
        $error = "Phone number must be 11 digits.";
    } else {
        // Check if phone number already exists
        $sql_check = "SELECT * FROM tbl_user WHERE phone='$phone'";
        $res_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($res_check) > 0) {
            $error = "Phone number already exists.";
        } else {
            // Check if passwords match
            if ($password != $confirm_password) {
                $error = "Passwords do not match.";
            } else {
                // Encrypt the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert into database
                $sql = "INSERT INTO tbl_user (name, phone, email, address, password) VALUES ('$name', '$phone', '$email', '$address', '$hashed_password')";
                
                if (mysqli_query($conn, $sql)) {
                    header('location: login.php');
                } else {
                    $error = "Failed to register. Try again.";
                }
            }
        }
    }
}
?>

<!-- HTML Form for Registration -->
<form action="" method="POST" onsubmit="return validateForm()">
    <h2>Register</h2>
    <div>
        <label>Name:</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label>Phone:</label>
        <input type="text" name="phone" id="phone" required>
        <span id="phone-error"></span>
    </div>
    <div>
        <label>Email (Optional):</label>
        <input type="email" name="email">
    </div>
    <div>
        <label>Address:</label>
        <input type="text" name="address" required>
    </div>
    <div>
        <label>Password:</label>
        <input type="password" name="password" id="password" required>
    </div>
    <div>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm-password" required>
    </div>
    <input type="submit" name="submit" value="Register">
    <div style="color: red;"><?php echo $error; ?></div>
</form>

<script>
    // JS Validation
    function validateForm() {
        var phone = document.getElementById('phone').value;
        var phoneError = document.getElementById('phone-error');
        var phoneRegex = /^[0-9]{11}$/;
        
        if (!phoneRegex.test(phone)) {
            phoneError.innerText = "Phone number must be 11 digits.";
            return false;
        } else {
            phoneError.innerText = "";
            return true;
        }
    }
</script>
