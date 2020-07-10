<?php
require_once('./php/envionment.php');
$base_url = $_ENV["base_url"];

session_start();
session_destroy();
header("Location: $base_url");
