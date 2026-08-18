<?php

namespace App\Models;

use App\Core\Database;

class JurusanModel
{
    private $table = "tb_jurusan";
    private $db;

    // Singlton dalam penggunaan koneksi databasenya
    public function __construct()
    {
        $this->db = new Database();
    }

    // Semua data jurusan untuk halaman list
    public function getAllJurusan() : array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY jurusan ASC");
        return $this->db->resultSet();
    }

    // Data jurusan untuk halaman list dengan pencarian & pagination
    public function searchJurusan(string $keyword, int $limit, int $offset) : array
    {
        if ($keyword !== '') {
            $this->db->query("SELECT * FROM {$this->table} WHERE jurusan LIKE :keyword ORDER BY jurusan ASC LIMIT :limit OFFSET :offset");
            $this->db->bind("keyword", "%" . $keyword . "%");
        } else {
            $this->db->query("SELECT * FROM {$this->table} ORDER BY jurusan ASC LIMIT :limit OFFSET :offset");
        }
        $this->db->bind("limit", $limit, \PDO::PARAM_INT);
        $this->db->bind("offset", $offset, \PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Jumlah total data jurusan, opsional difilter kata kunci
    public function countJurusan(string $keyword = '') : int
    {
        if ($keyword !== '') {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE jurusan LIKE :keyword");
            $this->db->bind("keyword", "%" . $keyword . "%");
        } else {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table}");
        }
        return (int) $this->db->single()['total'];
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

    // Satu data jurusan berdasarkan id
    public function getJurusanById($id) : ?array
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        $result = $this->db->single();
        return $result ? $result : null;
    }

    // Menambahkan jurusan baru
    public function createJurusan($data) : bool
    {
        $this->db->query("INSERT INTO {$this->table} (jurusan, updated_at) VALUES (:jurusan, CURRENT_TIMESTAMP)");
        $this->db->bind("jurusan", $data["jurusan"]);
        return (bool) $this->db->execute();
    }

    // Memperbarui data jurusan
    public function updateJurusan($id, $data) : bool
    {
        $this->db->query("UPDATE {$this->table} SET jurusan = :jurusan, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind("jurusan", $data["jurusan"]);
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }

    // Menghapus data jurusan
    public function deleteJurusan($id) : bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }
}
