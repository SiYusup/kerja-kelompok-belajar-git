<?php

namespace App\Controllers;

use App\Core\Controller;

class SiswaController extends Controller
{
    public function index() 
    {
        $keyword = isset($_GET["search"]) ? trim($_GET["search"]) : "";
        $perHalaman = 9;
        $halamanAktif = isset($_GET["page"]) && is_numeric($_GET["page"]) ? max(1, (int) $_GET["page"]) : 1;
        $offset = ($halamanAktif - 1) * $perHalaman;

        $model = $this->model("SiswaModel");
        $totalData = $model->countSiswa($keyword);
        $totalHalaman = (int) ceil($totalData / $perHalaman);

        $data["title"] = "Daftar Siswa";
        $data["siswa"] = $model->getSiswaPaginate($perHalaman, $offset, $keyword);
        $data["keyword"] = $keyword;
        $data["halamanAktif"] = $halamanAktif;
        $data["totalHalaman"] = $totalHalaman;
        $data["totalData"] = $totalData;
        $data["perHalaman"] = $perHalaman;

        $this->view("siswa/index", $data);
    }

    public function detail($id)
    {
        $data["title"] = "Detail Siswa";
        $data["siswa"] = $this->model("SiswaModel")->getSiswaById($id);

        $this->view("siswa/detail", $data);
    }

    public function tambah()
    {
        $data["title"] = "Tambah Siswa";
        $data["kelas"] = $this->model("KelasModel")->getAllKelas();

        $this->view("siswa/tambah", $data);
    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("SiswaModel")->createSiswa([
                "id_kelas" => $_POST["id_kelas"],
                "nama" => $_POST["nama"],
                "nisn" => $_POST["nisn"],
                "jurusan" => $_POST["jurusan"],
                "kelas" => $_POST["kelas"],
                "tempat_lahir" => $_POST["tempat_lahir"],
                "tanggal_lahir" => $_POST["tanggal_lahir"],
                "jenis_kelamin" => $_POST["jenis_kelamin"],
                "alamat" => $_POST["alamat"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Data siswa berhasil ditambahkan."];
        }

        header("Location: /siswa");
        exit;
    }

    public function edit($id)
    {
        $data["title"] = "Edit Siswa";
        $data["siswa"] = $this->model("SiswaModel")->getSiswaById($id);
        $data["kelas"] = $this->model("KelasModel")->getAllKelas();

        $this->view("siswa/edit", $data);
    }

    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("SiswaModel")->updateSiswa($id, [
                "id_kelas" => $_POST["id_kelas"],
                "nama" => $_POST["nama"],
                "nisn" => $_POST["nisn"],
                "jurusan" => $_POST["jurusan"],
                "kelas" => $_POST["kelas"],
                "tempat_lahir" => $_POST["tempat_lahir"],
                "tanggal_lahir" => $_POST["tanggal_lahir"],
                "jenis_kelamin" => $_POST["jenis_kelamin"],
                "alamat" => $_POST["alamat"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Data siswa berhasil diperbarui."];
        }

        header("Location: /siswa");
        exit;
    }

    public function delete($id)
    {
        $this->model("SiswaModel")->deleteSiswa($id);

        $_SESSION["flash"] = ["type" => "success", "message" => "Data siswa berhasil dihapus."];

        header("Location: /siswa");
        exit;
    }
}