<?php
//Include the database connection script
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

//Process the information contained in the form
if(isset($_POST['signupBtn'])){

    //Create an array to hold any error message from the form
    $errorArray = array();

    //Perform validation of the form
    $requiredFields = array('email', 'firstname', 'lastname', 'phone', 'username', 'password');

    //Run the function to inspect empty field and combine the output data into an array
    $errorArray = array_merge($errorArray, check_empty_fields($requiredFields));

    //Inspect the fields that require checking for minimum length
    $fieldsLengthChecked = array('username' => 4, 'password' => 6);

    //Run the function to inspect minimum required length and combine the return data into an array
    $errorArray = array_merge($errorArray, check_min_length($fieldsLengthChecked));

    //Perform email validation and combine the return data into an array
    $errorArray = array_merge($errorArray, check_email($_POST));

    //Inspect if error array is empty, and process the form data and insert record if it is indeed empty
    if(empty($errorArray)){

        //Gather form data and hold in variables
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phone = $_POST['phone'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        //Perform password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try{

            //Create SQL insert statement
            $sqlInsert = "INSERT INTO users (email, firstname, lastname, phone, username, password, join_date)
              VALUES (:email, :firstname, :lastname, :phone, :username, :password, now())";

            //Sanitize the data using PDO
            $statement = $db->prepare($sqlInsert);

            //STore the data in the database
            $statement->execute(array(':email' => $email, ':firstname' => $firstname, ':lastname' => $lastname, ':phone' => $phone, ':username' => $username, ':password' => $hashed_password));

            //Inspect if a new row was created
            if($statement->rowCount() == 1){
                $result = "<p style='padding:20px; border: 1px solid gray; color: green;'> Registration Successful</p>";
            }
        }catch (PDOException $ex){
            $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> An error occurred: ".$ex->getMessage()."</p>";
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
          <tr><td></td><td><h3>Registration Form</h2></td><td></td></tr>
          <tr><td>Email:</td> <td><input type="text" value="" name="email"></td></tr>
          <tr><td>First Name:</td> <td><input type="text" value="" name="firstname"></td></tr>
          <tr><td>Last Name:</td> <td><input type="text" value="" name="lastname"></td></tr>
          <tr><td>Telephone Number:</td> <td><input type="text" value="" name="phone"></td></tr>
          <tr><td>Username:</td> <td><input type="text" value="" name="username"></td></tr>
          <tr><td>Password:</td> <td><input type="password" value="" name="password"></td></tr>
          <tr><td></td><td><input style="float: right;" type="submit" name="signupBtn" value="Signup"></td></tr>
      </table>
  </form>
</center>

<p align = "center"><a href="index.php">Back</a> </p>
</body>
</html>
