<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'config.php';


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
} else {
    echo '';
}