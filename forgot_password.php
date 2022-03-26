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
          <tr><img src = "register.jpg" alt = "City View" width="392" height="100" /></tr>
          <tr><td></td><td><h3>Password Reset Form</h2></td><td></td></tr>
          <tr><td>Email:</td> <td><input type="text" value="" name="email"></td></tr>
          <tr><td>New Password:</td> <td><input type="password" value="" name="new_password"></td></tr>
          <tr><td>Confirm Password:</td> <td><input type="password" value="" name="confirm_password"></td></tr>
          <tr><td></td><td><input style="float: right;" type="submit" name="passwordResetBtn" value="Reset Password"></td></tr>
      </table>
  </form>
</center>

<p align = "center"><a href="index.php">Back</a> </p>

</body>
</html>
