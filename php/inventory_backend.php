<?php

require_once('envionment.php');
require_once('db.php');

$base_url = $_ENV["base_url"];

if (!isset($_SESSION)) {
    session_start();
};

if (empty($_SESSION['roleid'])) {
    header("Location: " . $base_url);
}
// category list
$category_list_query = "SELECT id, category_name FROM category";
$category_list = $mysqli->query($category_list_query);

// filter
$productname_filter = "";
if (isset($_POST["productname_filter"])) {
    $productname_filter = $_POST["productname_filter"];
    $filter_query = "SELECT * FROM orders WHERE item_name='$productname_filter'";
    $filter_result = $mysqli->query($filter_query);
} else {
    $query = "SELECT * FROM orders";
    $filter_result = $mysqli->query($query);
}

// add produt by ajax call
if (isset($_POST['product_add'])) {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $cost = $_POST['cost'];
    $date = date("m/d/Y");

    $insert_query = "INSERT INTO orders(customer_username, item_name, quantity, DATE, qty_fulfiled, cost, category_id, location_id, order_type) VALUES ('ADMIN', '$product_name', '$quantity', '$date', '1', '$cost', '$category', '1', '1')";
    $insert_result = $mysqli->query($insert_query);

    if ($insert_result === TRUE) {
        $last_id = $mysqli->insert_id;
        $get_new_added_row_query = "SELECT * FROM orders WHERE id = '$last_id'";
        $get_new_added_row = $mysqli->query($get_new_added_row_query);

        $new_added_data = [];
        while ($row = $get_new_added_row->fetch_assoc()) {
            $new_added_data = $row;
        }

        $category = [];
        while ($row = $category_list->fetch_assoc()) {
            array_push($category, $row);
        }

        $return_data['new_data'] = $new_added_data;
        $return_data['category'] = $category;

        $response['type'] = "success";
        $response['message'] = "Successfully added!";
        $response['data'] = $return_data;
    } else {
        $response['type']  = "error";
        $response['message'] = "Add failed!";
        $response['data'] = $insert_query;
    }

    echo json_encode($response);
}

// update orderdata by ajax call
if (isset($_POST['save_changes'])) {
    $ToBeUpdatedData = $_POST['save_changes'];
    $date = date("m/d/Y");

    foreach ($ToBeUpdatedData as $row) {
        $field = $row['field'];
        $value = $row['value'];
        $selected_row = $row['selectedRow'];

        $update_query = "UPDATE orders SET $field='$value', date='$date' WHERE id='$selected_row'";
        $update_result = $mysqli->query($update_query);

        if ($update_result === TRUE) {
            $response['type'] = "success";
            $response['message'] = "Successfully updated!";
            $response['data'] = $update_result;
        } else {
            $response['type']  = "error";
            $response['message'] = "Update failed!";
            $response['data'] = null;
        }
    }

    echo json_encode($response);
}

// delete product by ajax call
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['delete_product'];
    $delete_query = "DELETE FROM orders WHERE id='$product_id'";
    $delete_result = $mysqli->query($delete_query);

    if ($delete_result === TRUE) {
        $response['type'] = "success";
        $response['message'] = "Successfully deleted!";
        $response['data'] = $delete_result;
    } else {
        $response['type']  = "error";
        $response['message'] = "Delete failed!";
        $response['data'] = null;
    }

    echo json_encode($response);
}

// dropdown inventory list as excel
// if (isset($_POST['download_inventory_list'])) {
//     $expo_query = "SELECT  `customer_note`, `customer_username`,`item_name`,`quantity` FROM orders";
//     $result = $mysqli->query($expo_query);
//     $columnHeader = '';
//     $columnHeader = "Customer Note" . "\t" . "Customer Username" . "\t" . "Item name" . "\t" . "Quantity" . "\t";

//     $setData = '';
//     while ($rec = mysqli_fetch_row($result)) {
//         $rowData = '';
//         foreach ($rec as $value) {
//             $value = '"' . $value . '"' . "\t";
//             $rowData .= $value;
//         }
//         $setData .= trim($rowData) . "\n";
//     }

//     header("Content-type: application/octet-stream");
//     header("Content-Disposition: attachment; filename=inventory.xls");
//     header("Pragma: no-cache");
//     header("Expires: 0");

//     echo ucwords($columnHeader) . "\n" . $setData . "\n";
//     header("Location: " . $base_url .'inventory.php');
// }
