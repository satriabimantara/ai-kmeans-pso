<?php
$sumbu_x = json_encode($data['sumbu_x']);
$kualitas_kmeans = json_encode($data['kualitas_kluster_kmeans']);
$kualitas_kmeanspso = json_encode($data['kualitas_kluster_kmeanspso']);
?>
<div class="container">
    <h5 class="alert alert-primary" role="alert">
        Tabel Hasil Klusterisasi K-Means dan K-Means PSO
    </h5>
    <div class="row mb-4">
        <div class="col">
            <table class="table table-striped" id="table_perbandingan_pengujian">
                <thead>
                    <tr>
                        <th scope="col">Pengujian ke-</th>
                        <th scope="col">SC K-Means</th>
                        <th scope="col">SC K-Means PSO</th>
                        <th scope="col">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['sumbu_x'] as $value) : ?>
                        <tr>
                            <th scope="row"><?= $value + 1; ?></th>
                            <td><?= $data['kualitas_kluster_kmeans'][$value]; ?></td>
                            <td><?= $data['kualitas_kluster_kmeanspso'][$value]; ?></td>
                            <td>
                                <?php
                                if ($data['kualitas_kluster_kmeans'][$value] > $data['kualitas_kluster_kmeanspso'][$value]) {
                                    echo "K-Means";
                                } else {
                                    echo "K-Means PSO";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <h5 class="alert alert-primary" role="alert">
        Grafik Hasil Klusterisasi K-Means dan K-Means PSO
    </h5>
    <div class="row mb-5">
        <div class="col">
            <div>
                <canvas id="myBarChart"></canvas>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col">
            <div>
                <canvas id="myLineChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById("myBarChart").getContext('2d');
    const myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $sumbu_x; ?>,
            datasets: [{
                    label: 'K-Means',
                    backgroundColor: 'rgb(255, 0, 0)',
                    borderColor: 'rgb(255, 0, 0)',
                    fill: false,
                    data: <?php echo $kualitas_kmeans; ?>
                },
                {
                    label: 'K-Means PSO',
                    backgroundColor: 'rgb(0, 0, 255)',
                    borderColor: 'rgb(0, 0, 255)',
                    fill: false,
                    data: <?php echo $kualitas_kmeanspso; ?>
                }
            ]
        },
        options: {
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Pengujian ke-'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Silhouette Coeficient'
                    },
                    ticks: {
                        min: -1,
                        max: 1,
                        stepSize: 0.1 // <----- This prop sets the stepSize
                    }
                }]
            },
        }
    });
    const ctx_line = document.getElementById("myLineChart").getContext('2d');
    const myLineChart = new Chart(ctx_line, {
        type: 'line',
        data: {
            labels: <?php echo $sumbu_x; ?>,
            datasets: [{
                    label: 'K-Means',
                    backgroundColor: 'rgb(255, 0, 0)',
                    borderColor: 'rgb(255, 0, 0)',
                    fill: false,
                    data: <?php echo $kualitas_kmeans; ?>
                },
                {
                    label: 'K-Means PSO',
                    backgroundColor: 'rgb(0, 0, 255)',
                    borderColor: 'rgb(0, 0, 255)',
                    fill: false,
                    data: <?php echo $kualitas_kmeanspso; ?>
                }
            ]
        },
        options: {
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Pengujian ke-'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Silhouette Coeficient'
                    },
                    ticks: {
                        min: -1,
                        max: 1,
                        stepSize: 0.1 // <----- This prop sets the stepSize
                    }
                }]
            },
        }
    });
</script>