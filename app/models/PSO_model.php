<?php
class PSO_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /*
    ====================
    PSO
    ===================
    */
    public function initializeParameters()
    {
        // parameters
        $params = array();
        $params['jumlah_partikel'] = 5;
        $params['max_iter'] = 100;
        $params['c1'] = 2.3;
        $params['c2'] = 2.0;
        $params['r1'] = mt_rand() / mt_getrandmax();
        $params['r2'] = mt_rand() / mt_getrandmax();
        $params['dimensi'] = 4;
        $params['v_max'] = 2.6;
        $params['v_min'] = -1 * $params['v_max'];
        $params['batas_atas'] = 10;
        $params['batas_bawah'] = 0;
        $params['phi'] = $params['c1'] + $params['c2'];
        $params['chai'] = 2 / abs(2 - $params['phi'] - sqrt(pow($params['phi'], 2) - (4 * $params['phi'])));
        return $params;
    }
    public function initializeParticles($params)
    {
        $batas_atas = $params['batas_atas'];
        $batas_bawah = $params['batas_bawah'];
        $particles = array();
        $GBest = array();
        // 1 partikel punya posisi dan kecepatan
        for ($indeks_partikel = 0; $indeks_partikel < $params['jumlah_partikel']; $indeks_partikel++) {
            $particles[$indeks_partikel] = array();
            $GBest[$indeks_partikel] = array();
            $particles[$indeks_partikel]['positions'] = array();
            $particles[$indeks_partikel]['velocities'] = array();
            $particles[$indeks_partikel]['fitness'] = 0;
            $particles[$indeks_partikel]['PBest'] = array();
            $GBest['positions'] = array();
            $GBest['fitness'] = 0;
            //inisialisasi posisi dan kecepatan
            for ($indeks_posisi = 0; $indeks_posisi < $params['dimensi']; $indeks_posisi++) {
                array_push($particles[$indeks_partikel]['positions'], rand($batas_bawah, $batas_atas));
                array_push($particles[$indeks_partikel]['velocities'], rand($params['v_min'], $params['v_max']));
            }
        }
        return array($particles, $GBest);
    }

    public function calculateFitnessFunction($particles)
    {
        /*
        FITNESS FUNCTION
        f(x) = a^3 + b^2 -c + 2d --> maximum
        */
        foreach ($particles as $indeks_partikel  => $particle) {
            $fx = pow($particle['positions'][0], 3) + pow($particle['positions'][1], 2) - $particle['positions'][2] + (2 * $particle['positions'][3]);
            $particles[$indeks_partikel]['fitness'] = $fx;
        }
        return $particles;
    }
    public function updatePBestGBest($particles_sebelum, $new_particles, $GBest)
    {
        $n_partikel = count($particles_sebelum);
        $max = 0;
        for ($indeks_partikel = 0; $indeks_partikel < $n_partikel; $indeks_partikel++) {
            // Bandingkan nilai fitness partikel before dan after
            if ($new_particles[$indeks_partikel]['fitness'] > $particles_sebelum[$indeks_partikel]['fitness']) {
                // Perbarui nilai PBest
                $particles_sebelum[$indeks_partikel]['PBest'] = $new_particles[$indeks_partikel]['positions'];
                $particles_sebelum[$indeks_partikel]['fitness'] = $new_particles[$indeks_partikel]['fitness'];
            }
            // echo "PARTIKEL KE-" . $indeks_partikel . "<br>";
            // echo "F(before) = " . $particles_sebelum[$indeks_partikel]['fitness'] . ", F(after) = " . $new_particles[$indeks_partikel]['fitness'];
            // echo "<br>";
        }
        $new_particles = $particles_sebelum;
        foreach ($new_particles as $indeks_particle  => $particle) {
            if ($particle['fitness'] > $max) {
                $max = $particle['fitness'];
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
            // Perbarui kecepatan dan posisi
            foreach ($particle['velocities'] as $idx => $v) {
                $pbest = $particle['PBest'][$idx];
                $position = $particle['positions'][$idx];
                $gbest = $GBest['positions'][$idx];
                $v = $chai * ($v + ($c1 * $r1 * ($pbest - $position)) + ($c2 * $r2 * ($gbest - $position)));
                if ($v >= $v_max) {
                    $particles[$indeks_partikel]['velocities'][$idx] = $v_max;
                } elseif ($v <= $v_min) {
                    $particles[$indeks_partikel]['velocities'][$idx] = $v_min;
                } else {
                    $particles[$indeks_partikel]['velocities'][$idx] = $v;
                }
                // Perbarui posisi
                $new_p = $particles[$indeks_partikel]['positions'][$idx] + $particles[$indeks_partikel]['velocities'][$idx];
                if ($new_p >= $params['batas_atas']) {
                    $particles[$indeks_partikel]['positions'][$idx] = $params['batas_atas'];
                } elseif ($new_p <= $params['batas_bawah']) {
                    $particles[$indeks_partikel]['positions'][$idx] = $params['batas_bawah'];
                } else {
                    $particles[$indeks_partikel]['positions'][$idx] = $new_p;
                }
            }
        }
        return $particles;
    }
    public function checkConvergentSolutionPSO($particles, $new_particles)
    {
        $selisih = 0;
        foreach ($particles as $indeks_particle  => $particle) {
            foreach ($particle['positions'] as $idx  => $p) {
                $selisih += abs($new_particles[$indeks_particle]['positions'][$idx] - $p);
            }
        }
        return $selisih;
    }
    public function PSO()
    {
        $params = $this->initializeParameters();
        list($particles, $GBest) = $this->initializeParticles($params);
        for ($indeks_iterasi = 0; $indeks_iterasi < $params['max_iter']; $indeks_iterasi++) {
            // 2. Hitung nilai fitness
            $new_particles = $this->calculateFitnessFunction($particles);
            // 3. Perbarui nilai PBest dan GBest
            list($new_particles, $GBest) = $this->updatePBestGBest($particles, $new_particles, $GBest);
            //4. Perbarui kecepatan dan posisi
            $new_particles = $this->updateVelocityAndPositions($new_particles, $GBest, $params);

            //5. Cek kekonvergenan solusi (posisi) yang dihasilkan
            $selisih = $this->checkConvergentSolutionPSO($particles, $new_particles);

            $this->cetakPartikel($particles, $indeks_iterasi);
            $this->cetakGBest($GBest);
            echo 'SELISIH = ' . $selisih . "<br><br>";
            $particles = $new_particles;
        }
    }

    /*
    ============================
    OPTIMASI CENTROID DENGAN PSO
    ============================
    */


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
}
