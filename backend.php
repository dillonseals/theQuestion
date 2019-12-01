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
    } else {
        $my_array = array(
            'success' => true
        );
    }
    $createStmt->bind_param("s", $name);
    $createStmt->execute();
    $createStmt->close();

    echo json_encode($my_array);



   /*  // TODO - should only run if insertion successful
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

    echo json_encode($my_array); */
}

// get number of rows and columns
if ($json_obj['request'] == 'printInputs') {
    // variables
    $name = $json_obj['name'];
    $rows = '';
    $columns = '';
    $id = '';
    // find number of rows and columns
    $getRCStmt = $conn->prepare('select id, rows, columns from sheets where name = ?');
    if (!$getRCStmt) {
        printf("Query Prep Failed: %s\n", $conn->error);
    }  
    $getRCStmt->bind_param('s', $name);
    $getRCStmt->execute();
    $getRCStmt->bind_result($bindID, $bindRows, $bindColumns);

    while ($getRCStmt->fetch()) {
        $id = $bindID;
        $rows = $bindRows;
        $columns = $bindColumns;
    }

    $getRCStmt->close();

    $my_array = array(
        'id' => $id,
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
    $getDataStmt = $conn->prepare('select content, position, type from data where sheetID = ?');
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

// update cell data
if ($json_obj['request'] == 'changeData') {
    // variables
    $content = $json_obj['content'];
    $position = $json_obj['position'];
    $my_array = '';
    // update db
    $changeStmt = $conn->prepare('update data set content=? where position=?');
    if (!$changeStmt) {
        printf("Query Prep Failed: %s\n", $conn->error);
        $my_array = array(
            'success' => false
        );
    } else {
        $my_array = array(
            'success' => true
        );
    }
    $changeStmt->bind_param('ss', $content, $position);
    $changeStmt->execute();
    $changeStmt->close();

    echo json_encode($my_array);
}

if ($json_obj['request'] == 'createColumn') {
    $id = $json_obj['id'];
    $cols = $json_obj['newCols'];
    $my_array ='';
    // update db
    $colStmt = $conn->prepare('update sheets set columns=? where id=?');
    if (!$colStmt) {
        printf("Query Prep Failed: %s\n", $conn->error);
        $my_array = array(
            'success' => false
        );
    } else {
        $my_array = array(
            'success' => true
        );
    }
    $colStmt->bind_param('ii', $cols, $id);
    $colStmt->execute();
    $colStmt->close();

    echo json_encode($my_array);
}




?>