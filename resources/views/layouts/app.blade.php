<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Inventario - EMDELL</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #c40000 0%, #8b0000 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-header h4 {
            color: #ffffff;
            margin: 10px 0 0 0;
            font-weight: bold;
        }

        .sidebar-header small {
            color: #f4d000;
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo img {
            width: 180px;
            height: auto;
        }

        /* ================= MENU ================= */
        .sidebar .nav {
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: rgba(244, 208, 0, 0.25);
            color: #ffffff;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: #f4d000;
            color: #1f1f1f;
            font-weight: bold;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            color: #f4d000;
        }

        /* ================= FOOTER SIDEBAR ================= */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .user-info-sidebar {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85em;
            text-align: center;
            margin-bottom: 15px;
        }

        .user-info-sidebar i {
            font-size: 2em;
            display: block;
            margin-bottom: 8px;
            color: #f4d000;
        }

        /* ================= CONTENIDO ================= */
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .top-bar {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        /* ================= CARDS ================= */
        .stats-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stats-card.red .icon {
            background: linear-gradient(135deg, #c40000 0%, #8b0000 100%);
        }

        .stats-card.yellow .icon {
            background: linear-gradient(135deg, #f4d000 0%, #f1c40f 100%);
            color: #1f1f1f;
        }

        .stats-card.dark .icon {
            background: linear-gradient(135deg, #2c2c2c 0%, #000000 100%);
        }

        .stats-card h3 {
            font-size: 2em;
            font-weight: bold;
            margin: 10px 0;
        }

        .stats-card p {
            color: #777;
            margin: 0;
        }

        /* ================= LOADING ================= */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #c40000 0%, #8b0000 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loading-screen.hide {
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid #f4d000;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: #ffffff;
            font-size: 1.5em;
            margin-top: 30px;
            font-weight: bold;
        }

        .loading-subtext {
            color: #f4d000;
            margin-top: 10px;
        }

        /* ================= ANIMACIONES ================= */
        .welcome-animation {
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</head>

<body>


    <div class="loading-screen" id="loadingScreen">
        <div class="loader"></div>
        <div class="loading-text">EMDELL</div>
    </div>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="{{ asset('img/logo_emdell.png') }}" alt="Logo EMDELL">
            </div>
            <h4><small style="color: rgb(255, 255, 255);">Inventario de Bienes de Consumo</small></h4>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('materiales.*') ? 'active' : '' }}"
                    href="{{ route('materiales.index') }}">
                    <i class="fas fa-box"></i> Materiales
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('movimientos.*') ? 'active' : '' }}"
                    href="{{ route('movimientos.index') }}">
                    <i class="fas fa-exchange-alt"></i> Movimientos
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#categoriasMenu" role="button">
                    <i class="fas fa-th-large"></i> Categorías
                    <i class="fas fa-chevron-down float-end" style="font-size: 0.8em; margin-top: 4px;"></i>
                </a>
                <div class="collapse" id="categoriasMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link py-2" href="{{ route('categorias.subcategorias', 1) }}">
                                <i class="fas fa-briefcase"></i> Administración
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-2" href="{{ route('categorias.subcategorias', 2) }}">
                                <i class="fas fa-wrench"></i> Técnica
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
                    href="{{ route('usuarios.index') }}">
                    <i class="fas fa-users"></i> Usuarios
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                    href="{{ route('roles.index') }}">
                    <i class="fas fa-user-tag"></i> Roles
                </a>
            </li>
        </ul>

        <!-- Footer del Sidebar con info de usuario -->
        <div class="sidebar-footer">
            <div class="user-info-sidebar">
                <i class="fas fa-user-circle"></i>
                <div><strong>{{ Auth::user()->name }}</strong></div>
                <div class="text-white-50" style="font-size: 0.9em;">{{ Auth::user()->email }}</div>
            </div>

            <!-- Botón de Cerrar Sesión dentro del footer -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-light btn-sm w-100 mt-2">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="top-bar d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">@yield('page-title', 'Dashboard')</h2>
                <small class="text-muted">@yield('page-subtitle', 'Bienvenido al sistema')</small>
            </div>
            <div>
                <span class="text-muted">
                    <i class="far fa-calendar"></i>
                    {{ date('d/m/Y') }}
                </span>
            </div>
        </div>

        <!-- Contenido -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>


        // Animación de carga
        window.addEventListener('load', function () {
            setTimeout(function () {
                document.getElementById('loadingScreen').classList.add('hide');

                // Agregar animación al contenido
                document.querySelector('.main-content').classList.add('welcome-animation');
            }, 500); // Duración de 1.5 segundos
        });

        // Mensajes de éxito
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // Mensajes de error
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        @endif

        // Confirmación para eliminar
        function confirmarEliminacion(event) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    @yield('scripts')

    @yield('scripts')
</body>

</html>