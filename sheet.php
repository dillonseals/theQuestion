<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<link rel="stylesheet" type="text/css" href="./sheet.css" />-->
    <title>Adalo</title>
</head>
<body>
    <!-- Sheet Name Input --> 
    <div id='existingSheetName'>
        <form>
            Load Existing Sheet<br>
            <input type='text' placeholder='name' id='loadName' />
            <input type='button' value='Load' id='loadNameBtn' />
        </form>
    </div>
    <!-- Create new sheet button -->
    <div id='newBtnDiv'>
        <form>
            Create a New Spreadsheet<br>
            <input type='text' placeholder='name' id='newName' />
            <input type='button' value='Create New Spreadsheet' id='newSheetBtn' />
        </form>
        <br>
    </div>
    <!-- Display Sheet Info -->
    <div id='sheetInfo'>Current Spreadsheet:<br></div><br>
    <!-- New Column and New Row Buttons -->
    <div id='newButtons'>
        <form>
            <input type='button' value='Create New Row' id='newRowBtn' />
            <input type='button' value='Create New Column' id='newColBtn' />
        </form>
    </div><br>
    <!-- Display Rows & Columns -->
    <div id='spreadsheet'></div>


    <!--
    <div id='row1'>
        row1, item1 - row1, item2 - row1, item3
    </div><br>
    <div id='row2'>
        row2, item1 - row2, item2 - row2, item3
    </div><br>
    -->


    <!--
    <div id='spreadsheet'>
        <div class='item1'>1, 1</div>
        <div class='item2'>2, 1</div>
        <div class='item3'>3, 1</div>
        <div class='item4'>4, 1</div>
        <div class='item5'>1, 2</div>
        <div class='item4'>4</div>
        <div class='item4'>4</div>
        <div class='item4'>4</div>
    </div>
    -->

<script>
// load existing spreadsheet
const loadSheet = function() {
    console.log('loadSheet');
    let name = document.getElementById('loadName').value;
    printInputs(name);
}

// create new spreadsheet
const createSheet = function() {
    console.log("createSheet");
    // do stuff
    let name = document.getElementById('newName').value;
    const data = {request: 'createSheet', name: name}
    fetch('./backend.php', {
        method: 'POST',
        body: JSON.stringify(data),
    })
    .then(res => res.json())
    .then(function(response) {
        if (response.success) {
            printInputs(name);
        } else {
            console.log("Error - success == false");
        }
    })
    .catch(function(error) {
    });
}

// print inputs for selected sheet
const printInputs = function(name) {
    console.log("printInputs");
    window.name = name;
    // get rows & columns
    const data = {request: 'printInputs', name: name};
    fetch('./backend.php', {
        method: 'POST',
        body: JSON.stringify(data),
    })
    .then(res => res.json())
    // print inputs
    .then(function(response) {
        // set global variables
        window.rows = response.rows;
        window.columns = response.columns;
        // print rows and columns (row = i, column = k)
        for (let i = 0; i < response.rows; i++) {
            for (let k = 0; k < response.columns; k++) {
                let newInput = document.createElement('INPUT');
                let newId = i + ', ' + k;
                newInput.setAttribute('id', newId);
                newInput.setAttribute('type', 'text');
                newInput.setAttribute('value', newInput.id);
                //document.getElementById(newInput.id).addEventListener('onkeyup', changeData, false);
                // why does the above break things??
                // append cell to spreadsheet
                document.getElementById('spreadsheet').appendChild(newInput);
                // update db when input is changed
                //newInput.addEventListener('onchange', changeData, false);
                //document.getElementById(newId).addEventListener('onchange', changeData, false);
            }
            document.getElementById('spreadsheet').appendChild(document.createElement('br'));
        }
        // update info
        document.getElementById('sheetInfo').innerHTML = 'Current Spreadsheet - ' + name;
        // get data
        getData(response.id);
    })
    .catch(function(error){})
}

// load cell data
const getData = function(id) {
    console.log('getData');
    window.id = id;
    const data = {request: 'getData', id: id};
    fetch('./backend.php', {
        method: 'POST',
        body: JSON.stringify(data),
    })
    .then(res => res.json())
    .then(function(response) {
        for (let i = 0; i < response.length; i++) {
            let cell = document.getElementById(response[i].position);
            cell.setAttribute('value', response[i].content);
        }
    })
    .catch(function(error){})
}

// update cell data
const changeData = function() {
    console.log('changeData');
    const data = {request: 'changeData', content: this.value, position: this.id};
    fetch('./backend.php', {
        method: 'POST',
        body: JSON.stringify(data),
    })
    .then(res => res.json())
    .then(function(response) {
        //
    })
    .catch(function(error){})
}

// add column
const createColumn = function() {
    console.log('createColumn');
    const data = {request: 'createColumn', id: window.id, newCols: window.columns + 1};
    fetch('./backend.php', {
        method: 'POST',
        body: JSON.stringify(data),
    })
    .then(res => res.json())
    .then(function(response) {
        if (response.success) {
            // reset spreadsheet div
            document.getElementById('spreadsheet').innerHTML = '';
            printInputs(window.name);
        }
    })
    .catch(function(error){})
}

// add row
const createRow = function() {
    console.log('createRow');
    const data = {request: 'createRow', id: window.id, newRows: window.rows + 1};
    fetch('./backend.php', {
        method: 'POST',
        body: JSON.stringify(data),
    })
    .then(res => res.json())
    .then(function(response) {
        if (response.success) {
            // reset spreadsheet div
            document.getElementById('spreadsheet').innerHTML = '';
            printInputs(window.name);
        }
    })
    .catch(function(error){})
}

// event listeners
//document.addEventListener('DOMContentLoaded', printInputs, false);
document.getElementById('newSheetBtn').addEventListener('click', createSheet, false);
document.getElementById('newRowBtn').addEventListener('click', createRow, false);
document.getElementById('newColBtn').addEventListener('click', createColumn, false);
document.getElementById('loadNameBtn').addEventListener('click', loadSheet, false);
</script>
</body>
</html>