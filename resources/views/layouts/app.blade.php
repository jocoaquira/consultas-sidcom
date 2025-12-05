<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIDCOM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        /* Ocupa todo el espacio */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Layout completo */
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 0;
        }

        /* Navbar fijo arriba */
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            padding: 0.8rem 1rem;
            margin: 0 2px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        /* Contenedor principal sin límites */
        .container-fluid {
            padding: 20px;
            max-width: 100%;
            margin: 0;
        }

        /* Tarjetas sin margen lateral */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card-header {
            border-bottom: 1px solid rgba(0,0,0,.1);
            background-color: #fff;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.2rem 1.5rem;
        }

        /* Alertas mejoradas */
        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin: 15px 20px;
        }

        /* Tablas responsivas */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        /* Footer opcional */
        .footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 1rem 0;
            margin-top: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }

            .card-header {
                padding: 1rem;
            }

            .nav-link {
                padding: 0.5rem 0.8rem;
                margin: 2px 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-minecart-loaded me-2"></i>SIDCOM
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('operadores.index') ? 'active bg-primary' : '' }}"
                               href="{{ route('operadores.index') }}">
                                <i class="bi bi-people-fill me-1"></i> Operadores Mineros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('estadisticas.*') ? 'active bg-primary' : '' }}"
                            href="{{ route('estadisticas.index') }}">
                                <i class="bi bi-bar-chart me-1"></i> Estadísticas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('actualizacion-operadors.*') ? 'active bg-primary' : '' }}"
                               href="{{ route('actualizacion-operadors.index') }}">
                                <i class="bi bi-list-check me-1"></i> Actualizaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('actualizacion-operadors.create') ? 'active bg-primary' : '' }}"
                               href="{{ route('actualizacion-operadors.create') }}">
                                <i class="bi bi-plus-circle me-1"></i> Nueva Actualización
                            </a>
                        </li>
                        <!-- Puedes agregar más enlaces aquí -->
                        <!--
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-bar-chart me-1"></i> Reportes
                            </a>
                        </li>
                        -->
                    </ul>

                    <!-- Información del sistema (opcional) -->
                    <div class="navbar-text text-light d-none d-lg-block">
                        <small>
                            <i class="bi bi-calendar-check me-1"></i>
                            {{ now()->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Alertas -->
        <div class="alerts-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contenido principal - 100% ancho -->
        <main class="content">
            <div class="container-fluid px-0">
                @yield('content')
            </div>
        </main>

        <!-- Footer opcional -->
        <!--
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col text-center text-muted">
                        <small>SIDCOM &copy; {{ date('Y') }} - Sistema de Consultas Mineras</small>
                    </div>
                </div>
            </div>
        </footer>
        -->
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // DataTables para tablas (opcional)
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']]
            });
        });

        // Highlight enlace activo
        $(document).ready(function() {
            $('.nav-link').each(function() {
                if ($(this).attr('href') === window.location.pathname) {
                    $(this).addClass('active bg-primary');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
