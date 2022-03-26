<?php
include_once 'resource/session.php';
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

if(isset($_POST['loginBtn'])){
    //Instantiate the array to hold the errors
    $errorArray = array();

    //Perform validation
    $requiredFields = array('username', 'password');
    $errorArray = array_merge($errorArray, check_empty_fields($requiredFields));

    if(empty($errorArray)){

        //Gather the information contained in the form
        $user = $_POST['username'];
        $password = $_POST['password'];

        //Validate if the user exists in the dtaabse
        $sqlQuery = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $user));

       while($row = $statement->fetch()){
           $id = $row['id'];
           $hashed_password = $row['password'];
           $username = $row['username'];

           if(password_verify($password, $hashed_password)){
               $_SESSION['id'] = $id;
               $_SESSION['username'] = $username;
               header("location: index.php");
           }else{
               $result = "<p style='padding: 20px; color: red; border: 1px solid gray;'> Invalid username or password</p>";
           }
       }

    }else{
        if(count($errorArray) == 1){
            $result = "<p style='color: red;'>There was one error in the form </p>";
        }else{
            $result = "<p style='color: red;'>There were " .count($errorArray). " error in the form </p>";
        }
    }
}
?>

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

<?php if(isset($result)) echo $result; ?>
<?php if(!empty($errorArray)) echo show_errors($errorArray); ?>

<center>
  <form method="post" action="">
      <table bgcolor="white">
          <tr><img src = "register.jpg" alt = "City View" width="378" height="100" /></tr>
          <tr><td></td><td><h3>Login Form</h2></td><td></td></tr>
          <tr><td>Username:</td> <td><input type="text" value="" name="username"></td></tr>
          <tr><td>Password:</td> <td><input type="password" value="" name="password"></td></tr>
          <tr><td><a href="forgot_password.php">Forgot Password?</a></td><td><input style="float: right;" type="submit" name="loginBtn" value="Signin"></td></tr>
      </table>
  </form>
  <p><a href="index.php">Back</a> </p>
</center>

</body>
</html>
