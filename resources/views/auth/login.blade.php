    <!DOCTYPE html>
<html lang="id">
    <head>
        @include('partials.header')
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
            <div class="card shadow-sm p-4" style="width: 100%; max-width: 350px; border-radius: 12px;">
                
                <h4 class="text-center mb-4">Login</h4>

                <form action="/login" method="POST">
                    @csrf

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" class="form-control mb-3" required>

                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="form-control mb-3" required>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>
                        <a href="/forgot_password" class="text-decoration-none small">Lupa kata sandi?</a>
                        <a href="/register" class="text-decoration-none small" id="register-link">Belum punya akun?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2">Masuk</button>

                </form>

            </div>
        </div>

        @include('partials.footer')
    </body>
</html>