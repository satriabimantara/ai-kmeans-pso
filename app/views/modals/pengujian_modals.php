<!-- Modals Pengujian Metode -->
<form action="<?= BASEURL; ?>pengujian/skenario_pengujian" method="post">
    <div class="modal fade" id="modalPengujianMetode" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalPengujianMetodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPengujianMetodeLabel">Pengujian Metode K-Means dan K-Means PSO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jumlahKOptimal">Jumlah Kluster</label>
                        <input type="number" class="form-control" id="jumlahKOptimal" name="jumlahKOptimal" required="true" min="2" max="<?= $data['amount_data']; ?>" value="" aria-describedby="jumlahKHelp">
                        <small id="jumlahKHelp" class="form-text text-muted">Jumlah kluster maksimum sebesar <?= $data['amount_data']; ?></small>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_partikel">Jumlah Partikel</label>
                        <input type="number" step="1" min="1" max="500" class="form-control" id="jumlah_partikel" required="true" name="jumlah_partikel">
                    </div>
                    <div class="form-group">
                        <label for="jumlah_iterasi">Jumlah Iterasi</label>
                        <input type="number" step="1" min="1" max="1000" class="form-control" id="jumlah_iterasi" required="true" name="jumlah_iterasi">
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label for="parameter_c1">Parameter c1</label>
                            <input type="number" step="0.1" min="0" max="5" class="form-control" id="parameter_c1" required="true" name="parameter_c1">
                        </div>
                        <div class="col">
                            <label for="parameter_c2">Parameter c2</label>
                            <input type="number" step="0.1" min="0" max="5" class="form-control" id="parameter_c2" required="true" name="parameter_c2">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_pengujian">Jumlah Pengujian</label>
                        <input type="number" step="1" min="1" max="100" class="form-control" id="jumlah_pengujian" required="true" name="jumlah_pengujian">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-info" value="" name="btnProsesMetode" id="btnProsesMetode">Proses</button>
                </div>
            </div>
        </div>
    </div>
</form>