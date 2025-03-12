<?php include('partials/menu.php'); ?>

<!-- Main Content Section Starts -->
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Users</h1>

        <br />

        <?php 
            if(isset($_SESSION['add']))
            {
                echo $_SESSION['add']; // Displaying Session Message
                unset($_SESSION['add']); // Removing Session Message
            }

            if(isset($_SESSION['delete']))
            {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
            
            if(isset($_SESSION['update']))
            {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }

            if(isset($_SESSION['user-not-found']))
            {
                echo $_SESSION['user-not-found'];
                unset($_SESSION['user-not-found']);
            }
        ?>

        <br><br><br>

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>

            <?php 
                // Query to get all users from the tbl_user
                $sql = "SELECT * FROM tbl_user";
                // Execute the query
                $res = mysqli_query($conn, $sql);

                // Check whether the query is executed successfully
                if($res == TRUE)
                {
                    // Count the number of rows
                    $count = mysqli_num_rows($res); // Get the total number of rows

                    $sn = 1; // Serial Number

                    // Check if we have any users
                    if($count > 0)
                    {
                        // Fetch all the rows
                        while($rows = mysqli_fetch_assoc($res))
                        {
                            // Get individual user details
                            $id = $rows['id'];
                            $full_name = $rows['name'];
                            $phone = $rows['phone'];
                            $email = $rows['email'];
                            $address = $rows['address'];

                            // Display the values in the table
                            ?>
                            
                            <tr>
                                <td><?php echo $sn++; ?>. </td>
                                <td><?php echo $full_name; ?></td>
                                <td><?php echo $phone; ?></td>
                                <td><?php echo $email ? $email : 'Not Provided'; ?></td>
                                <td><?php echo $address; ?></td>
                                <td>
                                    <!-- Actions: Update and Delete User -->
                                    <a href="<?php echo SITEURL; ?>admin/update-user.php?id=<?php echo $id; ?>" class="btn-secondary">Update User</a>
                                    <a href="<?php echo SITEURL; ?>admin/delete-user.php?id=<?php echo $id; ?>" class="btn-danger">Delete User</a>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                    else
                    {
                        // No users found
                        echo "<tr><td colspan='6' class='error'>No users found</td></tr>";
                    }
                }
            ?>

        </table>

    </div>
</div>
<!-- Main Content Section Ends -->

<?php include('partials/footer.php'); ?>
