@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('wrapper')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Data Master</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Karyawan</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group" id="list-action-btn">
                        <button type="button" class="btn btn-primary btn-sm" id="tambah-data-karyawan"> <i
                                class="bx bx-plus"></i>Tambah Data Karyawan</button>
                        <button type="button" class="btn btn-secondary btn-sm" hidden id="import-data-karyawan"> <i
                                class="bx bx-import"></i>Import Data Karyawan</button>
                        <button type="button" class="btn btn-dark btn-sm" id="refreshButton"> <i
                                class="bx bx-refresh"></i>Refresh</button>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr />
            <div class="card" id="page-list-data">
                <div class="card-body">
                    @include('components.datacontrols')
                    <div class="table-responsive">
                        <table class="table align-middle" style="width:100%" id="tb-data">
                            <thead class="table-info">
                                <tr>
                                    <th>No</th>
                                    <th>No Badge</th>
                                    <th>Nama</th>
                                    <th>TTL</th>
                                    <th>Age</th>
                                    <th>Kontak</th>
                                    <th>Nama Istri/Suami</th>
                                    <th>No HP Istri/Suami</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <i class="fa-solid fa-spinner fa-spin-pulse fa-xl m-5"
                                            style="color: #63E6BE;font-size:5em"></i>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-info">
                                <tr>
                                    <th>No</th>
                                    <th>No Badge</th>
                                    <th>Nama</th>
                                    <th>TTL</th>
                                    <th>Age</th>
                                    <th>Kontak</th>
                                    <th>Nama Istri/Suami</th>
                                    <th>No HP Istri/Suami</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row mt-1">
                        <div class="col-sm-12">
                            <label for="">Showing</label>
                            <label for="" id="showing"></label>
                        </div>
                    </div>
                </div>
            </div>
            @include('karyawan.form')
        </div>
    </div>
    <!--end page wrapper -->
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('') }}assets/lib/DataTable.js"></script>
    <script src="{{ asset('') }}assets/lib/FormValidate.js"></script>


    <script>
        const btnTambah = $('#tambah-data-karyawan');
        const btnSeller = $('#import-data-karyawan');
        const previewImage = $('#preview-image');
        const pageListData = $('#page-list-data');
        const pageFormData = $('#page-form-data');
        const listBtnAction = $('#list-action-btn');
        const btnCancel = $('#btn-cancel');
        const baseUrl = "{{ url('/') }}";
        const elmtTable = $('#tb-data tbody');

        const tokenCsrf = "{{ csrf_token() }}";
        const BaseUrlApi = "{{ url('panel-admin/master-karyawan/karyawan') }}";

        let modeForm = 'create';
        let uidData = null;
        let importFileExcel = null;
        let DataKaryawan = {
            foto: null,
            no_badge: null,
            nama_karyawan: null,
            tempat_lahir: null,
            tgl_lahir: null,
            no_hp_wa: null,
            nama_istri_suami: null,
            no_hp_istri_suami: null,
            email:null,
            _token: "{{ csrf_token() }}"
        }

        const headers = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json',
            }
        }

        const dataTable = new DataTable({
            tableSelector: '#tb-data tbody',
            paginationSelector: '#pagination-list',
            showingInfoSelector: '#showing',
            baseUrlApi: BaseUrlApi,
            token: token,
            columns: [{
                    key: 'no',
                    label: 'No'
                },
                {
                    key: 'no_badge',
                    label: 'No Badge'
                },
                {
                    key: 'nama_karyawan',
                    label: 'Nama Karyawan'
                },
                {
                    key: 'tempat_lahir',
                    label: 'Tempat Lahir',
                    render: (value, item) => `${value}, ${item.tgl_lahir}`
                },

                {
                    key: 'usia',
                    label: 'Usia',
                    render: (value, item) => {
                        return `${value} ${value > 1 ? 'Tahun' : '0 Tahun'}`
                    }
                },
                {
                    key: 'no_hp_wa',
                    label: 'No HP/WA',
                    render: (value, item) => {
                        return `Telp : ${value} <br> Email : ${item.email?item.email:'-'}`
                    }
                },
                {
                    key: 'nama_istri_suami',
                    label: 'Nama Istri/Suami'
                },
                {
                    key: 'no_hp_istri_suami',
                    label: 'No HP Istri/Suami'
                },
                {
                    key: 'id',
                    label: 'Action',
                    render: (value, item) => {
                        return `<div class="d-flex order-actions">
                        <a href="javascript:;" onclick="editDataKaryawan(${item.id})"><i class="bx bx-edit-alt"></i></a>
                        <a href="javascript:;" class="ms-4 bg-danger text-white" onclick="deleteDataKaryawan(${item.id})"><i class="bx bx-trash-alt"></i></a>
                    </div>`
                    }
                }

            ],
            limit: 10,
            useAxios: false
        });

        const insertDataKarayawan = async () => {
            if (!$('#foto').prop('files')[0]) {
                Swal.fire({
                    title: "Required",
                    text: "Please upload your profile picture",
                    icon: "warning"
                });
                return
            }
            DataKaryawan.foto = $('#foto').prop('files')[0];
            DataKaryawan.no_badge = $('#no_badge').val();
            DataKaryawan.nama_karyawan = $('#nama_karyawan').val();
            DataKaryawan.tempat_lahir = $('#tempat_lahir').val();
            DataKaryawan.tgl_lahir = $('#tgl_lahir').val();
            DataKaryawan.no_hp_wa = $('#no_hp_wa').val();
            DataKaryawan.nama_istri_suami = $('#nama_istri_suami').val();
            DataKaryawan.no_hp_istri_suami = $('#no_hp_istri_suami').val();
            DataKaryawan.email = $('#email').val();


            try {
                const response = await axios.post(BaseUrlApi, DataKaryawan, headers);
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Success " + response.data.message,
                    text: "Data Karyawan berhasil disimpan.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500
                });
                pageListData.show()
                listBtnAction.show()
                pageFormData.hide()
                dataTable.refreshData();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    let errorMessage = "";
                    for (const [key, messages] of Object.entries(errors)) {
                        errorMessage += `${messages.join(', ')}\n`;
                    }
                    Swal.fire({
                        title: "Validation Error",
                        text: errorMessage,
                        icon: "error"
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Something went wrong. Please try again.",
                        icon: "error"
                    });
                }
                console.log(error);
            }
        }

        const updateDataKarayawan = async () => {
            DataKaryawan.foto = $('#foto').prop('files')[0];
            DataKaryawan.no_badge = $('#no_badge').val();
            DataKaryawan.nama_karyawan = $('#nama_karyawan').val();
            DataKaryawan.tempat_lahir = $('#tempat_lahir').val();
            DataKaryawan.tgl_lahir = $('#tgl_lahir').val();
            DataKaryawan.no_hp_wa = $('#no_hp_wa').val();
            DataKaryawan.nama_istri_suami = $('#nama_istri_suami').val();
            DataKaryawan.no_hp_istri_suami = $('#no_hp_istri_suami').val();
            DataKaryawan.email = $('#email').val();
            DataKaryawan._method = 'PUT';
            headers.headers.Authorization = `Bearer ${token}`
            headers.headers.Accept = 'application/json';
            try {
                const response = await axios.post(BaseUrlApi + '/' + uidData, DataKaryawan, headers);
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Success " + response.data.message,
                    text: "Data Karyawan berhasil disimpan.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500
                });
                pageListData.show()
                listBtnAction.show()
                pageFormData.hide()
                dataTable.refreshData();
                uidData = null;
                modeForm = 'create';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    let errorMessage = "";
                    for (const [key, messages] of Object.entries(errors)) {
                        errorMessage += `${messages.join(', ')}\n`;
                    }
                    Swal.fire({
                        title: "Validation Error",
                        text: errorMessage,
                        icon: "error"
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Something went wrong. Please try again.",
                        icon: "error"
                    });
                }
                console.log(error);
            }
        }

        const tambahDataKaryawan = () => {
            btnTambah.on('click', function() {
                modeForm = 'create';
                uidData = null;
                pageListData.hide()
                listBtnAction.hide()
                pageFormData.show()
            });
        }

        const editDataKaryawan = async (id) => {
            try {
                const response = await axios.get(BaseUrlApi + '/' + id, headers);
                const {
                    no_badge,
                    nama_karyawan,
                    tempat_lahir,
                    tgl_lahir,
                    no_hp_wa,
                    nama_istri_suami,
                    no_hp_istri_suami,
                    foto
                } = response.data.data;
                uidData = id;
                console.log(response.data)

                $('#preview-image').attr('src', '{{ asset('storage') }}/' + foto)
                $('#no_badge').val(no_badge);
                $('#nama_karyawan').val(nama_karyawan);
                $('#tempat_lahir').val(tempat_lahir);
                $('#tgl_lahir').val(tgl_lahir);
                $('#no_hp_wa').val(no_hp_wa);
                $('#nama_istri_suami').val(nama_istri_suami);
                $('#no_hp_istri_suami').val(no_hp_istri_suami);
                modeForm = 'update';
                uidData = id;
                pageListData.hide()
                listBtnAction.hide()
                pageFormData.show()
            } catch (error) {
                console.log(error)
            }

        }

        const CancelButton = () => {
            btnCancel.on('click', function() {
                pageListData.show()
                listBtnAction.show()
                pageFormData.hide()
            });
        }

        const deleteDataKaryawan = async (id) => {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await axios.delete(BaseUrlApi + '/' + id, headers);
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            title: "Success " + response.data.message,
                            text: "Data Karyawan berhasil di hapus.",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        dataTable.refreshData();
                    } catch (error) {
                        console.log(error)
                        if (error.response && error.response.status === 500) {
                            const errors = error.response.data;
                            console.log(errors)
                            let errorMessage = errors.message;

                            Swal.fire({
                                title: "Gagal Hapus",
                                text: errorMessage,
                                icon: "error"
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Something went wrong. Please try again.",
                                icon: "error"
                            });
                        }
                    }
                }
            });

        }

        const importDataKaryawan = () => {
            btnSeller.on('click', async function() {
                const {
                    value: file
                } = await Swal.fire({
                    title: "Select File",
                    input: "file",
                    html: '<a href="#" class="btn-link">Download template</a>',
                    inputAttributes: {
                        "accept": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel",
                        "aria-label": "Upload your profile picture"
                    },
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to select an file!';
                        }
                        return null;
                    }
                });
                if (file) {
                    importFileExcel = file

                    console.log(importFileExcel)
                }
            });
        }

        const init = () => {
            tambahDataKaryawan();
            CancelButton();
            importDataKaryawan();

            $("#tgl_lahir").flatpickr({
                enableTime: false,
                maxDate: "today",
                dateFormat: "Y-m-d",
            });

            $('#upload-foto').click(function(e) {
                e.preventDefault();
                $('#foto').trigger('click');
            });

            $('#foto').change(function(event) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {

                        previewImage.attr('src', e.target.result);

                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            $('#searchInput').on('input', function() {
                dataTable.setSearchTerm(this.value);
            });

            $('#limitSelect').on('change', function() {
                dataTable.setLimit(this.value);
            });

            // Event listener for refresh button
            $('#refreshButton').on('click', function() {
                console.log('refresh button clicked');
                dataTable.refreshData();
            });

            const form = document.querySelector('#form-data');
            const validator = new ValidateForm(form, {
                rules: {
                    no_badge: {
                        required: true,
                        minlength: 1
                    },
                    nama_karyawan: {
                        required: true,
                        minlength: 1
                    },
                    tempat_lahir: {
                        required: true,
                        minlength: 1
                    },
                    tgl_lahir: {
                        required: true,
                        // date: true
                    },
                    email: {
                        required: true,
                        email: true // Aturan validasi email
                    },
                    no_hp_wa: {
                        required: true,
                        digits: true
                    },
                    nama_istri_suami: {
                        required: true,
                        minlength: 1
                    },
                    no_hp_istri_suami: {
                        required: true,
                        digits: true
                    }
                },
                messages: {
                    no_badge: {
                        required: "No Badge is required",
                        // minlength: "Please enter a valid No Badge"
                    },
                    nama_karyawan: {
                        required: "Nama Karyawan is required",
                        // minlength: "Please enter a valid Nama Karyawan"
                    },
                    tempat_lahir: {
                        required: "Tempat Lahir is required",
                        // minlength: "Please enter a valid Tempat Lahir"
                    },
                    tgl_lahir: {
                        required: "Tanggal Lahir is required",
                        // date: "Please enter a valid date"
                    },
                    no_hp_wa: {
                        required: "No HP/WA is required",
                        // digits: "Please enter a valid phone number"
                    },
                    nama_istri_suami: {
                        required: "Nama Istri/Suami is required",
                        // minlength: "Please enter a valid Nama Istri/Suami"
                    },
                    no_hp_istri_suami: {
                        required: "No HP Istri/Suami is required",
                        // digits: "Please enter a valid phone number"
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address"
                    },
                },
                errorClass: 'is-invalid',
                submitHandler: function(form) {
                    // form.submit(); // Handle form submission
                    if (modeForm == 'create') {
                        insertDataKarayawan();
                    } else {
                        updateDataKarayawan();
                    }
                }
            });

            validator.init();

        }

        init();
    </script>
@endsection
