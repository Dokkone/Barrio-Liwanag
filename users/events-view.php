<?php
   //session start
   session_start();
   /* Database credentials. Assuming you are running MySQL
    server with default setting (user 'root' with no password) */
    $dbHost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "barrio-liwanag"; //change niyo ito according sa name ng database niyo

    $conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
    
?>


 <!-- Set variables here -->
 <?php
    
    $_SESSION['eventid'] = $_GET['eventid'];                                                                             //temporary 
    if (empty($_SESSION['eventid'] )){
        header('Location: events-user.php');
        die();
    }
    $queryname = $_SESSION['eventid'];
    // Using prepared statement to prevent SQL injection
    $sql = "SELECT * FROM events WHERE id = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind the parameter to the prepared statement
    $stmt->bind_param("s", $queryname);
    
    // Execute the prepared statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    // Check if the query was successful
    if ($result) {
        // Loop through each row in the result set
        while ($row = mysqli_fetch_assoc($result)) {
            // Each event found will go through here
            $eventname = $row['eventname'];                                                                 //CHANGE THESE VARIABLES
            $eventdate = $row['start_date'];
            $eventdesc = $row['description'];
            $event_enddate = $row['end_date'];
        }
        // Free the result set
        mysqli_free_result($result);
    } else {
        // If the query fails, handle the error
        echo "Error: " . mysqli_error($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="../css/mainstyle.css">
<link href="../images/logo.png" rel="icon">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $eventname; ?></title>
    <!-- bootstrap carousel -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    
    .carousel-control-prev-icon { /*carousel buttons color, change fill='%23fff' fffwith hex */ 
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000000' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E") !important;
    }
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000000' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E") !important;
    }

    .eventcontainer {
        display: flex;
        flex-wrap: wrap;
        padding: 90px 90px;
    }
    
    .eventcontainer-left,
    .eventcontainer-right {
        width: 100%;
        padding: 20px;
    }
    
    @media (min-width: 768px) {
    .eventcontainer-left {
        width: 60%;
        }
    
    .eventcontainer-right {
        width: 40%;
        border-radius: 25px;
        background: #e0e0e0;
        box-shadow:  5px 5px 10px #B7BE9E,
                    -5px -5px 10px #B7BE9E;
        }

    }

    /*Lightbox */
    .row > .column {
    padding: 0 8px;
    }

    .row:after {
    content: "";
    display: table;
    clear: both;
    }

    /* Create four equal columns that floats next to eachother */
    .column {
    float: left;
    width: 25%;
    }

    /* The Modal (background) */
    .modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: black;
    z-index: 9999; /* Ensure the modal appears on top */
    }

    /* Modal Content */
    .modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    width: 90%;
    max-width: 1200px;
    }

    /* The Close Button */
    .close {
    color: white;
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 35px;
    font-weight: bold;
    }

    .close:hover,
    .close:focus {
    color: #999;
    text-decoration: none;
    cursor: pointer;
    }

    /* Hide the slides by default */
    .mySlides {
    display: none;
    }

    /* Next & previous buttons */
    .prev,
    .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    width: auto;
    padding: 16px;
    margin-top: -50px;
    color: black;
    font-weight: bold;
    font-size: 20px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    -webkit-user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
    right: 0;
    border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
    background-color: rgba(0, 0, 0, 0.8);
    }

    /* Number text (1/3 etc) */
    .numbertext {
    color: #f2f2f2;
    font-size: 12px;
    padding: 8px 12px;
    position: absolute;
    top: 0;
    }

    /* Caption text */
    .caption-container {
    text-align: center;
    background-color: black;
    padding: 2px 16px;
    color: white;
    }

    img.demo {
    opacity: 0.6;
    }

    .active,
    .demo:hover {
    opacity: 1;
    }

    img.hover-shadow {
    transition: 0.3s;
    }

    .hover-shadow:hover {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    </style>

</head>
<link rel="stylesheet" href="../css/mainstyle.css">
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
        <div class="eventcontainer">          
            <!-- Left Container -->
                <div class="eventcontainer-left">
                    <!-- Go back button -->
                    <a href="javascript:history.back()" class="btn btn-secondary" style="margin-right: 10px; margin-bottom: 10px;">Go back</a>
                    <strong style="font-size: 40px;"><?php echo $eventname; ?></strong> <!--Event Name -->
                    <h4> 
                        <?php 
                        // Determine event status
                        $status = '';
                        $current_date = date('Y-m-d');
                        if ($eventdate <= $current_date && $current_date <= $event_enddate) {
                            $status = 'ONGOING';
                            $color = 'green'; // ONGOING status color
                        } elseif ($eventdate > $current_date) {
                            $status = 'UPCOMING';
                            $color = 'blue'; // UPCOMING status color
                        } else {
                            $status = 'CLOSED';
                            $color = 'red'; // CLOSED status color
                        }

                        // Output event dates with status
                        if ($eventdate == $event_enddate) { // for single day events
                            echo $eventdate . " - <span style='color: $color;'>$status</span>";
                        } else { // for multiple day events
                            echo $eventdate . " - " . $event_enddate . " <span style='color: $color;'>($status)</span>";
                        }
                        ?>
                    </h4>
                    <p style="padding-right: 20px; text-align:justify;"> 
                        <?php
                         $eventdesc = nl2br($eventdesc); //show paragraphs
                         echo $eventdesc; ?>           
                    </p>
                </div>

            <!-- Right Container -->
            <div class="eventcontainer-right">
                <!-- Carousel -->
                <div id="demo" class="carousel slide" data-bs-ride="carousel">
                      <!-- The slideshow/carousel -->
                    <div class="carousel-inner">
                        <?php
                        // Using prepared statement to prevent SQL injection
                        $sql = "SELECT * FROM events WHERE id = ?";
                        
                        // Prepare the SQL statement
                        $stmt = $conn->prepare($sql);
                        
                        // Bind the parameter to the prepared statement
                        $stmt->bind_param("s", $queryname);
                        
                        // Execute the prepared statement
                        $stmt->execute();
                        
                        // Get the result
                        $result = $stmt->get_result();
                        while ($row = mysqli_fetch_assoc($result)) {
                        $imagecount = 0;
                        for ($i = 1; $i <= 4; $i++) {
                                if(!empty($row['event_images_' . $i])){
                                    $imagecount++;
                                    $image_data = base64_encode($row['event_images_' . $i]);
                                    $image_src = 'data:image/jpeg;base64,' . $image_data; 
                                    if($imagecount == 1){ //set the first image found active
                                        echo '<div class="carousel-item active"  style = "max-height:560px;">
                                            <img class="d-block" onclick="openModal();currentSlide('.$i.')" style="width:100%" src="' . $image_src . '">
                                            </div>';
                                        $image_src_lightbox[$imagecount] = $image_src;  
                                    }else{
                                        echo '<div class="carousel-item"  style = "max-height:560px;  ">
                                            <img class="d-block" onclick="openModal();currentSlide('.$i.')" style="width:100%;  " src="' . $image_src . '">
                                            </div>';
                                        $image_src_lightbox[$imagecount] = $image_src;  
                                    }
                                }
                            }
                        }
                        if( $imagecount== 0){ //incase 0 images were uploaded, default
                            echo '<img src="../images/cover-pic-fb.jpg" alt="Barrio Liwanag Cover" width="100%">';
                        }

                        //Lightbox
                        echo' <div id="myModal" class="modal">
                        <span class="close cursor" onclick="closeModal()">&times;</span>
                        <div class="modal-content">';
                        
                        for ($j = 1; $j <= $imagecount; $j++) {
                            echo "<div class='mySlides'>
                            <div class='numbertext'>$j / $imagecount</div>
                            <img src='" . $image_src_lightbox[$j] . "' style='width:100%'>
                            </div>";
                        }
                        //next and previous
                        if($imagecount >1){
                        echo'
                          <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                          <a class="next" onclick="plusSlides(1)">&#10095;</a>
                        </div>
                        ';
                        }
                        //End of Lightbox
                        ?>
                        
                    </div>
                     <!-- Left and right controls/icons for carousel-->
                     <?php if($imagecount >1){
                    echo '<button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>';
                    }?>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <center>
        <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>
    
    <script> //lightbox script
    function openModal() {
    document.getElementById("myModal").style.display = "block";
    }

    function closeModal() {
    document.getElementById("myModal").style.display = "none";
    }

    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
    showSlides(slideIndex += n);
    }

    function currentSlide(n) {
    showSlides(slideIndex = n);
    }

    function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    var captionText = document.getElementById("caption");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    captionText.innerHTML = dots[slideIndex-1].alt;
    }
    </script>
        
</body>
</html>