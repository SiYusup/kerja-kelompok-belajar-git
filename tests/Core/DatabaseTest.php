<?php

namespace App\Tests\Core;

use PHPUnit\Framework\TestCase;
use App\Core\Database;

class DatabaseTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $this->db = new Database();
        $this->bersihkanDataUji();
    }

    protected function tearDown(): void
    {
        $this->bersihkanDataUji();
    }

    // Membersihkan data sisa milik DatabaseTest (penanda "DB Uji") bila ada
    private function bersihkanDataUji(): void
    {
        $this->db->query("DELETE FROM tb_mapel WHERE mapel LIKE :k");
        $this->db->bind("k", "%DB Uji%");
        $this->db->execute();
    }

    public function testKoneksiDatabaseBerhasil(): void
    {
        $db = new Database();

        $this->assertInstanceOf(Database::class, $db);
    }

    public function testQueryExecuteSelectBerhasil(): void
    {
        $this->db->query("SELECT COUNT(*) AS total FROM tb_kelas");

        $stmt = $this->db->execute();

        $this->assertNotFalse($stmt, "Statement harus berhasil dieksekusi.");
    }

    public function testSingleMengembalikanArrayAssoc(): void
    {
        $this->db->query("SELECT COUNT(*) AS total FROM tb_kelas");

        $row = $this->db->single();

        $this->assertIsArray($row, "Hasil single() harus berupa array assoc.");
        $this->assertArrayHasKey('total', $row, "Kolom total tidak ditemukan.");
    }

    public function testResultSetMengembalikanArrayArrayAssoc(): void
    {
        $this->db->query("SELECT id, kelas FROM tb_kelas ORDER BY id ASC");

        $rows = $this->db->resultSet();

        $this->assertIsArray($rows, "Hasil resultSet() harus berupa array.");
        $this->assertNotEmpty($rows, "Harus ada minimal satu data kelas.");
        foreach ($rows as $row) {
            $this->assertArrayHasKey('id', $row, "Kolom id tidak ditemukan.");
            $this->assertArrayHasKey('kelas', $row, "Kolom kelas tidak ditemukan.");
        }
    }

    public function testBindMendeteksiTipeIntegerUntukLimit(): void
    {
        $this->db->query("SELECT * FROM tb_kelas ORDER BY id ASC LIMIT :limit");
        $this->db->bind("limit", 1);

        $rows = $this->db->resultSet();

        $this->assertCount(1, $rows, "Bind tipe integer harus diterapkan pada LIMIT (string akan error di MySQL).");
    }

    public function testBindMendeteksiTipeIntegerUntukWhere(): void
    {
        $this->db->query("SELECT * FROM tb_kelas WHERE id = :id");
        $this->db->bind("id", 1);

        $row = $this->db->single();

        $this->assertIsArray($row, "Bind integer untuk klausa WHERE harus mengembalikan baris.");
    }

    public function testInsertSelectDeleteRoundTrip(): void
    {
        $this->db->query("INSERT INTO tb_mapel (mapel, update_at) VALUES (:mapel, CURRENT_TIMESTAMP)");
        $this->db->bind("mapel", "DB Uji Round Trip");
        $this->assertTrue((bool) $this->db->execute(), "Gagal menginput data uji.");

        $this->db->query("SELECT * FROM tb_mapel WHERE mapel = :mapel");
        $this->db->bind("mapel", "DB Uji Round Trip");
        $row = $this->db->single();
        $this->assertIsArray($row, "Data uji tidak ditemukan setelah diinsert.");
        $this->assertSame("DB Uji Round Trip", $row['mapel'], "Nilai kolom tidak sesuai.");

        $this->db->query("DELETE FROM tb_mapel WHERE id = :id");
        $this->db->bind("id", $row['id']);
        $this->assertTrue((bool) $this->db->execute(), "Gagal menghapus data uji.");

        $this->db->query("SELECT * FROM tb_mapel WHERE id = :id");
        $this->db->bind("id", $row['id']);
        $this->assertFalse($this->db->single(), "Data uji masih ada setelah dihapus.");
    }
}
