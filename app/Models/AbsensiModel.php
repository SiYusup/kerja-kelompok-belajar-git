<?php

namespace App\Models;

use App\Core\Database;

class AbsensiModel
{
    private $table = "tb_absensi";
    private $db;

    // Singlton dalam penggunaan koneksi databasenya
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllAbsensi() : array
    {
        $this->db->query("
            SELECT a.*, g.nama AS nama_guru, g.nip AS nip_guru,
                   s.nama AS nama_siswa, s.nisn AS nisn_siswa,
                   m.mapel, k.kelas
            FROM {$this->table} a
            JOIN tb_guru g ON a.id_guru = g.id
            JOIN tb_siswa s ON a.id_siswa = s.id
            JOIN tb_mapel m ON a.id_mapel = m.id
            JOIN tb_kelas k ON a.id_kelas = k.id
            ORDER BY a.tanggal DESC, a.updated_at DESC
        ");
        return $this->db->resultSet();
    }

    public function getAbsensiById($id) : array
    {
        $this->db->query("
            SELECT a.*, g.nama AS nama_guru, g.nip AS nip_guru,
                   s.nama AS nama_siswa, s.nisn AS nisn_siswa,
                   m.mapel, k.kelas
            FROM {$this->table} a
            JOIN tb_guru g ON a.id_guru = g.id
            JOIN tb_siswa s ON a.id_siswa = s.id
            JOIN tb_mapel m ON a.id_mapel = m.id
            JOIN tb_kelas k ON a.id_kelas = k.id
            WHERE a.id = :id
        ");
        $this->db->bind("id", $id);
        return $this->db->single();
    }

    // Data guru untuk dropdown form (relasi ke tb_guru)
    public function getListGuru() : array
    {
        $this->db->query("SELECT id, nama, nip FROM tb_guru ORDER BY nama ASC");
        return $this->db->resultSet();
    }

    // Data murid untuk dropdown form (relasi ke tb_siswa)
    public function getListSiswa() : array
    {
        $this->db->query("SELECT id, nama, nisn, id_kelas FROM tb_siswa ORDER BY nama ASC");
        return $this->db->resultSet();
    }

    public function createAbsensi($data) : bool
    {
        $this->db->query("INSERT INTO {$this->table} (id_jadwal, id_siswa, id_guru, id_mapel, id_kelas, tanggal, kehadiran, updated_at) VALUES (:id_jadwal, :id_siswa, :id_guru, :id_mapel, :id_kelas, :tanggal, :kehadiran, CURRENT_TIMESTAMP)");
        $this->db->bind("id_jadwal", $data["id_jadwal"] ?? null);
        $this->db->bind("id_siswa", $data["id_siswa"]);
        $this->db->bind("id_guru", $data["id_guru"]);
        $this->db->bind("id_mapel", $data["id_mapel"]);
        $this->db->bind("id_kelas", $data["id_kelas"]);
        $this->db->bind("tanggal", $data["tanggal"]);
        $this->db->bind("kehadiran", $data["kehadiran"]);
        return (bool) $this->db->execute();
    }

    public function updateAbsensi($id, $data) : bool
    {
        $this->db->query("UPDATE {$this->table} SET id_jadwal = :id_jadwal, id_siswa = :id_siswa, id_guru = :id_guru, id_mapel = :id_mapel, id_kelas = :id_kelas, tanggal = :tanggal, kehadiran = :kehadiran, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind("id_jadwal", $data["id_jadwal"] ?? null);
        $this->db->bind("id_siswa", $data["id_siswa"]);
        $this->db->bind("id_guru", $data["id_guru"]);
        $this->db->bind("id_mapel", $data["id_mapel"]);
        $this->db->bind("id_kelas", $data["id_kelas"]);
        $this->db->bind("tanggal", $data["tanggal"]);
        $this->db->bind("kehadiran", $data["kehadiran"]);
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }

    public function deleteAbsensi($id) : bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }
}
