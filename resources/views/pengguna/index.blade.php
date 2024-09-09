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
                <div class="breadcrumb-title pe-3">Pengaturan</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Managemen User</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group" id="list-action-btn">
                        <button type="button" class="btn btn-primary btn-sm" id="tambah-data-karyawan" onclick="tambahDataUser()"> <i
                                class="bx bx-plus"></i>Tambah User</button>
                        <button type="button" class="btn btn-secondary btn-sm" hidden id="import-data-karyawan"> <i
                                class="bx bx-import"></i>Import Data Karyawan</button>
                        <button type="button" class="btn btn-dark btn-sm" id="refreshButton"> <i
                                class="bx bx-refresh"></i>Refresh</button>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr />
            <div class="row">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-group">
                                <a href="{{ url('/panel-admin/managemen-user') }}" class="list-group-item list-group-item-action active" aria-current="true">Pengguna</a>
                                <a href="{{ url('/panel-admin/group-user') }}" class="list-group-item list-group-item-action " aria-current="true">Group</a>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="card" id="page-list-data">
                        <div class="card-body">
                            @include('components.datacontrols')
                            <div class="table-responsive">
                                <table class="table align-middle" style="width:100%" id="tb-data">
                                    <thead class="table-info">
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
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
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
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
                    
                </div>
            </div>
            @include('pengguna.form')
        </div>
    </div>
    <!--end page wrapper -->
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('') }}assets/lib/DataTable.js"></script>
    <script src="{{ asset('') }}assets/lib/FormValidate.js"></script>


    <script>
        // const noop = () => {};
        // console.log = noop;
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
        const BaseUrlApi = "{{ url('panel-admin/managemen-user/users') }}";

        let modeForm = 'create';
        let uidData = null;
        let DataUsers = {
            name: null,
            email:null,
            password: null,
            phone: null,
            roles: null,
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
                    key: 'name',
                    label: 'Username'
                },
                {
                    key: 'email',
                    label: 'email'
                },
                {
                    key: 'phone',
                    label: 'phone',
                    render: (value, item) => `${value?value:'-'}`
                },
                {
                    key: 'roles',
                    label: 'roles',
                    render: (value, item) => `${value[0].name}`
                },

                {
                    key: 'id',
                    label: 'Action',
                    render: (value, item) => {
                        return `<div class="d-flex order-actions">
                        <a href="javascript:;" onclick="editDataUsers(${item.id})"><i class="bx bx-edit-alt"></i></a>
                        <a href="javascript:;" class="ms-4 bg-danger text-white" onclick="deleteDataUsers(${item.id})" ${item.roles[0].id == 1 ? 'hidden':''}><i class="bx bx-trash-alt"></i></a>
                    </div>`
                    }
                }

            ],
            limit: 10,
            useAxios: false
        });

        const insertDataUsers = async () => {
            DataUsers.name = $('#name').val();
            DataUsers.email = $('#email').val();
            DataUsers.password = $('#password').val();
            DataUsers.phone = $('#phone').val();    
            DataUsers.roles = $('#roles').val();
            try {
                const response = await axios.post(BaseUrlApi, DataUsers, headers);
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Success " + response.data.message,
                    text: "Data Users berhasil disimpan.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500
                });
                $('#modal-form').modal('hide')
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

        const updateDataUsers = async () => {
            DataUsers.name = $('#name').val();
            DataUsers.email = $('#email').val();
            DataUsers.password = $('#password').val();
            DataUsers.phone = $('#phone').val();    
            DataUsers.roles = $('#roles').val();
            DataUsers._method = 'PUT';
            headers.headers.Authorization = `Bearer ${token}`
            headers.headers.Accept = 'application/json';
            console.log(uidData)
            try {
                const response = await axios.post(BaseUrlApi + '/' + uidData, DataUsers, headers);
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Success " + response.data.message,
                    text: "Data Karyawan berhasil disimpan.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2500
                });
                $('#modal-form').modal('hide')
                console.log(response.data)
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

        const tambahDataUser = () => {
            modeForm = 'create';
            uidData = null;
            $('#form-data')[0].reset()
            $('#form-data .invalid-feedback').remove()
            $('input,select').removeClass('is-invalid')
            listRoles();
            $('#notes-password').text('Notes :Jika password Kosong, Password default (123456)')
            $('#modal-form').modal('show')
        }

        const editDataUsers = async (id) => {
            $('#form-data .invalid-feedback').remove()
            $('input,select').removeClass('is-invalid')
            try {
                const response = await axios.get(BaseUrlApi + '/' + id, headers);
                const data = response.data.data.data;
                uidData = data.id;
                
                $('#name').val(data.name)
                $('#email').val(data.email)
                $('#password').val(data.password)
                $('#phone').val(data.phone)
                $('#notes-password').text('Notes : Bila password tidak diubah, biarkan kosong')


                listRoles(data.roles[0].id);
                modeForm = 'update';
                
                $('#modal-form').modal('show')
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

        const deleteDataUsers = async (id) => {
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
        const listRoles = async (data='') => {
            headers.params = {
                'all':true
            }
            try {
                const response = await axios.get('{{ url('/panel-admin/group-user/roles') }}', headers);
                const roles = response.data.data.data;
                let listOptions = $('#roles')
                listOptions.empty();

                listOptions.append('<option value="">Pilih Role</option>');
                roles.forEach(role => {
                    listOptions.append('<option value="' + role.id + '" ' + (data == role.id ? 'selected' : '') + '>' + role.name + '</option>');
                });
            } catch (error) {
                console.log(error)
            }
        }
        const init = () => {
            listRoles();
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
                    roles: {
                        required: true,
                    },
                    name: {
                        required: true,
                        minlength: 1
                    },
                    email: {
                        required: true,
                        email: true // Aturan validasi email
                    },
                    phone: {
                        required: true,
                        digits: true
                    },
                },
                messages: {
                    roles: {
                        required: "Roles is required",
                        // minlength: "Please enter a valid No Badge"
                    },
                    name: {
                        required: "Nama is required",
                        // minlength: "Please enter a valid Nama Karyawan"
                    },
                    phone: {
                        required: "Phone is required",
                        digits: "Please enter a valid Phone No"
                    },
                    email: {
                        required: "Password is required",
                        email: "Please enter a valid email address"
                    },
                },
                errorClass: 'is-invalid',
                submitHandler: function(form) {
                    // form.submit(); // Handle form submission
                    if (modeForm == 'create') {
                        insertDataUsers();
                    } else {
                        updateDataUsers();
                    }
                }
            });

            validator.init();

        }

        init();
    </script>
@endsection
