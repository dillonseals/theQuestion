<?php
// access db connector
include 'dbConnect.php';

$conn = OpenCon();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// set the MIME Type to application/json
header("Content-Type: application/json");
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

// create new sheet
if ($json_obj['request'] == 'createSheet') {
    // do stuff
}


?>