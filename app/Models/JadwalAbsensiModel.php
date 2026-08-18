<?php

namespace App\Models;

use App\Core\Database;

class JadwalAbsensiModel
{
    private $table = "tb_absensi_jadwal";
    private $db;

    // Singlton dalam penggunaan koneksi databasenya
    public function __construct()
    {
        $this->db = new Database();
    }

    // Semua jadwal absensi lengkap dengan relasi kelas, guru, dan mapel
    public function getAllJadwal() : array
    {
        $this->db->query("
            SELECT j.*, k.kelas, g.nama AS nama_guru, g.nip AS nip_guru, m.mapel
            FROM {$this->table} j
            JOIN tb_kelas k ON j.id_kelas = k.id
            JOIN tb_guru g ON j.id_guru = g. id
            JOIN tb_mapel m ON j.id_mapel = m.id
            ORDER BY j.updated_at DESC
        ");
        return $this->db->resultSet();
    }

    // Satu jadwal absensi berdasarkan id
    public function getJadwalById($id) : array
    {
        $this->db->query("
            SELECT j.*, k.kelas, g.nama AS nama_guru, g.nip AS nip_guru, m.mapel
            FROM {$this->table} j
            JOIN tb_kelas k ON j.id_kelas = k.id
            JOIN tb_guru g ON j.id_guru = g.id
            JOIN tb_mapel m ON j.id_mapel = m.id
            WHERE j.id = :id
        ");
        $this->db->bind("id", $id);
        return $this->db->single();
    }

    // Menambahkan jadwal absensi baru (kelas, guru, mapel, bulan, tahun)
    public function createJadwal($data) : bool
    {
        $this->db->query("INSERT INTO {$this->table} (id_kelas, id_guru, id_mapel, bulan, tahun, updated_at) VALUES (:id_kelas, :id_guru, :id_mapel, :bulan, :tahun, CURRENT_TIMESTAMP)");
        $this->db->bind("id_kelas", $data["id_kelas"]);
        $this->db->bind("id_guru", $data["id_guru"]);
        $this->db->bind("id_mapel", $data["id_mapel"]);
        $this->db->bind("bulan", $data["bulan"]);
        $this->db->bind("tahun", $data["tahun"]);
        return (bool) $this->db->execute();
    }

    // Memperbarui jadwal absensi
    public function updateJadwal($id, $data) : bool
    {
        $this->db->query("UPDATE {$this->table} SET id_kelas = :id_kelas, id_guru = :id_guru, id_mapel = :id_mapel, bulan = :bulan, tahun = :tahun, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind("id_kelas", $data["id_kelas"]);
        $this->db->bind("id_guru", $data["id_guru"]);
        $this->db->bind("id_mapel", $data["id_mapel"]);
        $this->db->bind("bulan", $data["bulan"]);
        $this->db->bind("tahun", $data["tahun"]);
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }

    // Menghapus jadwal beserta seluruh data absensi siswa yang terhubung
    public function deleteJadwal($id) : bool
    {
        $this->db->query("DELETE FROM tb_absensi WHERE id_jadwal = :id");
        $this->db->bind("id", $id);
        $this->db->execute();

        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind("id", $id);
        return (bool) $this->db->execute();
    }

    // Data kelas untuk dropdown form
    public function getListKelas() : array
    {
        $this->db->query("SELECT id, kelas FROM tb_kelas ORDER BY kelas ASC");
        return $this->db->resultSet();
    }

    // Data guru untuk dropdown form (relasi ke tb_guru)
    public function getListGuru() : array
    {
        $this->db->query("SELECT id, nama, nip FROM tb_guru ORDER BY nama ASC");
        return $this->db->resultSet();
    }

    // Data mapel untuk dropdown form (relasi ke tb_mapel)
    public function getListMapel() : array
    {
        $this->db->query("SELECT id, mapel FROM tb_mapel ORDER BY mapel ASC");
        return $this->db->resultSet();
    }

    // Data siswa pada kelas tertentu untuk dropdown absensi & rekap
    public function getListSiswaByKelas($idKelas) : array
    {
        $this->db->query("SELECT id, nama, nisn, jenis_kelamin FROM tb_siswa WHERE id_kelas = :id_kelas ORDER BY nama ASC");
        $this->db->bind("id_kelas", $idKelas);
        return $this->db->resultSet();
    }

    // Data absensi siswa yang terhubung ke sebuah jadwal
    public function getAbsensiByJadwal($idJadwal) : array
    {
        $this->db->query("
            SELECT a.*, s.nama AS nama_siswa, s.nisn AS nisn_siswa
            FROM tb_absensi a
            JOIN tb_siswa s ON a.id_siswa = s.id
            WHERE a.id_jadwal = :id_jadwal
            ORDER BY s.nama ASC
        ");
        $this->db->bind("id_jadwal", $idJadwal);
        return $this->db->resultSet();
    }

    // Statistik jumlah kehadiran per status untuk sebuah jadwal
    public function getStatistikAbsensi($idJadwal) : array
    {
        $this->db->query("
            SELECT kehadiran, COUNT(*) AS jumlah
            FROM tb_absensi
            WHERE id_jadwal = :id_jadwal
            GROUP BY kehadiran
        ");
        $this->db->bind("id_jadwal", $idJadwal);
        return $this->db->resultSet();
    }

    // Rekap jumlah kehadiran per tanggal dan status untuk sebuah jadwal
    public function getRekapHarian($idJadwal) : array
    {
        $this->db->query("
            SELECT DATE(tanggal) AS tanggal, kehadiran, COUNT(*) AS jumlah
            FROM tb_absensi
            WHERE id_jadwal = :id_jadwal
            GROUP BY DATE(tanggal), kehadiran
            ORDER BY tanggal ASC
        ");
        $this->db->bind("id_jadwal", $idJadwal);
        return $this->db->resultSet();
    }
}
