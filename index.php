<?php include_once 'resource/session.php' ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Homepage</title>
</head>
<body style = "background-color:cyan;">
  <h1><center>Welcome to Luicito dela Cruz' Registration System</center></h1><hr>
  <table>
    <tr>
      <center>
        <img src = "calgary.jpg" alt = "City View" />
      </center>
    </tr>
    <tr><center>
      <?php if(!isset($_SESSION['username'])): ?>
      <P>You are currently not signed in <a href="login.php">Login</a> Not yet a member? <a href="signup.php">Signup</a> </P>
      <?php else: ?>
      <p>You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?> <a href="logout.php">Logout</a> </p>
      <?php endif ?>
    </center>
    </tr>
  </table>


</body>
</html>
