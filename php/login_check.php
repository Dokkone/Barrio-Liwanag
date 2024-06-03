<?php
session_start();
// Configuration
$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "barrio-liwanag"; //change niyo ito according sa name ng database niyo

// Connect to Database
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if (!$conn) {
  die("Connection failed: ". mysqli_connect_error());
}

// LOGIN
if(isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare SQL statement
  $sql = "SELECT * FROM user WHERE username =?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $username);

  // Execute the query
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if(mysqli_num_rows($result) > 0) {
    // Fetch the row
    $row = mysqli_fetch_assoc($result);

    // Verify the password
    if(password_verify($password, $row['password'])) {
      // Password is correct, proceed with session setup
      $_SESSION['id'] = $row['id'];
      $_SESSION['username'] = $username;
      $_SESSION['role'] = $row['role'];

      // FOR REDIRECTING ADMIN TO DASHBOARD
      if($_SESSION['role'] == 'admin') {
        header('Location:../admin/content_dashboard.php');
      }
      // FOR REDIRECTING HOME PAGE
      else if ($_SESSION['role'] == 'user') {
        header('Location:../home.php');
      }

    } else {
      // Invalid password
      $_SESSION['error'] = "Invalid password";
      header('Location:../login.php?error=1');
      exit();
    }
  } else {
    // User not found
    $_SESSION['error'] = "Invalid username";
    header('Location:../login.php?error=1');
    exit();
  }
}
?>