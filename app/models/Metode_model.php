<?php
class Metode_model
{
    private $k;
    private $table_columns;
    private $max_loop;
    private $centroids;

    public function __construct()
    {
        $this->table_columns = $this->initializeColumns();
        $this->max_loop = 1000;
        $this->centroids = array();
    }
    public function initializeColumns()
    {
        $columns = array();
        // array_push($columns, "gender");
        // array_push($columns, "age");
        // array_push($columns, "annual_income");
        // array_push($columns, "spending_score");
        for ($i = 0; $i < 10; $i++) {
            $string = "category_" . ($i + 1);
            array_push($columns, $string);
        }
        // array_push($columns, "pc_1");
        // array_push($columns, "pc_2");
        return $columns;
    }
    public function initializeCentroids($numberK, $data)
    {
        $centroids = array();
        for ($i = 0; $i < $numberK; $i++) {
            array_push($centroids, $data[rand(0, count($data) - 1)]);
        }
        return $centroids;
    }
    public function setCentroids($pusat_kluster_optimal)
    {
        $centroids = array();
        foreach ($pusat_kluster_optimal as $indeks_kluster => $centroid) {
            array_push($centroids, $centroid);
        }
        return $centroids;
    }
    /*
    ====================
    KMEANS
    ===================
    */
    public function euclideanDistance($data, $centroid)
    {
        $centroid_distance = array();
        foreach ($data as $indeks_data  => $sample) {
            $sum = 0;
            foreach ($this->table_columns as $column) {
                $sum += pow(($sample[$column] - $centroid[$column]), 2);
            }
            $dist = sqrt($sum);
            array_push($centroid_distance, $dist);
        }
        return $centroid_distance;
    }
    public function findClusterData($data, $centroids_distance)
    {
        // indeks kluster dimulai dari 1,2,...,k, sehingga indeks_centroid+1
        foreach ($data as $indeks_data  => $sample) {
            $min = 999999;
            foreach ($centroids_distance as $indeks_centroid  => $centroid_distance) {
                if ($centroid_distance[$indeks_data] < $min) {
                    $min = $centroid_distance[$indeks_data];
                    $data[$indeks_data]["kluster"] = $indeks_centroid + 1;
                }
            }
        }
        return $data;
    }
    public function findNewCentroid($data_with_same_kluster)
    {
        $n_data = count($data_with_same_kluster);
        $new_centroid = array();
        if ($n_data == 0) {
            $n_data = 0.00001;
        }
        // inisialisasi array dengan nol sesuai kolom yang ada
        foreach ($this->table_columns as $column) {
            $new_centroid[$column] = 0;
        }
        foreach ($data_with_same_kluster as $sample) {
            foreach ($this->table_columns as $column) {
                $new_centroid[$column] += $sample[$column];
            }
        }
        foreach ($this->table_columns as $column) {
            $new_centroid[$column] /= $n_data;
        }
        return $new_centroid;
    }
    public function checkConvergentCentroids($centroids, $new_centroids)
    {
        $selisih_centroid = array();
        $epsilon = 0.00000001;
        for ($indeks_kluster = 0; $indeks_kluster < $this->k; $indeks_kluster++) {
            $ifSame = 1;
            $selisih_centroid[$indeks_kluster] = array();
            foreach ($this->table_columns as $column) {
                if (round($centroids[$indeks_kluster][$column], 20) != round($new_centroids[$indeks_kluster][$column], 20)) {
                    $ifSame = 0;
                }
                array_push($selisih_centroid[$indeks_kluster], abs($centroids[$indeks_kluster][$column] - $new_centroids[$indeks_kluster][$column]));
            }
        }
        return array($ifSame, $selisih_centroid);
    }
    public function calculateSSE($data_with_same_kluster, $centroids)
    {
        $centroids_distance = array();
        $sum_of_sse = 0;
        for ($indeks_kluster = 0; $indeks_kluster < $this->k; $indeks_kluster++) {
            array_push($centroids_distance, $this->euclideanDistance($data_with_same_kluster[$indeks_kluster], $centroids[$indeks_kluster]));
            // kuadratkan hasil perhitungan
            foreach ($centroids_distance[$indeks_kluster] as $indeks => $value) {
                $centroids_distance[$indeks_kluster][$indeks] = pow($centroids_distance[$indeks_kluster][$indeks], 2);
                $sum_of_sse += $centroids_distance[$indeks_kluster][$indeks];
            }
        }
        return $sum_of_sse;
    }
    public function KMeans($data, $kluster_optimal, $pusat_kluster_awal = [])
    {
        $this->k = $kluster_optimal;
        $all_sse = array();
        if (empty($pusat_kluster_awal)) {
            $this->centroids = $this->initializeCentroids($this->k, $data);
        } else {
            $this->centroids = $this->setCentroids($pusat_kluster_awal);
        }
        $centroids = $this->centroids;
        for ($indeks_looping = 0; $indeks_looping < $this->max_loop; $indeks_looping++) {
            /*
            Menghitung jarak data ke-i dengan semua centroid
            */
            $centroids_distance = array();
            for ($i = 0; $i < $this->k; $i++) {
                array_push($centroids_distance, $this->euclideanDistance($data, $centroids[$i]));
            }

            /*
            Cari jarak minimum data ke-i dengan k-kluster. Kluster dengan jarak
            minimum akan menjadi kluster bagi data ke-i, diperoleh data baru yang sudah diberi label kluster
            */
            $new_data = $this->findClusterData($data, $centroids_distance);

            /*
            Perbarui nilai centroids
            */
            $new_centroids = array();
            $data_with_same_cluster = array();
            for ($indeks_kluster = 0; $indeks_kluster < $this->k; $indeks_kluster++) {
                $data_with_same_cluster[$indeks_kluster] = array();
                foreach ($new_data as $indeks_data  => $sample) {
                    if ($sample['kluster'] == $indeks_kluster + 1) {
                        array_push($data_with_same_cluster[$indeks_kluster], $sample);
                    }
                }
                array_push($new_centroids, $this->findNewCentroid($data_with_same_cluster[$indeks_kluster]));
            }
            /*
            Cek kekonvergenan centroid lama dan new_centroid. Jika centroid_lama = new_centroid, break. Jika tidak lanjut iterasi berikutnya
            */
            list($ifKonvergen, $selisih_centroid) = $this->checkConvergentCentroids($centroids, $new_centroids);
            if ($ifKonvergen) {
                $sse = $this->calculateSSE($data_with_same_cluster, $centroids);
                array_push($all_sse, $sse);
                break;
            } else {
                $centroids = $new_centroids;
            }
        }
        /*
        return value berupa:
        - data terindeks kluster
        - centroid optimal
        - nilai sse di setiap K
        */
        return array($new_data, $centroids, $all_sse);
    }
    /*
    ====================
    END KMEANS
    ===================
    */

    /*
    ====================
    ELBOW
    ===================
    */
    public function calculateElbow($data, $postData)
    {
        $SSE_allK = array();
        $selisih_SSE = array();
        $minK = $postData['inputJumlahMinimumKluster'];
        $maxK = $postData['inputJumlahMaksimumKluster'];
        $array_k = array();
        for ($indeks_kluster = $minK; $indeks_kluster <= $maxK; $indeks_kluster++) {
            array_push($array_k, $indeks_kluster);
            $SSE_allK[$indeks_kluster] = array();
            $selisih_SSE[$indeks_kluster] = array();
            list($new_data, $centroid_optimal, $all_sse) = $this->KMeans($data, $indeks_kluster);
            array_push($SSE_allK[$indeks_kluster], $all_sse[0]);
            // Hitung selisih
            if ($indeks_kluster == $minK) {
                array_push($selisih_SSE[$indeks_kluster], 0);
            } else {
                $now = $SSE_allK[$indeks_kluster][0];
                $before = $SSE_allK[$indeks_kluster - 1][0];
                array_push($selisih_SSE[$indeks_kluster], abs($now - $before));
            }
        }
        return array($SSE_allK, $selisih_SSE, $array_k);
    }
    /*
    ====================
    END ELBOW
    ===================
    */


    /*
    ============================
    OPTIMASI CENTROID DENGAN PSO
    ============================
    */
    public function setBoundaries($data, &$params)
    {

        $params['batas_atas'] = array();
        $params['batas_bawah'] = array();
        $params['v_max'] = array();
        $params['v_min'] = array();

        foreach ($this->table_columns as $indeks_kolom  => $column) {
            $params['batas_atas'][$column] = 0;
            $params['batas_bawah'][$column] = 9999;
            foreach ($data as $indeks_data  => $sample) {
                if ($sample[$column] > $params['batas_atas'][$column]) {
                    $params['batas_atas'][$column] = $sample[$column];
                }
                if ($sample[$column] < $params['batas_bawah'][$column]) {
                    $params['batas_bawah'][$column] = $sample[$column];
                }
            }
            $params['v_max'][$column] = $params['batas_atas'][$column];
            $params['v_min'][$column] = -1 * $params['v_max'][$column];
        }
    }
    public function initializeParameters($postData)
    {
        // parameters
        $params = array();
        $params['jumlah_partikel'] = $postData['jumlah_partikel'];
        $params['jumlah_iterasi'] = $postData['jumlah_iterasi'];
        $params['c1'] = $postData['parameter_c1'];
        $params['c2'] = $postData['parameter_c2'];
        // $params['jumlah_partikel'] = 500;
        // $params['jumlah_iterasi'] = 100;
        // $params['c1'] = 2.1;
        // $params['c2'] = 2.5;
        $params['r1'] = mt_rand() / mt_getrandmax();
        $params['r2'] = mt_rand() / mt_getrandmax();
        $params['phi'] = $params['c1'] + $params['c2'];
        $params['chai'] = 2 / abs(2 - $params['phi'] - sqrt(pow($params['phi'], 2) - (4 * $params['phi'])));
        return $params;
    }
    public function initializeParticles($data, $number_of_k, $params)
    {
        $particles = array();
        $GBest = array();
        $GBest['positions'] = array();
        $GBest['fitness'] = 9999; //karena fungsi fitnessnya minimasi SSE
        for ($indeks_partikel = 0; $indeks_partikel < $params['jumlah_partikel']; $indeks_partikel++) {
            $particles[$indeks_partikel] = array();
            $particles[$indeks_partikel]['positions'] = array();
            $particles[$indeks_partikel]['velocities'] = array();
            $particles[$indeks_partikel]['PBest'] = array();
            $particles[$indeks_partikel]['fitness'] = 99999;
            //inisialisasi particle dengan memilih titik data sample secara random sejumlah k kluster
            for ($indeks_kluster = 0; $indeks_kluster < $number_of_k; $indeks_kluster++) {
                $velocity = array();
                foreach ($this->table_columns as $indeks_atribut  => $column) {
                    $velocity[$column] = rand($params['v_min'][$column], $params['v_max'][$column]);
                }
                array_push($particles[$indeks_partikel]['positions'], $data[rand(0, count($data) - 1)]);
                array_push($particles[$indeks_partikel]['velocities'], $velocity);
            }
        }
        return array($particles, $GBest);
    }
    public function calculateFitnessFunction($data, $particles)
    {
        /*
        FITNESS FUNCTION
        f(x) = SSE --> minimumkan nilai SSE
        */
        foreach ($particles as $indeks_partikel  => $particle) {
            $centroid_distance = array();
            // Cari jarak euclidean setiap data dengan setiap centroid
            foreach ($particle['positions'] as $indeks_centroid  => $centroid) {
                array_push($centroid_distance, $this->euclideanDistance($data, $centroid));
            }
            $new_data = $this->findClusterData($data, $centroid_distance);

            //Kelompokkan data sesuai klusternya
            $data_with_same_cluster = array();
            for ($indeks_kluster = 0; $indeks_kluster < $this->k; $indeks_kluster++) {
                $data_with_same_cluster[$indeks_kluster] = array();
                foreach ($new_data as $indeks_data  => $sample) {
                    if ($sample['kluster'] == $indeks_kluster + 1) {
                        array_push($data_with_same_cluster[$indeks_kluster], $sample);
                    }
                }
            }
            // Hitung nilai SSE partikel
            $centroids = $particle['positions'];
            $particles[$indeks_partikel]['fitness'] = $this->calculateSSE($data_with_same_cluster, $centroids);
        }
        return $particles;
    }
    public function updatePBestGBest($particles_sebelum, $new_particles, $GBest)
    {
        $min = 99999;
        for ($indeks_partikel = 0; $indeks_partikel < count($particles_sebelum); $indeks_partikel++) {
            if ($new_particles[$indeks_partikel]['fitness'] < $particles_sebelum[$indeks_partikel]['fitness']) {
                //perbarui nilai PBest
                $particles_sebelum[$indeks_partikel]['PBest'] = $new_particles[$indeks_partikel]['positions'];
                $particles_sebelum[$indeks_partikel]['fitness'] = $new_particles[$indeks_partikel]['fitness'];
            }
        }
        $new_particles = $particles_sebelum;
        //Perbarui niali GBest
        foreach ($new_particles as $indeks_particle  => $particle) {
            if ($particle['fitness'] < $min) {
                $min = $particle['fitness'];
                $GBest['positions'] = $particle['positions'];
                $GBest['fitness'] = $particle['fitness'];
            }
        }
        return array($new_particles, $GBest);
    }
    public function updateVelocityAndPositions($particles, $GBest, $params)
    {
        $c1 = $params['c1'];
        $c2 = $params['c2'];
        $r1 = $params['r1'];
        $r2 = $params['r2'];
        $chai = $params['chai'];
        $v_max = $params['v_max'];
        $v_min = $params['v_min'];
        foreach ($particles as $indeks_partikel  => $particle) {
            foreach ($particle['velocities'] as $indeks_kluster  => $velocity) {
                foreach ($this->table_columns as $indeks_kolom  => $column) {
                    $v = $velocity[$column];
                    $pbest = $particle['PBest'][$indeks_kluster][$column];
                    $position = $particle['positions'][$indeks_kluster][$column];
                    $gbest = $GBest['positions'][$indeks_kluster][$column];
                    $v = $chai * (floatval($v) + ($c1 * $r1 * (floatval($pbest) - floatval($position))) + ($c2 * $r2 * (floatval($gbest) - floatval($position))));
                    if ($v >= $v_max[$column]) {
                        $particles[$indeks_partikel]['velocities'][$indeks_kluster][$column] = $v_max;
                    } elseif ($v <= $v_min[$column]) {
                        $particles[$indeks_partikel]['velocities'][$indeks_kluster][$column] = $v_min;
                    } else {
                        $particles[$indeks_partikel]['velocities'][$indeks_kluster][$column] = $v;
                    }
                    // Perbarui posisi
                    $new_p = floatval($particles[$indeks_partikel]['positions'][$indeks_kluster][$column]) + floatval($particles[$indeks_partikel]['velocities'][$indeks_kluster][$column]);

                    if ($new_p >= floatval($params['batas_atas'][$column])) {
                        $particles[$indeks_partikel]['positions'][$indeks_kluster][$column] = floatval($params['batas_atas'][$column]);
                    } elseif ($new_p <= floatval($params['batas_bawah'][$column])) {
                        $particles[$indeks_partikel]['positions'][$indeks_kluster][$column] = floatval($params['batas_bawah'][$column]);
                    } else {
                        $particles[$indeks_partikel]['positions'][$indeks_kluster][$column] = $new_p;
                    }
                }
            }
        }
        return $particles;
    }

    public function checkConvergentPSOSolution($particles, $new_particles)
    {
        $selisih = 0;
        foreach ($particles as $indeks_particle  => $particle) {
            foreach ($particle['positions'] as $indeks_kluster  => $position) {
                foreach ($this->table_columns as $indeks_kolom  => $column) {
                    $selisih += abs($new_particles[$indeks_particle]['positions'][$indeks_kluster][$column] - $position[$column]);
                }
            }
        }
        return $selisih;
    }

    public function optimasiCentroidPSO($data, $number_of_k, $postData)
    {
        $this->k = $number_of_k;
        // 1. inisialisasi parameter PSO
        $params = $this->initializeParameters($postData);
        // tentukan batasan untuk setiap atribut dari data dengan mencari nilai max dan min di setiap atributnya (Vmax, Vmin, Ba, Bb)
        $this->setBoundaries($data, $params);

        // 2. inisialisasi partikel
        list($particles, $GBest) = $this->initializeParticles($data, $number_of_k, $params);

        for ($indeks_iterasi = 0; $indeks_iterasi < $params['jumlah_iterasi']; $indeks_iterasi++) {
            // 3. Hitung nilai fitness partikel
            $new_particles = $this->calculateFitnessFunction($data, $particles);
            //4. Perbarui nilai PBest dan GBest
            list($new_particles, $GBest) = $this->updatePBestGBest($particles, $new_particles, $GBest);
            //5. Perbarui kecepatan dan posisi partikel
            $new_particles = $this->updateVelocityAndPositions($new_particles, $GBest, $params);
            //6. Cek kekonvergenan solusi yang dihasilkan
            $selisih = $this->checkConvergentPSOSolution($particles, $new_particles);

            // $this->cetakPartikel($particles, $indeks_iterasi);
            // $this->cetakGBest($GBest);
            // echo 'SELISIH = ' . $selisih . "<br><br>";
            if ($selisih > 0) {
                $particles = $new_particles;
            } else {
                break;
            }
        }
        return $GBest['positions'];
    }

    public function cetakGBest($GBest)
    {

        echo "Nilai Fitness Terbaik GBest = " . $GBest['fitness'];
        echo "<br>";
    }

    public function cetakPartikel($particles, $iterasi)
    {
        echo "ITERASI KE-" . $iterasi . "<br>";
        foreach ($particles as $indeks_partikel  => $particle) {
            echo "Partikel ke-" . $indeks_partikel . ", Fitness = " . $particle['fitness'];
            echo "<br>";
        }
        echo "<br>";
    }
    public function cetakEnter($n)
    {
        for ($i = 0; $i < $n; $i++) {
            echo "<br>";
        }
    }

    /*
    ====================
    SILHOUETTE COEFICIENT
    ===================
    */
    public function euclideTwoPoints($point_1, $point_2)
    {
        $sum = 0;
        foreach ($this->table_columns as $column) {

            $sum += pow(($point_1[$column] - $point_2[$column]), 2);
        }
        $distance = sqrt($sum);
        return $distance;
    }
    public function hitungAo($datum, $data_with_same_cluster)
    {
        $n_data = count($data_with_same_cluster);

        $sum_distance = 0;
        foreach ($data_with_same_cluster as $point) {
            //hitung jarak datum dengan seluruh point dalam kluster yang sama
            $sum_distance += $this->euclideTwoPoints($point, $datum);
        }
        if ($n_data == 0) {
            return 0;
        } else {
            return $sum_distance / ($n_data - 1);
        }
    }
    public function hitungBo($datum, $data_per_cluster, $kluster)
    {
        $all_bo = array();
        //hitung jarak datum dengan semua data di kluster lain
        unset($data_per_cluster[$kluster]);
        foreach ($data_per_cluster as $indeks_kluster  => $data_with_same_cluster) {
            $n_data = count($data_with_same_cluster);
            if ($n_data == 0) {
                $n_data = 0.000001;
            }
            $sum_distance = 0;
            foreach ($data_with_same_cluster as $point) {
                //hitung jarak datum dengan seluruh point dalam kluster yang sama
                $sum_distance += $this->euclideTwoPoints($point, $datum);
            }
            array_push($all_bo, ($sum_distance / $n_data));
        }

        //nilai b(o) adalah nilai minimum dari all_bo
        return min($all_bo);
    }
    public function silhouetteCoeficient($source_data, $k)
    {
        //inisialisasi wadah
        $data_per_cluster = array();
        for ($i = 0; $i < $k; $i++) {
            $data_per_cluster[$i] = array();
        }
        //kelompokan data untuk setiap cluster
        foreach ($source_data as $indeks_data => $datum) {
            $kluster = $datum['kluster'];
            array_push($data_per_cluster[$kluster - 1], $datum);
        }
        //hitung nilai a(o), b(o), dan s(o) untuk setiap data
        $sum = 0;
        foreach ($source_data as $indeks_data => $datum) {
            $kluster = $datum['kluster'] - 1;
            $source_data[$indeks_data]['a(o)'] = $this->hitungAo($datum, $data_per_cluster[$kluster]);
            $source_data[$indeks_data]['b(o)'] = $this->hitungBo($datum, $data_per_cluster, $kluster);
            $source_data[$indeks_data]['s(o)'] = ($source_data[$indeks_data]['b(o)'] - $source_data[$indeks_data]['a(o)']) / max($source_data[$indeks_data]['a(o)'], $source_data[$indeks_data]['b(o)']);
            $sum += $source_data[$indeks_data]['s(o)'];
        }
        return array($source_data, $sum / count($source_data));
    }

    /*
    ====================
    END SILHOUETTE COEFICIENT
    ===================
    */
}
