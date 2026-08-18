<?php

namespace App\Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\AbsensiModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\MapelModel;
use App\Models\KelasModel;

class AbsensiModelTest extends TestCase
{
    private $absensiModel;
    private $guruModel;
    private $siswaModel;
    private $mapelModel;
    private $kelasModel;

    private $testMapelId;
    private $testKelasId;
    private $testGuruId;
    private $testSiswaId;

    protected function setUp(): void
    {
        $this->absensiModel = new AbsensiModel();
        $this->guruModel = new GuruModel();
        $this->siswaModel = new SiswaModel();
        $this->mapelModel = new MapelModel();
        $this->kelasModel = new KelasModel();

        $this->hapusDataTest();
        $this->testMapelId = $this->buatMapelTest();
        $this->testKelasId = $this->buatKelasTest();
        $this->testGuruId = $this->buatGuruTest();
        $this->testSiswaId = $this->buatSiswaTest();

        $this->assertNotNull($this->testMapelId, "Gagal menyiapkan data mapel relasi.");
        $this->assertNotNull($this->testKelasId, "Gagal menyiapkan data kelas relasi.");
        $this->assertNotNull($this->testGuruId, "Gagal menyiapkan data guru relasi.");
        $this->assertNotNull($this->testSiswaId, "Gagal menyiapkan data siswa relasi.");
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
    }

    private function hapusDataTest(): void
    {
        foreach ($this->absensiModel->getAllAbsensi() as $absensi) {
            if (str_contains($absensi['nama_guru'], 'Real Test') || str_contains($absensi['nama_siswa'], 'Real Test')) {
                $this->absensiModel->deleteAbsensi($absensi['id']);
            }
        }
        foreach ($this->guruModel->getAllGuru() as $guru) {
            if (str_contains($guru['nama'], 'Real Test')) {
                $this->guruModel->deleteGuru($guru['id']);
            }
        }
        foreach ($this->siswaModel->getAllSiswa() as $siswa) {
            if (str_contains($siswa['nama'], 'Real Test')) {
                $this->siswaModel->deleteSiswa($siswa['id']);
            }
        }
        foreach ($this->mapelModel->getAllMapel() as $mapel) {
            if (str_contains($mapel['mapel'], 'Real Test')) {
                $this->mapelModel->deleteMapel($mapel['id']);
            }
        }
        foreach ($this->kelasModel->getAllKelas() as $kelas) {
            if (str_contains($kelas['kelas'], 'Real Test')) {
                $this->kelasModel->deleteKelas($kelas['id']);
            }
        }
    }

    private function buatMapelTest(): ?int
    {
        $this->mapelModel->createMapel(["mapel" => "Mapel Absensi Real Test"]);
        foreach ($this->mapelModel->getAllMapel() as $row) {
            if ($row['mapel'] === "Mapel Absensi Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatKelasTest(): ?int
    {
        $this->kelasModel->createKelas(["kelas" => "Kelas Real Test"]);
        foreach ($this->kelasModel->getAllKelas() as $row) {
            if ($row['kelas'] === "Kelas Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatGuruTest(): ?int
    {
        $this->guruModel->createGuru([
            "id_mapel" => $this->testMapelId,
            "nama" => "Uji Guru Absensi Real Test",
            "nip" => "198505062008011002",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1985-05-06",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Merdeka No. 1"
        ]);
        foreach ($this->guruModel->getAllGuru() as $row) {
            if ($row['nama'] === "Uji Guru Absensi Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatSiswaTest(): ?int
    {
        $this->siswaModel->createSiswa([
            "id_kelas" => $this->testKelasId,
            "nama" => "Uji Siswa Absensi Real Test",
            "nisn" => "9988123400",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-06-15",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Sudirman No. 9"
        ]);
        foreach ($this->siswaModel->getAllSiswa() as $row) {
            if ($row['nama'] === "Uji Siswa Absensi Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function dataAbsensi(): array
    {
        return [
            "id_siswa" => $this->testSiswaId,
            "id_guru" => $this->testGuruId,
            "id_mapel" => $this->testMapelId,
            "id_kelas" => $this->testKelasId,
            "tanggal" => "2026-08-06 08:00:00",
            "kehadiran" => "HADIR"
        ];
    }

    private function buatAbsensiTest(): ?int
    {
        $this->assertTrue($this->absensiModel->createAbsensi($this->dataAbsensi()), "Gagal menginput data absensi uji.");
        foreach ($this->absensiModel->getAllAbsensi() as $row) {
            if ($row['nama_guru'] === "Uji Guru Absensi Real Test" && $row['nama_siswa'] === "Uji Siswa Absensi Real Test") {
                return (int) $row['id'];
            }
        }
        return null;
    }

    public function testGetAllAbsensiReturnsArray(): void
    {
        $idUji = $this->buatAbsensiTest();
        $this->assertNotNull($idUji, "Data absensi uji tidak ditemukan di database.");

        $allAbsensi = $this->absensiModel->getAllAbsensi();
        $this->assertIsArray($allAbsensi, "Hasil getAllAbsensi harus berupa array.");

        foreach ($allAbsensi as $absensi) {
            $this->assertArrayHasKey('id', $absensi, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('nama_guru', $absensi, "Kolom nama_guru (relasi) tidak ditemukan.");
            $this->assertArrayHasKey('nama_siswa', $absensi, "Kolom nama_siswa (relasi) tidak ditemukan.");
        }
    }

    public function testGetAbsensiByIdReturnsSingleData(): void
    {
        $idUji = $this->buatAbsensiTest();
        $this->assertNotNull($idUji, "Data absensi uji tidak ditemukan di database.");

        $detail = $this->absensiModel->getAbsensiById($idUji);

        $this->assertIsArray($detail, "Hasil getAbsensiById harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Uji Guru Absensi Real Test", $detail['nama_guru'], "Relasi guru tidak sesuai.");
        $this->assertSame("Uji Siswa Absensi Real Test", $detail['nama_siswa'], "Relasi siswa tidak sesuai.");
        $this->assertSame("HADIR", $detail['kehadiran'], "kehadiran tidak sesuai.");
    }

    public function testCreateAbsensiReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->absensiModel->createAbsensi($this->dataAbsensi()), "Gagal menginput data absensi.");

        $daftarId = array_column($this->absensiModel->getAllAbsensi(), 'id');
        $this->assertNotEmpty($daftarId, "Tidak ada data absensi di database!");
    }

    public function testUpdateAbsensiReturnsTrueOnSuccess(): void
    {
        $idUji = $this->buatAbsensiTest();
        $this->assertNotNull($idUji, "Data absensi uji tidak ditemukan di database.");

        $dataUpdate = $this->dataAbsensi();
        $dataUpdate['kehadiran'] = "IZIN";

        $this->assertTrue($this->absensiModel->updateAbsensi($idUji, $dataUpdate), "Gagal memperbarui data absensi.");

        $detail = $this->absensiModel->getAbsensiById($idUji);
        $this->assertSame("IZIN", $detail['kehadiran'], "kehadiran tidak berubah setelah update.");
    }

    public function testDeleteAbsensiReturnsTrueOnSuccess(): void
    {
        $idUji = $this->buatAbsensiTest();
        $this->assertNotNull($idUji, "Data absensi uji tidak ditemukan di database.");

        $this->assertTrue($this->absensiModel->deleteAbsensi($idUji), "Gagal menghapus data absensi.");

        $daftarId = array_column($this->absensiModel->getAllAbsensi(), 'id');
        $this->assertNotContains((int) $idUji, $daftarId, "Data absensi masih ada setelah dihapus!");
    }

    public function testGetListGuruReturnsArray(): void
    {
        $listGuru = $this->absensiModel->getListGuru();

        $this->assertIsArray($listGuru, "Hasil getListGuru harus berupa array.");
        $this->assertContains("Uji Guru Absensi Real Test", array_column($listGuru, 'nama'), "Data guru untuk dropdown tidak ditemukan.");
    }

    public function testGetListSiswaReturnsArray(): void
    {
        $listSiswa = $this->absensiModel->getListSiswa();

        $this->assertIsArray($listSiswa, "Hasil getListSiswa harus berupa array.");
        $this->assertContains("Uji Siswa Absensi Real Test", array_column($listSiswa, 'nama'), "Data siswa untuk dropdown tidak ditemukan.");
    }
}
