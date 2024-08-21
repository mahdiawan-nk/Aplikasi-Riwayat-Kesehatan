<div class="row">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-8">
        <div class="card border-top border-0 border-4 border-info" id="page-form-data" style="display: none">
            <form class="card-body" id="form-data">
                <div class="border p-4 rounded">
                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-info"></i>
                        </div>
                        <h5 class="mb-0 text-info">Data Karyawan</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <label for="no_badge" class="col-sm-3 col-form-label">No Badge</label>
                                <div class="col-sm-9 position-relative">
                                    <input type="text" class="form-control" id="no_badge" name="no_badge"
                                        placeholder="Cari data karyawan guanakan No Badge"
                                        oninput="searchDataKaryawan(this.value)">
                                    <div id="searchResults" class="search-result list-group mt-1 d-none"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nama_karyawan" class="col-sm-3 col-form-label">Nama Karyawan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_karyawan"
                                        placeholder="Enter Nama Karyawan" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat Tanggal Lahir</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="tempat_lahir"
                                        placeholder="Enter Tempat Lahir" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="tgl_lahir"
                                        placeholder="Enter Tanggal Lahir" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="no_hp" class="col-sm-3 col-form-label">No HP/WA</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="no_hp_wa"
                                        placeholder="Enter No HP/WA" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nama_istri_suami" class="col-sm-3 col-form-label">Nama Istri/Suami</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_istri_suami"
                                        placeholder="Enter Nama Istri/Suami" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="no_hp_istri_suami" class="col-sm-3 col-form-label">No HP Istri/Suami</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="no_hp_istri_suami"
                                        placeholder="Enter No HP Istri/Suami" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <img src="https://dummyimage.com/500x500/000/fff.png&text=foto+user" id="img-karyawan"
                                class="img-thumbnail" alt="karyawan"
                                style="width: 100%;max-height: 310px;object-fit: cover">
                        </div>
                    </div>


                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-info"></i>
                        </div>
                        <h5 class="mb-0 text-info">Data MCU</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <label for="riwayat_kesehatan" class="col-sm-3 col-form-label">Riwayat Kesehatan</label>
                                <div class="col-sm-9">
                                    <textarea name="riwayat_kesehatan" id="riwayat_kesehatan" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="riwayat_konsumsi_obat" class="col-sm-3 col-form-label">Riwayat Konsumsi
                                    Obat</label>
                                <div class="col-sm-9">
                                    <textarea name="riwayat_konsumsi_obat" id="riwayat_konsumsi_obat" cols="30" rows="10" class="form-control"></textarea>

                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="score_kardiovaskular_jakarta" class="col-sm-3 col-form-label">Score
                                    Kardiovaskular
                                    Jakarta</label>
                                <div class="col-sm-9">
                                    <input type="radio" class="btn-check" name="score_kardiovaskular_jakarta"
                                        id="success-outlined" autocomplete="off" value="Low Risk">
                                    <label class="btn btn-outline-success" for="success-outlined">Low Risk</label>
                                    <input type="radio" class="btn-check" name="score_kardiovaskular_jakarta"
                                        id="warning-outlined" autocomplete="off" value="Medium Risk">
                                    <label class="btn btn-outline-warning" for="warning-outlined">Medium Risk</label>
                                    <input type="radio" class="btn-check" name="score_kardiovaskular_jakarta"
                                        id="danger-outlined" autocomplete="off" value="High Risk">
                                    <label class="btn btn-outline-danger" for="danger-outlined">High Risk</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="file_mcu" class="col-sm-3 col-form-label">File MCU</label>
                                <div class="col-sm-6">
                                    <input type="file" class="form-control" id="file_mcu" name="file_mcu"
                                        placeholder="" accept="application/pdf">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="tahun_mcu" name="tahun_mcu"
                                        placeholder="Tahun MCU Dilakukan">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-info px-5" id="btn-submit">Submit</button>
                                    <button type="button" class="btn btn-secondary px-5" onclick="cancelForm()"
                                        id="btn-cancel">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
