
<br><br><br><br><br><br>

<br><br><br><br><br><br>
<!-- footer Section Starts Here -->
<section class="footer">
    <div class="container">
        <div class="footer-left">
            <h4>Roadside Cafe</h4>
            <p>Kamrabad, Sarishabari</p>
            <p>01772457415</p>
            <p><a href="mailto:roadsidecafe@gmail.com">roadsidecafe@gmail.com</a></p>
        </div>

        <div class="footer-middle">
            <a href="https://www.facebook.com/">
                <img src="https://img.icons8.com/fluent/50/000000/facebook-new.png" alt="Facebook" />
            </a>
            <p>Social Media </p>
        </div>

        <div class="footer-right">
            <h4>Our Location</h4>
            <!-- Embed Google Maps iframe for the address  -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3622.41322086287!2d89.84456427505435!3d24.781300148421224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fd9b006b6bdd4d%3A0xd7c5f9019f31491!2sRoad%20Side%20Cafe!5e0!3m2!1sen!2sbd!4v1740668135064!5m2!1sen!2sbd" 
                width="300" 
                height="200" 
                frameborder="0" 
                style="border:0;" 
                allowfullscreen="">
            </iframe>

            <p>View on Google Maps</p>
        </div>
    </div>

    <div class="containerres">
            <p >All rights reserved by Roadside Cafe</p>
        </div>


</section>
<!-- footer Section Ends Here -->

</body>
</html>

<!-- Add the following CSS to style your footer -->
<style>

.containerres {
    display: flex;
    justify-content: center;  /* Horizontally centers the content */
    align-items: center;      /* Vertically centers the content */
    height: 100px;            /* Set height of the container */
    text-align: center;       /* Ensures the text inside the p tag is also centered */
    color:beige;

}

    /* General styling */
    .footer {
        background-color: #222;
        color: white;
        padding: 30px 0;
    }

    .footer .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer-left,
    .footer-middle,
    .footer-right {
        flex: 1;
        text-align: center;
        padding: 10px;
    }

    .footer-left h4 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #ff9900;
    }

    .footer-left p {
        margin: 5px 0;
        font-size: 16px;
    }

    .footer-left a {
        color: #ff9900;
        text-decoration: none;
    }

    .footer-left a:hover {
        text-decoration: underline;
    }

    .footer-middle img,
    .footer-right img {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
    }

    .footer-right p {
        font-size: 14px;
        margin-top: 5px;
    }

    .footer-right a {
        color: #ff9900;
        text-decoration: none;
    }

    .footer-right a:hover {
        text-decoration: underline;
    }

    /* Responsive styling for mobile devices */
    @media screen and (max-width: 768px) {
        .footer .container {
            flex-direction: column;
            align-items: center;
        }

        .footer-left,
        .footer-middle,
        .footer-right {
            flex: none;
            text-align: center;
            margin-bottom: 20px;
        }

        .footer-left h4 {
            font-size: 20px;
        }

        .footer-left p,
        .footer-right p {
            font-size: 14px;
        }
    }
</style>

<!-- Optional JavaScript for smooth transitions and animations -->
<script>
    // Add some effects on hover for the social icons and Google Map link
    const socialIcons = document.querySelectorAll('.footer a');
    socialIcons.forEach(icon => {
        icon.addEventListener('mouseover', function() {
            icon.style.transform = 'scale(1.1)';
            icon.style.transition = 'transform 0.3s ease';
        });

        icon.addEventListener('mouseout', function() {
            icon.style.transform = 'scale(1)';
        });
    });
</script>
