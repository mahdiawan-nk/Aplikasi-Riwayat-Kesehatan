<div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="form-data">
                <div class="row mb-3">
                    <label for="input53" class="col-sm-3 col-form-label">Roles</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-flag"></i></span>
                            <select class="form-select" name="roles" id="roles" fdprocessedid="22whqg">
                                <option value="">Pilih Role</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="input49" class="col-sm-3 col-form-label">Enter Your Name</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Your Name"
                                fdprocessedid="hzizgo">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="input50" class="col-sm-3 col-form-label">Phone No</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-microphone"></i></span>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone No"
                                fdprocessedid="748i4m">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="input51" class="col-sm-3 col-form-label">Email Address</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email"
                                fdprocessedid="cvl7k">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="input52" class="col-sm-3 col-form-label">Choose Password</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-lock-open"></i></span>
                            <input type="text" class="form-control" name="password" id="password" placeholder="Choose Password"
                                fdprocessedid="sfi38p">
                        </div>
                        <small id="notes-password" class="form-text text-muted"></small>

                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4" id="btn-submit" fdprocessedid="zf35kx">Submit</button>
                            <button type="button" class="btn btn-light px-4" fdprocessedid="r8yx3">Reset</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div> --}}
        </div>
    </div>
</div>
