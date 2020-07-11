<?php

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
$filter_order_query = "SELECT * FROM orders";
$filter_result = $mysqli->query($filter_order_query);

