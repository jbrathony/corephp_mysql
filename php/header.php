<?php
$base_url = $_ENV["base_url"];

if (!isset($_SESSION)) {
    session_start();
};

if (isset($_SESSION['roleid'])) {
    if ($_SESSION['roleid'] == 1) {
        header("Location: " . $base_url . "admin_navigation.php");
    } else {
        header("Location: " . $base_url . "customer_panel.php");
    }
} else {
    require_once('./php/login.php');
}
