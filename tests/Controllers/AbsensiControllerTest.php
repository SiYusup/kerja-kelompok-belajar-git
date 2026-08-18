<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\AbsensiController;
use App\Models\JadwalAbsensiModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\AbsensiModel;

class AbsensiControllerTest extends TestCase
{
    private $jadwalModel;
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
        $this->jadwalModel = new JadwalAbsensiModel();
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
        foreach ($this->jadwalModel->getAllJadwal() as $jadwal) {
            if (str_contains($jadwal['nama_guru'], 'Real Test')) {
                $this->jadwalModel->deleteJadwal($jadwal['id']);
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
        $this->mapelModel->createMapel(["mapel" => "Mapel Ctrl Absen Real Test"]);
        foreach ($this->mapelModel->getAllMapel() as $row) {
            if ($row['mapel'] === "Mapel Ctrl Absen Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatKelasTest(): ?int
    {
        $this->kelasModel->createKelas(["kelas" => "Kelas Abs Real Test"]);
        foreach ($this->kelasModel->getAllKelas() as $row) {
            if ($row['kelas'] === "Kelas Abs Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatGuruTest(): ?int
    {
        $this->guruModel->createGuru([
            "id_mapel" => $this->testMapelId,
            "nama" => "Uji Guru Ctrl Absen Real Test",
            "nip" => "197903152006041003",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1979-03-15",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Merdeka No. 1"
        ]);
        foreach ($this->guruModel->getAllGuru() as $row) {
            if ($row['nama'] === "Uji Guru Ctrl Absen Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatSiswaTest(): ?int
    {
        $this->siswaModel->createSiswa([
            "id_kelas" => $this->testKelasId,
            "nama" => "Uji Siswa Ctrl Absen Real Test",
            "nisn" => "9988123456",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-06-15",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Sudirman No. 9"
        ]);
        foreach ($this->siswaModel->getAllSiswa() as $row) {
            if ($row['nama'] === "Uji Siswa Ctrl Absen Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatJadwalTest(): int
    {
        $this->assertTrue($this->jadwalModel->createJadwal([
            "id_kelas" => $this->testKelasId,
            "id_guru" => $this->testGuruId,
            "id_mapel" => $this->testMapelId,
            "bulan" => "Agustus",
            "tahun" => "2026/2027"
        ]), "Gagal membuat jadwal uji.");
        foreach ($this->jadwalModel->getAllJadwal() as $row) {
            if ($row['nama_guru'] === "Uji Guru Ctrl Absen Real Test" && $row['bulan'] === "Agustus") {
                return (int) $row['id'];
            }
        }
        $this->fail("Jadwal uji tidak ditemukan.");
    }

    public function testIndexMenampilkanJadwalAbsensi(): void
    {
        $controller = new AbsensiController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Jadwal Absensi</title>', $output);
    }

    public function testDetailMenampilkanJadwal(): void
    {
        $id = $this->buatJadwalTest();

        $controller = new AbsensiController();
        ob_start();
        $controller->detail($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Detail Absensi</title>', $output);
        $this->assertStringContainsString('Uji Guru Ctrl Absen Real Test', $output);
    }

    public function testEditMenampilkanJadwal(): void
    {
        $id = $this->buatJadwalTest();

        $controller = new AbsensiController();
        ob_start();
        $controller->edit($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Edit Jadwal Absensi</title>', $output);
        $this->assertStringContainsString('Uji Guru Ctrl Absen Real Test', $output);
    }

    public function testRekapMenampilkanDashboard(): void
    {
        $id = $this->buatJadwalTest();

        $absensiModel = new AbsensiModel();
        $this->assertTrue($absensiModel->createAbsensi([
            "id_jadwal" => $id,
            "id_siswa" => $this->testSiswaId,
            "id_guru" => $this->testGuruId,
            "id_mapel" => $this->testMapelId,
            "id_kelas" => $this->testKelasId,
            "tanggal" => "2026-08-06 08:00:00",
            "kehadiran" => "HADIR"
        ]), "Gagal membuat data absensi uji untuk rekap.");

        $controller = new AbsensiController();
        ob_start();
        $controller->rekap($id);
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Rekap Absensi</title>', $output);
        $this->assertStringContainsString('Uji Guru Ctrl Absen Real Test', $output);
        $this->assertStringContainsString('Total Siswa', $output);
        $this->assertStringContainsString('Laki-Laki', $output);
        $this->assertStringContainsString('Perempuan', $output);
        $this->assertStringContainsString('Senin', $output);
        $this->assertStringContainsString('Minggu', $output);
        $this->assertStringContainsString('Jumlah Hadir', $output);
    }

    public function testDaftarBulanMengembalikanDuaBelasBulan(): void
    {
        $controller = new AbsensiController();
        $method = new \ReflectionMethod(AbsensiController::class, 'daftarBulan');
        $method->setAccessible(true);

        $bulan = $method->invoke($controller);

        $this->assertCount(12, $bulan, "Daftar bulan harus berisi 12 bulan.");
        $this->assertSame('Januari', $bulan[0]);
        $this->assertSame('Desember', $bulan[11]);
    }

    public function testDaftarTahunMengembalikanRentangTahunAjaran(): void
    {
        $controller = new AbsensiController();
        $method = new \ReflectionMethod(AbsensiController::class, 'daftarTahun');
        $method->setAccessible(true);

        $tahun = $method->invoke($controller);

        $tahunSekarang = (int) date("Y");
        $this->assertCount(6, $tahun, "Rentang tahun ajaran harus berjumlah 6.");
        $this->assertSame(($tahunSekarang - 2) . "/" . ($tahunSekarang - 1), $tahun[0]);
        $this->assertSame(($tahunSekarang + 3) . "/" . ($tahunSekarang + 4), $tahun[5]);
        foreach ($tahun as $t) {
            $this->assertMatchesRegularExpression('#^\d{4}/\d{4}$#', $t, "Format tahun ajaran harus YYYY/YYYY.");
        }
    }
}
