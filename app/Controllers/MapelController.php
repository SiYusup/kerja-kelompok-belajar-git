<?php

namespace App\Controllers;

use App\Core\Controller;

class MapelController extends Controller
{
    public function index()
    {
        $data["title"] = "Daftar Mapel";
        $data["mapel"] = $this->model("MapelModel")->getAllMapel();

        $this->view("mapel/index", $data);
    }
}
