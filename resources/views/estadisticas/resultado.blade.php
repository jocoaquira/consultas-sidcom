<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-pie-chart me-2"></i>Resultados de la Consulta
            <span class="float-end">
                <small>{{ $fechaInicio }} - {{ $fechaFin }}</small>
            </span>
        </h5>
    </div>
    <div class="card-body">
        <!-- Resumen principal -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h2 class="display-3 text-primary">{{ $total }}</h2>
                        <h4 class="text-muted mb-3">
                            @if($tipo === 'I')
                                <i class="bi bi-house-door me-2"></i>Comercio Interno (I)
                            @elseif($tipo === 'E')
                                <i class="bi bi-globe me-2"></i>Comercio Externo (E)
                            @else
                                <i class="bi bi-collection me-2"></i>Todos los Tipos de Comercio
                            @endif
                        </h4>
                        <div class="mb-3">
                            @foreach($estados as $estado)
                                @if(isset($estadosNombres[$estado]))
                                <span class="badge bg-{{ $estado == '1' ? 'success' : ($estado == '2' ? 'warning' : ($estado == '3' ? 'info' : 'danger')) }} fs-6 me-2">
                                    {{ $estadosNombres[$estado] }} ({{ $estado }})
                                </span>
                                @endif
                            @endforeach
                        </div>
                        <p class="lead mb-0">
                            <i class="bi bi-calendar-range me-1"></i>
                            Período: <strong>{{ $fechaInicio }}</strong> al <strong>{{ $fechaFin }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="display-1 text-muted opacity-25">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h5 class="mt-3">Consulta Finalizada</h5>
                        <p class="text-muted mb-0">{{ now()->format('d/m/Y H:i') }}</p>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i>Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribución por estado -->
        @if($porEstado->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-pie-chart-fill me-2"></i>Distribución por Estado
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($porEstado as $estadoCodigo => $datosEstado)
                                @php
                                    $estadoNombre = $estadosNombres[$estadoCodigo] ?? "Estado {$estadoCodigo}";
                                    $color = match($estadoCodigo) {
                                        '1' => 'success',
                                        '2' => 'warning',
                                        '3' => 'info',
                                        '0' => 'danger',
                                        default => 'secondary'
                                    };
                                    $icono = match($estadoCodigo) {
                                        '1' => 'check-circle',
                                        '2' => 'clock-history',
                                        '3' => 'question-circle',
                                        '0' => 'x-circle',
                                        default => 'circle'
                                    };
                                    $porcentaje = $total > 0 ? round(($datosEstado->total / $total) * 100, 1) : 0;
                                @endphp
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border-{{ $color }}">
                                        <div class="card-body text-center py-4">
                                            <div class="mb-2">
                                                <i class="bi bi-{{ $icono }} fs-1 text-{{ $color }}"></i>
                                            </div>
                                            <h3 class="text-{{ $color }} mb-1">{{ $datosEstado->total }}</h3>
                                            <h6 class="text-muted mb-2">{{ $estadoNombre }}</h6>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $color }}"
                                                     style="width: {{ $porcentaje }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $porcentaje }}%</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Distribución por tipo (si se seleccionó "Todos") -->
        @if($tipo === 'T' && $porTipo && $porTipo->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>Distribución por Tipo de Comercio
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($porTipo as $tipoComercio)
                                @php
                                    $tipoNombre = $tiposNombres[$tipoComercio->tipo_form_comercio] ?? $tipoComercio->tipo_form_comercio;
                                    $color = $tipoComercio->tipo_form_comercio === 'I' ? 'primary' : 'info';
                                    $icono = $tipoComercio->tipo_form_comercio === 'I' ? 'house-door' : 'globe';
                                    $porcentaje = $total > 0 ? round(($tipoComercio->total / $total) * 100, 1) : 0;
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="card border-{{ $color }}">
                                        <div class="card-body text-center py-4">
                                            <div class="mb-2">
                                                <i class="bi bi-{{ $icono }} fs-1 text-{{ $color }}"></i>
                                            </div>
                                            <h2 class="text-{{ $color }}">{{ $tipoComercio->total }}</h2>
                                            <h5 class="text-muted">Comercio {{ $tipoNombre }}</h5>
                                            <div class="progress" style="height: 15px;">
                                                <div class="progress-bar bg-{{ $color }}"
                                                     style="width: {{ $porcentaje }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $porcentaje }}% del total</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabla de distribución por día -->
        @if($porDia->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-date me-2"></i>Distribución por Día
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                        <th>Gráfico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($porDia as $dia)
                                    @php
                                        $porcentaje = $total > 0 ? round(($dia->total / $total) * 100, 1) : 0;
                                        $fechaFormateada = \Carbon\Carbon::parse($dia->fecha)->format('d/m/Y');
                                    @endphp
                                    <tr>
                                        <td>{{ $fechaFormateada }}</td>
                                        <td><strong>{{ $dia->total }}</strong></td>
                                        <td>{{ $porcentaje }}%</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-primary"
                                                         style="width: {{ $porcentaje }}%"
                                                         title="{{ $dia->total }} formularios ({{ $porcentaje }}%)">
                                                    </div>
                                                </div>
                                                <small class="ms-2 text-muted">{{ $dia->total }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th>TOTAL</th>
                                        <th>{{ $total }}</th>
                                        <th>100%</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Botones de acción -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('estadisticas.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Nueva Consulta
                    </a>
                    <div>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-speedometer2 me-1"></i>Volver al Dashboard
                        </a>
                        <button class="btn btn-primary ms-2" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>Imprimir Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
