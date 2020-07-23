<?php

require_once('./db.php');

$expo_query = "SELECT  `item_name`, `quantity_in_hand`, `cost` FROM inventory";
$result = $mysqli->query($expo_query);
$columnHeader = '';
$columnHeader = "Product Name" . "\t" . "Quantity In Hand" . "\t" . "Cost" . "\t" . "\t";

$setData = '';
while ($rec = mysqli_fetch_row($result)) {
    $rowData = '';
    foreach ($rec as $value) {
        $value = '"' . $value . '"' . "\t";
        $rowData .= $value;
    }
    $setData .= trim($rowData) . "\n";
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=inventory.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
