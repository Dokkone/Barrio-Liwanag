<?php
   //session start
session_start();
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="../css/mainstyle.css">
<link href="../images/logo.png" rel="icon">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

</head>
<body>
    <header class="header">
		<h1 class="logo"><a href="../home.php"><img src="../images/logo-with-text.png" alt="barrio-liwanag-logo-with-text" width="200" height="75"></a></h1>
            <ul class="main-nav">
                <li><a href="../home.php">Home</a></li>
                <li><a href="events-user.php">Events</a></li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
	</header> 
    
    <section>
        <div class="container">
            <!-- Left Container -->
            <div class="container-left">
                <strong style="font-size: 40px;">Contact Us mga Ka-BARRIO!</strong>
                <p style="padding-right: 20px; text-align:justify;">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras convallis turpis id odio convallis commodo. 
                    Maecenas eget tortor leo. Sed elementum, arcu sed dictum iaculis, ante dui commodo enim, 
                    id tincidunt dui nulla non massa. Nunc ac ex quis orci tempus molestie id nec leo. Aliquam erat volutpat. 
                    Pellentesque sed consectetur urna, id consectetur elit. Donec placerat elit nec lobortis fringilla. Morbi
                </p>
                <strong style="padding-right: 20px;"><p style="padding-right: 50px;">Social Media Account</p></strong>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                <center>
                    <div class="social-media-container">
                        <a href="https://www.facebook.com/barrioliwanag" class="social-media-link" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
                        <!-- <a href="https://www.twitter.com/" class="social-media-link" target="_blank"><i class="fab fa-twitter"></i> Twitter</a>
                        <a href="https://www.instagram.com/" class="social-media-link" target="_blank"><i class="fab fa-instagram"></i> Instagram </a> -->
                    </div>
                </center>
            </div>
            <!-- Right Container -->
            <div class="container-right">
                <img src="../images/cover-pic-fb.jpg" alt="Barrio Liwanag Cover" width="100%">
                <center>
                    <strong style="font-size: 20px;"><h1>Barrio-Liwanag</h1></strong>
                </center>
            </div>
        </div>
    </section>

    <footer>
        <center>
        <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>
</body>
</html>