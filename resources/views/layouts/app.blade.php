<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIDCOM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- En la sección <head> de layouts/app.blade.php, después de otros CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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

        /* Navbar carmesí oscuro */
        .navbar {
            background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%) !important;
            box-shadow: 0 4px 20px rgba(139, 0, 0, 0.3);
            padding: 0.5rem 0;
            border-bottom: 3px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #fff !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand:hover {
            color: #FFD700 !important;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        .nav-link {
            padding: 0.8rem 1rem;
            margin: 0 3px;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%) !important;
            color: #8B0000 !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-link.active:hover {
            background: linear-gradient(135deg, #FFED4E 0%, #FFB347 100%) !important;
        }

        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.4rem 0.7rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
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
            box-shadow: 0 4px 20px rgba(139, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .card-header {
            border-bottom: 2px solid rgba(139, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.2rem 1.5rem;
            color: #8B0000;
            font-weight: 600;
        }

        /* Alertas mejoradas */
        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(139, 0, 0, 0.1);
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

        /* Información del sistema en navbar */
        .navbar-text {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            border-left: 2px solid rgba(255, 215, 0, 0.5);
        }

        /* Acciones rápidas */
        .quick-actions {
            border-top: 1px solid rgba(139, 0, 0, 0.1);
            background-color: rgba(139, 0, 0, 0.02);
            padding: 1.5rem 0;
            margin-top: auto;
        }

        .quick-actions .card {
            transition: all 0.3s ease;
            height: 100%;
        }

        .quick-actions .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(139, 0, 0, 0.15) !important;
        }

        .quick-actions .icon-container {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        /* Información del sistema en footer */
        .system-info {
            background-color: rgba(139, 0, 0, 0.03);
            border-top: 1px solid rgba(139, 0, 0, 0.1);
            padding: 1rem 0;
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
                padding: 0.7rem 1rem;
                margin: 2px 0;
                text-align: center;
            }

            .navbar-collapse {
                background-color: rgba(107, 12, 12, 0.95);
                padding: 1rem;
                border-radius: 0 0 10px 10px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
                margin-top: 0.5rem;
            }

            .quick-actions {
                padding: 1rem 0;
            }
        }

        /* Efecto de brillo sutil en hover para navbar-brand */
        .navbar-brand i {
            transition: all 0.3s ease;
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.3);
        }

        .navbar-brand:hover i {
            text-shadow: 0 0 12px rgba(255, 215, 0, 0.6);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Navbar Carmesí -->
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
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
                            <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}"
                            href="{{ route('dashboard.index') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('operadores.index') ? 'active' : '' }}"
                               href="{{ route('operadores.index') }}">
                                <i class="bi bi-people-fill me-1"></i> Operadores Mineros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('estadisticas.*') ? 'active' : '' }}"
                            href="{{ route('estadisticas.index') }}">
                                <i class="bi bi-bar-chart me-1"></i> Estadísticas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('actualizacion-operadors.*') ? 'active' : '' }}"
                               href="{{ route('actualizacion-operadors.index') }}">
                                <i class="bi bi-list-check me-1"></i> Actualizaciones
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bloqueo-operadors.*') ? 'active' : '' }}"
                            href="{{ route('bloqueo-operadors.index') }}">
                                <i class="bi bi-shield-lock me-1"></i> Bloqueo Operadores
                            </a>
                        </li>
                    </ul>

                    <!-- Información del sistema -->
                    <div class="navbar-text">
                        <small class="text-light">
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

        <!-- Acciones rápidas (opcional, se puede mostrar o no) -->

        <section class="quick-actions">
            <div class="container-fluid">
                <h5 class="mb-3" style="color: #8B0000;">
                    <i class="bi bi-lightning me-2"></i>Acceso Rápido
                </h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('dashboard.index') }}" class="card text-decoration-none">
                            <div class="card-body text-center p-3">
                                <div class="icon-container" style="background-color: rgba(139, 0, 0, 0.1);">
                                    <i class="bi bi-speedometer2 fs-3" style="color: #8B0000;"></i>
                                </div>
                                <h6 class="mb-1" style="color: #8B0000;">Dashboard</h6>
                                <p class="text-muted mb-0 small">Panel principal</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('estadisticas.index') }}" class="card text-decoration-none">
                            <div class="card-body text-center p-3">
                                <div class="icon-container" style="background-color: rgba(40, 167, 69, 0.1);">
                                    <i class="bi bi-bar-chart fs-3" style="color: #28a745;"></i>
                                </div>
                                <h6 class="mb-1" style="color: #8B0000;">Estadísticas</h6>
                                <p class="text-muted mb-0 small">Consultas personalizadas</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('operadores.index') }}" class="card text-decoration-none">
                            <div class="card-body text-center p-3">
                                <div class="icon-container" style="background-color: rgba(0, 123, 255, 0.1);">
                                    <i class="bi bi-people-fill fs-3" style="color: #007bff;"></i>
                                </div>
                                <h6 class="mb-1" style="color: #8B0000;">Operadores</h6>
                                <p class="text-muted mb-0 small">Gestión de operadores</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('actualizacion-operadors.index') }}" class="card text-decoration-none">
                            <div class="card-body text-center p-3">
                                <div class="icon-container" style="background-color: rgba(255, 193, 7, 0.1);">
                                    <i class="bi bi-list-check fs-3" style="color: #ffc107;"></i>
                                </div>
                                <h6 class="mb-1" style="color: #8B0000;">Actualizaciones</h6>
                                <p class="text-muted mb-0 small">Registro y seguimiento</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Información del sistema -->
        <footer class="system-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Sistema SIDCOM |
                            <i class="bi bi-clock ms-2 me-1"></i>Actualizado: {{ now()->format('d/m/Y H:i:s') }} |
                            <i class="bi bi-cpu ms-2 me-1"></i>Versión 1.0.0
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Al final del body de layouts/app.blade.php, antes de @stack('scripts') -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
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
                    $(this).addClass('active');
                }
            });
        });

        // Efecto hover para acciones rápidas
        $(document).ready(function() {
            $('.quick-actions .card').hover(
                function() {
                    $(this).css({
                        'transform': 'translateY(-5px)',
                        'box-shadow': '0 10px 25px rgba(139, 0, 0, 0.15)'
                    });
                },
                function() {
                    $(this).css({
                        'transform': 'translateY(0)',
                        'box-shadow': '0 4px 20px rgba(139, 0, 0, 0.08)'
                    });
                }
            );
        });
    </script>

    @stack('scripts')
</body>
</html>
