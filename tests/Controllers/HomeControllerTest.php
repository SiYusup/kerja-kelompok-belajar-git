<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;

class HomeControllerTest extends TestCase
{
    public function testIndexMerenderHalamanBeranda(): void
    {
        $controller = new HomeController();

        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Beranda</title>', $output);
        $this->assertStringContainsString('Pilih Menu', $output);
        $this->assertStringContainsString('Cek Siswa', $output);
        $this->assertStringContainsString('Cek Guru', $output);
        $this->assertStringContainsString('Absensi Siswa', $output);
    }
}
