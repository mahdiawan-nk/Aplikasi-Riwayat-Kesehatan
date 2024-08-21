<div class="row">
    <div class="col-sm-3">
    </div>
    <div class="col-sm-6">
        <div class="card border-top border-0 border-4 border-info" id="page-form-data" style="display: none">
            <form class="card-body" id="form-data" enctype="multipart/form-data">
                <div class="border p-4 rounded">
                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-info"></i>
                        </div>
                        <h5 class="mb-0 text-info">Data Karyawan</h5>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <img src="assets/images/photo.png" alt="" id="preview-image"
                                class="rounded border-1 border-dark d-block mx-auto"
                                style="width: 15%!important;height: 150px;object-fit: contain">
                            <button class="btn btn-sm btn-secondary d-block mx-auto" id="upload-foto"
                                style="width: 15%!important">Upload Foto</button>
                            <input type="file" class="form-control" id="foto" placeholder="Enter No Badge"
                                accept="image/*" style="display: none" name="foto">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="no_badge" class="col-sm-3 col-form-label">No Badge</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_badge" id="no_badge"
                                placeholder="Enter No Badge" >
                            
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nama_karyawan" class="col-sm-3 col-form-label">Nama Karyawan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan"
                                placeholder="Enter Nama Karyawan" >
                                
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat Tanggal Lahir</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir"
                                placeholder="Enter Tempat Lahir" >
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir"
                                placeholder="Enter Tanggal Lahir" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="no_hp_wa" class="col-sm-3 col-form-label">No HP/WA</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_hp_wa" id="no_hp_wa"
                                placeholder="Enter No HP/WA" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nama_istri_suami" class="col-sm-3 col-form-label">Nama Istri/Suami</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_istri_suami" id="nama_istri_suami"
                                placeholder="Enter Nama Istri/Suami" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="no_hp_istri_suami" class="col-sm-3 col-form-label">No HP Istri/Suami</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_hp_istri_suami" id="no_hp_istri_suami"
                                placeholder="Enter No HP Istri/Suami" >
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-info px-5" id="btn-submit">Submit</button>
                            <button type="button" class="btn btn-secondary px-5" id="btn-cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
