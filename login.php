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

    <style type="text/css">
    .form-style-6{
    	font: 95% Arial, Helvetica, sans-serif;
    	max-width: 400px;
    	margin: 10px auto;
    	padding: 16px;
    	background: #F7F7F7;
    }
    .form-style-6 h1{
    	background: #43D1AF;
    	padding: 20px 0;
    	font-size: 140%;
    	font-weight: 300;
    	text-align: center;
    	color: #fff;
    	margin: -16px -16px 16px -16px;
    }
    .form-style-6 input[type="text"],
    .form-style-6 input[type="date"],
    .form-style-6 input[type="datetime"],
    .form-style-6 input[type="email"],
    .form-style-6 input[type="number"],
    .form-style-6 input[type="search"],
    .form-style-6 input[type="time"],
    .form-style-6 input[type="url"],
    .form-style-6 input[type="password"],
    .form-style-6 textarea,
    .form-style-6 select
    {
    	-webkit-transition: all 0.30s ease-in-out;
    	-moz-transition: all 0.30s ease-in-out;
    	-ms-transition: all 0.30s ease-in-out;
    	-o-transition: all 0.30s ease-in-out;
    	outline: none;
    	box-sizing: border-box;
    	-webkit-box-sizing: border-box;
    	-moz-box-sizing: border-box;
    	width: 100%;
    	background: #fff;
    	margin-bottom: 4%;
    	border: 1px solid #ccc;
    	padding: 3%;
    	color: #555;
    	font: 95% Arial, Helvetica, sans-serif;
    }
    .form-style-6 input[type="text"]:focus,
    .form-style-6 input[type="date"]:focus,
    .form-style-6 input[type="datetime"]:focus,
    .form-style-6 input[type="email"]:focus,
    .form-style-6 input[type="number"]:focus,
    .form-style-6 input[type="search"]:focus,
    .form-style-6 input[type="time"]:focus,
    .form-style-6 input[type="url"]:focus,
    .form-style-6 input[type="password"]:focus,
    .form-style-6 textarea:focus,
    .form-style-6 select:focus
    {
    	box-shadow: 0 0 5px #43D1AF;
    	padding: 3%;
    	border: 1px solid #43D1AF;
    }

    .form-style-6 input[type="submit"],
    .form-style-6 input[type="button"]{
    	box-sizing: border-box;
    	-webkit-box-sizing: border-box;
    	-moz-box-sizing: border-box;
    	width: 100%;
    	padding: 3%;
    	background: #43D1AF;
    	border-bottom: 2px solid #30C29E;
    	border-top-style: none;
    	border-right-style: none;
    	border-left-style: none;
    	color: #fff;
    }
    .form-style-6 input[type="submit"]:hover,
    .form-style-6 input[type="button"]:hover{
    	background: #2EBC99;
    }
    hr.new2 {
      border: 1px solid green;
      border-radius: 5px;
    }
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

<div class="form-style-6">
  <h1>Login</h1>
  <form  method="post" action="">
  <br /><br />
  <input type="text" name="username" placeholder="Username" />
  <input type="password" name="password" placeholder="Password" />
  <br /><br /><br />
  <input type="submit" name="loginBtn" value="Signin" />
  </form>
  <br /><br />
  <hr class="new2"/>
  <p align = "center">
    <a href="forgot_password.php">Forgot Password?</a><br />
    <a href="index.php">Back</a>
  </p>
</div>

</body>
</html>
