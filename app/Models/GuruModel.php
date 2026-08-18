<?php

namespace App\Models;

use App\Core\Database;

class GuruModel
{
    private $table = "tb_guru";
    private $db;

    // Singlton dalam penggunaan koneksi databasenya
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @unused Tidak dipakai lagi pada halaman index (digantikan getGuruPaginate()).
     * Fungsi ini tetap dipertahankan dan tidak dihapus agar fitur lama tetap tersedia.
     */
    public function getAllGuru() : array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY updated_at DESC");
        return $this->db->resultSet();
    }

    /**
     * @unused Tidak dipakai lagi pada halaman detail (digantikan getGuruDetail()).
     * Fungsi ini tetap dipertahankan dan tidak dihapus.
     */
    public function getGuruById($id) : array
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return $this->db->single();
    }

    // Mendapatkan satu guru beserta nama mapelnya (dipakai pada halaman detail)
    public function getGuruDetail($id) : ?array
    {
        $this->db->query("
            SELECT g.*, m.mapel AS nama_mapel
            FROM {$this->table} g
            JOIN tb_mapel m ON g.id_mapel = m.id
            WHERE g.id = :id
        ");
        $this->db->bind("id", $id);
        $result = $this->db->single();
        return $result ? $result : null;
    }

    // Data guru dengan dukungan pencarian & paginasi (dipakai pada halaman index)
    public function getGuruPaginate(int $limit, int $offset, string $keyword = "") : array
    {
        if ($keyword !== "") {
            $this->db->query("
                SELECT g.*, m.mapel AS nama_mapel
                FROM {$this->table} g
                JOIN tb_mapel m ON g.id_mapel = m.id
                WHERE g.nama LIKE :keyword OR g.nip LIKE :keyword OR g.jurusan LIKE :keyword OR m.mapel LIKE :keyword
                ORDER BY g.updated_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $this->db->bind("keyword", "%{$keyword}%");
        } else {
            $this->db->query("
                SELECT g.*, m.mapel AS nama_mapel
                FROM {$this->table} g
                JOIN tb_mapel m ON g.id_mapel = m.id
                ORDER BY g.updated_at DESC
                LIMIT :limit OFFSET :offset
            ");
        }
        $this->db->bind("limit", (int) $limit, \PDO::PARAM_INT);
        $this->db->bind("offset", (int) $offset, \PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Total jumlah guru (dengan pencarian bila ada) untuk keperluan paginasi
    public function countGuru(string $keyword = "") : int
    {
        if ($keyword !== "") {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE nama LIKE :keyword OR nip LIKE :keyword OR jurusan LIKE :keyword OR id_mapel IN (SELECT id FROM tb_mapel WHERE mapel LIKE :keyword)");
            $this->db->bind("keyword", "%{$keyword}%");
        } else {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table}");
        }
        $row = $this->db->single();
        return (int) $row["total"];
    }

    public function createGuru($data) : bool
    {
        $this->db->query("INSERT INTO {$this->table} (id_mapel, nama, nip, jurusan, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, updated_at) VALUES (:id_mapel, :nama, :nip, :jurusan, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :alamat, CURRENT_TIMESTAMP)");
        $this->db->bind("id_mapel", $data["id_mapel"]);
        $this->db->bind("nama", $data["nama"]);
        $this->db->bind("nip", $data["nip"]);
        $this->db->bind("jurusan", $data["jurusan"]);
        $this->db->bind("tempat_lahir", $data["tempat_lahir"]);
        $this->db->bind("tanggal_lahir", $data["tanggal_lahir"]);
        $this->db->bind("jenis_kelamin", $data["jenis_kelamin"]);
        $this->db->bind("alamat", $data["alamat"]);
        return (bool) $this->db->execute();
    }

    public function updateGuru($id, $data) : bool
    {
        $this->db->query("UPDATE {$this->table} SET id_mapel = :id_mapel, nama = :nama, nip = :nip, jurusan = :jurusan, tempat_lahir = :tempat_lahir, tanggal_lahir = :tanggal_lahir, jenis_kelamin = :jenis_kelamin, alamat = :alamat, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind("id_mapel", $data["id_mapel"]);
        $this->db->bind("nama", $data["nama"]);
        $this->db->bind("nip", $data["nip"]);
        $this->db->bind("jurusan", $data["jurusan"]);
        $this->db->bind("tempat_lahir", $data["tempat_lahir"]);
        $this->db->bind("tanggal_lahir", $data["tanggal_lahir"]);
        $this->db->bind("jenis_kelamin", $data["jenis_kelamin"]);
        $this->db->bind("alamat", $data["alamat"]);
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }

    public function deleteGuru($id) : bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }
}
