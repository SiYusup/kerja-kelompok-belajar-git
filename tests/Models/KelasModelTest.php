<?php

namespace App\Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\KelasModel;

class KelasModelTest extends TestCase
{
    private $kelasModel;

    protected function setUp(): void
    {
        $this->kelasModel = new KelasModel();
        $this->hapusDataTest();
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
    }

    private function hapusDataTest(): void
    {
        foreach ($this->kelasModel->getAllKelas() as $kelas) {
            if (str_contains($kelas['kelas'], 'Real Test')) {
                $this->kelasModel->deleteKelas($kelas['id']);
            }
        }
    }

    public function testGetAllKelasReturnsArray(): void
    {
        $allKelas = $this->kelasModel->getAllKelas();

        $this->assertIsArray($allKelas, "Hasil getAllKelas harus berupa array.");

        foreach ($allKelas as $kelas) {
            $this->assertArrayHasKey('id', $kelas, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('kelas', $kelas, "Kolom kelas tidak ditemukan.");
        }
    }

    public function testGetKelasByIdReturnsSingleData(): void
    {
        $data = ["kelas" => "XII RPL Real Test"];

        $this->assertTrue($this->kelasModel->createKelas($data), "Gagal menginput data kelas uji.");

        $idUji = null;
        foreach ($this->kelasModel->getAllKelas() as $kelas) {
            if ($kelas['kelas'] === "XII RPL Real Test") {
                $idUji = $kelas['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data kelas uji tidak ditemukan di database.");

        $detail = $this->kelasModel->getKelasById($idUji);

        $this->assertIsArray($detail, "Hasil getKelasById harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("XII RPL Real Test", $detail['kelas'], "kelas tidak sesuai.");
    }

    public function testCreateKelasReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->kelasModel->createKelas(["kelas" => "XII RPL Real Test"]), "Gagal menginput data kelas.");

        $daftarKelas = array_column($this->kelasModel->getAllKelas(), 'kelas');
        $this->assertContains("XII RPL Real Test", $daftarKelas, "Data kelas tidak ditemukan di database!");
    }

    public function testUpdateKelasReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->kelasModel->createKelas(["kelas" => "XII RPL Real Test"]), "Gagal menginput data kelas uji.");

        $idUji = null;
        foreach ($this->kelasModel->getAllKelas() as $kelas) {
            if ($kelas['kelas'] === "XII RPL Real Test") {
                $idUji = $kelas['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data kelas uji tidak ditemukan di database.");

        $this->assertTrue($this->kelasModel->updateKelas($idUji, ["kelas" => "XII TKJ Real Test"]), "Gagal memperbarui data kelas.");

        $detail = $this->kelasModel->getKelasById($idUji);
        $this->assertSame("XII TKJ Real Test", $detail['kelas'], "kelas tidak berubah setelah update.");
    }

    public function testSearchKelasMenyaringBerdasarkanKeyword(): void
    {
        $this->kelasModel->createKelas(["kelas" => "XII RPL Real Test"]);
        $this->kelasModel->createKelas(["kelas" => "XI RPL Real Test"]);
        $this->kelasModel->createKelas(["kelas" => "XII TKJ Real Test"]);

        $hasil = $this->kelasModel->searchKelas("RPL Real Test", 10, 0);

        $this->assertCount(2, $hasil, "Pencarian 'RPL Real Test' harus mengembalikan 2 data.");
        foreach ($hasil as $row) {
            $this->assertStringContainsString("RPL Real Test", $row['kelas'], "Hasil pencarian mengandung data yang tidak sesuai.");
        }
    }

    public function testCountKelasMenghitungTotalData(): void
    {
        $sebelum = $this->kelasModel->countKelas();

        $this->kelasModel->createKelas(["kelas" => "XII RPL Real Test"]);
        $this->kelasModel->createKelas(["kelas" => "XI RPL Real Test"]);

        $sesudah = $this->kelasModel->countKelas();

        $this->assertSame($sebelum + 2, $sesudah, "countKelas harus bertambah 2 setelah insert.");
        $this->assertSame(2, $this->kelasModel->countKelas("RPL Real Test"), "countKelas dengan keyword harus sesuai.");
    }

    public function testDeleteKelasReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->kelasModel->createKelas(["kelas" => "XII TKJ Real Test"]), "Gagal menginput data kelas uji.");

        $idUji = null;
        foreach ($this->kelasModel->getAllKelas() as $kelas) {
            if ($kelas['kelas'] === "XII TKJ Real Test") {
                $idUji = $kelas['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data kelas uji tidak ditemukan di database.");

        $this->assertTrue($this->kelasModel->deleteKelas($idUji), "Gagal menghapus data kelas.");

        $this->assertNotContains("XII TKJ Real Test", array_column($this->kelasModel->getAllKelas(), 'kelas'), "Data kelas masih ada setelah dihapus!");
    }
}
