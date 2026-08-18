<?php

namespace App\Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\MapelModel;

class MapelModelTest extends TestCase
{
    private $mapelModel;

    protected function setUp(): void
    {
        $this->mapelModel = new MapelModel();
        $this->hapusDataTest();
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
    }

    private function hapusDataTest(): void
    {
        foreach ($this->mapelModel->getAllMapel() as $mapel) {
            if (str_contains($mapel['mapel'], 'Real Test')) {
                $this->mapelModel->deleteMapel($mapel['id']);
            }
        }
    }

    public function testGetAllMapelReturnsArray(): void
    {
        $allMapel = $this->mapelModel->getAllMapel();

        $this->assertIsArray($allMapel, "Hasil getAllMapel harus berupa array.");

        foreach ($allMapel as $mapel) {
            $this->assertArrayHasKey('id', $mapel, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('mapel', $mapel, "Kolom mapel tidak ditemukan.");
        }
    }

    public function testGetMapelByIdReturnsSingleData(): void
    {
        $data = ["mapel" => "Matematika Real Test"];

        $this->assertTrue($this->mapelModel->createMapel($data), "Gagal menginput data mapel uji.");

        $idUji = null;
        foreach ($this->mapelModel->getAllMapel() as $mapel) {
            if ($mapel['mapel'] === "Matematika Real Test") {
                $idUji = $mapel['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data mapel uji tidak ditemukan di database.");

        $detail = $this->mapelModel->getMapelById($idUji);

        $this->assertIsArray($detail, "Hasil getMapelById harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Matematika Real Test", $detail['mapel'], "mapel tidak sesuai.");
    }

    public function testCreateMapelReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->mapelModel->createMapel(["mapel" => "Matematika Real Test"]), "Gagal menginput data mapel.");

        $daftarMapel = array_column($this->mapelModel->getAllMapel(), 'mapel');
        $this->assertContains("Matematika Real Test", $daftarMapel, "Data mapel tidak ditemukan di database!");
    }

    public function testDeleteMapelReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->mapelModel->createMapel(["mapel" => "B. Indonesia Real Test"]), "Gagal menginput data mapel uji.");

        $idUji = null;
        foreach ($this->mapelModel->getAllMapel() as $mapel) {
            if ($mapel['mapel'] === "B. Indonesia Real Test") {
                $idUji = $mapel['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data mapel uji tidak ditemukan di database.");

        $this->assertTrue($this->mapelModel->deleteMapel($idUji), "Gagal menghapus data mapel.");

        $this->assertNotContains("B. Indonesia Real Test", array_column($this->mapelModel->getAllMapel(), 'mapel'), "Data mapel masih ada setelah dihapus!");
    }
}
