<?php

namespace App\Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\SiswaModel;
use App\Core\Database;

class SiswaModelTest extends TestCase
{
    private $siswaModel;

    protected function setUp(): void
    {
        $this->siswaModel = new SiswaModel();
        $this->hapusDataTest();
    }

    protected function tearDown(): void
    {
        $this->hapusDataTest();
    }

    private function hapusDataTest(): void
    {
        foreach ($this->siswaModel->getAllSiswa() as $siswa) {
            if (str_contains($siswa['nama'], 'Real Test')) {
                $this->siswaModel->deleteSiswa($siswa['id']);
            }
        }
    }

    public function testGetAllSiswaReturnsArray(): void
    {
        // 1. Ambil semua data siswa dari database asli
        $allSiswa = $this->siswaModel->getAllSiswa();

        // 2. ASSERTION: Hasil harus berupa array
        $this->assertIsArray($allSiswa, "Hasil getAllSiswa harus berupa array.");

        // 3. ASSERTION: Setiap baris data harus memiliki kolom penting
        foreach ($allSiswa as $siswa) {
            $this->assertArrayHasKey('id', $siswa, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('nama', $siswa, "Kolom nama tidak ditemukan.");
            $this->assertArrayHasKey('nisn', $siswa, "Kolom nisn tidak ditemukan.");
        }
    }

    public function testGetSiswaBySiswaReturnsSingleData(): void
    {
        // 1. Siapkan data siswa uji
        $siswaUji = [
            "id_kelas" => 1,
            "nama" => "Uji Get By Id Real Test",
            "nisn" => "99885566",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "2008-01-15",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Sudirman No. 7"
        ];

        // 2. Simpan data uji ke database asli
        $this->assertTrue($this->siswaModel->createSiswa($siswaUji), "Gagal menginput data siswa uji.");

        // 3. Cari id data uji dari database
        $idUji = null;
        foreach ($this->siswaModel->getAllSiswa() as $siswa) {
            if ($siswa['nama'] === "Uji Get By Id Real Test") {
                $idUji = $siswa['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data siswa uji tidak ditemukan di database.");

        // 4. Ambil detail data berdasarkan id
        $detail = $this->siswaModel->getSiswaBySiswa($idUji);

        // 5. ASSERTION: Data yang dikembalikan harus sesuai
        $this->assertIsArray($detail, "Hasil getSiswaBySiswa harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Uji Get By Id Real Test", $detail['nama'], "nama tidak sesuai.");
        $this->assertSame("99885566", $detail['nisn'], "nisn tidak sesuai.");
    }

    public function testCreateSiswaReturnsTrueOnSuccess(): void
    {
        // 1. Siapkan data siswa pertama dan kedua
        $siswa1 = [
            "id_kelas" => 1,
            "nama" => "Rian Hidayat Real Test",
            "nisn" => "99881122",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-05-12",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Merdeka No. 10"
        ];

        $siswa2 = [
            "id_kelas" => 2,
            "nama" => "Siti Aminah Real Test",
            "nisn" => "99883344",
            "jurusan" => "TKJ",
            "kelas" => "XII",
            "tempat_lahir" => "Surabaya",
            "tanggal_lahir" => "2008-08-20",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Pemuda No. 45"
        ];

        // 2. Eksekusi penyimpanan data ke database asli
        $result1 = $this->siswaModel->createSiswa($siswa1);
        $this->assertTrue($result1, "Gagal menginput data siswa pertama ke database.");

        $result2 = $this->siswaModel->createSiswa($siswa2);
        $this->assertTrue($result2, "Gagal menginput data siswa kedua ke database.");

        // 3. PENGECEKAN: Ambil semua data dari database untuk memastikan data masuk
        $allSiswa = $this->siswaModel->getAllSiswa();
        
        // Ambil daftar nama saja dari seluruh data siswa yang ada di database
        $daftarNama = array_column($allSiswa, 'nama');

        // 4. ASSERTION: Pastikan kedua nama siswa di atas ada di dalam database
        $this->assertContains("Rian Hidayat Real Test", $daftarNama, "Nama siswa 1 tidak ditemukan di database!");
        $this->assertContains("Siti Aminah Real Test", $daftarNama, "Nama siswa 2 tidak ditemukan di database!");
    }

    public function testGetSiswaByIdReturnsSingleDataWithKelas(): void
    {
        $this->siswaModel->createSiswa([
            "id_kelas" => 1,
            "nama" => "Uji Detail Real Test",
            "nisn" => "99880099",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "2008-02-10",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Merdeka No. 2"
        ]);

        $idUji = null;
        foreach ($this->siswaModel->getAllSiswa() as $siswa) {
            if ($siswa['nama'] === "Uji Detail Real Test") {
                $idUji = $siswa['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data siswa uji tidak ditemukan di database.");

        $detail = $this->siswaModel->getSiswaById($idUji);

        $this->assertIsArray($detail, "Hasil getSiswaById harus berupa array.");
        $this->assertSame((int) $idUji, (int) $detail['id'], "id tidak sesuai.");
        $this->assertSame("Uji Detail Real Test", $detail['nama'], "nama tidak sesuai.");
        $this->assertArrayHasKey('nama_kelas', $detail, "Relasi nama_kelas tidak ditemukan.");
        $this->assertSame("XII RPL", $detail['nama_kelas'], "nama_kelas tidak sesuai.");
    }

    public function testGetSiswaPaginateSupportsSearch(): void
    {
        $this->siswaModel->createSiswa([
            "id_kelas" => 1,
            "nama" => "Uji Paginasi Real Test",
            "nisn" => "99880001",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Jakarta",
            "tanggal_lahir" => "2008-04-11",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Melati No. 1"
        ]);
        $this->siswaModel->createSiswa([
            "id_kelas" => 2,
            "nama" => "Uji Paginasi Real Test 2",
            "nisn" => "99880002",
            "jurusan" => "TKJ",
            "kelas" => "XII",
            "tempat_lahir" => "Surabaya",
            "tanggal_lahir" => "2008-04-12",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Melati No. 2"
        ]);

        $hasil = $this->siswaModel->getSiswaPaginate(10, 0, "Paginasi");

        $this->assertIsArray($hasil, "Hasil getSiswaPaginate harus berupa array.");
        $this->assertLessThanOrEqual(10, count($hasil), "Jumlah hasil melebihi limit.");
        $this->assertContains("Uji Paginasi Real Test", array_column($hasil, 'nama'), "Hasil pencarian tidak ditemukan.");
        $this->assertArrayHasKey('nama_kelas', $hasil[0] ?? [], "Kolom nama_kelas (relasi) tidak ditemukan.");
    }

    public function testCountSiswaReturnsCorrectTotal(): void
    {
        $this->siswaModel->createSiswa([
            "id_kelas" => 1,
            "nama" => "Uji Count Real Test",
            "nisn" => "99880003",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "2008-05-13",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Mawar No. 3"
        ]);
        $this->siswaModel->createSiswa([
            "id_kelas" => 2,
            "nama" => "Uji Count Real Test 2",
            "nisn" => "99880004",
            "jurusan" => "TKJ",
            "kelas" => "XII",
            "tempat_lahir" => "Semarang",
            "tanggal_lahir" => "2008-05-14",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Mawar No. 4"
        ]);

        $total = $this->siswaModel->countSiswa("Count Real Test");

        $this->assertIsInt($total, "Hasil countSiswa harus berupa integer.");
        $this->assertSame(2, $total, "Jumlah total siswa hasil pencarian tidak sesuai.");
    }

    public function testUpdateSiswaReturnsTrueOnSuccess(): void
    {
        $this->siswaModel->createSiswa([
            "id_kelas" => 1,
            "nama" => "Uji Update Real Test",
            "nisn" => "99880005",
            "jurusan" => "RPL",
            "kelas" => "XII",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "2008-06-15",
            "jenis_kelamin" => "LAKI",
            "alamat" => "Jl. Kenanga No. 5"
        ]);

        $idUji = null;
        foreach ($this->siswaModel->getAllSiswa() as $siswa) {
            if ($siswa['nama'] === "Uji Update Real Test") {
                $idUji = $siswa['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data siswa uji tidak ditemukan di database.");

        $result = $this->siswaModel->updateSiswa($idUji, [
            "id_kelas" => 2,
            "nama" => "Uji Update Real Test",
            "nisn" => "99880005",
            "jurusan" => "TKJ",
            "kelas" => "XII",
            "tempat_lahir" => "Bandung",
            "tanggal_lahir" => "2008-06-15",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Kenanga No. 55"
        ]);
        $this->assertTrue($result, "Gagal memperbarui data siswa dari database.");

        $detail = $this->siswaModel->getSiswaById($idUji);
        $this->assertSame("TKJ", $detail['jurusan'], "jurusan tidak berubah setelah update.");
        $this->assertSame("PEREMPUAN", $detail['jenis_kelamin'], "jenis_kelamin tidak berubah setelah update.");
        $this->assertSame("Jl. Kenanga No. 55", $detail['alamat'], "alamat tidak berubah setelah update.");
    }

    public function testDeleteSiswaReturnsTrueOnSuccess(): void
    {
        // 1. Siapkan data siswa uji
        $siswaUji = [
            "id_kelas" => 2,
            "nama" => "Uji Hapus Real Test",
            "nisn" => "99887788",
            "jurusan" => "TKJ",
            "kelas" => "XII",
            "tempat_lahir" => "Semarang",
            "tanggal_lahir" => "2008-03-09",
            "jenis_kelamin" => "PEREMPUAN",
            "alamat" => "Jl. Pandanaran No. 21"
        ];

        // 2. Simpan data uji ke database asli
        $this->assertTrue($this->siswaModel->createSiswa($siswaUji), "Gagal menginput data siswa uji.");

        // 3. Cari id data uji dari database
        $idUji = null;
        foreach ($this->siswaModel->getAllSiswa() as $siswa) {
            if ($siswa['nama'] === "Uji Hapus Real Test") {
                $idUji = $siswa['id'];
                break;
            }
        }
        $this->assertNotNull($idUji, "Data siswa uji tidak ditemukan di database.");

        // 4. Eksekusi penghapusan data
        $result = $this->siswaModel->deleteSiswa($idUji);
        $this->assertTrue($result, "Gagal menghapus data siswa dari database.");

        // 5. ASSERTION: Pastikan data sudah tidak ada di database
        $daftarNama = array_column($this->siswaModel->getAllSiswa(), 'nama');
        $this->assertNotContains("Uji Hapus Real Test", $daftarNama, "Data siswa masih ada setelah dihapus!");
    }
}
