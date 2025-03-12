<?php include('../config/constants.php'); ?>

<html>
    <head>
        <title>Admin Login System</title>
        <link rel="stylesheet" href="../css/admin.css">
        <style>
            /* Login Form Styles */
            body {
                font-family: 'Arial', sans-serif;
                background: #f4f7fc;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .login {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
            }

            .login h1 {
                text-align: center;
                color: #333;
                margin-bottom: 30px;
            }

            .login form {
                display: flex;
                flex-direction: column;
            }

            .login input[type="text"], .login input[type="password"] {
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            .login input[type="submit"] {
                padding: 12px;
                background: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                transition: background 0.3s ease;
            }

            .login input[type="submit"]:hover {
                background: #0056b3;
            }

            .login .link {
                text-align: center;
                margin-top: 20px;
            }

            .login .link a {
                color: #007bff;
                text-decoration: none;
                font-weight: bold;
            }

            .login .link a:hover {
                text-decoration: underline;
            }

            .error, .success {
                text-align: center;
                padding: 10px;
                border-radius: 5px;
                margin: 10px 0;
            }

            .error {
                background: #f44336;
                color: white;
            }

            .success {
                background: #4CAF50;
                color: white;
            }
        </style>
    </head>

    <body>
        <div class="login">
            <h1>Admin Login</h1>

            <?php 
                // Display success or error messages
                if(isset($_SESSION['login'])) {
                    echo $_SESSION['login'];
                    unset($_SESSION['login']);
                }
                if(isset($_SESSION['no-login-message'])) {
                    echo $_SESSION['no-login-message'];
                    unset($_SESSION['no-login-message']);
                }
            ?>

            <!-- Login Form Starts -->
            <form action="" method="POST" id="loginForm" onsubmit="return validateForm()">
                <label>Username:</label>
                <input type="text" name="username" id="username" placeholder="Enter Username" required>

                <label>Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter Password" required>

                <input type="submit" name="submit" value="Login">


            </form>
            <!-- Login Form Ends -->

        </div>

        <script>
            // Form Validation Function
            function validateForm() {
                var username = document.getElementById("username").value;
                var password = document.getElementById("password").value;

                if (username == "" || password == "") {
                    alert("Both fields are required.");
                    return false;
                }

                return true;
            }

            // Optional: You can add JS animations or alerts here
        </script>
    </body>
</html>

<?php
// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize and hash password
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = md5($password); // It's recommended to use bcrypt or Argon2 for password hashing in modern PHP.

    // SQL to check if user exists
    $sql = "SELECT * FROM tbl_admin WHERE username='$username' AND password='$hashed_password'";
    $res = mysqli_query($conn, $sql);

    // Check if user exists
    if (mysqli_num_rows($res) == 1) {
        // User exists, set session variables
        $row = mysqli_fetch_assoc($res);
        $_SESSION['login'] = "<div class='success'>Login successful.</div>";
        $_SESSION['user'] = $row['username'];

        // Redirect to dashboard
        header('location:'.SITEURL.'admin/');
    } else {
        // Invalid login credentials
        $_SESSION['login'] = "<div class='error text-center'>Username or password did not match.</div>";
        header('location:'.SITEURL.'admin/login.php');
    }
}
?>
