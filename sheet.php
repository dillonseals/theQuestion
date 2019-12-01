<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Adalo</title>
</head>
<body>
    <!-- Create new sheet button -->
    <div id='newBtnDiv'>
        <form>
            Create a New Spreadsheet<br>
            <input type='text' placeholder='name' id='newName' />
            <input type='submit' value='Create New Spreadsheet' id='newBtn' />
        </form>
        <br>
    </div>
    <!-- Display Sheet ID -->
    <div id='sheetID'></div><br>
    <!-- Display Rows & Columns -->
    <div id='spreadsheet'></div>

<script>
// create new spreadsheet
const createSheet = function() {
    console.log("createSheet");
    // do stuff
    let name = document.getElementById('newName').value;
    const data = {request: 'createSheet', name: name}
    fetch("./login.php", {
            method: "POST",
            body: JSON.stringify(data),
    })
    .then(res => res.json())
    .then(function(response) {
        if (response.success) {
            updateSheet();
        } else {
            console.log("Error - success == false");
        }
    })
    .catch(function(error) {
    });
}

// update spreadsheet
const updateSheet = function() {
    // display sheet id

    // display column titles
    // NOTE - display add column button

    // display rows in each column
    // NOTE - display filled rows + 1 (add row button)
}

// add column
const createColumn = function() {
    // do stuff
}

// add row
const createRow = function() {
    // do stuff
}

// event listeners
document.addEventListener('DOMContentLoaded', updateSheet, false);
document.getElementById('newBtn').addEventListener('click', createSheet, false);
</script>
</body>
</html>