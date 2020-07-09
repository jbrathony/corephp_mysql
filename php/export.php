<?php

require_once('./db.php');

$expo_query = "SELECT  `customer_note`, `customer_username`,`item_name`,`quantity` FROM orders";
$result = $mysqli->query($expo_query);
$columnHeader = '';
$columnHeader = "Customer Note" . "\t" . "Customer Username" . "\t" . "Item name" . "\t" . "Quantity" . "\t";

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
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
