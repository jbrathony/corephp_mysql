<?php
$filterParam = "";
if (isset($_POST["filter"])) {
    $filterParam = $_POST["filter"];
    $sqlSelect = "SELECT * FROM orders WHERE customer_username='$filterParam'";
    $result = $mysqli->query($sqlSelect);
}
