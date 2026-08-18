<?php
require_once 'config/db.php';
require_once 'controllers/AbsenController.php';

// 2. Inisialisasi koneksi Database
$database = new Database();
$db = $database->getConnection();

// 3. Jalankan Controller
$controller = new AbsensiController($db);
$controller->index();
?>