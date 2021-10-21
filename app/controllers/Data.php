<?php
class Data extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data = array();
        $this->data['title_web'] = "Data";
        $this->data['nav_list'] = $this->model("Navbar_list_model")->page_data();
    }
    public function index()
    {
        $this->view("templates/header", $this->data);
        $this->view("data/index");
        $this->view("templates/footer");
    }
    public function data_original()
    {
        $this->data['daftar_data'] = $this->model("Data_model")->getAllData("reviews");
        $this->view("templates/header", $this->data);
        $this->view("data/data-original", $this->data);
        $this->view("templates/footer");
    }
    public function data_normalisasi()
    {
        $this->data['daftar_data'] = $this->model("Data_model")->getAllData("reviews_transform");
        $this->view("templates/header", $this->data);
        $this->view("data/data-normalisasi", $this->data);
        $this->view("templates/footer");
    }
    public function hapus_data_original($id_reviews)
    {
        echo $id_reviews;
    }
}
