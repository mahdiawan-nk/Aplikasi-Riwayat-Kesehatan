@extends('layouts.app')

@section('style')
    {{-- <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" /> --}}
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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('wrapper')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-sm-flex flex-wrap align-items-center mb-3">
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
                                    <th>Tahun MCU</th>
                                    <th>Riwayat Kesehatan</th>
                                    <th>Riwayat Konsumsi Obat</th>
                                    <th>SKJ</th>
                                    <th>Status Fit Work</th>
                                    <th>Medis Kondisi</th>
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
                                    <th>Tahun MCU</th>
                                    <th>Riwayat Kesehatan</th>
                                    <th>Riwayat Konsumsi Obat</th>
                                    <th>SKJ</th>
                                    <th>Status Fit Work</th>
                                    <th>Medis Kondisi</th>
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
            {{-- @include('data-mcu.detail') --}}
        </div>
    </div>
    <!--end page wrapper -->
@endsection

@section('script')
    <script src="{{ asset('') }}assets/lib/DataTable.js"></script>
    <script src="{{ asset('') }}assets/lib/FormValidate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        const btnTambah = $('#tambah-data-karyawan');
        const btnSeller = $('#import-data-karyawan');
        const previewImage = $('#preview-image');
        const pageListData = $('#page-list-data');
        const pageFormData = $('#page-form-data');
        const listBtnAction = $('#list-action-btn');
        const btnCancel = $('#btn-cancel');
        const baseUrl = "{{ url('panel-admin/mcu-karyawan/karyawan-mcu') }}";

        let modeForm = 'create';
        let uidData = null;

        let DataMcuKaryawan = {
            file_mcu: null,
            id_karyawan: null,
            riwayat_kesehatan: null,
            riwayat_konsumsi_obat: null,
            score_kardiovaskular_jakarta: null,
            tahun_mcu: null,
            hasil_mcu: null,
            medical_condition: null,
            fitwork_condition: null,
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
                    key: 'status_fit_to_work',
                    label: 'Status Fit Work',
                    render: (value) => {
                        return `<span class="text-wrap" style="width: 15rem;"> ${value.name_status}</span>`
                    }
                },
                {
                    key: 'medical_condition',
                    label: 'Medical Condition',
                    render: (value) => {
                        if(value.length == 0) return `<span class="text-wrap d-block" style="width: 15rem;"> - </span>`
                        return value.map((item) => {
                            return `<span class="text-wrap d-block" style="width: 15rem;"> ${item.name}</span>`
                        }).join('')
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
                                    <a href="javascript:;" onclick="editDataMcu(${item.id})" class="bg-secondary text-white" hidden><i class="fa-solid fa-eye fa-xs"></i></a>
                                    <a href="javascript:;" onclick="editDataMcu(${item.id})" class="ms-4"><i class="bx bx-edit-alt"></i></a>
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
                const response = await axios.get('/panel-admin/master-karyawan/karyawan', headers, {
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
                const response = await axios.get('/panel-admin/master-karyawan/karyawan/' + id, headers);
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

            const selectedMEdicalCondition = $('#list-medical-condition input[type="checkbox"]:checked')
                .map(function() {
                    return $(this).val(); // Get the value attribute which is the ID
                }).get();

            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.riwayat_kesehatan = $('#riwayat_kesehatan').val();
            DataMcuKaryawan.riwayat_konsumsi_obat = $('#riwayat_konsumsi_obat').val();
            DataMcuKaryawan.file_mcu = $('#file_mcu').prop('files')[0];
            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.score_kardiovaskular_jakarta = $('input[name="score_kardiovaskular_jakarta"]:checked')
                .val();
            DataMcuKaryawan.hasil_mcu = $('#hasil_mcu').val();
            DataMcuKaryawan.medical_condition = selectedMEdicalCondition;
            DataMcuKaryawan.fitwork_condition = $('#list-fitwork-condition input[name="fitwork_condition"]:checked')
                .val();
            try {
                const response = await axios.post(baseUrl, DataMcuKaryawan, headers);
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Success " + response.data.message,
                    text: "Data Karyawan berhasil disimpan.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500
                });
                // cancelForm()
                // dataTable.refreshData();
                console.log(response.data)
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
            const selectedMEdicalCondition = $('#list-medical-condition input[type="checkbox"]:checked')
                .map(function() {
                    return $(this).val(); // Get the value attribute which is the ID
                }).get();
            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.riwayat_kesehatan = $('#riwayat_kesehatan').val();
            DataMcuKaryawan.riwayat_konsumsi_obat = $('#riwayat_konsumsi_obat').val();
            DataMcuKaryawan.file_mcu = $('#file_mcu').prop('files')[0];
            DataMcuKaryawan.tahun_mcu = $('#tahun_mcu').val();
            DataMcuKaryawan.score_kardiovaskular_jakarta = $('input[name="score_kardiovaskular_jakarta"]:checked')
                .val();
            DataMcuKaryawan.hasil_mcu = $('#hasil_mcu').val();
            DataMcuKaryawan.medical_condition = selectedMEdicalCondition;
            DataMcuKaryawan.fitwork_condition = $('#list-fitwork-condition input[name="fitwork_condition"]:checked').val()
            DataMcuKaryawan._method = 'PUT';
            console.log(DataMcuKaryawan)
            try {
                const response = await axios.post(baseUrl + '/' + uidData, DataMcuKaryawan, headers);
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
                        const response = await axios.delete(baseUrl + '/' + id, headers);
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
            listMedicalCondition().then((data) => {
                $('#list-medical-condition').empty();
                $('#list-medical-condition').append(data);
            })
            listFitWorkCondition().then((data) => {
                $('#list-fitwork-condition').empty();
                $('#list-fitwork-condition').append(data);
            })
        }
        const editDataMcu = async (id) => {
            try {
                const response = await axios.get(baseUrl + '/' + id, headers);
                const {
                    id_karyawan,
                    riwayat_kesehatan,
                    riwayat_konsumsi_obat,
                    score_kardiovaskular_jakarta,
                    tahun_mcu,
                    file_mcu,
                    hasil_mcu,
                    status_fit_to_work,
                    medical_condition
                } = response.data.data;
                uidData = id;
                showDataKaryawan(response.data.data.id_karyawan);
                DataMcuKaryawan.file_mcu = file_mcu;
                $('#no_badge').attr('disabled', true);
                $('#riwayat_kesehatan').summernote('code', riwayat_kesehatan);
                $('#riwayat_konsumsi_obat').summernote('code', riwayat_konsumsi_obat);
                $('#hasil_mcu').summernote('code', hasil_mcu);
                $('input[name="score_kardiovaskular_jakarta"][value="' + score_kardiovaskular_jakarta + '"]').prop(
                    'checked', true);
                $('#tahun_mcu').val(tahun_mcu);
                listMedicalCondition(medical_condition).then((data) => {
                    $('#list-medical-condition').empty();
                    $('#list-medical-condition').append(data);
                })
                listFitWorkCondition(status_fit_to_work).then((data) => {
                    $('#list-fitwork-condition').empty();
                    $('#list-fitwork-condition').append(data);
                })
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

        const listMedicalCondition = async (dataMedical = null) => {
            try {
                const response = await axios.get('{{ url('/panel-admin/medical-condition') }}', headers);
                const data = response.data.data;
                console.log(dataMedical)
                const list = data.map((item) => {
                    const isChecked = dataMedical.includes(item.id) ? 'checked' : '';
                    return (`<div class="form-check form-check-success me-2">
                                        <input class="form-check-input" type="checkbox" ${isChecked} name="medical_condition[]" value="${item.id}"
                                            id="medical-condition-${item.id}">
                                        <label class="form-check-label" for="medical-condition-${item.id}">
                                            ${item.name}
                                        </label>
                                    </div>`)
                }).join('');
                return list
            } catch (error) {
                console.log(error)
            }
        }

        const listFitWorkCondition = async (status = null) => {
            try {
                const response = await axios.get('{{ url('/panel-admin/fitwork-condition') }}', headers);
                const data = response.data.data;
                console.log(status)
                const list = data.map((item) => {
                    const checked = (item.id == status) ? 'checked' : '';
                    return (`<div class="form-check">
								<input class="form-check-input" ${checked} type="radio" name="fitwork_condition" value="${item.id}" id="fitwork${item.id}">
								<label class="form-check-label" for="fitwork${item.id}">
                                ${item.name_status}
								</label>
							  </div>`)
                }).join('');
                return list
            } catch (error) {
                console.log(error)
            }
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

            $('#riwayat_kesehatan, #riwayat_konsumsi_obat,#hasil_mcu').summernote({
                placeholder: 'Enter your content here',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],

                ]
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
                    
                    // 'medical_condition[]': {
                    //     required: true
                    // },
                    fitwork_condition: {
                        required: true
                    },
                    hasil_mcu: {
                        required: true
                    }

                },
                messages: {
                    no_badge: {
                        required: "No Badge is required",
                    },
                    riwayat_kesehatan: {
                        required: "Riwayat Kesehatan is Required",
                    },
                    riwayat_konsumsi_obat: {
                        required: "Riwayat Konsumsi Obat is required",
                    },
                    score_kardiovaskular_jakarta: {
                        required: "Score Kardiovaskular is required",
                    },
                    tahun_mcu: {
                        required: "Tahun MCU is required",
                    },
                    
                    // 'medical_condition[]': {
                    //     required: "Medical Condition is required",
                    // },
                    fitwork_condition: {
                        required: "Fitwork Condition is required",
                    },
                    hasil_mcu: {
                        required: "Reason MCU is required",
                    }
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
