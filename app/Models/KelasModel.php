<?php

namespace App\Models;

use App\Core\Database;

class KelasModel
{
    private $table = "tb_kelas";
    private $db;

    // Singlton dalam penggunaan koneksi databasenya
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllKelas() : array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY updated_at DESC");
        return $this->db->resultSet();
    }

    // Data kelas untuk halaman list dengan pencarian & pagination
    public function searchKelas(string $keyword, int $limit, int $offset) : array
    {
        if ($keyword !== '') {
            $this->db->query("SELECT * FROM {$this->table} WHERE kelas LIKE :keyword ORDER BY kelas ASC LIMIT :limit OFFSET :offset");
            $this->db->bind("keyword", "%" . $keyword . "%");
        } else {
            $this->db->query("SELECT * FROM {$this->table} ORDER BY kelas ASC LIMIT :limit OFFSET :offset");
        }
        $this->db->bind("limit", $limit, \PDO::PARAM_INT);
        $this->db->bind("offset", $offset, \PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Jumlah total data kelas, opsional difilter kata kunci
    public function countKelas(string $keyword = '') : int
    {
        if ($keyword !== '') {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE kelas LIKE :keyword");
            $this->db->bind("keyword", "%" . $keyword . "%");
        } else {
            $this->db->query("SELECT COUNT(*) AS total FROM {$this->table}");
        }
        return (int) $this->db->single()['total'];
    }

    public function getKelasById($id) : ?array
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        $result = $this->db->single();
        return $result ? $result : null;
    }

    public function createKelas($data) : bool
    {
        $this->db->query("INSERT INTO {$this->table} (kelas, updated_at) VALUES (:kelas, CURRENT_TIMESTAMP)");
        $this->db->bind("kelas", $data["kelas"]);
        return (bool) $this->db->execute();
    }

    public function updateKelas($id, $data) : bool
    {
        $this->db->query("UPDATE {$this->table} SET kelas = :kelas, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind("kelas", $data["kelas"]);
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }

    public function deleteKelas($id) : bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }
}
