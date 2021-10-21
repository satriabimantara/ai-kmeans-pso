<div class="alert alert-info" role="alert">
    <?php for ($i = 0; $i < count($data['hasilPengujianKMeansPSO']); $i++) : ?>
        <h4 class="alert-heading"><?= $i; ?>). Silhouette Coeficient K-Means PSO = <?= $data['hasilPengujianKMeansPSO'][$i]; ?></h4>
        <br>
    <?php endfor; ?>
</div>
<div class="row">
    <div class="col-lg-6">
        <?php Flasher::flash(); ?>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col">
            <table class="table table-striped" id="table_metode_kmeanspso">
                <thead>
                    <tr>
                        <th scope="col">Nomor</th>
                        <th scope="col">Kluster</th>
                        <th scope="col">a(o)</th>
                        <th scope="col">b(o)</th>
                        <th scope="col">s(o)</th>
                        <?php foreach ($data['columns'] as $column) : ?>
                            <th scope="col"><?= $column; ?></th>
                        <?php endforeach; ?>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['new_dataset'] as $indeks_data => $reviews) : ?>
                        <tr>
                            <td><?= $indeks_data + 1; ?></td>
                            <td><?= $reviews['kluster']; ?></td>
                            <td><?= $reviews['a(o)']; ?></td>
                            <td><?= $reviews['b(o)']; ?></td>
                            <td><?= $reviews['s(o)']; ?></td>
                            <?php foreach ($data['columns'] as $column) : ?>
                                <td><?= $reviews[$column]; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>