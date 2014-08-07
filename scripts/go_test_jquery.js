function addrow(){
 var tableRef = document.getElementById('ranks_table');

  // Insert a row in the table at row index 0
  var newRow   = tableRef.insertRow(-1);

  // Insert a cell in the row at index 0
  var newCell  = newRow.insertCell(0);

  // Append a text node to the cell
  var newText  = document.createTextNode('New top row')
  newCell.appendChild(newText);
}
