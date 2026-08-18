<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\KelasController;
use App\Models\KelasModel;

class KelasControllerTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        $this->model = new KelasModel();
        $this->hapusDataTest();
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
    }

    private function hapusDataTest(): void
    {
        foreach ($this->model->getAllKelas() as $kelas) {
            if (str_contains($kelas['kelas'], 'Real Test')) {
                $this->model->deleteKelas($kelas['id']);
            }
        }
    }

    private function buatKelasTest(): int
    {
        $this->assertTrue($this->model->createKelas(["kelas" => "Uji Kelas Real Test"]), "Gagal membuat data kelas uji.");
        foreach ($this->model->getAllKelas() as $kelas) {
            if ($kelas['kelas'] === "Uji Kelas Real Test") return (int) $kelas['id'];
        }
        $this->fail("Data kelas uji tidak ditemukan.");
    }

    public function testIndexMenampilkanDaftarKelas(): void
    {
        $this->buatKelasTest();

        $_GET['search'] = "Uji Kelas Real Test";
        $controller = new KelasController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        unset($_GET['search']);

        $this->assertStringContainsString('<title>Daftar Kelas</title>', $output);
        $this->assertStringContainsString('Uji Kelas Real Test', $output);
    }

    public function testIndexMenampilkanKosongSaatTidakAdaData(): void
    {
        $controller = new KelasController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Daftar Kelas', $output);
    }

    public function testIndexMenyaringDataSesuaiKeyword(): void
    {
        $this->model->createKelas(["kelas" => "RPL Real Test"]);
        $this->model->createKelas(["kelas" => "TKJ Real Test"]);

        $_GET['search'] = "RPL Real Test";
        $controller = new KelasController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        unset($_GET['search']);

        $this->assertStringContainsString('RPL Real Test', $output);
        $this->assertStringNotContainsString('TKJ Real Test', $output);
    }

    public function testTambahMenampilkanForm(): void
    {
        $controller = new KelasController();
        ob_start();
        $controller->tambah();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Tambah Kelas</title>', $output);
        $this->assertStringContainsString('Form Tambah Kelas', $output);
    }

    public function testEditMenampilkanFormDenganData(): void
    {
        $id = $this->buatKelasTest();

        $controller = new KelasController();
        ob_start();
        $controller->edit($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Edit Kelas</title>', $output);
        $this->assertStringContainsString('Form Edit Kelas', $output);
        $this->assertStringContainsString('Uji Kelas Real Test', $output);
    }

    public function testEditMenampilkanNotifikasiSaatTidakDitemukan(): void
    {
        $controller = new KelasController();
        ob_start();
        $controller->edit(999999);
        $output = ob_get_clean();

        $this->assertStringContainsString('Data kelas tidak ditemukan', $output);
    }
}
