@extends('layouts.app')

@section('style')
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <style>
        .search-result {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        .search-result .list-group-item {
            cursor: pointer;
        }
    </style>
@endsection

@section('wrapper')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Data MCU</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">MCU Karyawan</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group" id="list-action-btn">
                        <button type="button" class="btn btn-primary btn-sm" id="tambah-data-karyawan"
                            onclick="tambahDataMcu()"> <i class="bx bx-plus"></i>Tambah Data MCU</button>
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
                        <table id="tb-data" class="table align-middle" style="width:100%">
                            <thead class="table-info">
                                <tr>
                                    <th>No</th>
                                    <th>No Badge</th>
                                    <th>Nama</th>
                                    <th>Age</th>
                                    <th>Tahun MCU</th>
                                    <th>Riwayat Kesehatan</th>
                                    <th>Riwayat Konsumsi Obat</th>
                                    <th>SKJ</th>
                                    <th>File MCU</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot class="table-info">
                                <tr>
                                    <th>No</th>
                                    <th>No Badge</th>
                                    <th>Nama</th>
                                    <th>Age</th>
                                    <th>Tahun MCU</th>
                                    <th>Riwayat Kesehatan</th>
                                    <th>Riwayat Konsumsi Obat</th>
                                    <th>SKJ</th>
                                    <th>File MCU</th>
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

            @include('data-mcu.form')
        </div>
    </div>
    <!--end page wrapper -->
@endsection

@section('script')
    <script src="assets/lib/DataTable.js"></script>
    <script src="assets/lib/FormValidate.js"></script>

    <script>
        const btnTambah = $('#tambah-data-karyawan');
        const btnSeller = $('#import-data-karyawan');
        const previewImage = $('#preview-image');
        const pageListData = $('#page-list-data');
        const pageFormData = $('#page-form-data');
        const listBtnAction = $('#list-action-btn');
        const btnCancel = $('#btn-cancel');
        const baseUrl = "{{ url('/api/karyawan-mcu') }}";

        let modeForm = 'create';
        let uidData = null;

        let DataMcuKaryawan = {
            file_mcu: null,
            id_karyawan: null,
            riwayat_kesehatan: null,
            riwayat_konsumsi_obat: null,
            score_kardiovaskular_jakarta: null,
            tahun_mcu: null,
            _token: '{{ csrf_token() }}',
        }

        const headers = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        }
        const dataTable = new DataTable({
            tableSelector: '#tb-data tbody',
            paginationSelector: '#pagination-list',
            showingInfoSelector: '#showing',
            baseUrlApi: baseUrl,
            token: token,
            columns: [{
                    key: 'no',
                    label: 'No'
                },
                {
                    key: 'karyawan',
                    label: 'No Badge',
                    render: (value, item) => {
                        return `${value.no_badge}`
                    }
                },
                {
                    key: 'karyawan',
                    label: 'Nama Karyawan',
                    render: (value, item) => {
                        return `${value.nama_karyawan}`
                    }
                },
                {
                    key: 'usia',
                    label: 'Usia',
                    render: (value, item) => {
                        return `${value} ${value > 1 ? 'Tahun' : '0 Tahun'}`
                    }
                },
                {
                    key: 'tahun_mcu',
                    label: 'Tahun MCU'
                },
                {
                    key: 'riwayat_kesehatan',
                    label: 'Riwayat Kesehatan',
                    render: (value) => {
                        return `<span class="text-wrap" style="width: 20rem;"> ${value}</span>`
                    }
                },
                {
                    key: 'riwayat_konsumsi_obat',
                    label: 'Riwayat Konsumsi Obat',
                    render: (value) => {
                        return `<span class="text-wrap" style="width: 15rem;"> ${value}</span>`
                    }
                },
                {
                    key: 'score_kardiovaskular_jakarta',
                    label: 'SKJ',
                    render: (value) => {
                        return `<span class="text-wrap" style="width: 15rem;"> ${value}</span>`
                    }
                },
                {
                    key: 'file_mcu',
                    label: 'File MCU',
                    render: (value, item) => {
                        return `<a href="{{ asset('storage/${value}') }}" target="_blank"> <i class="fa-solid fa-file-pdf fa-xl" style="color: #74C0FC;"></i></a>`
                    }
                },
                {
                    key: 'id',
                    label: 'Action',
                    render: (value, item) => {
                        return `<div class="d-flex order-actions">
                        <a href="javascript:;" onclick="editDataMcu(${item.id})"><i class="bx bx-edit-alt"></i></a>
                        <a href="javascript:;" class="ms-4 bg-danger text-white" onclick="deleteDataMcu(${item.id})"><i class="bx bx-trash-alt"></i></a>
                    </div>`
                    }
                }

            ],
            limit: 10,
            useAxios: false
        });

        const searchDataKaryawan = async (value) => {
            try {
                const response = await axios.get('/api/karyawan',headers, {
                    params: {
                        search: value
                    }
                });

                let filteredData = response.data.data.karyawans;
                $('#searchResults').empty().addClass('d-none'); // Kosongkan hasil pencarian sebelumnya

                if (filteredData.length > 0) {
                    filteredData.forEach(item => {
                        // Konversi objek menjadi string JSON untuk disertakan dalam onclick
                        const itemJson = JSON.stringify(item);
                        $('#searchResults').append(
                            `<a href="javascript:;" class="d-flex list-group-item list-group-item-action" aria-current="true" onclick='showDataKaryawan(${item.id})'>
                                <img src="${item.foto ? '{{ asset('storage') }}/' + item.foto : 'https://dummyimage.com/65x75/000/fff.png&text=foto+user'}" class="rounded me-2" alt="..." style="width: 65px; height: 75px;">
                                <div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 text-dark">${item.no_badge}</h5>
                                    </div>
                                    <p class="mb-1 text-dark">${item.nama_karyawan}</p>
                                    <p class="mb-1 text-dark">${item.tempat_lahir}, ${item.tgl_lahir}</p>
                                </div>
                            </a>`
                        );
                    });
                    $('#searchResults').removeClass('d-none');
                }
            } catch (error) {
                console.log(error);
            }
        };

        const showDataKaryawan = async (id) => {
            try {
                const response = await axios.get('/api/karyawan/' + id,headers);
                const data = response.data.data;
                DataMcuKaryawan.id_karyawan = data.id;
                $('#searchResults').empty().addClass('d-none');

                $('#no_badge').val(data.no_badge);
                $('#nama_karyawan').val(data.nama_karyawan);
                $('#tempat_lahir').val(data.tempat_lahir);
                $('#tgl_lahir').val(data.tgl_lahir);
                $('#no_hp_wa').val(data.no_hp_wa);
                $('#nama_istri_suami').val(data.nama_istri_suami);
                $('#no_hp_istri_suami').val(data.no_hp_istri_suami);

                $('#img-karyawan').attr('src', data.foto ? '{{ asset('storage') }}/' + data.foto :
                    'https://dummyimage.com/65x310/000/fff.png&text=foto+user');
            } catch (error) {
                console.log(error)
            }

        };

        const insertDataMcu = async () => {
            if (!$('#file_mcu').prop('files')[0]) {
                Swal.fire({
                    title: "Required",
                    text: "Please upload your profile picture",
                    icon: "warning"
                });
                return
            }

            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.riwayat_kesehatan = $('#riwayat_kesehatan').val();
            DataMcuKaryawan.riwayat_konsumsi_obat = $('#riwayat_konsumsi_obat').val();
            DataMcuKaryawan.file_mcu = $('#file_mcu').prop('files')[0];
            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.score_kardiovaskular_jakarta = $('input[name="score_kardiovaskular_jakarta"]:checked')
                .val();
            try {
                const response = await axios.post('/api/karyawan-mcu', DataMcuKaryawan, headers);
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Success " + response.data.message,
                    text: "Data Karyawan berhasil disimpan.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500
                });
                cancelForm()
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

        const updateDataMcu = async () => {

            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.riwayat_kesehatan = $('#riwayat_kesehatan').val();
            DataMcuKaryawan.riwayat_konsumsi_obat = $('#riwayat_konsumsi_obat').val();
            DataMcuKaryawan.file_mcu = $('#file_mcu').prop('files')[0];
            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.score_kardiovaskular_jakarta = $('input[name="score_kardiovaskular_jakarta"]:checked')
                .val();
            DataMcuKaryawan._method = 'PUT';
            try {
                const response = await axios.post('/api/karyawan-mcu/' + uidData, DataMcuKaryawan, headers);
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Success " + response.data.message,
                    text: "Data Karyawan berhasil disimpan.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500
                });
                console.log(response.data)
                cancelForm()
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

        const deleteDataMcu = async (id) => {
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
                        const response = await axios.delete(baseUrl + '/' + id,headers);
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            title: "Success " + response.data.message,
                            text: "Data MCU berhasil di hapus.",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        dataTable.refreshData();
                    } catch (error) {
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong. Please try again.",
                            icon: "error"
                        });
                        console.log(error);
                    }
                }
            });

        }

        const tambahDataMcu = () => {
            modeForm = 'create';
            uidData = null;
            pageListData.hide()
            listBtnAction.hide()
            pageFormData.show()
        }
        const editDataMcu = async (id) => {
            try {
                const response = await axios.get(baseUrl + '/' + id,headers);
                const {
                    id_karyawan,
                    riwayat_kesehatan,
                    riwayat_konsumsi_obat,
                    score_kardiovaskular_jakarta,
                    tahun_mcu,
                    file_mcu,
                } = response.data.data;
                uidData = id;
                showDataKaryawan(response.data.data.id_karyawan);
                DataMcuKaryawan.file_mcu = file_mcu;
                $('#no_badge').attr('disabled', true);
                $('#riwayat_kesehatan').val(riwayat_kesehatan);
                $('#riwayat_konsumsi_obat').val(riwayat_konsumsi_obat);
                // $('#score_kardiovaskular_jakarta').val(score_kardiovaskular_jakarta);
                $('input[name="score_kardiovaskular_jakarta"][value="'+score_kardiovaskular_jakarta+'"]').prop('checked', true);
                $('#tahun_mcu').val(tahun_mcu);
                modeForm = 'update';
                uidData = id;
                pageListData.hide()
                listBtnAction.hide()
                pageFormData.show()
            } catch (error) {
                console.log(error)
            }

        }

        const cancelForm = () => {
            pageListData.show()
            listBtnAction.show()
            pageFormData.hide()
        }



        const init = () => {
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
                    riwayat_kesehatan: {
                        required: true,
                        minlength: 1
                    },
                    riwayat_konsumsi_obat: {
                        required: true,
                        minlength: 1
                    },
                    score_kardiovaskular_jakarta: {
                        required: true,
                    },
                    tahun_mcu: {
                        required: true,
                        digits: true
                    },

                },
                messages: {
                    no_badge: {
                        required: "No Badge is required",
                    },
                    riwayat_kesehatan: {
                        required: "Riwayat Kesehatan is",
                    },
                    riwayat_konsumsi_obat: {
                        required: "Riwayat Konsumsi Obat is required",
                    },
                    score_kardiovaskular_jakarta: {
                        required: "Tanggal Lahir is required",
                    },
                    tahun_mcu: {
                        required: "Tahun MCU is required",
                    },
                },
                errorClass: 'is-invalid',
                submitHandler: function(form) {
                    // form.submit(); // Handle form submission
                    if (modeForm == 'create') {
                        insertDataMcu();
                    } else {
                        updateDataMcu();
                    }
                }
            });

            validator.init();

            $(document).click(function(event) {
                if (!$(event.target).closest('#searchInput').length && !$(event.target).closest(
                        '#searchResults').length) {
                    $('#searchResults').empty().addClass('d-none');
                }
            });
        }

        init();
    </script>
@endsection
