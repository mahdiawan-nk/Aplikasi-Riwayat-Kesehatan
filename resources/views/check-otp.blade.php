<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('') }}assets/images/favicon-32x32.png" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('') }}assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('') }}assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('') }}assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('') }}assets/css/app.css" rel="stylesheet">
    <link href="{{ asset('') }}assets/css/icons.css" rel="stylesheet">
    <title>Verifikasi Login OTP</title>
    <style>
        .bg-login {
            background-image: url('{{ asset('assets/images/login-images/bg-login-img.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .otp-input {
            text-align: center;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            margin: 0 5px;
            border-radius: 0.375rem;
        }

        .otp-input:focus {
            box-shadow: none;
            border-color: #0056b3;
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
                            <img src="{{ asset('') }}assets/images/logo-1.png" width="180" alt="" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Verifikasi OTP</h3>
                                    </div>
                                    <div class="form-body">
                                        <div class="alert alert-primary" role="alert">
                                            <p class="mb-0">Silakan periksa email Anda untuk menemukan kode OTP yang
                                                telah kami kirimkan. Masukkan kode tersebut pada halaman verifikasi
                                                untuk melanjutkan proses. Harap diperhatikan bahwa kode OTP berlaku
                                                selama 5 menit. Jika Anda tidak menerima email, periksa folder spam atau
                                                coba kirim ulang permintaan kode OTP</p>
                                        </div>
                                        <form class="row g-3" method="POST" action="/auth-login" id="form-login">
                                            @csrf

                                            <div class="d-flex justify-content-center mb-3">
                                                <input type="text" class="form-control otp-input" maxlength="1"
                                                    id="otp1" required>
                                                <input type="text" class="form-control otp-input" maxlength="1"
                                                    id="otp2" required>
                                                <input type="text" class="form-control otp-input" maxlength="1"
                                                    id="otp3" required>
                                                <input type="text" class="form-control otp-input" maxlength="1"
                                                    id="otp4" required>
                                                <input type="text" class="form-control otp-input" maxlength="1"
                                                    id="otp5" required>
                                                <input type="text" class="form-control otp-input" maxlength="1"
                                                    id="otp6" required>
                                            </div>
                                            <div class="col-12 text-center">
                                                <a href="#" onclick="resendOtp()">Resend OTP</a>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary" id="btn-submit"><i
                                                            class="fa-solid fa-lock-open"
                                                            style="font-size:1em"></i>Verifikasi</button>
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
    <script src="{{ asset('') }}assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto-focus on the next input when a digit is entered
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            // Move focus back if deleted
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && e.target.value.length === 0) {
                    inputs[index - 1].focus();
                }
            });
        });
        const roles = '{{ session('role') }}';
        const url = "{{ url('/api/login') }}";
        const headers = {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json',
            }
        }

        let UsersOtp = {
            otp: null
        }

        const resendOtp = async () => {
            try {
                const response = await axios.get('/resend-otp', headers);
                Swal.fire({
                    title: "Token sent",
                    text: response.data.message,
                    icon: "success"
                });
            } catch (error) {
                console.log(error)

            }
        }

        const validateOtp = async () => {

            let btnSubmit = $('#btn-submit');

            btnSubmit.attr('disabled', true);
            btnSubmit.html('<i class="fa-solid fa-circle-notch fa-spin"></i> Loading...');
            try {
                // Mengambil gambar CAPTCHA dari server
                const response = await axios.post('/verify-otp', UsersOtp, headers);
                if (roles == 'admin') {
                    window.location.href = '/panel-admin/dashboard';

                }

                if (roles == 'karyawan') {
                    window.location.href = '/mcu-user';

                }
                console.log(response.data)
            } catch (error) {
                console.log(error)
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.message;
                    let errorMessage = "";

                    Swal.fire({
                        title: "Gagal Verifikasi",
                        text: errors,
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
            // captchaFetch();
            $('form#form-login').submit(async function(e) {
                e.preventDefault();
                UsersOtp.otp = document.getElementById('otp1').value + document.getElementById('otp2')
                    .value + document
                    .getElementById('otp3').value + document.getElementById('otp4').value + document
                    .getElementById(
                        'otp5').value + document.getElementById('otp6').value

                validateOtp();
            })
        }

        init();
    </script>
</body>

</html>
