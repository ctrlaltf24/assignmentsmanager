<?php
$servername = "";
$username = "";
$password = "";
$database = "";

// Create connection
$conn = new mysqli($servername, $username, $password,$database);

// Check connection
if ($conn->connect_error && $_SERVER['HTTP_HOST'] === 'localhost') {
    die("Connection failed: " . $conn->connect_error);
}
require_once "safePOSTAndGet.php";
