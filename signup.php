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
  <h1>Registration Form</h1>
  <form  method="post" action="">
  <br /><br />
  <input type="text" name="email" placeholder="E-mail Address*" />
  <input type="text" name="firstname" placeholder="First Name*" />
  <input type="text" name="lastname" placeholder="Last Name*" />
  <input type="text" name="phone" placeholder="Telephone Number*" />
  <input type="text" name="username" placeholder="Username*" />
  <input type="password" name="password" placeholder="Password*" />
  <br /><br /><br />
  <input style="float: right;" type="submit" name="signupBtn" value="Signup" />
  </form>
  <p align = "center">* Required information</p>
  <br />
  <hr class="new2"/>
  <p align = "center">
    <a href="index.php">Back</a>
  </p>
</div>
</body>
</html>
