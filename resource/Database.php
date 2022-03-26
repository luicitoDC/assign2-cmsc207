<?php
//Initialize variables that will store the connection parameters
$username = 'root';
$dsn = 'mysql:host=localhost; dbname=register';
$password = '';

try{
    //Create an instance of the PDO class with the required parameters
    $db = new PDO($dsn, $username, $password);

    //Set the pdo error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


} catch (PDOException $er){
    //Show the error message
    echo "Connection failed ".$er->getMessage();
}
