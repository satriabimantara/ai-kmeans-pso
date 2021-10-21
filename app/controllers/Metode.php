<?php
class Metode extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data = array();
        $this->data['title_web'] = "Metode Penelitian";
        $this->data['nav_list'] = $this->model("Navbar_list_model")->page_metode();
        // Retrieve data
        $this->data['dataset'] = $this->model("Data_model")->getAllData("reviews");
        $this->data['amount_data'] = $this->model("Data_model")->getAmountData("reviews");
        $this->data['columns'] = $this->model("Metode_model")->initializeColumns();

        // Variabel untuk perhitungan Elbow
        $this->data['sse_elbow'] = array();
        $this->data['selisih_sse'] = array();

        $this->data['hasilPengujianKMeansPSO'] = array();
    }
    public function index()
    {

        $this->view("templates/header", $this->data);
        $this->view("metode/index");
        $this->view("modals/metode_modals", $this->data);
        $this->view("templates/footer");
    }

    public function elbow()
    {
        if (isset($_POST)) {
            list($this->data['sse_elbow'], $this->data['selisih_sse'], $this->data['array_k']) = $this->model("Metode_model")->calculateElbow($this->data['dataset'], $_POST);
        }
        $this->data['title_web'] = "Kluster Optimal";
        $this->view("templates/header", $this->data);
        $this->view("metode/kluster_optimal", $this->data);
        $this->view("modals/metode_modals", $this->data);
        $this->view("templates/footer");
    }

    public function pengujian()
    {
        if (isset($_POST)) {
            $k_optimal = $_POST['jumlahKOptimal'];
            if ($_POST['typeMetode'] == "KMeans") {
                list($this->data['data_with_cluster'], $this->data['centroids'], $this->data['sse_elbow']) =  $this->model("Metode_model")->KMeans($this->data['dataset'], $k_optimal);
                list($this->data['new_dataset'], $this->data['kualitas_kluster']) = $this->model("Metode_model")->silhouetteCoeficient($this->data['data_with_cluster'], $k_optimal);

                $this->data['title_web'] = "Metode K-Means";
                $this->view("templates/header", $this->data);
                $this->view("metode/kmeans", $this->data);
                $this->view("modals/metode_modals", $this->data);
                $this->view("templates/footer");
            } elseif ($_POST['typeMetode'] == "KMeansPSO") {
                $jumlah_pengujian = $_POST['jumlah_pengujian'];
                $hasilPengujianKMeansPSO = array();
                for ($i = 0; $i < $jumlah_pengujian; $i++) {
                    $centroids = $this->model("Metode_model")->optimasiCentroidPSO($this->data['dataset'], $k_optimal, $_POST);
                    list($this->data['data_with_cluster'], $this->data['centroids'], $this->data['sse_elbow']) =  $this->model("Metode_model")->KMeans($this->data['dataset'], $k_optimal, $centroids);
                    list($this->data['new_dataset'], $this->data['kualitas_kluster']) = $this->model("Metode_model")->silhouetteCoeficient($this->data['data_with_cluster'], $k_optimal);
                    array_push($hasilPengujianKMeansPSO, $this->data['kualitas_kluster']);
                }
                $this->data['hasilPengujianKMeansPSO'] = $hasilPengujianKMeansPSO;

                $this->data['title_web'] = "Metode K-Means PSO";
                $this->view("templates/header", $this->data);
                $this->view("metode/kmeanspso", $this->data);
                $this->view("modals/metode_modals", $this->data);
                $this->view("templates/footer");
            }
        } else {
            //redirect ke halaman index karena melakukan refresh atau masuk via url
            Flasher::setFlash("Upaya masuk", "berhasil", "digagalkan", "danger");
            header("Location: " . BASEURL . "metode/");
            exit;
        }
    }
    public function testPSO()
    {
        $this->model("PSO_model")->PSO();
    }
}
