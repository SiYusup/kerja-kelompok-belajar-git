<?php

// Mulai session untuk flash message (notifikasi SweetAlert) setelah redirect
session_start();

// Setup konfigurasi penting
require_once __DIR__ . '/../config/config.php'; 
require_once __DIR__ . '/../app/Core/App.php'; 
require_once __DIR__ . '/../app/Core/Controller.php'; 
require_once __DIR__ . '/../app/Core/Database.php'; 

use App\Core\App;

// Jalankan app enginenya
$app = new App();