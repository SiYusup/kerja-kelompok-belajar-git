<?php

namespace App\Models;

use App\Core\Database;

class SiswaModel 
{
    private $table = "tb_siswa";
    private $db;

    // Singlton dalam penggunaan koneksi databasenya
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @unused Tidak dipakai lagi pada halaman index (digantikan getSiswaPaginate()).
     * Fungsi ini tetap dipertahankan dan tidak dihapus agar fitur lama tetap tersedia.
     */
    public function getAllSiswa() : array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY update_at DESC");
        return $this->db->resultSet();
    }

    /**
     * @unused Tidak dipakai lagi pada halaman detail (digantikan getSiswaById()).
     * Fungsi ini tetap dipertahankan dan tidak dihapus.
     */
    public function getSiswaBySiswa($id) : array
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return $this->db->single();
    }

    // Mendapatkan satu siswa beserta nama kelasnya (dipakai pada halaman detail)
    public function getSiswaById($id) : ?array
    {
        $this->db->query("
            SELECT s.*, k.kelas AS nama_kelas
            FROM {$this->table} s
            JOIN tb_kelas k ON s.id_kelas = k.id
            WHERE s.id = :id
        ");
        $this->db->bind("id", $id);
        $result = $this->db->single();
        return $result ? $result : null;
    }

    // Data siswa dengan dukungan pencarian & paginasi (dipakai pada halaman index)
    public function getSiswaPaginate(int $limit, int $offset, string $keyword = "") : array
    {
        if ($keyword !== "") {
            $this->db->query("
                SELECT s.*, k.kelas AS nama_kelas
                FROM {$this->table} s
                JOIN tb_kelas k ON s.id_kelas = k.id
                WHERE s.nama LIKE :keyword OR s.nisn LIKE :keyword OR s.jurusan LIKE :keyword OR s.kelas LIKE :keyword
                ORDER BY s.update_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $this->db->bind("keyword", "%{$keyword}%");
        } else {
            $this->db->query("
                SELECT s.*, k.kelas AS nama_kelas
                FROM {$this->table} s
                JOIN tb_kelas k ON s.id_kelas = k.id
                ORDER BY s.update_at DESC
                LIMIT :limit OFFSET :offset
            ");
        }
        $this->db->bind("limit", (int) $limit, \PDO::PARAM_INT);
        $this->db->bind("offset", (int) $offset, \PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Total jumlah siswa (dengan pencarian bila ada) untuk keperluan paginasi
    public function countSiswa(string $keyword = "") : int
    {
        if ($keyword !== "") {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE nama LIKE :keyword OR nisn LIKE :keyword OR jurusan LIKE :keyword OR kelas LIKE :keyword");
            $this->db->bind("keyword", "%{$keyword}%");
        } else {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table}");
        }
        $row = $this->db->single();
        return (int) $row["total"];
    }

    public function createSiswa($data) : bool
    {
        $this->db->query("INSERT INTO {$this->table} (id_kelas, nama, nisn, jurusan, kelas, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, update_at) VALUES (:id_kelas, :nama, :nisn, :jurusan, :kelas, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :alamat, CURRENT_TIMESTAMP)");
        $this->db->bind("id_kelas", $data["id_kelas"]);
        $this->db->bind("nama", $data["nama"]);
        $this->db->bind("nisn", $data["nisn"]);
        $this->db->bind("jurusan", $data["jurusan"]);
        $this->db->bind("kelas", $data["kelas"]);
        $this->db->bind("tempat_lahir", $data["tempat_lahir"]);
        $this->db->bind("tanggal_lahir", $data["tanggal_lahir"]);
        $this->db->bind("jenis_kelamin", $data["jenis_kelamin"]);
        $this->db->bind("alamat", $data["alamat"]);
        return (bool) $this->db->execute();
    }

    public function updateSiswa($id, $data) : bool
    {
        $this->db->query("UPDATE {$this->table} SET id_kelas = :id_kelas, nama = :nama, nisn = :nisn, jurusan = :jurusan, kelas = :kelas, tempat_lahir = :tempat_lahir, tanggal_lahir = :tanggal_lahir, jenis_kelamin = :jenis_kelamin, alamat = :alamat, update_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind("id_kelas", $data["id_kelas"]);
        $this->db->bind("nama", $data["nama"]);
        $this->db->bind("nisn", $data["nisn"]);
        $this->db->bind("jurusan", $data["jurusan"]);
        $this->db->bind("kelas", $data["kelas"]);
        $this->db->bind("tempat_lahir", $data["tempat_lahir"]);
        $this->db->bind("tanggal_lahir", $data["tanggal_lahir"]);
        $this->db->bind("jenis_kelamin", $data["jenis_kelamin"]);
        $this->db->bind("alamat", $data["alamat"]);
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }

    public function deleteSiswa($id) : bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }
}
