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
                    <a href="#" class="nav-link text-center">
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
                    <a href="/" class="nav-link text-center">
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
                                                        alt="Admin" class="rounded p-1 bg-primary" width="160"
                                                        id="avatar-user">
                                                    <div class="mt-3">
                                                        <h4 id="no-badge">120943232</h4>
                                                        <p class="text-secondary mb-1" id="name">Mahdiawan
                                                            Nurkholifah</p>
                                                    </div>
                                                </div>
                                                <hr class="my-4" />
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">Tempat Lahir</h6>
                                                        <span class="text-secondary"
                                                            id="tempat-lahir">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">Tanggal Lahir</h6>
                                                        <span class="text-secondary"
                                                            id="tanggal-lahir">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">No. HP/WA</h6>
                                                        <span class="text-secondary"
                                                            id="no-wa">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">Nama Istri/Suami</h6>
                                                        <span class="text-secondary"
                                                            id="istri-suami">https://codervent.com</span>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column align-items-start">
                                                        <h6 class="mb-1">No HP Istri/Suami</h6>
                                                        <span class="text-secondary"
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
                                                <div class="list-group">
                                                    <a href="javascript:;" style="cursor: context-menu"
                                                        class="list-group-item list-group-item-action "
                                                        aria-current="true">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h5 class="mb-1">Periode MCU 2021</h5>
                                                            <label class="text-primary" style="cursor: pointer">Download MCU</label>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex flex-column">
                                                            <h6>Riwayat Kesehatan :</h6>
                                                            <p class="mb-1">Donec id elit non mi porta gravida at
                                                                eget metus. Maecenas sed diam eget risus varius blandit.
                                                            </p>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <h6>Riwayat Konsumsi Obat :</h6>
                                                            <p class="mb-1">Donec id elit non mi porta gravida at
                                                                eget metus. Maecenas sed diam eget risus varius blandit.
                                                            </p>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <h6>Score Kardiovaskular Jakarta :</h6>
                                                            <p class="mb-1">Low Riks
                                                            </p>
                                                        </div>
                                                    </a>
                                                </div>
                                                {{-- <div class="accordion" id="accordionPanelsStayOpenExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapseOne"
                                                                aria-expanded="true"
                                                                aria-controls="panelsStayOpen-collapseOne">
                                                                MCU PERIODE 2021
                                                            </button>
                                                        </h2>
                                                        <div id="panelsStayOpen-collapseOne"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="panelsStayOpen-headingOne">
                                                            <div class="accordion-body p-0">
                                                                <div class="list-group border-0">
                                                                    <a href="javascript:;"
                                                                        class="list-group-item list-group-item-action rounded-0"
                                                                        aria-current="true">
                                                                        <div
                                                                            class="d-flex w-100 justify-content-between">
                                                                            <h5 class="mb-1">Riwayat Kesehatan</h5>
                                                                        </div>
                                                                        <p class="mb-1">Donec id elit non mi porta
                                                                            gravida at eget metus. Maecenas sed diam
                                                                            eget risus varius blandit.</p> <small>Donec
                                                                            id elit non mi porta.</small>
                                                                    </a>
                                                                    <a href="javascript:;"
                                                                        class="list-group-item list-group-item-action">
                                                                        <div
                                                                            class="d-flex w-100 justify-content-between">
                                                                            <h5 class="mb-1">Riwayat Konsumsi Obat
                                                                            </h5>
                                                                        </div>
                                                                        <p class="mb-1">Donec id elit non mi porta
                                                                            gravida at eget metus. Maecenas sed diam
                                                                            eget risus varius blandit.</p> <small
                                                                            class="text-muted">Donec id elit non mi
                                                                            porta.</small>
                                                                    </a>
                                                                    <a href="javascript:;"
                                                                        class="list-group-item list-group-item-action rounded-0">
                                                                        <div class="d-flex align-items-center">
                                                                            <div>
                                                                                <p class="mb-0 text-secondary">Score
                                                                                    Kardiovaskuler Jakarta</p>
                                                                                <h4 class="my-1">42</h4>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex align-items-center">
                                                                            <div>
                                                                                <p class="mb-0 text-secondary">File MCU
                                                                                </p>
                                                                                <h4 class="my-1">file.pdf</h4>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapseTwo"
                                                                aria-expanded="false"
                                                                aria-controls="panelsStayOpen-collapseTwo">
                                                                MCU PERIODE 2022
                                                            </button>
                                                        </h2>
                                                        <div id="panelsStayOpen-collapseTwo"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="panelsStayOpen-headingTwo">
                                                            <div class="accordion-body">
                                                                <strong>This is the second item's accordion
                                                                    body.</strong> It is hidden by default, until the
                                                                collapse plugin adds the appropriate classes that we use
                                                                to style each element. These classes control the overall
                                                                appearance, as well as the showing and hiding via CSS
                                                                transitions. You can modify any of this with custom CSS
                                                                or overriding our default variables. It's also worth
                                                                noting that just about any HTML can go within the
                                                                <code>.accordion-body</code>, though the transition does
                                                                limit overflow.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapseThree"
                                                                aria-expanded="false"
                                                                aria-controls="panelsStayOpen-collapseThree">
                                                                MCU PERIODE 2023
                                                            </button>
                                                        </h2>
                                                        <div id="panelsStayOpen-collapseThree"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="panelsStayOpen-headingThree">
                                                            <div class="accordion-body">
                                                                <strong>This is the third item's accordion
                                                                    body.</strong> It is hidden by default, until the
                                                                collapse plugin adds the appropriate classes that we use
                                                                to style each element. These classes control the overall
                                                                appearance, as well as the showing and hiding via CSS
                                                                transitions. You can modify any of this with custom CSS
                                                                or overriding our default variables. It's also worth
                                                                noting that just about any HTML can go within the
                                                                <code>.accordion-body</code>, though the transition does
                                                                limit overflow.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
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
        {{-- <footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer> --}}
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
        const token = localStorage.getItem('token');
        const fetchData = async () => {
            try {
                const response = await axios.get('/api/user', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    // withCredentials: true // Pastikan cookie dikirimkan
                });
                $('#name').text(response.data.nama_karyawan)
                $('#no-badge').text(response.data.no_badge)
                $('#tempat-lahir').text(response.data.tempat_lahir)
                $('#tanggal-lahir').text(response.data.tgl_lahir)
                $('#no-wa').text(response.data.no_hp_wa)
                $('#istri-suami').text(response.data.nama_istri_suami)
                $('#no-wa-istri-suami').text(response.data.no_hp_istri_suami)
                $('#avatar-user').attr('src', response.data.foto ? '{{ asset('storage') }}/' + response.data.foto :
                    'https://dummyimage.com/65x310/000/fff.png&text=foto+user')
                console.log(response.data)
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
        const logOutUser = async () => {
            try {
                // Kirim permintaan logout ke server
                const response = await axios.post('/api/logout', {}, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    withCredentials: true // Pastikan cookie dikirimkan
                });

                // Redirect setelah logout berhasil
                localStorage.removeItem('token');
                window.location.href = "/";
            } catch (error) {
                console.log(error);
            }
        };
        fetchData();
        $(document).ready(function() {
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
