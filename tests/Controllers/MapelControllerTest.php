<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\MapelController;
use App\Models\MapelModel;

class MapelControllerTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        $this->model = new MapelModel();
        $this->hapusDataTest();
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
    }

    private function hapusDataTest(): void
    {
        foreach ($this->model->getAllMapel() as $mapel) {
            if (str_contains($mapel['mapel'], 'Real Test')) {
                $this->model->deleteMapel($mapel['id']);
            }
        }
    }

    private function buatMapelTest(): int
    {
        $this->assertTrue($this->model->createMapel(["mapel" => "Uji Ctrl Mapel Real Test"]), "Gagal membuat data mapel uji.");
        foreach ($this->model->getAllMapel() as $mapel) {
            if ($mapel['mapel'] === "Uji Ctrl Mapel Real Test") return (int) $mapel['id'];
        }
        $this->fail("Data mapel uji tidak ditemukan.");
    }

    public function testIndexMenampilkanDaftarMapel(): void
    {
        $this->buatMapelTest();

        $controller = new MapelController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Daftar Mata Pelajaran</title>', $output);
        $this->assertStringContainsString('Uji Ctrl Mapel Real Test', $output);
    }

    public function testIndexMenampilkanKosongSaatTidakAdaData(): void
    {
        $controller = new MapelController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Daftar Mata Pelajaran', $output);
    }
}
