<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
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
        .captcha-box {
            border-radius: 5px;
            border: 1px solid;
            padding: 1rem;
        }

        #canvas {
            width: 200px;
            height: 60px;
        }
    </style>
</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="assets/images/logo-1.png" width="180" alt="" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Sign in</h3>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" method="POST" action="/auth-login" id="form-login">
                                            @csrf
                                            <div class="col-12">
                                                <label for="username" class="form-label">No Badge</label>
                                                <input type="text" class="form-control" id="username"
                                                    name="pengguna-login" placeholder="Username" required>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter
                                                    Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="inputChoosePassword" value="" name="password-login"
                                                        placeholder="Enter Password"> <a href="javascript:;"
                                                        class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary" id="btn-submit"><i
                                                            class="bx bxs-lock-open"></i>Sign in</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->

    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const url = "{{ url('/api/login-user') }}";
        const headers = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json',
            }
        }
        let Users = {
            no_badge: '',
            password: '',
            type: 'karyawan',
            _token: "{{ csrf_token() }}"
        }
        const fetchLogin = async () => {
            let btnSubmit = $('#btn-submit');

            btnSubmit.attr('disabled', true);
            btnSubmit.html('<i class="fa-solid fa-circle-notch fa-spin"></i> Loading...');
            try {
                const response = await axios.post('/auth-user/login', Users, headers);
                console.log(response.data.data.token)
                window.location.href = '/auth-user/verifikasi-otp';
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    const errors = error.response.data.errors;
                    let errorMessage = "";

                    Swal.fire({
                        title: "unauthorized",
                        text: 'Gagal Login, silahkan coba kembali',
                        icon: "error"
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: error.response.data.errors,
                        icon: "error"
                    });
                }
                btnSubmit.attr('disabled', false);
                btnSubmit.html('<i lass="fa-solid fa-lock-open" style="font-size:1em"></i>Sign in');
                console.error(error);
            }
        }

        const init = () => {

            $('form#form-login').submit(async function(e) {
                e.preventDefault();
                Users.no_badge = $('input#username').val();
                Users.password = $('input#inputChoosePassword').val();
                fetchLogin();
            });
        }

        init();

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
</body>

</html>
