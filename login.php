<?php
    session_start();

    if(isset($_SESSION['role'])) header('location: home.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/LSstyle.css">
    <link href="images/logo.png" rel="icon">
</head>
<body>

    <div class="form-container">
        <!-----------LOGIN---------------->
        <center>
          <img src="images/logo-with-text.png" alt="barrio-liwanag-logo-with-text" width="250" height="100">
        </center>
        <form method="post" action="php/login_check.php">
          <div class="form-group">
            <input type="text" name="username" placeholder="Enter your username" required>
            <!-- <label>Username</label> -->
          </div>
          <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
            <!-- <label>Password</label> -->
          </div>
          <br>
          <center>
            <button type="submit" name="login" value="Login">Login</button>
          </center>

          <!-- showcase invalid user/pass on screen --> 
          <br>
          <center>
            <?php
              if(isset($_GET['error']) && $_GET['error'] == 1) {
                if(isset($_SESSION['error'])) {
                  echo '<div id="error-message">' . $_SESSION['error'] . '</div>';
                  unset($_SESSION['error']);
                }
              }
            ?>
          </center>

        </form>
        <center>
          <p>Don't have an account? <a class="link" href="signup.php">Sign Up</a></p>
        </center>
      </div>
    
</body>
</html>