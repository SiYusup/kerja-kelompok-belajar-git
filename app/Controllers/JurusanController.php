<?php

namespace App\Controllers;

use App\Core\Controller;

class JurusanController extends Controller
{
    public function index()
    {
        $data["title"] = "Daftar Jurusan";

        $keyword = isset($_GET["search"]) ? trim($_GET["search"]) : '';
        $perHalaman = 10;
        $halamanAktif = isset($_GET["page"]) && (int) $_GET["page"] > 0 ? (int) $_GET["page"] : 1;
        $offset = ($halamanAktif - 1) * $perHalaman;

        $model = $this->model("JurusanModel");
        $data["jurusan"] = $model->searchJurusan($keyword, $perHalaman, $offset);
        $data["keyword"] = $keyword;
        $data["perHalaman"] = $perHalaman;
        $data["halamanAktif"] = $halamanAktif;
        $data["totalData"] = $model->countJurusan($keyword);
        $data["totalHalaman"] = (int) ceil($data["totalData"] / $perHalaman);

        $this->view("jurusan/index", $data);
    }

    public function tambah()
    {
        $data["title"] = "Tambah Jurusan";

        $this->view("jurusan/tambah", $data);
    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("JurusanModel")->createJurusan([
                "jurusan" => $_POST["jurusan"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Jurusan berhasil ditambahkan."];
        }

        header("Location: /jurusan");
        exit;
    }

    public function edit($id)
    {
        $data["title"] = "Edit Jurusan";
        $data["jurusan"] = $this->model("JurusanModel")->getJurusanById($id);

        $this->view("jurusan/edit", $data);
    }

    public function update($id)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model("JurusanModel")->updateJurusan($id, [
                "jurusan" => $_POST["jurusan"]
            ]);

            $_SESSION["flash"] = ["type" => "success", "message" => "Jurusan berhasil diperbarui."];
        }

        header("Location: /jurusan");
        exit;
    }

    public function delete($id)
    {
        $this->model("JurusanModel")->deleteJurusan($id);

        $_SESSION["flash"] = ["type" => "success", "message" => "Jurusan berhasil dihapus."];

        header("Location: /jurusan");
        exit;
    }
}
