<div class="alert alert-info" role="alert">
    <h4 class="alert-heading">Data Penelitian</h4>
</div>
<div class="row">
    <div class="col-lg-6">
        <?php Flasher::flash(); ?>
    </div>
</div>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <table class="table table-striped" id="table_data_original">
                <thead>
                    <tr>
                        <th scope="col">Nomor</th>
                        <th scope="col">User ID</th>
                        <?php for ($i = 1; $i <= 10; $i++) : ?>
                            <th scope="col"><?= "Category_" . $i; ?></th>
                        <?php endfor; ?>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['daftar_data'] as $index => $review) : ?>
                        <tr>
                            <th scope="row"><?= $index + 1; ?></th>
                            <td><?= $review['user_id']; ?></td>
                            <?php
                            for ($i = 1; $i <= 10; $i++) {
                                $str = "category_" . $i;
                                echo "<td>" . $review[$str] . "</td>";
                            }
                            ?>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <a class="btn btn-danger btn-sm" href="<?= BASEURL; ?>data/hapus_data_original/<?= $review['id_reviews'] ?>" onclick="return confirm('Hapus data ini?');" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>