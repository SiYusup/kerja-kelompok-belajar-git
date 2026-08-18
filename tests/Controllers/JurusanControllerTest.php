<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\JurusanController;
use App\Models\JurusanModel;

class JurusanControllerTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        $this->model = new JurusanModel();
        $this->hapusDataTest();
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
    }

    private function hapusDataTest(): void
    {
        foreach ($this->model->getAllJurusan() as $jurusan) {
            if (str_contains($jurusan['jurusan'], 'Real Test')) {
                $this->model->deleteJurusan($jurusan['id']);
            }
        }
    }

    private function buatJurusanTest(): int
    {
        $this->assertTrue($this->model->createJurusan(["jurusan" => "Uji Ctrl Jurusan Real Test"]), "Gagal membuat data jurusan uji.");
        foreach ($this->model->getAllJurusan() as $jurusan) {
            if ($jurusan['jurusan'] === "Uji Ctrl Jurusan Real Test") return (int) $jurusan['id'];
        }
        $this->fail("Data jurusan uji tidak ditemukan.");
    }

    public function testIndexMenampilkanListJurusan(): void
    {
        $this->buatJurusanTest();

        $_GET['search'] = "Uji Ctrl Jurusan";
        $controller = new JurusanController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        unset($_GET['search']);

        $this->assertStringContainsString('<title>Daftar Jurusan</title>', $output);
        $this->assertStringContainsString('Tambah Jurusan', $output);
        $this->assertStringContainsString('Uji Ctrl Jurusan Real Test', $output);
    }

    public function testIndexMenampilkanKosongSaatTidakAdaData(): void
    {
        $controller = new JurusanController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Daftar Jurusan', $output);
    }

    public function testIndexMenyaringDataSesuaiKeyword(): void
    {
        $this->model->createJurusan(["jurusan" => "Uji RPL Pencarian Real Test"]);
        $this->model->createJurusan(["jurusan" => "Uji TKJ Pencarian Real Test"]);

        $_GET['search'] = "RPL Pencarian";
        $controller = new JurusanController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        unset($_GET['search']);

        $this->assertStringContainsString('Uji RPL Pencarian Real Test', $output);
        $this->assertStringNotContainsString('Uji TKJ Pencarian Real Test', $output);
    }

    public function testIndexMenampilkanPaginationSaatDataLebihDariSatuHalaman(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $this->model->createJurusan(["jurusan" => sprintf("Uji Pagination %02d Real Test", $i)]);
        }
        $total = $this->model->countJurusan();

        $_GET['page'] = 2;
        $controller = new JurusanController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        unset($_GET['page']);

        $sisa = $total - 10;
        $barisHalaman2 = $sisa > 10 ? 10 : $sisa;

        $this->assertStringContainsString('pagination', $output);
        $this->assertStringContainsString("Menampilkan {$barisHalaman2} dari {$total} data jurusan.", $output);
    }

    public function testTambahMenampilkanForm(): void
    {
        $controller = new JurusanController();
        ob_start();
        $controller->tambah();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Tambah Jurusan</title>', $output);
        $this->assertStringContainsString('Form Tambah Jurusan', $output);
    }

    public function testEditMenampilkanFormDenganData(): void
    {
        $id = $this->buatJurusanTest();

        $controller = new JurusanController();
        ob_start();
        $controller->edit($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Edit Jurusan</title>', $output);
        $this->assertStringContainsString('Form Edit Jurusan', $output);
        $this->assertStringContainsString('Uji Ctrl Jurusan Real Test', $output);
    }

    public function testEditMenampilkanNotifikasiSaatTidakDitemukan(): void
    {
        $controller = new JurusanController();
        ob_start();
        $controller->edit(999999);
        $output = ob_get_clean();

        $this->assertStringContainsString('Data jurusan tidak ditemukan', $output);
    }
}
