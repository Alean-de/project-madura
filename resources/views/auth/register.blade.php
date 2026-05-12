<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 350px; border-radius: 12px;">
            
            <h4 class="text-center mb-4">Pendaftaran</h4>

            <form action="" method="POST">
                @csrf

                <input type="text" name="name" placeholder="Nama" class="form-control mb-3" required>

                <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>

                <input type="password" name="password" placeholder="Kata Sandi" class="form-control mb-3" required>

                <input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" class="form-control mb-3" required>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label for="login-link">Sudah memiliki akun?</label>
                    <a href="/login" class="text-decoration-none small" id="login-link">Masuk</a>
                </div>

                <button type="submit" class="btn btn-outline-primary w-100 mt-2">Daftar</button>
            </form>

        </div>
    </div>
</body>
</html>