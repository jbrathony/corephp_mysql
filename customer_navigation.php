<?php

$base_url = "http://exceltomysql.com/";

if (!isset($_SESSION)) {
    session_start();
};

if (empty($_SESSION['roleid'])) {
    header("Location: " . $base_url);
}

echo "customer navigation";
