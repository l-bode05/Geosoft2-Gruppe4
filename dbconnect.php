<?php

// Contains connection to database and is included in every file
$servername = "localhost";
$username = "root";
$password = "dark knight rises zakbum";
$dbname = "test";

// DB Password salt
global $dbsalt;
$dbsalt="hg32*'*)(§/iuh";
    
// Create connection
global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




?> 