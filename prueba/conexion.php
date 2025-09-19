<?php
$localhost = "localhost";
$username = "phpmyadmin";
$password = "kali";
$dbname = "cruddb";
$con = new mysqli($localhost, $username, $password,
$dbname);
if($con->connect_error) {
 die("connection failed : " . $con->connect_error);
}
?>