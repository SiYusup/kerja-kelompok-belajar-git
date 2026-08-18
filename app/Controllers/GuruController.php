<?php

namespace App\Controllers;

use App\Core\Controller;

class GuruController extends Controller
{
    public function index()
    {
        $keyword = isset($_GET["search"]) ? trim($_GET["search"]) : "";
        $perHalaman = 9;
        $halamanAktif = isset($_GET["page"]) && is_numeric($_GET["page"]) ? max(1, (int) $_GET["page"]) : 1;
        $offset = ($halamanAktif - 1) * $perHalaman;

        $model = $this->model("GuruModel");
        $totalData = $model->countGuru($keyword);
        $totalHalaman = (int) ceil($totalData / $perHalaman);

        $data["title"] = "Daftar Guru";
        $data["guru"] = $model->getGuruPaginate($perHalaman, $offset, $keyword);
        $data["keyword"] = $keyword;
        $data["halamanAktif"] = $halamanAktif;
        $data["totalHalaman"] = $totalHalaman;
        $data["totalData"] = $totalData;
        $data["perHalaman"] = $perHalaman;

        $this->view("guru/index", $data);
    }

    public function detail($id)
    {
        $data["title"] = "Detail Guru";
        $data["guru"] = $this->model("GuruModel")->getGuruDetail($id);

        $this->view("guru/detail", $data);
    }

    public function tambah()
    {
        $data["title"] = "Tambah Guru";
        $data["mapel"] = $this->model("MapelModel")->getAllMapel();

        $this->view("guru/tambah", $data);
    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("GuruModel")->createGuru([
                "id_mapel" => $_POST["id_mapel"],
                "nama" => $_POST["nama"],
                "nip" => $_POST["nip"],
                "jurusan" => $_POST["jurusan"],
                "tempat_lahir" => $_POST["tempat_lahir"],
                "tanggal_lahir" => $_POST["tanggal_lahir"],
                "jenis_kelamin" => $_POST["jenis_kelamin"],
                "alamat" => $_POST["alamat"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Data guru berhasil ditambahkan."];
        }

        header("Location: /guru");
        exit;
    }

    public function edit($id)
    {
        $data["title"] = "Edit Guru";
        $data["guru"] = $this->model("GuruModel")->getGuruDetail($id);
        $data["mapel"] = $this->model("MapelModel")->getAllMapel();

        $this->view("guru/edit", $data);
    }

    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("GuruModel")->updateGuru($id, [
                "id_mapel" => $_POST["id_mapel"],
                "nama" => $_POST["nama"],
                "nip" => $_POST["nip"],
                "jurusan" => $_POST["jurusan"],
                "tempat_lahir" => $_POST["tempat_lahir"],
                "tanggal_lahir" => $_POST["tanggal_lahir"],
                "jenis_kelamin" => $_POST["jenis_kelamin"],
                "alamat" => $_POST["alamat"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Data guru berhasil diperbarui."];
        }

        header("Location: /guru");
        exit;
    }

    public function delete($id)
    {
        $this->model("GuruModel")->deleteGuru($id);

        $_SESSION["flash"] = ["type" => "success", "message" => "Data guru berhasil dihapus."];

        header("Location: /guru");
        exit;
    }
}
