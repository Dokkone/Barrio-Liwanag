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

// Process form submission to create admin accounts
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement to insert admin account details
    $sql = "INSERT INTO user (username, password, role, date_created) VALUES (?, ?, 'admin', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();

    // After successfully creating a new user
    include 'logging.php';
    $newUserId = $conn->insert_id; // Get the ID of the newly created user
    logUserAction('ACCOUNT create', $_SESSION['id'], $_SESSION['username'], $newUserId, $username);

    $stmt->close();

    // Redirect to another page after successful admin creation
    header('Location: ../admin/content_dashboard.php');
    exit(); // Stop further execution of the script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins</title>
    <link rel="stylesheet" href="../admin-css/admins.css">
    <link href="../images/admin-logo-old.png" rel="icon">
    <style>
        /* CSS for notification */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 10px;
            z-index: 9999;
            opacity: 1; /* Initially visible */
            transition: opacity 0.5s ease; /* Smooth transition */
        }
    </style>
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

    <section class="admin-form-container">
        <center>
            <div class="admin-form-wrapper">
                <h2>Create Admin Account</h2>
                <form id="admin-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Create Admin Account" id="create-admin-btn">
                    </div>
                </form>
            </div>
        </center>
    </section>


    <footer>
        <center>
            <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>

    <script>
        // Function to show notification
        function showNotification(message) {
            var notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);
            // Fade out after 5 seconds
            setTimeout(function() {
                notification.style.opacity = '0';
                setTimeout(function() {
                    notification.remove();
                }, 500); // Delay removing after fade-out animation
            }, 5000); // 5 seconds delay before starting fade-out animation
        }

        // Add event listener to the submit button
        document.getElementById('admin-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            // Disable the submit button to prevent multiple submissions
            document.getElementById('create-admin-btn').disabled = true;
            // Call function to show notification
            showNotification('Admin account created successfully.');
            // Submit form
            setTimeout(function() {
                document.getElementById('admin-form').submit();
            }, 1000); // Delay form submission after showing notification
        });
    </script>
</body>
</html>
