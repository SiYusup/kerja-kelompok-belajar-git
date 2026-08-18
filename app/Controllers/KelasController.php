<?php

namespace App\Controllers;

use App\Core\Controller;

class KelasController extends Controller
{
    public function index()
    {
        $data["title"] = "Daftar Kelas";

        $keyword = isset($_GET["search"]) ? trim($_GET["search"]) : '';
        $perHalaman = 10;
        $halamanAktif = isset($_GET["page"]) && (int) $_GET["page"] > 0 ? (int) $_GET["page"] : 1;
        $offset = ($halamanAktif - 1) * $perHalaman;

        $model = $this->model("KelasModel");
        $data["kelas"] = $model->searchKelas($keyword, $perHalaman, $offset);
        $data["keyword"] = $keyword;
        $data["perHalaman"] = $perHalaman;
        $data["halamanAktif"] = $halamanAktif;
        $data["totalData"] = $model->countKelas($keyword);
        $data["totalHalaman"] = (int) ceil($data["totalData"] / $perHalaman);

        $this->view("kelas/index", $data);
    }

    public function tambah()
    {
        $data["title"] = "Tambah Kelas";

        $this->view("kelas/tambah", $data);
    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("KelasModel")->createKelas([
                "kelas" => $_POST["kelas"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Kelas berhasil ditambahkan."];
        }

        header("Location: /kelas");
        exit;
    }

    public function edit($id)
    {
        $data["title"] = "Edit Kelas";
        $data["kelas"] = $this->model("KelasModel")->getKelasById($id);

        $this->view("kelas/edit", $data);
    }

    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("KelasModel")->updateKelas($id, [
                "kelas" => $_POST["kelas"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Kelas berhasil diperbarui."];
        }

        header("Location: /kelas");
        exit;
    }

    public function delete($id)
    {
        $this->model("KelasModel")->deleteKelas($id);

        $_SESSION["flash"] = ["type" => "success", "message" => "Kelas berhasil dihapus."];

        header("Location: /kelas");
        exit;
    }
}
