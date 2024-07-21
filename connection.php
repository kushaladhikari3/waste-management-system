<?php
$servername = "localhost:4306";
//c1
$username = "root";
$password = "rootroot";
$dbname = "wms";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

//Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>