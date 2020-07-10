<?php

require_once('envionment.php');

$servername = $_ENV["servername"];
$username = $_ENV["username"];
$password = $_ENV["password"];
$dbname = $_ENV["dbname"];

$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
