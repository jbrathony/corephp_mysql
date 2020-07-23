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

// edited items list
$edited_items_query = "SELECT id, item_name FROM inventory WHERE edit_flag=1";
$edited_items = $mysqli->query($edited_items_query);

// get filter dropdown list
$filter_list_query = "SELECT id, filter_name FROM filter_list ORDER BY id";
$filter_list = $mysqli->query($filter_list_query);

// filter
$productname_filter = "";
if (isset($_POST["productname_filter"])) {
    $productname_filter = $_POST["productname_filter"];
    $filter_query = "SELECT * FROM inventory WHERE item_name='$productname_filter'";
    $filter_result = $mysqli->query($filter_query);
} else {
    $query = "SELECT * FROM inventory";
    $filter_result = $mysqli->query($query);
}

// filter EDITED ITEMS
if (isset($_POST['edited_item'])) {
    $edited_item = $_POST["edited_item"];
    $filter_query = "SELECT * FROM inventory WHERE id='$edited_item'";
    $filter_result = $mysqli->query($filter_query);
}


// filter by filter dropdown list
if (isset($_POST['filter_list'])) {
    $filter_value = $_POST["filter_list"];
    $filter_query = "";
    switch ($filter_value) {
            // edited items
        case 1:
            $filter_query = "SELECT * FROM inventory WHERE edit_flag='1'";
            break;
            // newly added items
        case 2:
            $filter_query = "SELECT * FROM inventory WHERE edit_flag='2'";
            break;
            // quantity in hand is zero
        case 3:
            $filter_query = "SELECT * FROM inventory WHERE quantity_in_hand='0'";
            break;
        default:
            $filter_query = "SELECT * FROM inventory WHERE edit_flag='1'";
            break;
    }
    $filter_result = $mysqli->query($filter_query);
}

// add produt by ajax call
if (isset($_POST['product_add'])) {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $cost = $_POST['cost'];
    $date = date("m/d/Y");
    $edit_flag = 2; // 2 is newly added items

    $insert_query = "INSERT INTO inventory(item_name, quantity_in_hand, date, cost, category_id, edit_flag) VALUES ('$product_name', '$quantity', '$date', '$cost', '$category', '$edit_flag')";
    $insert_result = $mysqli->query($insert_query);

    if ($insert_result === TRUE) {
        $last_id = $mysqli->insert_id;
        $get_new_added_row_query = "SELECT * FROM inventory WHERE id = '$last_id'";
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
        $response['message'] = "Product name is already exist!";
        $response['data'] = $insert_result;
    }

    echo json_encode($response);
}

// update orderdata by ajax call
if (isset($_POST['save_changes'])) {
    $ToBeUpdatedData = $_POST['save_changes'];
    $date = date("m/d/Y");
    $update_result;
    $edited_items = [];
    $edit_flag = 1; // 1 is edited items

    foreach ($ToBeUpdatedData as $row) {
        $field = $row['field'];
        $value = $row['value'];
        $selected_row = $row['selectedRow'];

        $update_query = "UPDATE inventory SET $field='$value', date='$date', edit_flag='$edit_flag' WHERE id='$selected_row'";
        $update_result = $mysqli->query($update_query);
    }

    if ($update_result === TRUE) {
        $get_edited_items_query = "SELECT id, item_name FROM inventory WHERE edit_flag=1";
        $get_edited_items = $mysqli->query($get_edited_items_query);

        while ($row = $get_edited_items->fetch_assoc()) {
            array_push($edited_items, $row);
        }

        $response['type'] = "success";
        $response['message'] = "Successfully updated!";
        $response['data'] = $edited_items;
    } else {
        $response['type']  = "error";
        $response['message'] = "Update failed!";
        $response['data'] = null;
    }

    echo json_encode($response);
}

// update edit flag by check box
if (isset($_POST['checkEditItem'])) {
    $selected_row = $_POST['checkEditItem'];
    $edited_items = [];


    $update_query = "UPDATE inventory SET edit_flag='0' WHERE id='$selected_row'";
    $update_result = $mysqli->query($update_query);

    if ($update_result === TRUE) {
        $get_edited_items_query = "SELECT id, item_name FROM inventory WHERE edit_flag=1";
        $get_edited_items = $mysqli->query($get_edited_items_query);

        while ($row = $get_edited_items->fetch_assoc()) {
            array_push($edited_items, $row);
        }

        $response['type'] = "success";
        $response['message'] = "Successfully checked!";
        $response['data'] = $edited_items;
    } else {
        $response['type']  = "error";
        $response['message'] = "Update failed!";
        $response['data'] = null;
    }

    echo json_encode($response);
}

// delete product by ajax call
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['delete_product'];
    $delete_query = "DELETE FROM inventory WHERE id='$product_id'";
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
