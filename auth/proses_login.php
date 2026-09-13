<?php

session_start();

require_once "../config/koneksi.php";

$role = $_POST['role'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';