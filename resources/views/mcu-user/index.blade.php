<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <title>Dashtreme - Multipurpose Bootstrap5 Admin Template</title>
    <style>
        .loading-text {
            position: relative;
            overflow: hidden;
            color: #ccc;
        }

        .loading-text::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.1) 25%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.1) 75%);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .section-authentication-signin {
            height: 100%;
            margin-top: 5rem;
            margin-bottom: 3rem;
        }

        @media screen and (max-width: 991px) {
            .section-authentication-signin {
                height: 100%;
                margin-top: 5rem;
                margin-bottom: 3rem;
            }
        }
    </style>
</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <header class="login-header shadow">
            <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        {{-- <img src="assets/images/logo-1.png" width="70" alt="" /> --}}
                        <h3>{{ config('app.name') }}</h3>
                    </a>
                    <button class="navbar-toggler d-none d-sm-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1"
                        aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item"> <a class="nav-link active" aria-current="page" href="#"><i
                                        class='bx bx-home-alt me-1'></i>Home</a>
                            </li>
                            <li class="nav-item"> <a class="nav-link" href="#" onclick="logOutUser()"><i
                                        class='bx bx-user me-1'></i>Keluar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <nav class="navbar navbar-dark bg-primary navbar-expand fixed-bottom d-md-none d-lg-none d-xl-none p-0">
            <ul class="navbar-nav nav-justified w-100">
                <li class="nav-item">
                    <a href="/mcu-user" class="nav-link text-center">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-house" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                            <path fill-rule="evenodd"
                                d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                        </svg>
                        <span class="small d-block">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link text-center" onclick="logOutUser()">
                        <svg width="1.5em" height="1.5em" fill="currentColor" class="bi bi-box-arrow-in-right"
                            viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
                            <path fill-rule="evenodd"
                                d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                        </svg>
                        <span class="small d-block">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="section-authentication-signin d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    <img src="https://dummyimage.com/160x215/000/fff.png&text=foto+user"
                                                        alt="Admin" class="rounded p-1 bg-primary " width="160"
                                                        id="avatar-user">
                                                    <div class="mt-3 ">
                                                        <h4 id="no-badge" class="loading-text text-white">120943232</h4>
                                                        <p class="text-secondary mb-1 loading-text text-white" id="name">Mahdiawan
                                                            Nurkholifah</p>
                                                    </div>
                                                </div>
                                                <hr class="my-4" />
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">Tempat Lahir</h6>
                                                        <span class="text-secondary loading-text text-white w-100"
                                                            id="tempat-lahir">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">Tanggal Lahir</h6>
                                                        <span class="text-secondary loading-text text-white w-100"
                                                            id="tanggal-lahir">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">No. HP/WA</h6>
                                                        <span class="text-secondary loading-text text-white w-100"
                                                            id="no-wa">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">Nama Istri/Suami</h6>
                                                        <span class="text-secondary loading-text text-white w-100"
                                                            id="istri-suami">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">No HP Istri/Suami</h6>
                                                        <span class="text-secondary loading-text text-white w-100"
                                                            id="no-wa-istri-suami">https://codervent.com</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-xl-8 col-lg-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="d-flex align-items-center mb-3">Riwayat Kesehatan MCU</h5>
                                                <div class="list-group" id="list-mcu-user">
                                                    <a href="javascript:;"
                                                        class="list-group-item list-group-item-action loading-text">
                                                        <div class="d-flex w-100 justify-content-between ">
                                                            <h5 class="mb-1 text-white">Periode MCU 2021</h5>
                                                            <label class="text-primary text-white"
                                                                style="cursor: pointer">Download MCU</label>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-white">Riwayat Kesehatan :</h6>
                                                            <p class="mb-1 text-white">adfsad</p>
                                                        </div>
                                                        <div class="d-flex flex-column mt-2">
                                                            <h6 class="text-white">Riwayat Konsumsi Obat :</h6>
                                                            <p class="mb-1 text-white"> dfads</p>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-white">Score Kardiovaskular Jakarta :</h6>
                                                            <p class="mb-1 text-white">adf a</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
        <footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom d-none d-xl-block">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Password show & hide js -->
    <script>
        const fetchData = async () => {
            try {
                const response = await axios.get('/auth-user/me', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    // withCredentials: true // Pastikan cookie dikirimkan
                });
                const data = response.data.data.data
                $('#name').text(
                    data.nama_karyawan).removeClass('loading-text text-white')
                $('#no-badge').text(
                    data.no_badge).removeClass('loading-text text-white')
                $('#tempat-lahir').text(
                    data.tempat_lahir).removeClass('loading-text text-white')
                $('#tanggal-lahir').text(
                    data.tgl_lahir).removeClass('loading-text text-white')
                $('#no-wa').text(
                    data.no_hp_wa).removeClass('loading-text text-white')
                $('#istri-suami').text(
                    data.nama_istri_suami).removeClass('loading-text text-white')
                $('#no-wa-istri-suami').text(
                    data.no_hp_istri_suami).removeClass('loading-text text-white')
                $('#avatar-user').attr('src', 
                data.foto ? '{{ asset('storage') }}/' + 
                data.foto :
                    'https://dummyimage.com/160x215/000/fff.png&text=foto+user')
                fetchDataMCu(
                    data.id)
            } catch (error) {
                console.log(error)
                Swal.fire({
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    icon: "error",
                    title: "Session Expired",
                    text: "Your session has expired. Please log in again.",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        logOutUser();
                    }
                });

            }
        };
        const fetchDataMCu = async (id) => {
            try {
                const response = await axios.get('/mcu-user/' + id, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    // withCredentials: true // Pastikan cookie dikirimkan
                });
                const data = response.data.data;
                const elmView = $('#list-mcu-user');
                elmView.empty();
                if(data.length == 0){
                    elmView.append(`<a href="javascript:;" style="cursor: context-menu"
                                                        class="list-group-item list-group-item-action p-0"
                                                        aria-current="true">
                                        <div class="alert border-0 border-start border-5 border-info alert-dismissible fade show py-2 mb-0">
                                            <div class="d-flex align-items-center">
                                                <div class="font-35 text-info"><i class="bx bx-info-square"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0 text-info">Info Alerts</h6>
                                                    <div>Belum Memiliki Riwayat MCU</div>
                                                </div>
                                            </div>
                                        </div>
                                                        
                                    </a>`)
                }
                const viewList = data.map((item) => {
                    return `
                        <a href="javascript:;" style="cursor: context-menu"
                                                        class="list-group-item list-group-item-action "
                                                        aria-current="true">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h5 class="mb-1">Periode MCU : ${item.tahun_mcu}</h5>
                                                            
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex flex-column">
                                                            <h6>Riwayat Kesehatan :</h6>
                                                            <p class="mb-1">${item.riwayat_kesehatan}
                                                            </p>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex flex-column">
                                                            <h6>Riwayat Konsumsi Obat :</h6>
                                                            <p class="mb-1">${item.riwayat_konsumsi_obat}
                                                            </p>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex flex-column">
                                                            <h6>Score Kardiovaskular Jakarta :</h6>
                                                            <p class="mb-1">${item.score_kardiovaskular_jakarta}
                                                            </p>
                                                        </div>
                                                    </a>
                    `
                }).join('')
                elmView.append(viewList)
            } catch (error) {
                console.log(error)
            }
        }
        const logOutUser = async () => {
            try {
                // Kirim permintaan logout ke server
                const response = await axios.get('/auth-user/logout', {}, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    withCredentials: true // Pastikan cookie dikirimkan
                });

                // Redirect setelah logout berhasil
                window.location.href = "/";
            } catch (error) {
                console.log(error);
            }
        };
        
        $(document).ready(function() {
            fetchData();
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    <script src="assets/js/app.js"></script>
</body>

</html>
