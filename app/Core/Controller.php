<?php

namespace App\Core;

class Controller 
{
    public function view(string $view, array $data = array()) {
        extract($data);
        // include (bukan require_once) agar template dapat dirender ulang dalam satu proses,
        // misalnya saat diuji berulang kali oleh PHPUnit.
        include __DIR__ . "/../Views/layouts/Header.php";
        include __DIR__ . "/../Views/" . $view . '.php';
        include __DIR__ . "/../Views/layouts/Footer.php";
    }

    public function model($model) {
        require_once __DIR__ . "/../Models/" . $model . ".php";
        $modelClass = 'App\\Models\\' . $model;
        return new $modelClass;
    }
}