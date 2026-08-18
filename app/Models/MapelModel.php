<?php

namespace App\Models;

use App\Core\Database;

class MapelModel
{
    private $table = "tb_mapel";
    private $db;

    // Singlton dalam penggunaan koneksi databasenya
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllMapel() : array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY update_at DESC");
        return $this->db->resultSet();
    }

    public function getMapelById($id) : array
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return $this->db->single();
    }

    public function createMapel($data) : bool
    {
        $this->db->query("INSERT INTO {$this->table} (mapel, update_at) VALUES (:mapel, CURRENT_TIMESTAMP)");
        $this->db->bind("mapel", $data["mapel"]);
        return (bool) $this->db->execute();
    }

    public function deleteMapel($id) : bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }
}
