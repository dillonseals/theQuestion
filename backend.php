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
// TODO - test
if ($json_obj['request'] == 'createSheet') {
    // variables
    $name = $json_obj['name'];
    $id = '';
    $my_array = '';
    // access db
    $createStmt = $conn->prepare('insert into sheets (name) values (?)');
    if (!$createStmt) {
        printf("Query Prep Failed: %s\n", $conn->error);
        $my_array = array(
            "success" => false
        );
    }
    $createStmt->bind_param("s", $name);
    $createStmt->execute();
    $createStmt->close();
    // TODO - should only run if insertion successful
    $getIDStmt = $conn->prepare('select id from sheets where name = ?');
    if (!$getIDStmt) {
        printf("Query Prep Failed: %s\n", $conn->error);
    }
    $getIDStmt->bind_param('s', $name);
    $getIDStmt->execute();
    $getIDStmt->bind_result($bindID);
    while ($getIDStmt->fetch()) {
        $id = $bindID;
    }
    $getIDStmt->close();

    $my_array = array(
        "success" => true,
        "id" => $id
    );

    echo json_encode($my_array);
}


?>