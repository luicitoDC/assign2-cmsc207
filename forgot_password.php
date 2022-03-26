<?php
//Include the database connection scripts
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

//Process the values in the form if the user clicked the button for reset password
if(isset($_POST['passwordResetBtn'])){
    //Initialize the array that will be used to hold any error message from the form
    $errorArray = array();

    //Perform form validation
    $requiredFields = array('email', 'new_password', 'confirm_password');

    //Inspect empty field and combine the return data in the errorArray array
    $errorArray = array_merge($errorArray, check_empty_fields($requiredFields));

    //Check if the password meets the required minimum length
    $fieldsLengthChecked = array('new_password' => 6, 'confirm_password' => 6);

    //Inspect the minimum required length and merge the return data in the errorArray array
    $errorArray = array_merge($errorArray, check_min_length($fieldsLengthChecked));

    //Perform validation of the email and append the results in the errorArray array
    $errorArray = array_merge($errorArray, check_email($_POST));

    //Inspect if no error is present; then process the data in the form and insert record if there's no error
    if(empty($errorArray)){
        //Gather the data in the form and create variables to store them
        $email = $_POST['email'];
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        //Check the new password and check if the same with the confirm password
        if($password1 != $password2){
            $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> New password and confirm password does not match</p>";
        }else{
            try{

                //Check if the inputted email address exixts in the databse
                $sqlQuery = "SELECT email FROM users WHERE email =:email";

                //Sanitize the data using PDO
                $statement = $db->prepare($sqlQuery);

                //Run the query
                $statement->execute(array(':email' => $email));

                //Inspect if the record exists in the database
                if($statement->rowCount() == 1){

                    //Perform password hashing
                    $securePassword = password_hash($password1, PASSWORD_DEFAULT);

                    //Update the password in the database
                    $sqlUpdate = "UPDATE users SET password =:password WHERE email=:email";

                    //Sanitize SQL statement using PDO
                    $statement = $db->prepare($sqlUpdate);

                    //Run the statement
                    $statement->execute(array(':password' => $securePassword, ':email' => $email));

                    $result = "<p style='padding:20px; border: 1px solid gray; color: green;'> Password Reset Successful</p>";
                }
                else{
                    $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> The email address provided
                                does not exist in our database, please try again</p>";
                }
            }catch (PDOException $ex){
                $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> An error occurred: ".$ex->getMessage()."</p>";
            }
        }
    }
    else{
        if(count($errorArray) == 1){
            $result = "<p style='color: red;'> There was 1 error in the form<br>";
        }else{
            $result = "<p style='color: red;'> There were " .count($errorArray). " errors in the form <br>";
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


<center><img src = "register.jpg" alt = "City View" width="430" height="100" /></center>
<div class="form-style-6">
  <h1>Password Reset Form</h1>
  <form  method="post" action="">
  <br /><br />
  <input type="text" name="email" placeholder="Email Address" />
  <input type="password" name="new_password" placeholder="New Password" />
  <input type="password" name="confirm_password" placeholder="Confirm Password" />
  <br /><br /><br />
  <input style="float: right;" type="submit" name="passwordResetBtn" value="Reset Password" />
  </form>
  <br /><br />
  <hr class="new2"/>
  <p align = "center">
    <a href="index.php">Back</a>
  </p>
</div>

</body>
</html>
