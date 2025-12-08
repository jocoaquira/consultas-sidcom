<?php

namespace App\Http\Controllers;

use App\Models\operador_minero;
use App\Http\Requests\Storeoperador_mineroRequest;
use App\Http\Requests\Updateoperador_mineroRequest;
use App\Notifications\NotificacionOperador;
use App\Models\Email;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OperadorMineroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = operador_minero::query()
            ->withCount(['usuario', 'email'])
            ->with(['usuario' => function($q) {
                $q->where('estado_usuario', '=', '1');
            }]);

        // Búsqueda global
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('razon_social', 'like', "%{$search}%")
                  ->orWhere('nombre_rep_legal', 'like', "%{$search}%")
                  ->orWhere('nit', 'like', "%{$search}%")
                  ->orWhere('ci_rep_legal', 'like', "%{$search}%")
                  ->orWhere('email_op_min', 'like', "%{$search}%")
                  ->orWhere('observaciones', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo de operador
        if ($request->filled('tipo')) {
            $query->where('actor_minero', $request->tipo);
        }

        // Filtro por estado de usuarios
        if ($request->filled('estado')) {
            if ($request->estado === 'activo') {
                $query->whereHas('usuario', function($q) {
                    $q->where('estado_usuario', '=', '1');
                });
            } elseif ($request->estado === 'inactivo') {
                $query->whereDoesntHave('usuario', function($q) {
                    $q->where('estado_usuario', '=', '1');
                });
            }
        }

        // Filtro por pestaña (documentos vencidos)
        $tab = $request->get('tab', 'todos');
        $now = Carbon::now();

        switch ($tab) {
            case 'por_vencer':
                // Documentos que vencen en los próximos 10 días (pero no vencidos aún)
                $query->where(function($q) use ($now) {
                    $fechaLimite = Carbon::now()->addDays(10);
                    $q->where(function($sq) use ($now, $fechaLimite) {
                        $sq->whereBetween('fecha_exp_nim', [$now, $fechaLimite]);
                    })
                    ->orWhere(function($sq) use ($now, $fechaLimite) {
                        $sq->whereBetween('fecha_expiracion', [$now, $fechaLimite]);
                    })
                    ->orWhere(function($sq) use ($now, $fechaLimite) {
                        $sq->where('actor_minero', 3)
                           ->whereBetween('fecha_exp_funda', [$now, $fechaLimite]);
                    });
                });
                break;

            case 'nim_vencido':
                $query->where('fecha_exp_nim', '<', $now);
                break;

            case 'seprec_vencido':
                $query->where('actor_minero', 3)
                      ->where('fecha_exp_funda', '<', $now);
                break;

            case 'idom_vencido':
                $query->where('fecha_expiracion', '<', $now);
                break;

            case 'todo_vencido':
                $query->where(function($q) use ($now) {
                    $q->where('fecha_exp_nim', '<', $now)
                      ->where('fecha_expiracion', '<', $now)
                      ->where(function($sq) use ($now) {
                          $sq->where('actor_minero', '!=', 3)
                             ->orWhere(function($ssq) use ($now) {
                                 $ssq->where('actor_minero', 3)
                                    ->where('fecha_exp_funda', '<', $now);
                             });
                      });
                });
                break;

            case 'activos_vencidos':
                $query->whereHas('usuario', function($q) {
                    $q->where('estado_usuario', '=', '1');
                })
                ->where(function($q) use ($now) {
                    $q->where('fecha_exp_nim', '<', $now)
                      ->orWhere('fecha_expiracion', '<', $now)
                      ->orWhere(function($sq) use ($now) {
                          $sq->where('actor_minero', 3)
                             ->where('fecha_exp_funda', '<', $now);
                      });
                });
                break;

            case 'bloqueados_vencidos':
                $query->whereDoesntHave('usuario', function($q) {
                    $q->where('estado_usuario', '=', '1');
                })
                ->where(function($q) use ($now) {
                    $q->where('fecha_exp_nim', '<', $now)
                      ->orWhere('fecha_expiracion', '<', $now)
                      ->orWhere(function($sq) use ($now) {
                          $sq->where('actor_minero', 3)
                             ->where('fecha_exp_funda', '<', $now);
                      });
                });
                break;
        }

        // Ordenamiento
        $sortBy = $request->get('sort', 'prioridad');

        if ($sortBy === 'prioridad') {
            // Para ordenamiento por prioridad, primero obtenemos TODOS los resultados
            // luego los ordenamos en memoria y finalmente paginamos
            $allOperadores = $query->get();

            $operadoresSorted = $allOperadores->sortByDesc(function($operador) use ($now) {
                $score = 0;

                // Si tiene usuarios activos
                $tieneUsuariosActivos = $operador->usuario()->where('estado_usuario', '=', '1')->count() > 0;
                if ($tieneUsuariosActivos) {
                    $score += 1000;
                }

                // Documentos vencidos
                if ($operador->fecha_expiracion && Carbon::parse($operador->fecha_expiracion)->lt($now)) {
                    $score += 500;
                }
                if ($operador->fecha_exp_nim && Carbon::parse($operador->fecha_exp_nim)->lt($now)) {
                    $score += 300;
                }
                if ($operador->actor_minero == 3 && $operador->fecha_exp_funda && Carbon::parse($operador->fecha_exp_funda)->lt($now)) {
                    $score += 200;
                }

                // Por días de vencimiento
                if ($operador->fecha_expiracion) {
                    $diasVencidos = Carbon::parse($operador->fecha_expiracion)->diffInDays($now, false);
                    if ($diasVencidos > 0) {
                        $score += min($diasVencidos, 100);
                    }
                }

                return $score;
            })->values(); // values() resetea los índices

            // Paginación manual
            $page = $request->get('page', 1);
            $perPage = 20;
            $total = $operadoresSorted->count();
            $items = $operadoresSorted->slice(($page - 1) * $perPage, $perPage)->values();

            $operador = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        } else {
            // Ordenamiento normal por base de datos
            $direction = $request->get('direction', 'desc');
            $query->orderBy($sortBy, $direction);
            $operador = $query->paginate(20)->withQueryString();
        }

        // Calcular estadísticas para las pestañas
        $stats = $this->calcularEstadisticas();

        return view("operador_minero.operador_minero", [
            "productos" => $operador,
            "stats" => $stats,
            "currentTab" => $tab
        ]);
    }

    /**
     * Calcular estadísticas para las pestañas
     */
    private function calcularEstadisticas()
    {
        $now = Carbon::now();
        $fechaLimite = Carbon::now()->addDays(10);

        return [
            'todos' => operador_minero::count(),
            'por_vencer' => operador_minero::where(function($q) use ($now, $fechaLimite) {
                    $q->where(function($sq) use ($now, $fechaLimite) {
                        $sq->whereBetween('fecha_exp_nim', [$now, $fechaLimite]);
                    })
                    ->orWhere(function($sq) use ($now, $fechaLimite) {
                        $sq->whereBetween('fecha_expiracion', [$now, $fechaLimite]);
                    })
                    ->orWhere(function($sq) use ($now, $fechaLimite) {
                        $sq->where('actor_minero', 3)
                           ->whereBetween('fecha_exp_funda', [$now, $fechaLimite]);
                    });
                })->count(),
            'nim_vencido' => operador_minero::where('fecha_exp_nim', '<', $now)->count(),
            'seprec_vencido' => operador_minero::where('actor_minero', 3)
                ->where('fecha_exp_funda', '<', $now)->count(),
            'idom_vencido' => operador_minero::where('fecha_expiracion', '<', $now)->count(),
            'todo_vencido' => operador_minero::where('fecha_exp_nim', '<', $now)
                ->where('fecha_expiracion', '<', $now)
                ->where(function($q) use ($now) {
                    $q->where('actor_minero', '!=', 3)
                      ->orWhere(function($sq) use ($now) {
                          $sq->where('actor_minero', 3)
                             ->where('fecha_exp_funda', '<', $now);
                      });
                })->count(),
            'activos_vencidos' => operador_minero::whereHas('usuario', function($q) {
                    $q->where('estado_usuario', '=', '1');
                })
                ->where(function($q) use ($now) {
                    $q->where('fecha_exp_nim', '<', $now)
                      ->orWhere('fecha_expiracion', '<', $now)
                      ->orWhere(function($sq) use ($now) {
                          $sq->where('actor_minero', 3)
                             ->where('fecha_exp_funda', '<', $now);
                      });
                })->count(),
            'bloqueados_vencidos' => operador_minero::whereDoesntHave('usuario', function($q) {
                    $q->where('estado_usuario', '=', '1');
                })
                ->where(function($q) use ($now) {
                    $q->where('fecha_exp_nim', '<', $now)
                      ->orWhere('fecha_expiracion', '<', $now)
                      ->orWhere(function($sq) use ($now) {
                          $sq->where('actor_minero', 3)
                             ->where('fecha_exp_funda', '<', $now);
                      });
                })->count(),
        ];
    }

    public function mensajeOperador(operador_minero $operador): string
    {
        $mensaje = '';

        if ($operador->fecha_expiracion < now()) {
            $mensaje = 'IDOM vencido en fecha: ' . date("d-m-Y", strtotime($operador->fecha_expiracion));
        } else {
            if ($operador->fecha_exp_nim < now()) {
                $mensaje = 'NIM O NIAR vencido en fecha: ' . date("d-m-Y", strtotime($operador->fecha_exp_nim));
            }
            if ($operador->fecha_exp_funda < now() && $operador->actor_minero == 3) {
                $mensaje .= ($mensaje ? '. ' : '') . 'SEPREC vencido en fecha: ' . date("d-m-Y", strtotime($operador->fecha_exp_funda));
            }
        }

        return $mensaje;
    }

    public function notificacion($id)
    {
        try {
            $operador = operador_minero::find($id);

            $email = new Email();
            $email->destino = 'wberlim91@gmail.com'; // $operador->email_op_min;
            $email->asunto = 'DOCUMENTOS VENCIDOS';
            $email->detalle = $this->mensajeOperador($operador);
            $email->fecha_emision = now();
            $email->id_operador_minero = $operador->id_operador_minero;
            $email->estado = 1;
            $email->save();

            return redirect()->route('operadores.index')->with('success', 'Notificación enviada con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('operadores.index')->with('error', 'No se pudo enviar la notificación: ' . $e->getMessage());
        }
    }

    // ... resto de métodos sin cambios
    public function create() {}
    public function store(Storeoperador_mineroRequest $request) {}
    public function show(operador_minero $operador_minero) {}
    public function edit(operador_minero $operador_minero) {}
    public function update(Updateoperador_mineroRequest $request, operador_minero $operador_minero) {}
    public function destroy(operador_minero $operador_minero) {}
}
