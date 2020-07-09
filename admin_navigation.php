<?php

$base_url = "http://exceltomysql.com/";

if (!isset($_SESSION)) {
    session_start();
};

if (empty($_SESSION['roleid'])) {
    header("Location: " . $base_url);
}

require_once('./php/db.php');
echo "admin navitation";
