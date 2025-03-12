<?php
// Start session

include('partials-front/menu.php');

// Check if the blog id is provided in the URL
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    // SQL query to fetch the specific blog
    $sql = "SELECT * FROM tbl_blog WHERE id = '$blog_id' AND status = 'Active'";
    $res = mysqli_query($conn, $sql);

    // Check if the blog exists
    if (mysqli_num_rows($res) > 0) {
        // Get the blog details
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $description = $row['description'];
        $image_name = $row['image_name'];
      //  $content = $row['content'];
        $created_at = $row['created_at'];
    } else {
        // Redirect if the blog is not found or not active
        header('location: index.php');
        exit();
    }
} else {
    // Redirect if no blog id is passed in the URL
    header('location: index.php');
    exit();
}

?>

<section class="view-blog">
    <div class="container">
        <div class="blog-content">
            <h2 class="text-center"><?php echo $title; ?></h2>
            <p class="blog-date">Posted on: <?php echo $created_at; ?></p>

            <div class="blog-main">
                <div class="blog-description">
                    <p><?php echo $description; ?></p>
                </div>

                <div class="blog-image">
                    <?php
                    // Check if the blog has an image
                    if ($image_name != "") {
                        echo "<img src='".SITEURL."images/blog/".$image_name."' alt='$title' class='img-responsive img-curve'>";
                    }
                    ?>
                </div>
            </div>


        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>

<!-- CSS for Blog Page -->
<style>
    .view-blog .container {
        padding: 40px 20px;
    }

    .view-blog .blog-content {
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }

    .view-blog h2 {
        color: #4CAF50;
        font-size: 36px;
        margin-bottom: 20px;
    }

    .view-blog .blog-date {
        font-size: 16px;
        color: #888;
        margin-bottom: 20px;
    }

    .view-blog .blog-main {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .view-blog .blog-description {
        flex: 1;
        font-size: 18px;
        color: #555;
        margin-right: 20px;
    }

    .view-blog .blog-image {
        flex: 1;
    }

    .view-blog img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        margin-bottom: 30px;
        border-radius: 8px;
    }

    .view-blog .blog-details {
        font-size: 16px;
        color: #333;
        line-height: 1.8;
    }

    .view-blog .btn-back {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        text-decoration: none;
    }

    .view-blog .btn-back:hover {
        background-color: #45a049;
    }
</style>
