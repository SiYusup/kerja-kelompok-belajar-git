<?php

namespace App\Tests\Core;

use PHPUnit\Framework\TestCase;
use App\Core\Controller;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;

class ControllerTest extends TestCase
{
    public function testModelMengembalikanInstansiModel(): void
    {
        $controller = new Controller();

        $kelas = $controller->model('KelasModel');
        $siswa = $controller->model('SiswaModel');
        $guru = $controller->model('GuruModel');

        $this->assertInstanceOf(KelasModel::class, $kelas);
        $this->assertInstanceOf(SiswaModel::class, $siswa);
        $this->assertInstanceOf(GuruModel::class, $guru);
    }

    public function testViewMerenderHeaderDanIsiView(): void
    {
        $controller = new Controller();

        ob_start();
        $controller->view('kelas/index', [
            'title' => 'Daftar Kelas',
            'kelas' => [['id' => 1, 'kelas' => 'XII RPL Uji View']],
        ]);
        $output = ob_get_clean();

        $this->assertStringContainsString('<!doctype html>', $output);
        $this->assertStringContainsString('<title>Daftar Kelas</title>', $output);
        $this->assertStringContainsString('Daftar Kelas', $output);
        $this->assertStringContainsString('XII RPL Uji View', $output);
        $this->assertStringContainsString('</html>', $output);
    }

    public function testViewDapatDirenderBerulangKali(): void
    {
        $controller = new Controller();

        // Panggilan pertama
        ob_start();
        $controller->view('kelas/index', ['title' => 'Satu', 'kelas' => []]);
        $pertama = ob_get_clean();

        // Panggilan kedua: harus tetap memunculkan isi view (bukan kosong)
        ob_start();
        $controller->view('kelas/index', ['title' => 'Dua', 'kelas' => [['id' => 2, 'kelas' => 'XI TKJ Uji Ulang']]]);
        $kedua = ob_get_clean();

        $this->assertStringContainsString('<title>Satu</title>', $pertama);
        $this->assertStringContainsString('<title>Dua</title>', $kedua);
        $this->assertStringContainsString('XI TKJ Uji Ulang', $kedua);
    }
}
