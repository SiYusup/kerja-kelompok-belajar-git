<?php

namespace App\Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\GuruModel;
use App\Models\MapelModel;

class GuruModelTest extends TestCase
{
    private $guruModel;
    private $mapelModel;
    private $testMapelId;

    protected function setUp(): void
    {
        $this->guruModel = new GuruModel();
        $this->mapelModel = new MapelModel();
        $this->hapusDataTest();
        $this->testMapelId = $this->buatMapelTest();
        $this->assertNotNull($this->testMapelId, "Gagal menyiapkan data mapel relasi.");
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
        if ($this->testMapelId) {
            $this->mapelModel->deleteMapel($this->testMapelId);
        }
    }

    private function hapusDataTest(): void
    {
        foreach ($this->guruModel->getAllGuru() as $guru) {
            if (str_contains($guru['nama'], 'Real Test')) {
                $this->guruModel->deleteGuru($guru['id']);
            }
        }
    }

    private function buatMapelTest(): ?int
    {
        $this->mapelModel->createMapel(["mapel" => "Mapel Real Test"]);
        foreach ($this->mapelModel->getAllMapel() as $mapel) {
            if ($mapel['mapel'] === "Mapel Real Test") {
                return (int) $mapel['id'];
            }
        }
        return null;
    }

    private function dataGuru(): array
    {
        return [
            "id_mapel" => $this->testMapelId,
            "nama" => "Uji Guru Real Test",
            "nip" => "198505062008011002",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1985-05-06",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Merdeka No. 1"
        ];
    }

    public function testGetAllGuruReturnsArray(): void
    {
        $allGuru = $this->guruModel->getAllGuru();

        $this->assertIsArray($allGuru, "Hasil getAllGuru harus berupa array.");

        foreach ($allGuru as $guru) {
            $this->assertArrayHasKey('id', $guru, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('nama', $guru, "Kolom nama tidak ditemukan.");
            $this->assertArrayHasKey('nip', $guru, "Kolom nip tidak ditemukan.");
        }
    }

    public function testGetGuruByIdReturnsSingleData(): void
    {
        $this->assertTrue($this->guruModel->createGuru($this->dataGuru()), "Gagal menginput data guru uji.");

        $idUji = null;
        foreach ($this->guruModel->getAllGuru() as $guru) {
            if ($guru['nama'] === "Uji Guru Real Test") {
                $idUji = $guru['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data guru uji tidak ditemukan di database.");

        $detail = $this->guruModel->getGuruById($idUji);

        $this->assertIsArray($detail, "Hasil getGuruById harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Uji Guru Real Test", $detail['nama'], "nama tidak sesuai.");
        $this->assertSame("198505062008011002", $detail['nip'], "nip tidak sesuai.");
    }

    public function testCreateGuruReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->guruModel->createGuru($this->dataGuru()), "Gagal menginput data guru.");

        $daftarNama = array_column($this->guruModel->getAllGuru(), 'nama');
        $this->assertContains("Uji Guru Real Test", $daftarNama, "Data guru tidak ditemukan di database!");
    }

    public function testDeleteGuruReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->guruModel->createGuru($this->dataGuru()), "Gagal menginput data guru uji.");

        $idUji = null;
        foreach ($this->guruModel->getAllGuru() as $guru) {
            if ($guru['nama'] === "Uji Guru Real Test") {
                $idUji = $guru['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data guru uji tidak ditemukan di database.");

        $this->assertTrue($this->guruModel->deleteGuru($idUji), "Gagal menghapus data guru.");

        $this->assertNotContains("Uji Guru Real Test", array_column($this->guruModel->getAllGuru(), 'nama'), "Data guru masih ada setelah dihapus!");
    }

    public function testGetGuruDetailReturnsSingleDataWithMapel(): void
    {
        $this->assertTrue($this->guruModel->createGuru($this->dataGuru()), "Gagal menginput data guru uji.");

        $idUji = null;
        foreach ($this->guruModel->getAllGuru() as $guru) {
            if ($guru['nama'] === "Uji Guru Real Test") {
                $idUji = $guru['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data guru uji tidak ditemukan di database.");

        $detail = $this->guruModel->getGuruDetail($idUji);

        $this->assertIsArray($detail, "Hasil getGuruDetail harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Uji Guru Real Test", $detail['nama'], "nama tidak sesuai.");
        $this->assertArrayHasKey('nama_mapel', $detail, "Relasi nama_mapel tidak ditemukan.");
        $this->assertSame("Mapel Real Test", $detail['nama_mapel'], "nama_mapel tidak sesuai.");
    }

    public function testGetGuruDetailReturnsNullWhenNotFound(): void
    {
        $this->assertNull($this->guruModel->getGuruDetail(999999), "getGuruDetail harus mengembalikan null untuk id yang tidak ada.");
    }

    public function testGetGuruPaginateSupportsSearch(): void
    {
        $this->assertTrue($this->guruModel->createGuru($this->dataGuru()), "Gagal menginput data guru pertama.");
        $this->assertTrue($this->guruModel->createGuru([
            "id_mapel" => $this->testMapelId,
            "nama" => "Uji Guru Paginasi Real Test",
            "nip" => "199001102012012004",
            "jurusan" => "TKJ",
            "tempat_lahir" => "Surabaya",
            "tanggal_lahir" => "1990-01-10",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Pemuda No. 2"
        ]), "Gagal menginput data guru kedua.");

        $hasil = $this->guruModel->getGuruPaginate(10, 0, "Paginasi");

        $this->assertIsArray($hasil, "Hasil getGuruPaginate harus berupa array.");
        $this->assertLessThanOrEqual(10, count($hasil), "Jumlah hasil melebihi limit.");
        $this->assertContains("Uji Guru Paginasi Real Test", array_column($hasil, 'nama'), "Hasil pencarian tidak ditemukan.");
        $this->assertArrayHasKey('nama_mapel', $hasil[0] ?? [], "Kolom nama_mapel (relasi) tidak ditemukan.");
    }

    public function testCountGuruReturnsCorrectTotal(): void
    {
        $this->assertTrue($this->guruModel->createGuru($this->dataGuru()), "Gagal menginput data guru pertama.");
        $this->assertTrue($this->guruModel->createGuru([
            "id_mapel" => $this->testMapelId,
            "nama" => "Uji Guru Count Real Test",
            "nip" => "198006152008021001",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1980-06-15",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Asia Afrika No. 3"
        ]), "Gagal menginput data guru kedua.");

        $total = $this->guruModel->countGuru("Guru Count Real Test");

        $this->assertIsInt($total, "Hasil countGuru harus berupa integer.");
        $this->assertSame(1, $total, "Jumlah total guru hasil pencarian tidak sesuai.");
    }

    public function testUpdateGuruReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->guruModel->createGuru($this->dataGuru()), "Gagal menginput data guru uji.");

        $idUji = null;
        foreach ($this->guruModel->getAllGuru() as $guru) {
            if ($guru['nama'] === "Uji Guru Real Test") {
                $idUji = $guru['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data guru uji tidak ditemukan di database.");

        $result = $this->guruModel->updateGuru($idUji, [
            "id_mapel" => $this->testMapelId,
            "nama" => "Uji Guru Real Test",
            "nip" => "198505062008011002",
            "jurusan" => "TKJ",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1985-05-06",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Merdeka No. 10"
        ]);
        $this->assertTrue($result, "Gagal memperbarui data guru dari database.");

        $detail = $this->guruModel->getGuruDetail($idUji);
        $this->assertSame("TKJ", $detail['jurusan'], "jurusan tidak berubah setelah update.");
        $this->assertSame("PEREMPUAN", $detail['jenis_kelamin'], "jenis_kelamin tidak berubah setelah update.");
        $this->assertSame("Jl. Merdeka No. 10", $detail['alamat'], "alamat tidak berubah setelah update.");
    }
}
