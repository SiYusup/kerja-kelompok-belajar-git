<?php

namespace App\Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\JurusanModel;

class JurusanModelTest extends TestCase
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
        $this->assertTrue($this->model->createJurusan(["jurusan" => "Uji Jurusan Real Test"]), "Gagal membuat data jurusan uji.");
        foreach ($this->model->getAllJurusan() as $jurusan) {
            if ($jurusan['jurusan'] === "Uji Jurusan Real Test") return (int) $jurusan['id'];
        }
        $this->fail("Data jurusan uji tidak ditemukan.");
    }

    public function testGetAllJurusanReturnsArray(): void
    {
        $allJurusan = $this->model->getAllJurusan();

        $this->assertIsArray($allJurusan, "Hasil getAllJurusan harus berupa array.");

        foreach ($allJurusan as $jurusan) {
            $this->assertArrayHasKey('id', $jurusan, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('jurusan', $jurusan, "Kolom jurusan tidak ditemukan.");
        }
    }

    public function testGetJurusanByIdReturnsSingleData(): void
    {
        $idUji = $this->buatJurusanTest();

        $detail = $this->model->getJurusanById($idUji);

        $this->assertIsArray($detail, "Hasil getJurusanById harus berupa array.");
        $this->assertSame($idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Uji Jurusan Real Test", $detail['jurusan'], "jurusan tidak sesuai.");
    }

    public function testGetJurusanByIdReturnsNullWhenNotFound(): void
    {
        $this->assertNull($this->model->getJurusanById(999999), "getJurusanById harus mengembalikan null untuk id yang tidak ada.");
    }

    public function testCreateJurusanReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->model->createJurusan(["jurusan" => "Uji Jurusan Real Test"]), "Gagal menambahkan data jurusan.");

        $this->assertContains("Uji Jurusan Real Test", array_column($this->model->getAllJurusan(), 'jurusan'), "Data jurusan tidak ditemukan di database!");
    }

    public function testUpdateJurusanReturnsTrueOnSuccess(): void
    {
        $idUji = $this->buatJurusanTest();

        $this->assertTrue($this->model->updateJurusan($idUji, ["jurusan" => "Uji Jurusan Update Real Test"]), "Gagal memperbarui data jurusan.");

        $detail = $this->model->getJurusanById($idUji);
        $this->assertSame("Uji Jurusan Update Real Test", $detail['jurusan'], "jurusan tidak berubah setelah update.");
    }

    public function testDeleteJurusanReturnsTrueOnSuccess(): void
    {
        $idUji = $this->buatJurusanTest();

        $this->assertTrue($this->model->deleteJurusan($idUji), "Gagal menghapus data jurusan.");

        $this->assertNull($this->model->getJurusanById($idUji), "Data jurusan masih ada setelah dihapus!");
    }

    public function testSearchJurusanMenyaringBerdasarkanKeyword(): void
    {
        $this->model->createJurusan(["jurusan" => "Uji Pencarian RPL Real Test"]);
        $this->model->createJurusan(["jurusan" => "Uji Pencarian TKJ Real Test"]);
        $this->model->createJurusan(["jurusan" => "Uji Pencarian Lainnya Real Test"]);

        $hasil = $this->model->searchJurusan("Pencarian RPL Real Test", 10, 0);

        $this->assertCount(1, $hasil, "Pencarian 'Pencarian RPL Real Test' harus mengembalikan 1 data.");
        $this->assertSame("Uji Pencarian RPL Real Test", $hasil[0]['jurusan'], "Data hasil pencarian tidak sesuai.");
    }

    public function testCountJurusanMenghitungTotalData(): void
    {
        $sebelum = $this->model->countJurusan();

        $this->model->createJurusan(["jurusan" => "Uji Hitung RPL Real Test"]);
        $this->model->createJurusan(["jurusan" => "Uji Hitung TKJ Real Test"]);

        $sesudah = $this->model->countJurusan();

        $this->assertSame($sebelum + 2, $sesudah, "countJurusan harus bertambah 2 setelah insert.");
        $this->assertSame(2, $this->model->countJurusan("Uji Hitung"), "countJurusan dengan keyword harus sesuai.");
    }
}
