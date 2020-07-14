<?php

/**
 * Excel file import to mysql
 */
if (isset($_POST["import"])) {
    require_once('db.php');

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'uploads/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        // print_r($spreadSheetAry); exit;
        $sheetCount = count($spreadSheetAry);

        $category_id = 1;
        $location_id = 1;
        $order_type = 1;
        $check_status = 0;
        $qty_fulfiled = 1;
        $date = date("m/d/Y");

        for ($i = 1; $i <= $sheetCount; $i++) {
            $customer_note = "";
            if (isset($spreadSheetAry[$i][0])) {
                $customer_note = $mysqli->real_escape_string($spreadSheetAry[$i][0]);
            }

            $customer_username = "";
            if (isset($spreadSheetAry[$i][1])) {
                $customer_username = $mysqli->real_escape_string($spreadSheetAry[$i][1]);
            }

            $item_name = "";  //    <-- product name
            if (isset($spreadSheetAry[$i][3])) {
                $item_name = $mysqli->real_escape_string($spreadSheetAry[$i][3]);
            }

            $quantity = "";
            if (isset($spreadSheetAry[$i][3])) {
                $quantity = $mysqli->real_escape_string($spreadSheetAry[$i][4]);
            }



            if (
                !empty($customer_username) || !empty($item_name)
                || !empty($quantity)
            ) {
                $query = "INSERT INTO orders(customer_username, item_name, quantity, customer_note, date, qty_fulfiled, category_id, location_id, order_type, check_status)
                VALUES ('$customer_username', '$item_name', '$quantity', '$customer_note', '$date', '$qty_fulfiled', '$category_id', '$location_id', '$order_type', '$check_status')";
                //    echo 'query === '.$query; exit;
                if ($mysqli->query($query) === TRUE) {
                    $type = "success";
                    $message = "Success! Excel Data Imported into the Database";
                } else {
                    $type = "danger";
                    $message = "Error! Problem in Importing Excel Data";
                }
            }
        }
    } else {
        $type = "danger";
        $message = "Invalid File Type. Upload Excel File.";
    }
}
