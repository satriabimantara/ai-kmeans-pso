<?php
class Pengujian extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data = array();
        $this->data['title_web'] = "Pengujian Metode";
        $this->data['nav_list'] = $this->model("Navbar_list_model")->page_pengujian();
        // Retrieve data
        $this->data['dataset'] = $this->model("Data_model")->getAllData("reviews");
        $this->data['amount_data'] = $this->model("Data_model")->getAmountData("reviews");
        $this->data['columns'] = $this->model("Metode_model")->initializeColumns();
        // Variabel untuk perhitungan Elbow
        $this->data['sse_elbow'] = array();
        $this->data['selisih_sse'] = array();
    }
    public function index()
    {
        $this->data['title_web'] = "Pengujian";
        $this->view("templates/header", $this->data);
        $this->view("pengujian/index");
        $this->view("modals/pengujian_modals", $this->data);
        $this->view("templates/footer");
    }
    public function skenario_pengujian()
    {
        if (isset($_POST)) {
            $k_optimal = $_POST['jumlahKOptimal'];
            $jumlah_pengujian = $_POST['jumlah_pengujian'];
            $this->data['sumbu_x'] = array();
            $this->data['kualitas_kluster_kmeans'] = array();
            $this->data['kualitas_kluster_kmeanspso'] = array();
            for ($i = 0; $i < $jumlah_pengujian; $i++) {
                array_push($this->data['sumbu_x'], $i);
                //metode K-Means
                list($this->data['data_with_cluster'], $this->data['centroids'], $this->data['sse_elbow']) =  $this->model("Metode_model")->KMeans($this->data['dataset'], $k_optimal);
                list($this->data['new_dataset'], $this->data['kualitas_kluster']) = $this->model("Metode_model")->silhouetteCoeficient($this->data['data_with_cluster'], $k_optimal);
                array_push($this->data['kualitas_kluster_kmeans'], $this->data['kualitas_kluster']);

                //metode K-Means PSO
                $centroids = $this->model("Metode_model")->optimasiCentroidPSO($this->data['dataset'], $k_optimal, $_POST);
                list($this->data['data_with_cluster'], $this->data['centroids'], $this->data['sse_elbow']) =  $this->model("Metode_model")->KMeans($this->data['dataset'], $k_optimal, $centroids);
                list($this->data['new_dataset'], $this->data['kualitas_kluster']) = $this->model("Metode_model")->silhouetteCoeficient($this->data['data_with_cluster'], $k_optimal);
                array_push($this->data['kualitas_kluster_kmeanspso'], $this->data['kualitas_kluster']);
            }
            //tampilkan grafik
            $this->data['title_web'] = "Pengujian";
            $this->view("templates/header", $this->data);
            $this->view("pengujian/skenario_pengujian", $this->data);
            $this->view("modals/pengujian_modals", $this->data);
            $this->view("templates/footer");
        }
    }
}
