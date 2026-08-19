<?php

require_once 'config/db.php';
require_once 'controllers/AbsenController.php';

// 1. Inisialisasi koneksi Database
$database = new Database();
$db = $database->getConnection();

// 2 Jalankan Controller
$controller = new AbsensiController($db);
$controller->index();
?>