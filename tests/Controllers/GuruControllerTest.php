<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\GuruController;
use App\Models\GuruModel;
use App\Models\MapelModel;

class GuruControllerTest extends TestCase
{
    private $model;
    private $mapelModel;
    private $testMapelId;

    protected function setUp(): void
    {
        $this->model = new GuruModel();
        $this->mapelModel = new MapelModel();
        $this->hapusDataTest();
        $this->testMapelId = $this->buatMapelTest();
        $this->assertNotNull($this->testMapelId, "Gagal menyiapkan data mapel relasi.");
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
        unset($_GET['search'], $_GET['page']);
    }

    private function hapusDataTest(): void
    {
        foreach ($this->model->getAllGuru() as $guru) {
            if (str_contains($guru['nama'], 'Real Test')) {
                $this->model->deleteGuru($guru['id']);
            }
        }
        foreach ($this->mapelModel->getAllMapel() as $mapel) {
            if (str_contains($mapel['mapel'], 'Real Test')) {
                $this->mapelModel->deleteMapel($mapel['id']);
            }
        }
    }

    private function buatMapelTest(): ?int
    {
        $this->mapelModel->createMapel(["mapel" => "Mapel Ctrl Guru Real Test"]);
        foreach ($this->mapelModel->getAllMapel() as $mapel) {
            if ($mapel['mapel'] === "Mapel Ctrl Guru Real Test") return (int) $mapel['id'];
        }
        return null;
    }

    private function buatGuruTest(string $nama = "Uji Ctrl Guru Real Test"): int
    {
        $this->assertTrue($this->model->createGuru([
            "id_mapel" => $this->testMapelId,
            "nama" => $nama,
            "nip" => "198501012008011001",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1985-01-01",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Guru Uji No. 1"
        ]), "Gagal membuat data guru uji.");
        foreach ($this->model->getAllGuru() as $guru) {
            if ($guru['nama'] === $nama) return (int) $guru['id'];
        }
        $this->fail("Data guru uji tidak ditemukan.");
    }

    public function testIndexMenampilkanDaftarGuru(): void
    {
        $this->buatGuruTest();

        $controller = new GuruController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Daftar Guru</title>', $output);
        $this->assertStringContainsString('Tambah Guru', $output);
        $this->assertStringContainsString('Uji Ctrl Guru Real Test', $output);
    }

    public function testIndexDenganPencarianMenampilkanDataCocok(): void
    {
        $this->buatGuruTest();
        $_GET['search'] = 'Uji Ctrl Guru';

        $controller = new GuruController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Uji Ctrl Guru Real Test', $output);
    }

    public function testDetailMenampilkanDataGuru(): void
    {
        $id = $this->buatGuruTest();

        $controller = new GuruController();
        ob_start();
        $controller->detail($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Detail Guru</title>', $output);
        $this->assertStringContainsString('Uji Ctrl Guru Real Test', $output);
        $this->assertStringContainsString('Mapel Ctrl Guru Real Test', $output);
    }

    public function testDetailMenampilkanNotifikasiSaatTidakDitemukan(): void
    {
        $controller = new GuruController();
        ob_start();
        $controller->detail(999999);
        $output = ob_get_clean();

        $this->assertStringContainsString('Data guru tidak ditemukan', $output);
    }

    public function testTambahMenampilkanForm(): void
    {
        $controller = new GuruController();
        ob_start();
        $controller->tambah();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Tambah Guru</title>', $output);
        $this->assertStringContainsString('Form Tambah Guru', $output);
    }

    public function testEditMenampilkanFormDenganData(): void
    {
        $id = $this->buatGuruTest();

        $controller = new GuruController();
        ob_start();
        $controller->edit($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Edit Guru</title>', $output);
        $this->assertStringContainsString('Form Edit Guru', $output);
        $this->assertStringContainsString('Uji Ctrl Guru Real Test', $output);
    }

    public function testEditMenampilkanNotifikasiSaatTidakDitemukan(): void
    {
        $controller = new GuruController();
        ob_start();
        $controller->edit(999999);
        $output = ob_get_clean();

        $this->assertStringContainsString('Data guru tidak ditemukan', $output);
    }
}
