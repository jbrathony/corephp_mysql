<?php
if (isset($_POST["import"])) {

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

        for ($i = 1; $i <= $sheetCount; $i++) {
            $customer_note = "";
            if (isset($spreadSheetAry[$i][0])) {
                $customer_note = $mysqli->real_escape_string($spreadSheetAry[$i][0]);
            }
            // echo 'customer_note === '.$customer_note."<br>";

            $customer_username = "";
            if (isset($spreadSheetAry[$i][1])) {
                $customer_username = $mysqli->real_escape_string($spreadSheetAry[$i][1]);
            }
            // echo 'customer_username === '.$customer_username."<br>";

            $item_name = "";
            if (isset($spreadSheetAry[$i][3])) {
                $item_name = $mysqli->real_escape_string($spreadSheetAry[$i][3]);
            }
            // echo 'item_name === '.$item_name."<br>";

            $quantity = "";
            if (isset($spreadSheetAry[$i][3])) {
                $quantity = $mysqli->real_escape_string($spreadSheetAry[$i][4]);
            }
            // echo 'quantity === '.$quantity."<br>";



            if (
                !empty($customer_username) || !empty($item_name)
                || !empty($quantity)
            ) {
                $query = "INSERT INTO orders(customer_username, item_name, quantity, customer_note)
                VALUES ('$customer_username', '$item_name', '$quantity', '$customer_note')";
                //    echo 'query === '.$query; exit;
                if ($mysqli->query($query) === TRUE) {
                    $type = "success";
                    $message = "Excel Data Imported into the Database";
                } else {
                    $type = "error";
                    $message = "Problem in Importing Excel Data";
                }
            }
        }
    } else {
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
    }
}
