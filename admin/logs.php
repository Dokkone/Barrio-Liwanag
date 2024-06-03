<?php
//session start
session_start();
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['role'])) {
    header('Location:../login.php');
    exit();
}

// Include config file
require_once "../config.php";
$sql = "SELECT action_type, action_time, user_name, event_id, event_name FROM admin_logs";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Dashboard</title>
    <link rel="stylesheet" href="../admin-css/logs.css">
    <link href="../images/admin-logo-old.png" rel="icon">
</head>
<body>
    <header class="header">
        <h1 class="logo"><a href="../home.php"><img src="../images/logo-with-text.png" alt="barrio-liwanag-logo-with-text" width="200" height="75"></a></h1>
        <ul class="main-nav">
            <li><a href="content_dashboard.php">CONTENT DASHBOARD</a></li>
            <li><a href="logs.php">LOGS</a></li>
            <li><a href="admins.php">ADMINS</a></li>
            <li><a href="../php/logout.php">LOGOUT</a></li>
        </ul>
    </header>

    <center>
        <h2>Admin Logs</h2>
    </center>

    <!-- Admin Logs Table -->
    <table>
    <thead>
        <tr>
        <th>Admin Username</th>
        <th>Type of Action</th>
        <th>Event ID / Account ID</th>
        <th>Event Name / Username</th>
        <th>Date/Time</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0):?>
            <?php while($row = $result->fetch_assoc()):?>
                <tr>
                    <td><?php echo $row["user_name"];?></td>
                    <td><?php echo $row["action_type"];?></td>
                    <td><?php echo $row["event_id"];?></td>
                    <td><?php echo $row["event_name"];?></td>
                    <td><?php echo $row["action_time"];?></td>
                </tr>
            <?php endwhile;?>
        <?php else:?>
            <tr>
                <td colspan="5">0 results</td>
            </tr>
        <?php endif;?>
    </tbody>
    </table>

    <footer>
        <center>
        <p>&copy; Copyright Barrio Liwanag. All Rights Reserved 2024</p>
        </center>
    </footer>
</body>
</html>