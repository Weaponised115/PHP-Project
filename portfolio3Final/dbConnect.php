<?php

// Creating variables with the correct connection details
$db_host = "localhost";
$db_name = "u_230411191_aproject";
$username = "u-230411191";
$password = "Y6iqGkgYDMXWyFT";

// 'try' will attempt 
try{
    // To connect do the databse, using the above variables by creating a new PDO isntance 
    $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
    // If connection fails, store error details to $ex and display alert messaage with error
}catch(PDOException $ex){
    echo("Failed to connect.<br>");
    echo($ex->getMessage());
    exit;
}
?>