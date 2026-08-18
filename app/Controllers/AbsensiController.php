<?php

namespace App\Controllers;

use App\Core\Controller;

class AbsensiController extends Controller
{
    private function daftarBulan(): array
    {
        return ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    }

    private function daftarTahun(): array
    {
        $tahun = [];
        $awal = (int) date("Y") - 2;
        for ($t = $awal; $t <= (int) date("Y") + 3; $t++) {
            $tahun[] = $t . "/" . ($t + 1);
        }
        return $tahun;
    }

    public function index()
    {
        $model = $this->model("JadwalAbsensiModel");

        $data["title"] = "Jadwal Absensi";
        $data["jadwal"] = $model->getAllJadwal();
        $data["kelas"] = $model->getListKelas();
        $data["guru"] = $model->getListGuru();
        $data["mapel"] = $model->getListMapel();
        $data["bulan"] = $this->daftarBulan();
        $data["tahun"] = $this->daftarTahun();

        $this->view("absensi/index", $data);
    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("JadwalAbsensiModel")->createJadwal([
                "id_kelas" => $_POST["id_kelas"],
                "id_guru" => $_POST["id_guru"],
                "id_mapel" => $_POST["id_mapel"],
                "bulan" => $_POST["bulan"],
                "tahun" => $_POST["tahun"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Jadwal absensi berhasil dibuat."];
        }

        header("Location: /absensi");
        exit;
    }

    public function detail($id)
    {
        $model = $this->model("JadwalAbsensiModel");

        $data["title"] = "Detail Absensi";
        $data["jadwal"] = $model->getJadwalById($id);
        $data["absensi"] = $model->getAbsensiByJadwal($id);
        $data["siswa"] = $model->getListSiswaByKelas($data["jadwal"]["id_kelas"]);
        $data["kehadiran"] = ["HADIR", "IZIN", "SAKIT", "ALPHA"];

        $this->view("absensi/detail", $data);
    }

    public function edit($id)
    {
        $model = $this->model("JadwalAbsensiModel");

        $data["title"] = "Edit Jadwal Absensi";
        $data["jadwal"] = $model->getJadwalById($id);
        $data["kelas"] = $model->getListKelas();
        $data["guru"] = $model->getListGuru();
        $data["mapel"] = $model->getListMapel();
        $data["bulan"] = $this->daftarBulan();
        $data["tahun"] = $this->daftarTahun();

        $this->view("absensi/edit", $data);
    }

    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("JadwalAbsensiModel")->updateJadwal($id, [
                "id_kelas" => $_POST["id_kelas"],
                "id_guru" => $_POST["id_guru"],
                "id_mapel" => $_POST["id_mapel"],
                "bulan" => $_POST["bulan"],
                "tahun" => $_POST["tahun"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Jadwal absensi berhasil diperbarui."];
        }

        header("Location: /absensi");
        exit;
    }

    public function delete($id)
    {
        $this->model("JadwalAbsensiModel")->deleteJadwal($id);

        $_SESSION["flash"] = ["type" => "success", "message" => "Jadwal absensi berhasil dihapus."];

        header("Location: /absensi");
        exit;
    }

    public function tambahAbsensi($idJadwal)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $model = $this->model("JadwalAbsensiModel");
            $jadwal = $model->getJadwalById($idJadwal);

            $this->model("AbsensiModel")->createAbsensi([
                "id_jadwal" => $idJadwal,
                "id_siswa" => $_POST["id_siswa"],
                "id_guru" => $jadwal["id_guru"],
                "id_mapel" => $jadwal["id_mapel"],
                "id_kelas" => $jadwal["id_kelas"],
                "tanggal" => $_POST["tanggal"],
                "kehadiran" => $_POST["kehadiran"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Kehadiran siswa berhasil ditambahkan."];
        }

        header("Location: /absensi/detail/" . $idJadwal);
        exit;
    }

    public function hapusAbsensi($idJadwal, $idAbsensi)
    {
        $this->model("AbsensiModel")->deleteAbsensi($idAbsensi);

        $_SESSION["flash"] = ["type" => "success", "message" => "Kehadiran siswa berhasil dihapus."];

        header("Location: /absensi/detail/" . $idJadwal);
        exit;
    }

    // Dashboard rekapan absensi sebuah jadwal (kelas, guru, mapel, dan kehadiran siswa per tanggal)
    public function rekap($id)
    {
        $model = $this->model("JadwalAbsensiModel");

        $jadwal = $model->getJadwalById($id);
        if (!$jadwal) {
            $_SESSION["flash"] = ["type" => "warning", "message" => "Jadwal absensi tidak ditemukan."];
            header("Location: /absensi");
            exit;
        }

        $absensi = $model->getAbsensiByJadwal($id);
        $siswa = $model->getListSiswaByKelas($jadwal["id_kelas"]);

        // Jumlah siswa berdasarkan jenis kelamin
        $lakiLaki = 0;
        $perempuan = 0;
        foreach ($siswa as $s) {
            if (($s["jenis_kelamin"] ?? '') === "LAKI") {
                $lakiLaki++;
            } elseif (($s["jenis_kelamin"] ?? '') === "PEREMPUAN") {
                $perempuan++;
            }
        }

        // Peta kehadiran: id_siswa => tanggal (YYYY-MM-DD) => status
        $map = [];
        foreach ($absensi as $a) {
            $tgl = substr($a["tanggal"], 0, 10);
            $map[(string) $a["id_siswa"]][$tgl] = $a["kehadiran"];
        }

        // Kalender penuh satu bulan dari jadwal (hari di atas, tanggal di bawah)
        $daftarBulanNama = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $namaHariId = [1 => "Senin", 2 => "Selasa", 3 => "Rabu", 4 => "Kamis", 5 => "Jumat", 6 => "Sabtu", 7 => "Minggu"];

        $bulanAngka = array_search($jadwal["bulan"], $daftarBulanNama);
        $bulanAngka = $bulanAngka !== false ? $bulanAngka + 1 : (int) date("n");
        preg_match('/^(\d{4})/', $jadwal["tahun"], $mTahun);
        $tahunAwal = isset($mTahun[1]) ? (int) $mTahun[1] : (int) date("Y");
        // Tahun ajaran: Juli-Desember = tahun pertama, Januari-Juni = tahun kedua
        $tahun = $bulanAngka >= 7 ? $tahunAwal : $tahunAwal + 1;

        $jumlahHari = (int) date("t", strtotime(sprintf("%04d-%02d-01", $tahun, $bulanAngka)));
        $kalender = [];
        for ($d = 1; $d <= $jumlahHari; $d++) {
            $tgl = sprintf("%04d-%02d-%02d", $tahun, $bulanAngka, $d);
            $nHari = (int) date("N", strtotime($tgl));
            $kalender[] = [
                "tanggal" => $tgl,
                "tanggalAngka" => $d,
                "hari" => $namaHariId[$nHari]
            ];
        }

        // Statistik jumlah per status
        $statistikMap = [];
        foreach ($model->getStatistikAbsensi($id) as $s) {
            $statistikMap[$s["kehadiran"]] = (int) $s["jumlah"];
        }

        // Rekap harian: tanggal => status => jumlah
        $rekapHarianMap = [];
        foreach ($model->getRekapHarian($id) as $r) {
            $rekapHarianMap[$r["tanggal"]][$r["kehadiran"]] = (int) $r["jumlah"];
        }

        $data["title"] = "Rekap Absensi";
        $data["jadwal"] = $jadwal;
        $data["siswa"] = $siswa;
        $data["absensi"] = $absensi;
        $data["map"] = $map;
        $data["kalender"] = $kalender;
        $data["statistikMap"] = $statistikMap;
        $data["rekapHarianMap"] = $rekapHarianMap;
        $data["daftarStatus"] = ["HADIR", "IZIN", "SAKIT", "ALPHA"];
        $data["lakiLaki"] = $lakiLaki;
        $data["perempuan"] = $perempuan;

        $this->view("rekapan/index", $data);
    }
}
