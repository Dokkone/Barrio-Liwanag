<?php
// Configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "barrio-liwanag";

// Connect to Database
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create or retrieve operation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form was submitted
    if ($_POST['Action'] == 'create') {

        // Unique username check
        $username = $_POST['username'];

        // Check if the username has at least 5 characters
        if (strlen($username) < 5) {
            $error_message = "Username must be at least 5 characters long.";
            echo "<script>
            alert('$error_message');
            window.location.href='../signup.php';
            </script>";
            exit;
        }

        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Username already exists
            $error_message = "Username already exists. Please choose a different one.";
            echo "<script>
            alert('$error_message');
            window.location.href='../signup.php';
            </script>";
            exit;
        } else {
            // Hash the password
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // Insert the user data into the database
            $stmt = $conn->prepare("INSERT INTO user (username, password, role) VALUES (?, ?, 'user')");
            $stmt->bind_param("ss", $_POST['username'], $hashedPassword);
            if ($stmt->execute()) {
                // Redirect to login page after successful signup
                echo "<script>
                alert('Account Created Successfully');
                window.location.href='../login.php';
                </script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}
?>
