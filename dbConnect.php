<?php
function openCon() {
    $dbhost = "localhost";
    $dbuser = "dillon";
    $dbpass = "adalo";
    $db = "theQuestion";

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n". $conn -> error);

    return $conn;
}

function closeCon($conn) {
    $conn -> close();
}
?>