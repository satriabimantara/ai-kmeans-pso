<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-lg-6">
            <?php Flasher::flash(); ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="jumbotron">
                <h1 class="display-4">Pengujian Metode</h1>
                <p class="lead">
                    Optimasi K-Means Clustering Menggunakan Algoritma Particle Swarm Optimization Untuk Pengelompokkan Data Ulasan Di Situs Tripadvisor
                </p>
                <hr class="my-4">
                <p>
                    1. K-Means<br>
                    2. K-Means-PSO
                </p>
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseAlurPengujian" role="button" aria-expanded="false" aria-controls="collapseAlurPengujian">
                    Lihat Alur Metode
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="collapse" id="collapseAlurPengujian">
                <div class="card">
                    <h5 class="card-header">Petunjuk Alur Pengujian</h5>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">1. Tentukan nilai k optimal</li>
                            <li class="list-group-item">2. Masuk ke menu uji metode</li>
                            <li class="list-group-item">3. Masukkan nilai k optimal</li>
                            <li class="list-group-item">4. Masukkan nilai-nilai hyperparameter dari PSO</li>
                            <li class="list-group-item">5. Masukkan jumlah pengujian yang ingin dilakukan</li>
                            <li class="list-group-item">6. Proses pengujian akan memakan waktu beberapa menit</li>
                            <li class="list-group-item">7. Hasil pengujian akan membandingkan kinerja metode K-Means dan K-Means PSO</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>