function doPost(e) { 
  var result = 'Ok'; 
  if (e.parameter == 'undefined') {
    result = 'No Parameters';
  }
  else {
    var sheet_id = 'YOUR SHEET ID HERE'; 	
    var sheet = SpreadsheetApp.openById(sheet_id).getActiveSheet();		
    var Row = sheet.getLastRow()
    var newRow = Row + 1;						
    var rowData = [];
    rowData[0] = Row;
    rowData[1] = new Date(); 											
    for (var param in e.parameter) {
      var value = stripQuotes(e.parameter[param]);
      switch (param) {
        case 'temp':
          rowData[2] = value;
          result = "ok";
          break;
        case 'hum':
          rowData[3] = value;
          result = "ok";
          break;
        case 'press':
          rowData[4] = value;
          result = "ok";
          break;
        case 'windspeed':
          rowData[5] = value;
          result = "ok";
          break;
        default:
          result = "chybicka";
      }
    }
    var newRange = sheet.getRange(newRow, 1, 1, rowData.length);
    newRange.setValues([rowData]);
  }
  return ContentService.createTextOutput(result);
}
function stripQuotes( value ) {
  return value.replace(/^["']|['"]$/g, "");
}