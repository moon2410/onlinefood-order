<?php 
    // Start Session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Create Constants to Store Non Repeating Values
    define('SITEURL', 'http://localhost/onlinefood-order/');
    define('LOCALHOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'onlinefoodorder');
    
    // Establish the database connection
    $conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD);
    
    // Check if the connection is successful
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    
    // Select the database
    $db_select = mysqli_select_db($conn, DB_NAME);
    if (!$db_select) {
        die('Database selection failed: ' . mysqli_error($conn));
    }
?>
