<?php
//database connect
$servername = "localhost";
$username = "phpmyadmin";
$password = "aaasss1166";
$dbname = "line_bot";
$dbms='mysql';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
}
catch(PDOException $e)
{
    //echo "Connection failed: " . $e->getMessage();
}

//U2b33ee2b39ee44c5b4842c94c651a47c
