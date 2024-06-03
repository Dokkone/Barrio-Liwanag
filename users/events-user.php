<?php
//session start
session_start();
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit();
}

// Include config file to establish database connection
require_once "../config.php";

// Retrieve events data with images from the database
$sql = "SELECT * FROM events";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="../css/mainstyle.css">
    <link href="../images/logo.png" rel="icon">
</head>
<body>
    <header class="header">
        <h1 class="logo"><a href="../home.php"><img src="../images/logo-with-text.png" alt="barrio-liwanag-logo-with-text" width="200" height="75"></a></h1>
        <ul class="main-nav">
            <li><a href="../home.php">Home</a></li>
            <li><a href="events-user.php">Events</a></li>
            <li><a href="../home.php#contact_us">Contact Us</a></li>
            <li><a href="../home.php#about_us">About Us</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
    </header>

    <section class="parallax-events">
        <div class="event-list-container">
            <br>
                <?php
                //code to summarize string
                function truncate_string ($string, $maxlength, $extension) {
    
                    // Set the replacement for the "string break" in the wordwrap function
                    $cutmarker = "**cut_here**";
                
                    // Checking if the given string is longer than $maxlength
                    if (strlen($string) > $maxlength) {
                
                        // Using wordwrap() to set the cutmarker
                        // NOTE: wordwrap (PHP 4 >= 4.0.2, PHP 5)
                        $string = wordwrap($string, $maxlength, $cutmarker);
                
                        // Exploding the string at the cutmarker, set by wordwrap()
                        $string = explode($cutmarker, $string);
                
                        // Adding $extension to the first value of the array $string, returned by explode()
                        $string = $string[0] . $extension;
                    }
                
                    // returning $string
                    return $string;
                
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<button class="event-list" onclick="toggleEventDetails(' . $row['id'] . ')">';
                    echo '<div class="event-details" id="event-details-' . $row['id'] . '">';
                    
                    //Display an image next to text, can be optimized
                    if(!empty($row['event_images_' . 1])){
                        $image_data = base64_encode($row['event_images_' . 1]);
                        $image_src = 'data:image/jpeg;base64,' . $image_data; 
                        echo '<img class="display-image" src="' . $image_src . '">';
                        }

                    $eventurl = "events-view.php?eventid=" . $row['id']; // Read more code
                    echo '<strong><b><h1>' . $row['eventname'] . '</strong></b></h1>';
                    if($row['start_date'] ==$row['end_date']){  //for single day events
                        echo '<p>' . $row['start_date'] . '</p>';
                    }else{                              //for multiple day events
                        echo '<p>' . $row['start_date'] . ' - '. $row['end_date'] . '</p>';
                    }
                    //echo '<h4>' . $row['description'] . '</h4>';        
                    echo '<h4>' . truncate_string($row['description'], 200, ' ... ') . // change summary length here 
                    '<a href="' . $eventurl . '" class="read-more-link"><b>Read More</b></a></h4>'; 
                    echo '</div>';
                    echo '<div class="event-images" id="images-' . $row['id'] . '">';
                    echo '<center>';
                    for ($i = 1; $i <= 4; $i++) {
                        if(!empty($row['event_images_' . $i])){
                        $image_data = base64_encode($row['event_images_' . $i]);
                        $image_src = 'data:image/jpeg;base64,' . $image_data; 
                        echo '<img class="event-image" src="' . $image_src . '">';
                        }
                    }
                    echo '</div> </center>';
                    echo '</button>';

                    
                }
                ?>
                <br>
            </center>
        </div>
    </section>

    <footer>
        <center>
            <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>

    <script>
    function toggleEventDetails(eventId) {
        var eventDetails = document.getElementById('event-details-' + eventId);
        var eventImages = document.getElementById('images-' + eventId);
        if (eventDetails.style.display === 'block') {
            eventDetails.style.display = 'none';
            eventImages.style.display = 'block';
        } else {
            eventDetails.style.display = 'block';
            eventImages.style.display = 'none';
        }
    }
</script>

</body>
</html>
