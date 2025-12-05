<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-pie-chart me-2"></i>Resultados de la Consulta Personalizada
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
                                Formularios de Comercio Interno
                            @elseif($tipo === 'E')
                                Formularios de Comercio Externo
                            @else
                                Total de Formularios (Todos los tipos)
                            @endif
                        </h4>
                        <div class="mb-3">
                            <span class="badge bg-success fs-6">
                                Estados incluidos: {{ implode(', ', $estados) }}
                                @if($incluirEstado3)
                                    <small class="ms-1">(incluye estado 3 adicionalmente)</small>
                                @endif
                            </span>
                        </div>
                        <p class="lead mb-0">
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
                        <h5 class="mt-3">Consulta Completada</h5>
                        <p class="text-muted mb-0">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribución por estado -->
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
                            @php
                                $estadosLabels = [
                                    '1' => ['label' => 'Emitidos', 'color' => 'success', 'icon' => 'check-circle'],
                                    '2' => ['label' => 'Vencidos', 'color' => 'warning', 'icon' => 'clock-history'],
                                    '3' => ['label' => 'Estado 3', 'color' => 'info', 'icon' => 'question-circle']
                                ];
                            @endphp

                            @foreach($estados as $estado)
                                @if(isset($porEstado[$estado]))
                                    @php
                                        $datos = $estadosLabels[$estado];
                                        $totalEstado = $porEstado[$estado]->total;
                                        $porcentaje = $total > 0 ? round(($totalEstado / $total) * 100, 1) : 0;
                                    @endphp
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-{{ $datos['color'] }}">
                                            <div class="card-body text-center">
                                                <div class="mb-2">
                                                    <i class="bi bi-{{ $datos['icon'] }} fs-1 text-{{ $datos['color'] }}"></i>
                                                </div>
                                                <h4 class="text-{{ $datos['color'] }}">{{ $totalEstado }}</h4>
                                                <h6 class="text-muted">{{ $datos['label'] }}</h6>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-{{ $datos['color'] }}"
                                                         style="width: {{ $porcentaje }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $porcentaje }}% del total</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Si se seleccionó "Todos", mostrar distribución por tipo -->
        @if($tipo === 'T' && $porTipo)
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
                            @php
                                $tiposLabels = [
                                    'I' => ['label' => 'Interno', 'color' => 'primary', 'icon' => 'house-door'],
                                    'E' => ['label' => 'Externo', 'color' => 'info', 'icon' => 'globe']
                                ];
                            @endphp

                            @foreach($porTipo as $tipoComercio)
                                @if(isset($tiposLabels[$tipoComercio->tipo_form_comercio]))
                                    @php
                                        $datos = $tiposLabels[$tipoComercio->tipo_form_comercio];
                                        $porcentaje = $total > 0 ? round(($tipoComercio->total / $total) * 100, 1) : 0;
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-{{ $datos['color'] }}">
                                            <div class="card-body text-center">
                                                <div class="mb-2">
                                                    <i class="bi bi-{{ $datos['icon'] }} fs-1 text-{{ $datos['color'] }}"></i>
                                                </div>
                                                <h3 class="text-{{ $datos['color'] }}">{{ $tipoComercio->total }}</h3>
                                                <h5 class="text-muted">Comercio {{ $datos['label'] }}</h5>
                                                <div class="progress" style="height: 15px;">
                                                    <div class="progress-bar bg-{{ $datos['color'] }}"
                                                         style="width: {{ $porcentaje }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $porcentaje }}% del total</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabla de distribución por día -->
        @if($datosPorDia->count() > 0)
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
                                    @foreach($datosPorDia as $dia)
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
                        <i class="bi bi-arrow-left me-1"></i>Volver al Dashboard
                    </a>
                    <div>
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>Imprimir Reporte
                        </button>
                        <a href="{{ route('estadisticas.index') }}?reload=true" class="btn btn-primary ms-2">
                            <i class="bi bi-arrow-repeat me-1"></i>Nueva Consulta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
