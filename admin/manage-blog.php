<?php

include('partials/menu.php');


// Fetch all blogs
$sql = "SELECT * FROM tbl_blog";
$res = mysqli_query($conn, $sql);

?>
<!-- Manage Blogs Section -->
<section class="manage-blogs">
    <div class="container">
        <h2 class="text-center">Manage Blogs</h2>
        <a href="add-blog.php" class="btn btn-success">Add New Blog</a>
        <a href="manage-blog-settings.php" class="btn btn-success">Settings </a>
        <table class="tbl-full">
            <tr>
                <th>Blog ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='update-blog.php?id={$row['id']}' class='btn btn-primary'>Edit</a>
                        <a href='delete-blog.php?id={$row['id']}' class='btn btn-danger'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</section>
<?php include('partials/footer.php'); ?>
