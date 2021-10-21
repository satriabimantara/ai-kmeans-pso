<form action="<?= BASEURL; ?>metode/elbow" method="post">
    <div class="modal fade" id="modalCariKlusterOptimal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalCariKlusterOptimalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCariKlusterOptimalLabel">Cari Jumlah Kluster Optimal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-6">
                            <label for="inputJumlahMinimumKluster">Minimum Kluster</label>
                            <input type="number" class="form-control" id="inputJumlahMinimumKluster" name="inputJumlahMinimumKluster" aria-describedby="minimumKHelp" min="1" max="<?= $data['amount_data']; ?>" value="">
                            <small id="minimumKHelp" class="form-text text-muted">Jumlah kluster minimum adalah 1</small>
                        </div>
                        <div class="col-6">
                            <label for="inputJumlahMaksimumKluster">Maksimum Kluster</label>
                            <input type="number" class="form-control" id="inputJumlahMaksimumKluster" name="inputJumlahMaksimumKluster" aria-describedby="maksimumKHelp" max="<?= $data['amount_data']; ?>" min="3">
                            <small id="maksimumKHelp" class="form-text text-muted">Kluster maksimum sejumlah data</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-success" value="" name="btnCariJumlahKlusterOptimal" id="btnCariJumlahKlusterOptimal">Cari</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modals Metode -->
<form action="<?= BASEURL; ?>metode/pengujian" method="post">
    <div class="modal fade" id="modalMetode" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalMetodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMetodeLabel">Proses Metode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jumlahKOptimal">Jumlah Kluster</label>
                        <input type="number" class="form-control" id="jumlahKOptimal" name="jumlahKOptimal" required="true" min="1" max="<?= $data['amount_data']; ?>" value="" aria-describedby="jumlahKHelp">
                        <small id="jumlahKHelp" class="form-text text-muted">Jumlah kluster maksimum sebesar <?= $data['amount_data']; ?></small>
                    </div>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input typeAlgorithm" type="radio" name="typeMetode" id="KMeans" value="KMeans" checked>
                            <label class="form-check-label" for="KMeans">K-Means</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input typeAlgorithm" type="radio" name="typeMetode" id="KMeansPSO" value="KMeansPSO">
                            <label class="form-check-label" for="KMeansPSO">K-Means PSO</label>
                        </div>
                    </div>
                    <div style="display: none;" id="parametersPSO">
                        <div class="form-group">
                            <label for="jumlah_partikel">Jumlah Partikel</label>
                            <input type="number" step="1" min="10" max="500" class="form-control" id="jumlah_partikel" aria-describedby="jumlah_partikel_help" required="true" name="jumlah_partikel">
                            <small id="jumlah_partikel_help" class="form-text text-muted">Jumlah partikel minimum 10 dan maksimum 500</small>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_iterasi">Jumlah Iterasi</label>
                            <input type="number" step="1" min="10" max="1000" class="form-control" id="jumlah_iterasi" aria-describedby="jumlah_iterasi_help" required="true" name="jumlah_iterasi">
                            <small id="jumlah_iterasi_help" class="form-text text-muted">Jumlah Iterasi minimum 10 dan maksimum 1000</small>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <label for="parameter_c1">Parameter c1</label>
                                <input type="number" step="0.1" min="1" max="5" class="form-control" id="parameter_c1" aria-describedby="parameter_c1_help" required="true" name="parameter_c1">
                                <small id="parameter_c1_help" class="form-text text-muted">Parameter c1 minimum 1 dan maksimum 5</small>
                            </div>
                            <div class="col">
                                <label for="parameter_c2">Parameter c2</label>
                                <input type="number" step="0.1" min="1" max="5" class="form-control" id="parameter_c2" aria-describedby="parameter_c2_help" required="true" name="parameter_c2">
                                <small id="parameter_c2_help" class="form-text text-muted">Parameter c2 minimum 1 dan maksimum 5</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_pengujian">Jumlah Pengujian</label>
                            <input type="number" step="1" min="10" max="1000" class="form-control" id="jumlah_pengujian" aria-describedby="jumlah_pengujian_help" required="true" name="jumlah_pengujian">
                        </div>
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