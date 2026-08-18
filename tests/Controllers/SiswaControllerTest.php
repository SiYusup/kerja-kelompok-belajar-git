<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\SiswaController;
use App\Models\SiswaModel;

class SiswaControllerTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        $this->model = new SiswaModel();
        $this->hapusDataTest();
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
        unset($_GET['search'], $_GET['page']);
    }

    private function hapusDataTest(): void
    {
        foreach ($this->model->getAllSiswa() as $siswa) {
            if (str_contains($siswa['nama'], 'Real Test')) {
                $this->model->deleteSiswa($siswa['id']);
            }
        }
    }

    private function buatSiswaTest(string $nama = "Uji Ctrl Siswa Real Test"): int
    {
        $this->assertTrue($this->model->createSiswa([
            "id_kelas" => 1,
            "nama" => $nama,
            "nisn" => "99887001",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-01-01",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Uji No. 1"
        ]), "Gagal membuat data siswa uji.");
        foreach ($this->model->getAllSiswa() as $siswa) {
            if ($siswa['nama'] === $nama) return (int) $siswa['id'];
        }
        $this->fail("Data siswa uji tidak ditemukan.");
    }

    public function testIndexMenampilkanDaftarSiswa(): void
    {
        $this->buatSiswaTest();

        $controller = new SiswaController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Daftar Siswa</title>', $output);
        $this->assertStringContainsString('Tambah Siswa', $output);
        $this->assertStringContainsString('Uji Ctrl Siswa Real Test', $output);
    }

    public function testIndexDenganPencarianMenampilkanDataCocok(): void
    {
        $this->buatSiswaTest();
        $_GET['search'] = 'Uji Ctrl Siswa';

        $controller = new SiswaController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Uji Ctrl Siswa Real Test', $output);
    }

    public function testIndexDenganHalamanKedua(): void
    {
        $_GET['page'] = '2';

        $controller = new SiswaController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Daftar Siswa</title>', $output);
    }

    public function testDetailMenampilkanDataSiswa(): void
    {
        $id = $this->buatSiswaTest();

        $controller = new SiswaController();
        ob_start();
        $controller->detail($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Detail Siswa</title>', $output);
        $this->assertStringContainsString('Uji Ctrl Siswa Real Test', $output);
        $this->assertStringContainsString('NISN', $output);
    }

    public function testDetailMenampilkanNotifikasiSaatTidakDitemukan(): void
    {
        $controller = new SiswaController();
        ob_start();
        $controller->detail(999999);
        $output = ob_get_clean();

        $this->assertStringContainsString('Data siswa tidak ditemukan', $output);
    }

    public function testTambahMenampilkanForm(): void
    {
        $controller = new SiswaController();
        ob_start();
        $controller->tambah();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Tambah Siswa</title>', $output);
        $this->assertStringContainsString('Form Tambah Siswa', $output);
    }

    public function testEditMenampilkanFormDenganData(): void
    {
        $id = $this->buatSiswaTest();

        $controller = new SiswaController();
        ob_start();
        $controller->edit($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Edit Siswa</title>', $output);
        $this->assertStringContainsString('Form Edit Siswa', $output);
        $this->assertStringContainsString('Uji Ctrl Siswa Real Test', $output);
    }

    public function testEditMenampilkanNotifikasiSaatTidakDitemukan(): void
    {
        $controller = new SiswaController();
        ob_start();
        $controller->edit(999999);
        $output = ob_get_clean();

        $this->assertStringContainsString('Data siswa tidak ditemukan', $output);
    }
}
