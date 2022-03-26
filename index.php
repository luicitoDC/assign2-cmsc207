<?php include_once 'resource/session.php' ?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Luicito dela Cruz's Homepage</title>

    <style>
    hr.new1 {
      border: 3px solid green;
      border-radius: 5px;
    }
    </style>

</head>
<body style = "background-color:cyan;">

  <hr class="new1">
  <h1 style="color:blue"><center>Welcome to the LDC Registration System</center></h1>
  <hr class="new1">

  <table>
    <tr>
      <center>
        <img src = "calgary.jpg" alt = "City View" /><br>
      </center>
    </tr>
    <tr><center>
      <?php if(!isset($_SESSION['username'])): ?>
      <P>You are not currently signed in <a href="login.php">Login</a>. Not yet a member? <a href="signup.php">Register.</a> </P>
      <?php else: ?>
      <p>You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?> <a href="logout.php">Logout</a> </p>
      <?php endif ?>
    </center>
    </tr>
  </table>

</body>
</html>
