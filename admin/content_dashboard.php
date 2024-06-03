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

// Process form submission to create events
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_event'])) {
        $event_id = $_POST['event_id'];
        $sql = "SELECT eventname FROM events WHERE id =?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: ". $conn->error);
        }
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $eventName = $row['eventname'];
    
        // Delete event
        $sql = "DELETE FROM events WHERE id =?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: ". $conn->error);
        }
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
    
        // LOGGING EVENT
        include 'logging.php';
        logUserAction('EVENT delete', $_SESSION['id'], $_SESSION['username'], $event_id, $eventName);
    
        $stmt->close();
        header("Location: content_dashboard.php");
        exit();
    } elseif (isset($_POST['update_event'])) {
        // Redirect to the update event page with event ID as parameter
        $event_id = $_POST['event_id'];
        header("Location: update_event.php?event_id=$event_id");
        exit();
    } else {
        // Retrieve form data
        $eventname = isset($_POST['eventname']) ? $_POST['eventname'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

        // Prepare and execute SQL statement to insert event details
        $sql = "INSERT INTO events (eventname, description, start_date, end_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $eventname, $description, $start_date, $end_date);
        $stmt->execute();

        // Get the ID of the inserted event
        $event_id = $stmt->insert_id;

        // After successfully creating a new event
        include 'logging.php';
        $newUserId = $conn->insert_id; // Get the ID of the newly created user
        logUserAction('EVENT create', $_SESSION['id'], $_SESSION['username'], $newUserId, $eventname);

        // Close the statement
        $stmt->close();

        // Process image uploads and store them in the database
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

        // Redirect back to the content dashboard or any other page
        header("Location: content_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Dashboard</title>
    <link rel="stylesheet" href="../admin-css/content_dashboard.css">
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

    <!-- Creating Events Section -->
    <section>
        <!-- Form to create events -->
        <div class="page-title" style="font-size: small;">
            <div style="font-size: xx-large;">
                <p><strong><b>Creating Events</b></strong></p>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div style="display: flex;">
                    <!-- Left Column for Event Details -->
                    <div style="flex: 1;">
                        <label for="eventname">Event Name:</label>
                        <input type="text" id="eventname" name="eventname" required><br><br>
                        
                        <label for="description">Description:</label><br>
                        <textarea id="description" name="description" rows="4" cols="30"></textarea><br><br>
                        
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" required><br><br>
                        
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" required><br><br>
                    </div>
                    <!-- Right Column for Image Uploads -->
                    <div style="flex: 1;">
                        <label for="image1">Event Image 1:</label>
                        <input type="file" id="image1" name="image1"><br><br>
                        
                        <label for="image2">Event Image 2:</label>
                        <input type="file" id="image2" name="image2"><br><br>
                        
                        <label for="image3">Event Image 3:</label>
                        <input type="file" id="image3" name="image3"><br><br>
                        
                        <label for="image4">Event Image 4:</label>
                        <input type="file" id="image4" name="image4"><br><br>
                    </div>
                </div>
                <input type="submit" value="Create Event" class="create-btn">
            </form>
        </div>
    </section>


<!-- Delete and Update Section -->
<section>
    <!-- Display existing events with delete and update buttons -->
    <div class="event-list page-title event-form">
        <div style="font-size: xx-large;">
            <p><strong><b>Managing Events</b></strong></p>
        </div>
        <div class="events-container">
            <?php
            // Retrieve existing events from the database
            $sql = "SELECT * FROM events";
            $result = $conn->query($sql);

            // Display each event with delete and update buttons
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="event-card">';
                    echo '<h2>' . $row['eventname'] . '</h2>';
                    echo '<h4>' . $row['description'] . '</h4>';
                    echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                    echo '<input type="hidden" name="event_id" value="' . $row['id'] . '">';
                    echo '<input type="submit" name="delete_event" value="Delete" class="delete-btn">';
                    echo '<input type="submit" name="update_event" value="Update" class="update-btn">';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>No events found.</p>";
            }
            ?>
        </div>
    </div>
</section>



    <footer>
        <center>
            <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>
</body>
</html>
