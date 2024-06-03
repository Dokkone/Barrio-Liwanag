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
    <title>About Us</title>

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
            <center>
                <div>
                    <img src="../images/cover-pic-fb.jpg" alt="Barrio Liwanag Cover" width="40%">
                <!-- </div> -->
                <p style="padding-right: 20px; text-align:justify;">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras convallis turpis id odio convallis commodo. 
                    Maecenas eget tortor leo. Sed elementum, arcu sed dictum iaculis, ante dui commodo enim, 
                    id tincidunt dui nulla non massa. Nunc ac ex quis orci tempus molestie id nec leo. Aliquam erat volutpat. 
                    Pellentesque sed consectetur urna, id consectetur elit. Donec placerat elit nec lobortis fringilla. Morbi
                </p>
            </center>
        </div>
    </section>

    <footer>
        <center>
        <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>
</body>
</html>