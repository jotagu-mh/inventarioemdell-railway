<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Fondo degradado cálido: rojo → naranja → amarillo */
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffb347 0%, #ffb347 50%, #ffb347 100%);
            display: flex;
            align-items: center;
        }

        /* Caja semi-transparente */
        .card-transparent {
            background-color: rgba(255, 255, 255, 0.85);
        }

        /* Inputs con sombra y borde suave */
        .form-control {
            border-radius: 0.75rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            box-shadow: 0 2px 8px rgba(255, 94, 58, 0.5);
            border-color: #ff4e50;
        }

        /* Botón moderno */
        .btn-login {
            border-radius: 0.75rem;
            box-shadow: 0 4px 8px rgba(255, 94, 58, 0.3);
            transition: all 0.2s ease-in-out;
            background: linear-gradient(45deg, #ff4e50, #ffb347);
            border: none;
        }

        .btn-login:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(255, 94, 58, 0.4);
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <!-- Logo -->
                <div class="text-center mb-4">
                    <a href="/">
                        <img src="{{ asset('img/logo_emdell_2.png') }}" alt="Mi Logo" class="img-fluid" style="max-width:430px;">
                    </a>
                </div>

                <!-- Caja del formulario -->
                <div class="card card-transparent shadow-lg rounded-4 border-0">
                    <div class="card-body p-4">

                        <!-- Mensaje de sesión -->
                        <x-auth-session-status class="mb-3 text-success" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Remember Me y Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        Recuérdame
                                    </label>
                                </div>
                            </div>

                            <!-- Botón de login -->
                            <button type="submit" class="btn btn-login w-100 py-2">
                                Iniciar sesión
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

