<?php
//session start
session_start();
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit();
}

// Include config file
require_once "../config.php";

// Check if event ID is provided in the URL
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    header('Location: content_dashboard.php');
    exit();
}

$event_id = $_GET['event_id'];

// Initialize variables to store event details
$eventname = $description = $start_date = $end_date = '';
$event_images = [];

// Retrieve existing event details from the database
$sql = "SELECT eventname, description, start_date, end_date, event_images_1, event_images_2, event_images_3, event_images_4 FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->bind_result($eventname, $description, $start_date, $end_date, $event_images[0], $event_images[1], $event_images[2], $event_images[3]);
$stmt->fetch();
$stmt->close();

// Process form submission to update event details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $eventname = $_POST['eventname'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Prepare and execute SQL statement to update event details
    $sql = "UPDATE events SET eventname = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $eventname, $description, $start_date, $end_date, $event_id);
    $stmt->execute();

    // After successfully updating an event
    include 'logging.php';
    $newUserId = $conn->insert_id; // Get the ID of the newly created user
    logUserAction('EVENT update', $_SESSION['id'], $_SESSION['username'], $event_id, $eventname);

    $stmt->close();

    // Process image uploads and update event images in the database
    for ($i = 1; $i <= 4; $i++) {
        // Check if an image was uploaded for the current field
        if ($_FILES['image'.$i]['size'] > 0) {
            // Get the image data
            $image_tmp = $_FILES['image'.$i]['tmp_name'];
            $image_data = file_get_contents($image_tmp);

            // Prepare and execute SQL statement to update the event image
            $sql = "UPDATE events SET event_images_$i = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("bi", $image_data, $event_id);
            $stmt->send_long_data(0, $image_data);
            $stmt->execute();

            // Close the statement
            $stmt->close();
        }
    }

    // Set notification message
    $notification = "Event details updated successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
    <link rel="stylesheet" href="../admin-css/update_event.css">
    <link href="../images/admin-logo-old.png" rel="icon">
</head>
<body>
    <header class="header">
        <h1 class="logo"><a href="content_dashboard.php"><img src="../images/logo-with-text.png" alt="barrio-liwanag-logo-with-text" width="200" height="75"></a></h1>
        <ul class="main-nav">
            <li><a href="content_dashboard.php">CONTENT DASHBOARD</a></li>
            <li><a href="logs.php">LOGS</a></li>
            <li><a href="admins.php">ADMINS</a></li>
            <li><a href="../php/logout.php">LOGOUT</a></li>
        </ul>
    </header>
    <!-- Notification card -->
    <div id="notification" class="notification-card"></div>
        <!-- Form container to center the form -->

    <section>
        <!-- Form container to center the form -->
        <div class="form-container">
            <!-- Form to update event -->
            <div class="event-form page-title" style="font-size: small;">
                <div style="font-size: xx-large;">
                    <p><strong><b>Update Event</b></strong></p>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?event_id=' . $event_id); ?>" enctype="multipart/form-data">
                    <div class="form-columns" style="display: flex;">
                        <!-- Left Column -->
                        <div class="column" style="flex: 1;">
                            <label for="eventname">Event Name:</label>
                            <input type="text" id="eventname" name="eventname" value="<?php echo $eventname; ?>" required><br><br>

                            <label for="description">Description:</label><br>
                            <textarea id="description" name="description" rows="10" cols="50"><?php echo $description; ?></textarea><br><br>

                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>" required><br><br>

                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" required><br><br>
                        </div>
                        <!-- Right Column -->
                        <div class="column" style="flex: 1;">
                            <!-- File input fields for updating event images -->
                            <?php for ($i = 1; $i <= 4; $i++) { ?>
                                <label for="image<?php echo $i; ?>">Event Image <?php echo $i; ?>:</label>
                                <input type="file" id="image<?php echo $i; ?>" name="image<?php echo $i; ?>"><br><br>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="go-back-btn">
                        <a href="content_dashboard.php">Go Back</a>
                    </div>
                    <input type="submit" value="Update Event" class="create-btn">
                </form>
            </div>
        </div>
    </section>





    <?php
    // PHP code to display notification
    if(isset($notification)) {
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '    var notification = document.getElementById("notification");';
        echo '    notification.textContent = "' . $notification . '";';
        echo '    notification.style.display = "block";';
        echo '    setTimeout(function() {';
        echo '        notification.style.display = "none";';
        echo '    }, 5000);'; // Hide notification after 5 seconds
        echo '});';
        echo '</script>';
    }
    ?>
    <footer>
        <center>
            <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>
</body>
</html>
