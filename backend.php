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
    $createStmt = $conn->prepare('insert into sheets (name, rows, columns) values (?, 0, 0)');
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

// get number of rows and columns
if ($json_obj['request'] == 'printInputs') {
    // variables
    $id = $json_obj['id'];
    $rows = '';
    $columns = '';
    // find number of rows and columns
    $getRCStmt = $conn->prepare('select (rows, columns) from sheets where id = ?');
    if (!$getRCStmt) {
        printf("Query Prep Failed: %s\n", $conn->error);
    }  
    $getRCStmt->bind_param('i', $id);
    $getRCStmt->execute();
    $getRCStmt->bind_result($bindRows, $bindColumns);

    while ($getRCStmt->fetch()) {
        $rows = $bindRows;
        $columns = $bindColumns;
    }

    $getRCStmt->close();

    $my_array = array(
        'rows' => $rows,
        'columns' => $columns
    );

    echo json_encode($my_array);
}


// get sheet data
if ($json_obj['request'] == 'getData') {
    // variables
    $id = $json_obj['id'];
    $my_array = array();
    // access db
    $getDataStmt = $conn->prepare('select (content, position, type) from data where sheet = ?');
    if (!$getDataStmt) {
        printf("Query Prep Failed: %s\n", $conn->error);
    }
    $getDataStmt->bind_param('i', $id);
    $getDataStmt->execute();
    $getDataStmt->bind_result($bindContent, $bindPosition, $bindType);

    while ($getDataStmt->fetch()) {
        $temp = array(
            'content' => $bindContent,
            'position' => $bindPosition,
            'type' => $bindType
        );
        array_push($my_array, $temp);
    }

    $getDataStmt->close();

    echo json_encode($my_array);
}






?>