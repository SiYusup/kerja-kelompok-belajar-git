<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\JadwalAbsensiModel;
use App\Models\JurusanModel;
use App\Models\AbsensiModel;

class HttpIntegrationTest extends TestCase
{
    private static $process;
    private static $pipes;
    private static $port;
    private static $base;

    private static $mapelModel;
    private static $kelasModel;
    private static $guruModel;
    private static $siswaModel;
    private static $jadwalModel;
    private static $jurusanModel;
    private static $absensiModel;

    private static $mapelId;
    private static $kelasId;
    private static $guruId;
    private static $siswaId;

    public static function setUpBeforeClass(): void
    {
        self::$mapelModel = new MapelModel();
        self::$kelasModel = new KelasModel();
        self::$guruModel = new GuruModel();
        self::$siswaModel = new SiswaModel();
        self::$jadwalModel = new JadwalAbsensiModel();
        self::$jurusanModel = new JurusanModel();
        self::$absensiModel = new AbsensiModel();

        self::bersihkanSemuaDataTest();

        self::$mapelId = self::buatMapel("Mapel HTTP Real Test");
        self::$kelasId = self::buatKelas("Kelas HTTP Real Test");
        self::$guruId = self::buatGuru();
        self::$siswaId = self::buatSiswa();

        self::$port = random_int(21000, 29000);
        $publicDir = __DIR__ . '/../../public';
        $cmd = sprintf('%s -S 127.0.0.1:%d -t %s', escapeshellarg(PHP_BINARY), self::$port, escapeshellarg($publicDir));
        self::$process = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], self::$pipes);
        self::$base = "http://127.0.0.1:" . self::$port . "/index.php";

        $siap = false;
        for ($i = 0; $i < 50; $i++) {
            $res = @file_get_contents(self::$base . "?url=");
            if ($res !== false) {
                $siap = true;
                break;
            }
            usleep(200000);
        }
        self::assertTrue($siap, "Server uji tidak merespons dalam batas waktu.");
    }

    public static function tearDownAfterClass(): void
    {
        self::bersihkanSemuaDataTest();
        if (self::$process) {
            proc_terminate(self::$process);
            proc_close(self::$process);
            self::$process = null;
        }
    }

    private static function bersihkanSemuaDataTest(): void
    {
        foreach (self::$jadwalModel->getAllJadwal() as $jadwal) {
            if (str_contains($jadwal['nama_guru'], 'Real Test')) {
                self::$jadwalModel->deleteJadwal($jadwal['id']);
            }
        }
        foreach (self::$guruModel->getAllGuru() as $guru) {
            if (str_contains($guru['nama'], 'Real Test')) {
                self::$guruModel->deleteGuru($guru['id']);
            }
        }
        foreach (self::$siswaModel->getAllSiswa() as $siswa) {
            if (str_contains($siswa['nama'], 'Real Test')) {
                self::$siswaModel->deleteSiswa($siswa['id']);
            }
        }
        foreach (self::$mapelModel->getAllMapel() as $mapel) {
            if (str_contains($mapel['mapel'], 'Real Test')) {
                self::$mapelModel->deleteMapel($mapel['id']);
            }
        }
        foreach (self::$kelasModel->getAllKelas() as $kelas) {
            if (str_contains($kelas['kelas'], 'Real Test')) {
                self::$kelasModel->deleteKelas($kelas['id']);
            }
        }
        foreach (self::$jurusanModel->getAllJurusan() as $jurusan) {
            if (str_contains($jurusan['jurusan'], 'Real Test')) {
                self::$jurusanModel->deleteJurusan($jurusan['id']);
            }
        }
    }

    private static function buatMapel(string $nama): int
    {
        self::$mapelModel->createMapel(["mapel" => $nama]);
        foreach (self::$mapelModel->getAllMapel() as $row) {
            if ($row['mapel'] === $nama) return (int) $row['id'];
        }
        return 0;
    }

    private static function buatKelas(string $nama): int
    {
        self::$kelasModel->createKelas(["kelas" => $nama]);
        foreach (self::$kelasModel->getAllKelas() as $row) {
            if ($row['kelas'] === $nama) return (int) $row['id'];
        }
        return 0;
    }

    private static function buatGuru(): int
    {
        self::$guruModel->createGuru([
            "id_mapel" => self::$mapelId,
            "nama" => "Uji Guru HTTP Real Test",
            "nip" => "198001012008011001",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1980-01-01",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. HTTP No. 10"
        ]);
        foreach (self::$guruModel->getAllGuru() as $row) {
            if ($row['nama'] === "Uji Guru HTTP Real Test") return (int) $row['id'];
        }
        return 0;
    }

    private static function buatSiswa(): int
    {
        self::$siswaModel->createSiswa([
            "id_kelas" => self::$kelasId,
            "nama" => "Uji Siswa HTTP Real Test",
            "nisn" => "9988123401",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-06-15",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. HTTP No. 11"
        ]);
        foreach (self::$siswaModel->getAllSiswa() as $row) {
            if ($row['nama'] === "Uji Siswa HTTP Real Test") return (int) $row['id'];
        }
        return 0;
    }

    private static function headerInfo(array $headers): array
    {
        $status = 0;
        $location = '';
        foreach ($headers as $h) {
            if (preg_match('#^HTTP/\S+\s+(\d+)#', $h, $m)) {
                $status = (int) $m[1];
            } elseif (stripos($h, 'Location:') === 0) {
                $location = trim(substr($h, 9));
            }
        }
        return [$status, $location];
    }

    private static function httpGet(string $url): array
    {
        $ctx = stream_context_create(['http' => ['method' => 'GET', 'ignore_errors' => true, 'timeout' => 10]]);
        $body = @file_get_contents(self::$base . $url, false, $ctx);
        return array_merge(self::headerInfo($http_response_header ?? []), [2 => (string) $body]);
    }

    private static function httpPost(string $url, array $data): array
    {
        $content = http_build_query($data);
        $ctx = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($content),
            'content' => $content,
            'ignore_errors' => true,
            'timeout' => 10,
        ]]);
        $body = @file_get_contents(self::$base . $url, false, $ctx);
        return array_merge(self::headerInfo($http_response_header ?? []), [2 => (string) $body]);
    }

    private static function cariSiswaId(string $nama): ?int
    {
        foreach (self::$siswaModel->getAllSiswa() as $row) {
            if ($row['nama'] === $nama) return (int) $row['id'];
        }
        return null;
    }

    private static function cariGuruId(string $nama): ?int
    {
        foreach (self::$guruModel->getAllGuru() as $row) {
            if ($row['nama'] === $nama) return (int) $row['id'];
        }
        return null;
    }

    private static function cariJurusanId(string $nama): ?int
    {
        foreach (self::$jurusanModel->getAllJurusan() as $row) {
            if ($row['jurusan'] === $nama) return (int) $row['id'];
        }
        return null;
    }

    private static function cariKelasId(string $nama): ?int
    {
        foreach (self::$kelasModel->getAllKelas() as $row) {
            if ($row['kelas'] === $nama) return (int) $row['id'];
        }
        return null;
    }

    // ==================== ROUTING CORE ====================

    public function testHomePageDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Pilih Menu', $body);
    }

    public function testControllerTidakDikenalMenampilkanErrorMVC(): void
    {
        [, , $body] = self::httpGet('?url=halamanTidakAda');

        $this->assertStringContainsString('Error MVC', $body);
    }

    public function testHalamanKelasDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=kelas');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Kelas', $body);
    }

    public function testHalamanMapelDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=mapel');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Mata Pelajaran', $body);
    }

    // ==================== SISWA ====================

    public function testHalamanSiswaDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=siswa');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Siswa', $body);
    }

    public function testDetailSiswaDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=siswa/detail/' . self::$siswaId);

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Uji Siswa HTTP Real Test', $body);
    }

    public function testPencarianSiswaBekerja(): void
    {
        [, , $body] = self::httpGet('?url=siswa&search=' . urlencode('Uji Siswa HTTP'));

        $this->assertStringContainsString('Uji Siswa HTTP Real Test', $body);
    }

    public function testPaginasiSiswaHalamanDua(): void
    {
        [$status, , $body] = self::httpGet('?url=siswa&page=2');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Siswa', $body);
    }

    public function testSiswaCreateMengarahkanDanMenyimpanData(): void
    {
        $nama = 'Uji HTTP Siswa CRUD Real Test';
        [$status, $location] = self::httpPost('?url=siswa/create', [
            'id_kelas' => self::$kelasId,
            'nama' => $nama,
            'nisn' => '99887077',
            'jurusan' => 'RPL',
            'kelas' => 'XII',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2008-03-03',
            'jenis_kelamin' => 'LAKI',
            'alamat' => 'Jl. HTTP CRUD No. 1'
        ]);

        $this->assertSame(302, $status, "Create siswa harus redirect (302).");
        $this->assertStringContainsString('/siswa', $location);

        $id = self::cariSiswaId($nama);
        $this->assertNotNull($id, "Data siswa baru tidak ditemukan di database.");

        if ($id) self::$siswaModel->deleteSiswa($id);
    }

    public function testSiswaUpdateMengarahkanDanMengubahData(): void
    {
        $nama = 'Uji HTTP Siswa Update Real Test';
        self::$siswaModel->createSiswa([
            "id_kelas" => self::$kelasId,
            "nama" => $nama,
            "nisn" => "99887078",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-03-04",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. HTTP Update No. 1"
        ]);
        $id = self::cariSiswaId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpPost('?url=siswa/update/' . $id, [
            'id_kelas' => self::$kelasId,
            'nama' => $nama,
            'nisn' => '99887078',
            'jurusan' => 'TKJ',
            'kelas' => 'XII',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2008-03-04',
            'jenis_kelamin' => 'PEREMPUAN',
            'alamat' => 'Jl. HTTP Update No. 99'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/siswa', $location);

        $detail = self::$siswaModel->getSiswaById($id);
        $this->assertSame('TKJ', $detail['jurusan'], "jurusan tidak berubah setelah update.");
        $this->assertSame('PEREMPUAN', $detail['jenis_kelamin']);

        if ($id) self::$siswaModel->deleteSiswa($id);
    }

    public function testSiswaDeleteMengarahkanDanMenghapusData(): void
    {
        $nama = 'Uji HTTP Siswa Delete Real Test';
        self::$siswaModel->createSiswa([
            "id_kelas" => self::$kelasId,
            "nama" => $nama,
            "nisn" => "99887079",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-03-05",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. HTTP Delete No. 1"
        ]);
        $id = self::cariSiswaId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpGet('?url=siswa/delete/' . $id);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/siswa', $location);
        $this->assertNull(self::cariSiswaId($nama), "Data siswa masih ada setelah delete.");
    }

    // ==================== GURU ====================

    public function testHalamanGuruDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=guru');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Guru', $body);
    }

    public function testDetailGuruDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=guru/detail/' . self::$guruId);

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Uji Guru HTTP Real Test', $body);
    }

    public function testGuruCreateMengarahkanDanMenyimpanData(): void
    {
        $nama = 'Uji HTTP Guru CRUD Real Test';
        [$status, $location] = self::httpPost('?url=guru/create', [
            'id_mapel' => self::$mapelId,
            'nama' => $nama,
            'nip' => '198505062008011002',
            'jurusan' => 'RPL',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-05-06',
            'jenis_kelamin' => 'LAKI',
            'alamat' => 'Jl. HTTP Guru CRUD No. 1'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/guru', $location);

        $id = self::cariGuruId($nama);
        $this->assertNotNull($id, "Data guru baru tidak ditemukan di database.");

        if ($id) self::$guruModel->deleteGuru($id);
    }

    public function testGuruUpdateMengarahkanDanMengubahData(): void
    {
        $nama = 'Uji HTTP Guru Update Real Test';
        self::$guruModel->createGuru([
            "id_mapel" => self::$mapelId,
            "nama" => $nama,
            "nip" => "198505062008011003",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1985-05-06",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. HTTP Guru Update No. 1"
        ]);
        $id = self::cariGuruId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpPost('?url=guru/update/' . $id, [
            'id_mapel' => self::$mapelId,
            'nama' => $nama,
            'nip' => '198505062008011003',
            'jurusan' => 'TKJ',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-05-06',
            'jenis_kelamin' => 'PEREMPUAN',
            'alamat' => 'Jl. HTTP Guru Update No. 99'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/guru', $location);

        $detail = self::$guruModel->getGuruDetail($id);
        $this->assertSame('TKJ', $detail['jurusan']);
        $this->assertSame('PEREMPUAN', $detail['jenis_kelamin']);

        if ($id) self::$guruModel->deleteGuru($id);
    }

    public function testGuruDeleteMengarahkanDanMenghapusData(): void
    {
        $nama = 'Uji HTTP Guru Delete Real Test';
        self::$guruModel->createGuru([
            "id_mapel" => self::$mapelId,
            "nama" => $nama,
            "nip" => "198505062008011004",
            "jurusan" => "RPL",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "1985-05-06",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. HTTP Guru Delete No. 1"
        ]);
        $id = self::cariGuruId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpGet('?url=guru/delete/' . $id);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/guru', $location);
        $this->assertNull(self::cariGuruId($nama), "Data guru masih ada setelah delete.");
    }

    // ==================== KELAS ====================

    public function testPencarianKelasBekerja(): void
    {
        self::$kelasModel->createKelas(["kelas" => "Kelas Cari Real Test"]);
        $id = self::cariKelasId("Kelas Cari Real Test");

        [$status, , $body] = self::httpGet('?url=kelas&search=' . urlencode('Kelas Cari'));

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Kelas Cari Real Test', $body);

        if ($id) self::$kelasModel->deleteKelas($id);
    }

    public function testPaginasiKelasHalamanSatu(): void
    {
        [$status, , $body] = self::httpGet('?url=kelas&page=1');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Kelas', $body);
    }

    public function testKelasCreateMengarahkanDanMenyimpanData(): void
    {
        $nama = 'Kelas CRUD Real Test';
        [$status, $location] = self::httpPost('?url=kelas/create', [
            'kelas' => $nama
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/kelas', $location);

        $id = self::cariKelasId($nama);
        $this->assertNotNull($id, "Data kelas baru tidak ditemukan di database.");

        if ($id) self::$kelasModel->deleteKelas($id);
    }

    public function testKelasUpdateMengarahkanDanMengubahData(): void
    {
        $nama = 'Kelas Update Real Test';
        self::$kelasModel->createKelas(["kelas" => $nama]);
        $id = self::cariKelasId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpPost('?url=kelas/update/' . $id, [
            'kelas' => 'Kelas Baru Real Test'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/kelas', $location);

        $detail = self::$kelasModel->getKelasById($id);
        $this->assertSame('Kelas Baru Real Test', $detail['kelas'], "kelas tidak berubah setelah update.");

        if ($id) self::$kelasModel->deleteKelas($id);
    }

    public function testKelasDeleteMengarahkanDanMenghapusData(): void
    {
        $nama = 'Kelas Delete Real Test';
        self::$kelasModel->createKelas(["kelas" => $nama]);
        $id = self::cariKelasId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpGet('?url=kelas/delete/' . $id);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/kelas', $location);
        $this->assertNull(self::cariKelasId($nama), "Data kelas masih ada setelah delete.");
    }

    // ==================== JURUSAN ====================

    public function testHalamanJurusanDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=jurusan');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Jurusan', $body);
    }

    public function testPencarianJurusanBekerja(): void
    {
        [$status, , $body] = self::httpGet('?url=jurusan&search=' . urlencode('RPL'));

        $this->assertSame(200, $status);
        $this->assertStringContainsString('RPL - Rekayasa Perangkat Lunak', $body);
    }

    public function testPaginasiJurusanHalamanDua(): void
    {
        [$status, , $body] = self::httpGet('?url=jurusan&page=2');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Daftar Jurusan', $body);
    }

    public function testJurusanCreateMengarahkanDanMenyimpanData(): void
    {
        $nama = 'Uji HTTP Jurusan Real Test';
        [$status, $location] = self::httpPost('?url=jurusan/create', [
            'jurusan' => $nama
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/jurusan', $location);

        $id = self::cariJurusanId($nama);
        $this->assertNotNull($id, "Data jurusan baru tidak ditemukan di database.");

        if ($id) self::$jurusanModel->deleteJurusan($id);
    }

    public function testJurusanUpdateMengarahkanDanMengubahData(): void
    {
        $nama = 'Uji HTTP Jurusan Update Real Test';
        self::$jurusanModel->createJurusan(["jurusan" => $nama]);
        $id = self::cariJurusanId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpPost('?url=jurusan/update/' . $id, [
            'jurusan' => 'Uji HTTP Jurusan Diubah Real Test'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/jurusan', $location);

        $detail = self::$jurusanModel->getJurusanById($id);
        $this->assertSame('Uji HTTP Jurusan Diubah Real Test', $detail['jurusan'], "jurusan tidak berubah setelah update.");

        if ($id) self::$jurusanModel->deleteJurusan($id);
    }

    public function testJurusanDeleteMengarahkanDanMenghapusData(): void
    {
        $nama = 'Uji HTTP Jurusan Delete Real Test';
        self::$jurusanModel->createJurusan(["jurusan" => $nama]);
        $id = self::cariJurusanId($nama);
        $this->assertNotNull($id);

        [$status, $location] = self::httpGet('?url=jurusan/delete/' . $id);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/jurusan', $location);
        $this->assertNull(self::cariJurusanId($nama), "Data jurusan masih ada setelah delete.");
    }

    // ==================== ABSENSI ====================

    public function testHalamanAbsensiDapatDiakses(): void
    {
        [$status, , $body] = self::httpGet('?url=absensi');

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Jadwal Absensi', $body);
    }

    public function testAbsensiCreateMengarahkanDanMenyimpanJadwal(): void
    {
        [$status, $location] = self::httpPost('?url=absensi/create', [
            'id_kelas' => self::$kelasId,
            'id_guru' => self::$guruId,
            'id_mapel' => self::$mapelId,
            'bulan' => 'Agustus',
            'tahun' => '2026/2027'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/absensi', $location);

        $idJadwal = null;
        foreach (self::$jadwalModel->getAllJadwal() as $row) {
            if ($row['id_guru'] == self::$guruId && $row['bulan'] === 'Agustus') {
                $idJadwal = (int) $row['id'];
                break;
            }
        }
        $this->assertNotNull($idJadwal, "Jadwal absensi baru tidak ditemukan.");

        if ($idJadwal) self::$jadwalModel->deleteJadwal($idJadwal);
    }

    public function testAbsensiUpdateMengarahkanDanMengubahJadwal(): void
    {
        self::$jadwalModel->createJadwal([
            "id_kelas" => self::$kelasId,
            "id_guru" => self::$guruId,
            "id_mapel" => self::$mapelId,
            "bulan" => "Agustus",
            "tahun" => "2026/2027"
        ]);
        $idJadwal = null;
        foreach (self::$jadwalModel->getAllJadwal() as $row) {
            if ($row['id_guru'] == self::$guruId && $row['bulan'] === 'Agustus') {
                $idJadwal = (int) $row['id'];
                break;
            }
        }
        $this->assertNotNull($idJadwal);

        [$status, $location] = self::httpPost('?url=absensi/update/' . $idJadwal, [
            'id_kelas' => self::$kelasId,
            'id_guru' => self::$guruId,
            'id_mapel' => self::$mapelId,
            'bulan' => 'September',
            'tahun' => '2026/2027'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/absensi', $location);

        $detail = self::$jadwalModel->getJadwalById($idJadwal);
        $this->assertSame('September', $detail['bulan'], "bulan tidak berubah setelah update.");

        if ($idJadwal) self::$jadwalModel->deleteJadwal($idJadwal);
    }

    public function testTambahAbsensiMengarahkanDanMenyimpanKehadiran(): void
    {
        self::$jadwalModel->createJadwal([
            "id_kelas" => self::$kelasId,
            "id_guru" => self::$guruId,
            "id_mapel" => self::$mapelId,
            "bulan" => "Agustus",
            "tahun" => "2026/2027"
        ]);
        $idJadwal = null;
        foreach (self::$jadwalModel->getAllJadwal() as $row) {
            if ($row['id_guru'] == self::$guruId && $row['bulan'] === 'Agustus') {
                $idJadwal = (int) $row['id'];
                break;
            }
        }
        $this->assertNotNull($idJadwal);

        [$status, $location] = self::httpPost('?url=absensi/tambahAbsensi/' . $idJadwal, [
            'id_siswa' => self::$siswaId,
            'tanggal' => '2026-08-06 08:00:00',
            'kehadiran' => 'HADIR'
        ]);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/absensi/detail/' . $idJadwal, $location);

        $listAbsensi = self::$jadwalModel->getAbsensiByJadwal($idJadwal);
        $this->assertContains('Uji Siswa HTTP Real Test', array_column($listAbsensi, 'nama_siswa'), "Data kehadiran tidak tersimpan.");

        if ($idJadwal) self::$jadwalModel->deleteJadwal($idJadwal);
    }

    public function testHapusAbsensiMengarahkanDanMenghapusKehadiran(): void
    {
        self::$jadwalModel->createJadwal([
            "id_kelas" => self::$kelasId,
            "id_guru" => self::$guruId,
            "id_mapel" => self::$mapelId,
            "bulan" => "Agustus",
            "tahun" => "2026/2027"
        ]);
        $idJadwal = null;
        foreach (self::$jadwalModel->getAllJadwal() as $row) {
            if ($row['id_guru'] == self::$guruId && $row['bulan'] === 'Agustus') {
                $idJadwal = (int) $row['id'];
                break;
            }
        }
        $this->assertNotNull($idJadwal);

        self::$siswaModel->createSiswa([
            "id_kelas" => self::$kelasId,
            "nama" => "Uji Siswa HTTP Hapus Real Test",
            "nisn" => "9988123402",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-06-16",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. HTTP No. 12"
        ]);
        $idSiswa = self::cariSiswaId("Uji Siswa HTTP Hapus Real Test");
        $this->assertNotNull($idSiswa);

        [$status, $location] = self::httpPost('?url=absensi/tambahAbsensi/' . $idJadwal, [
            'id_siswa' => $idSiswa,
            'tanggal' => '2026-08-06 08:00:00',
            'kehadiran' => 'SAKIT'
        ]);
        $this->assertSame(302, $status);

        $idAbsensi = null;
        foreach (self::$jadwalModel->getAbsensiByJadwal($idJadwal) as $row) {
            if ($row['nama_siswa'] === "Uji Siswa HTTP Hapus Real Test") {
                $idAbsensi = (int) $row['id'];
                break;
            }
        }
        $this->assertNotNull($idAbsensi, "Data kehadiran uji tidak ditemukan.");

        [$status, $location] = self::httpGet('?url=absensi/hapusAbsensi/' . $idJadwal . '/' . $idAbsensi);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/absensi/detail/' . $idJadwal, $location);
        $this->assertNotContains('Uji Siswa HTTP Hapus Real Test', array_column(self::$jadwalModel->getAbsensiByJadwal($idJadwal), 'nama_siswa'), "Data kehadiran masih ada setelah dihapus.");

        if ($idSiswa) self::$siswaModel->deleteSiswa($idSiswa);
        if ($idJadwal) self::$jadwalModel->deleteJadwal($idJadwal);
    }

    public function testAbsensiDeleteMenghapusJadwalDanAbsensiTerhubung(): void
    {
        self::$jadwalModel->createJadwal([
            "id_kelas" => self::$kelasId,
            "id_guru" => self::$guruId,
            "id_mapel" => self::$mapelId,
            "bulan" => "Agustus",
            "tahun" => "2026/2027"
        ]);
        $idJadwal = null;
        foreach (self::$jadwalModel->getAllJadwal() as $row) {
            if ($row['id_guru'] == self::$guruId && $row['bulan'] === 'Agustus') {
                $idJadwal = (int) $row['id'];
                break;
            }
        }
        $this->assertNotNull($idJadwal);

        self::$siswaModel->createSiswa([
            "id_kelas" => self::$kelasId,
            "nama" => "Uji Siswa HTTP Delete Jd Real Test",
            "nisn" => "9988123403",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-06-17",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. HTTP No. 13"
        ]);
        $idSiswa = self::cariSiswaId("Uji Siswa HTTP Delete Jd Real Test");
        $this->assertNotNull($idSiswa);

        self::httpPost('?url=absensi/tambahAbsensi/' . $idJadwal, [
            'id_siswa' => $idSiswa,
            'tanggal' => '2026-08-06 08:00:00',
            'kehadiran' => 'HADIR'
        ]);
        $this->assertNotEmpty(self::$jadwalModel->getAbsensiByJadwal($idJadwal), "Data kehadiran uji tidak ditemukan.");

        [$status, $location] = self::httpGet('?url=absensi/delete/' . $idJadwal);

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/absensi', $location);
        $this->assertNotContains((int) $idJadwal, array_column(self::$jadwalModel->getAllJadwal(), 'id'), "Jadwal masih ada setelah delete.");
        $this->assertEmpty(self::$jadwalModel->getAbsensiByJadwal($idJadwal), "Absensi terhubung masih ada setelah jadwal dihapus.");

        if ($idSiswa) self::$siswaModel->deleteSiswa($idSiswa);
    }

    public function testRekapMenampilkanDashboardAbsensi(): void
    {
        self::$jadwalModel->createJadwal([
            "id_kelas" => self::$kelasId,
            "id_guru" => self::$guruId,
            "id_mapel" => self::$mapelId,
            "bulan" => "Agustus",
            "tahun" => "2026/2027"
        ]);
        $idJadwal = null;
        foreach (self::$jadwalModel->getAllJadwal() as $row) {
            if ($row['id_guru'] == self::$guruId && $row['bulan'] === 'Agustus') {
                $idJadwal = (int) $row['id'];
                break;
            }
        }
        $this->assertNotNull($idJadwal);

        self::$absensiModel->createAbsensi([
            "id_jadwal" => $idJadwal,
            "id_siswa" => self::$siswaId,
            "id_guru" => self::$guruId,
            "id_mapel" => self::$mapelId,
            "id_kelas" => self::$kelasId,
            "tanggal" => "2026-08-06 08:00:00",
            "kehadiran" => "HADIR"
        ]);

        [$status, , $body] = self::httpGet('?url=absensi/rekap/' . $idJadwal);

        $this->assertSame(200, $status);
        $this->assertStringContainsString('Rekap Absensi', $body);
        $this->assertStringContainsString('Kelas HTTP Real Test', $body);
        $this->assertStringContainsString('Uji Guru HTTP Real Test', $body);
        $this->assertStringContainsString('Uji Siswa HTTP Real Test', $body);
        $this->assertStringContainsString('HADIR', $body);

        if ($idJadwal) self::$jadwalModel->deleteJadwal($idJadwal);
    }

    public function testRekapMengarahkanKeAbsensiSaatJadwalTidakDitemukan(): void
    {
        [$status, $location] = self::httpGet('?url=absensi/rekap/999999');

        $this->assertSame(302, $status);
        $this->assertStringContainsString('/absensi', $location);
    }
}
