<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Inventaris Truk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            /* IVECO Eurocargo truck photo by High Contrast, CC BY 3.0 DE, Wikimedia Commons. */
            background:
                linear-gradient(135deg, rgba(29, 78, 216, .96), rgba(15, 23, 42, .94)),
                url("https://upload.wikimedia.org/wikipedia/commons/4/41/IVECO_Eurocargo_truck.jpg") center/cover;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            min-height: 100vh;
        }

        .login-card {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 24px 80px rgba(15, 23, 42, .34);
        }

        .system-badge {
            align-items: center;
            background: #1d4ed8;
            border-radius: 8px;
            color: #fff;
            display: inline-flex;
            height: 52px;
            justify-content: center;
            width: 52px;
        }

        .form-control,
        .btn {
            border-radius: 8px;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card login-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <span class="system-badge mb-3"><i class="bi bi-truck-front fs-3"></i></span>
                            <h1 class="h4 fw-bold mb-2">Sistem Informasi Inventaris Suku Cadang Truk</h1>
                            <p class="text-secondary mb-0">PT. Chakra Jawara Kabupaten Banjar</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
                            @csrf
                            <div>
                                <label for="email" class="form-label fw-semibold">Email / Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="admin@gudang.com" required autofocus>
                                </div>
                            </div>
                            <div>
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="password" required>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-lg w-100" type="submit">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </form>

                        <div class="bg-light border rounded-3 p-3 mt-4 small">
                            <div class="fw-semibold mb-2">Akun Dummy</div>
                            <div>Admin Gudang: admin@gudang.com / password</div>
                            <div>Pimpinan: pimpinan@perusahaan.com / password</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
