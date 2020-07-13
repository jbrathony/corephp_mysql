<?php

require_once('db.php');

// category list
$category_list_query = "SELECT id, category_name FROM category";
$category_list = $mysqli->query($category_list_query);

// location list
$location_list_query = "SELECT id, location FROM location";
$location_list = $mysqli->query($location_list_query);

// order list
$order_type_list_query = "SELECT id, type FROM order_type";
$order_type_list = $mysqli->query($order_type_list_query);

// date list
$select_date_query = "SELECT DISTINCT date FROM orders";
$date_list = $mysqli->query($select_date_query);

// customer name list
$select_customer_name_query = "SELECT DISTINCT customer_username AS customer_name FROM orders";
$customer_name_list = $mysqli->query($select_customer_name_query);

// filter
// $filter_order_query = "SELECT * FROM orders";
// $filter_result = $mysqli->query($filter_order_query);

$filter_date_list = "";
$filter_customer_list = "";
if (isset($_POST["date_list"]) && isset($_POST["customer_list"])) {
    $filter_date_list = $_POST["date_list"];
    $filter_customer_list = $_POST["customer_list"];
    $filter_order_query = "SELECT * FROM orders WHERE customer_username='$filter_customer_list' AND date='$filter_date_list'";
    $filter_result = $mysqli->query($filter_order_query);
}

if (isset($_POST["filter_error"])) {
    $filter_error_query = "SELECT * FROM orders WHERE error='1'";
    $filter_result = $mysqli->query($filter_error_query);
}

// update category by ajax call
if (isset($_POST['selectedCategory']) && isset($_POST['selectedRow'])) {
    $selected_row = $_POST['selectedRow'];
    $selected_category = $_POST['selectedCategory'];

    $update_query = "UPDATE orders SET category_id='$selected_category' WHERE id='$selected_row'";
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

    echo json_encode($response);
}


// update location by ajax call
if (isset($_POST['selectedLocation']) && isset($_POST['selectedRow'])) {
    $selected_row = $_POST['selectedRow'];
    $selected_location = $_POST['selectedLocation'];

    $update_query = "UPDATE orders SET location_id='$selected_location' WHERE id='$selected_row'";
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

    echo json_encode($response);
}


// update ordering by ajax call
if (isset($_POST['selectedOrderType']) && isset($_POST['selectedRow'])) {
    $selected_row = $_POST['selectedRow'];
    $selected_order_type = $_POST['selectedOrderType'];

    $update_query = "UPDATE orders SET order_type='$selected_order_type' WHERE id='$selected_row'";
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

    echo json_encode($response);
}


// update quantity fulfiled by ajax call
if (isset($_POST['updatedQtyFulfiled']) && isset($_POST['selectedRow'])) {
    $selected_row = $_POST['selectedRow'];
    $updated_qty_fulfiled = $_POST['updatedQtyFulfiled'];

    $update_query = "UPDATE orders SET qty_fulfiled='$updated_qty_fulfiled' WHERE id='$selected_row'";
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

    echo json_encode($response);
}


// update check_status by ajax call
if (isset($_POST['updatedStatus']) && isset($_POST['selectedRow'])) {
    $selected_row = $_POST['selectedRow'];
    $updated_status = $_POST['updatedStatus'];

    $update_query = "UPDATE orders SET check_status='$updated_status' WHERE id='$selected_row'";
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

    echo json_encode($response);
}
