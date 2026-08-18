<?php

namespace App\Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\JadwalAbsensiModel;
use App\Models\AbsensiModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\MapelModel;
use App\Models\KelasModel;

class JadwalAbsensiModelTest extends TestCase
{
    private $jadwalModel;
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
        $this->jadwalModel = new JadwalAbsensiModel();
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
        $this->mapelModel->createMapel(["mapel" => "Mapel Jadwal Real Test"]);
        foreach ($this->mapelModel->getAllMapel() as $row) {
            if ($row['mapel'] === "Mapel Jadwal Real Test") return (int) $row['id'];
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
            "nama" => "Uji Guru Jadwal Real Test",
            "nip" => "197903152006041003",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1979-03-15",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Merdeka No. 1"
        ]);
        foreach ($this->guruModel->getAllGuru() as $row) {
            if ($row['nama'] === "Uji Guru Jadwal Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function buatSiswaTest(): ?int
    {
        $this->siswaModel->createSiswa([
            "id_kelas" => $this->testKelasId,
            "nama" => "Uji Siswa Jadwal Real Test",
            "nisn" => "9988123456",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-06-15",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Sudirman No. 9"
        ]);
        foreach ($this->siswaModel->getAllSiswa() as $row) {
            if ($row['nama'] === "Uji Siswa Jadwal Real Test") return (int) $row['id'];
        }
        return null;
    }

    private function dataJadwal(): array
    {
        return [
            "id_kelas" => $this->testKelasId,
            "id_guru" => $this->testGuruId,
            "id_mapel" => $this->testMapelId,
            "bulan" => "Agustus",
            "tahun" => "2026/2027"
        ];
    }

    private function buatJadwalTest(): ?int
    {
        $this->assertTrue($this->jadwalModel->createJadwal($this->dataJadwal()), "Gagal membuat jadwal uji.");
        foreach ($this->jadwalModel->getAllJadwal() as $row) {
            if ($row['nama_guru'] === "Uji Guru Jadwal Real Test" && $row['bulan'] === "Agustus") {
                return (int) $row['id'];
            }
        }
        return null;
    }

    private function buatAbsensiTerhubung(int $idJadwal): ?int
    {
        $this->assertTrue($this->absensiModel->createAbsensi([
            "id_jadwal" => $idJadwal,
            "id_siswa" => $this->testSiswaId,
            "id_guru" => $this->testGuruId,
            "id_mapel" => $this->testMapelId,
            "id_kelas" => $this->testKelasId,
            "tanggal" => "2026-08-06 08:00:00",
            "kehadiran" => "HADIR"
        ]), "Gagal membuat data absensi terhubung.");
        foreach ($this->jadwalModel->getAbsensiByJadwal($idJadwal) as $row) {
            if ($row['nama_siswa'] === "Uji Siswa Jadwal Real Test") {
                return (int) $row['id'];
            }
        }
        return null;
    }

    public function testGetAllJadwalReturnsArray(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");

        $allJadwal = $this->jadwalModel->getAllJadwal();
        $this->assertIsArray($allJadwal, "Hasil getAllJadwal harus berupa array.");

        foreach ($allJadwal as $jadwal) {
            $this->assertArrayHasKey('id', $jadwal, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('kelas', $jadwal, "Kolom kelas (relasi) tidak ditemukan.");
            $this->assertArrayHasKey('nama_guru', $jadwal, "Kolom nama_guru (relasi) tidak ditemukan.");
            $this->assertArrayHasKey('mapel', $jadwal, "Kolom mapel (relasi) tidak ditemukan.");
            $this->assertArrayHasKey('bulan', $jadwal, "Kolom bulan tidak ditemukan.");
            $this->assertArrayHasKey('tahun', $jadwal, "Kolom tahun tidak ditemukan.");
        }
    }

    public function testGetJadwalByIdReturnsSingleData(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");

        $detail = $this->jadwalModel->getJadwalById($idUji);

        $this->assertIsArray($detail, "Hasil getJadwalById harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Kelas Real Test", $detail['kelas'], "Relasi kelas tidak sesuai.");
        $this->assertSame("Uji Guru Jadwal Real Test", $detail['nama_guru'], "Relasi guru tidak sesuai.");
        $this->assertSame("Mapel Jadwal Real Test", $detail['mapel'], "Relasi mapel tidak sesuai.");
        $this->assertSame("Agustus", $detail['bulan'], "bulan tidak sesuai.");
        $this->assertSame("2026/2027", $detail['tahun'], "tahun tidak sesuai.");
    }

    public function testCreateJadwalReturnsTrueOnSuccess(): void
    {
        $this->assertTrue($this->jadwalModel->createJadwal($this->dataJadwal()), "Gagal membuat jadwal absensi.");

        $daftarId = array_column($this->jadwalModel->getAllJadwal(), 'id');
        $this->assertNotEmpty($daftarId, "Tidak ada jadwal absensi di database!");
    }

    public function testUpdateJadwalReturnsTrueOnSuccess(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");

        $dataUpdate = $this->dataJadwal();
        $dataUpdate['bulan'] = "September";

        $this->assertTrue($this->jadwalModel->updateJadwal($idUji, $dataUpdate), "Gagal memperbarui jadwal.");

        $detail = $this->jadwalModel->getJadwalById($idUji);
        $this->assertSame("September", $detail['bulan'], "bulan tidak berubah setelah update.");
    }

    public function testDeleteJadwalReturnsTrueOnSuccess(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");

        $this->assertTrue($this->jadwalModel->deleteJadwal($idUji), "Gagal menghapus jadwal.");

        $daftarId = array_column($this->jadwalModel->getAllJadwal(), 'id');
        $this->assertNotContains((int) $idUji, $daftarId, "Jadwal masih ada setelah dihapus!");
    }

    public function testGetAbsensiByJadwalReturnsArray(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");
        $this->assertNotNull($this->buatAbsensiTerhubung((int) $idUji), "Data absensi terhubung tidak ditemukan.");

        $listAbsensi = $this->jadwalModel->getAbsensiByJadwal($idUji);

        $this->assertIsArray($listAbsensi, "Hasil getAbsensiByJadwal harus berupa array.");
        $this->assertContains("Uji Siswa Jadwal Real Test", array_column($listAbsensi, 'nama_siswa'), "Data absensi siswa tidak ditemukan.");
    }

    public function testDeleteJadwalDeletesLinkedAbsensi(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");
        $this->assertNotNull($this->buatAbsensiTerhubung((int) $idUji), "Data absensi terhubung tidak ditemukan.");

        $this->assertTrue($this->jadwalModel->deleteJadwal($idUji), "Gagal menghapus jadwal.");

        $listAbsensi = $this->jadwalModel->getAbsensiByJadwal($idUji);
        $this->assertEmpty($listAbsensi, "Data absensi masih tersisa setelah jadwal dihapus.");
    }

    public function testGetListKelasReturnsArray(): void
    {
        $listKelas = $this->jadwalModel->getListKelas();

        $this->assertIsArray($listKelas, "Hasil getListKelas harus berupa array.");
        $this->assertContains("Kelas Real Test", array_column($listKelas, 'kelas'), "Data kelas untuk dropdown tidak ditemukan.");
    }

    public function testGetListGuruReturnsArray(): void
    {
        $listGuru = $this->jadwalModel->getListGuru();

        $this->assertIsArray($listGuru, "Hasil getListGuru harus berupa array.");
        $this->assertContains("Uji Guru Jadwal Real Test", array_column($listGuru, 'nama'), "Data guru untuk dropdown tidak ditemukan.");
    }

    public function testGetListMapelReturnsArray(): void
    {
        $listMapel = $this->jadwalModel->getListMapel();

        $this->assertIsArray($listMapel, "Hasil getListMapel harus berupa array.");
        $this->assertContains("Mapel Jadwal Real Test", array_column($listMapel, 'mapel'), "Data mapel untuk dropdown tidak ditemukan.");
    }

    public function testGetListSiswaByKelasReturnsArray(): void
    {
        $listSiswa = $this->jadwalModel->getListSiswaByKelas($this->testKelasId);

        $this->assertIsArray($listSiswa, "Hasil getListSiswaByKelas harus berupa array.");
        $this->assertContains("Uji Siswa Jadwal Real Test", array_column($listSiswa, 'nama'), "Data siswa untuk dropdown tidak ditemukan.");
    }

    public function testGetStatistikAbsensiReturnsArray(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");
        $this->assertNotNull($this->buatAbsensiTerhubung((int) $idUji), "Data absensi terhubung tidak ditemukan.");

        $statistik = $this->jadwalModel->getStatistikAbsensi($idUji);

        $this->assertIsArray($statistik, "Hasil getStatistikAbsensi harus berupa array.");

        $ditemukan = false;
        foreach ($statistik as $row) {
            $this->assertArrayHasKey('kehadiran', $row, "Kolom kehadiran tidak ditemukan.");
            $this->assertArrayHasKey('jumlah', $row, "Kolom jumlah tidak ditemukan.");
            if ($row['kehadiran'] === 'HADIR') {
                $ditemukan = true;
                $this->assertSame(1, (int) $row['jumlah'], "Jumlah status HADIR tidak sesuai.");
            }
        }
        $this->assertTrue($ditemukan, "Statistik status HADIR tidak ditemukan.");
    }

    public function testGetRekapHarianReturnsArray(): void
    {
        $idUji = $this->buatJadwalTest();
        $this->assertNotNull($idUji, "Jadwal uji tidak ditemukan di database.");
        $this->assertNotNull($this->buatAbsensiTerhubung((int) $idUji), "Data absensi terhubung tidak ditemukan.");

        $rekap = $this->jadwalModel->getRekapHarian($idUji);

        $this->assertIsArray($rekap, "Hasil getRekapHarian harus berupa array.");
        $this->assertNotEmpty($rekap, "Rekap harian tidak boleh kosong.");
        $this->assertSame('2026-08-06', $rekap[0]['tanggal'], "Tanggal rekap tidak sesuai.");
        $this->assertSame('HADIR', $rekap[0]['kehadiran'], "Status rekap tidak sesuai.");
        $this->assertSame(1, (int) $rekap[0]['jumlah'], "Jumlah rekap tidak sesuai.");
    }
}
