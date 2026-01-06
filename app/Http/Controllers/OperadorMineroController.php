<?php

namespace App\Http\Controllers;

use App\Models\operador_minero;
use App\Http\Requests\Storeoperador_mineroRequest;
use App\Http\Requests\Updateoperador_mineroRequest;
use App\Models\Email;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

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
            // Para ordenamiento por prioridad
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
            })->values();

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

    /**
     * Enviar notificación por Email
     */
    public function notificarEmail($id)
    {
        try {
            $operador = operador_minero::find($id);

            if (!$operador) {
                return response()->json([
                    'success' => false,
                    'message' => 'Operador no encontrado.'
                ], 404);
            }

            // Validar que tenga email
            if (!$operador->email_op_min) {
                return response()->json([
                    'success' => false,
                    'message' => 'El operador no tiene correo electrónico registrado.'
                ], 400);
            }

            // Obtener mensaje de vencimiento
            $mensajeVencimiento = $this->mensajeOperador($operador);

            if (empty($mensajeVencimiento)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El operador no tiene documentos vencidos para notificar.'
                ], 400);
            }

            // Generar HTML del correo
            $htmlCorreo = $this->generarHTMLCorreo($operador, $mensajeVencimiento);

            // Enviar correo con Gmail (Laravel Mail)
            Mail::send([], [], function ($message) use ($operador, $htmlCorreo) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($operador->email_op_min)
                    ->subject('NOTIFICACION IMPORTANTE - DOCUMENTOS VENCIDOS')
                    ->html($htmlCorreo);
            });
            /*
            // Enviar correo con Resend
            Resend::emails()->send([
                'from' => 'Secretaría Departamental de Mineria, Metalurgia y Recursos Energéticos <onboarding@resend.dev>',
                'to' => $operador->email_op_min,
                'subject' => 'NOTIFICACIÓN IMPORTANTE - Documentos Vencidos',
                'html' => $htmlCorreo
            ]);
            */

            // Guardar registro en base de datos
            $email = new Email();
            $email->destino = $operador->email_op_min;
            $email->asunto = 'NOTIFICACIÓN EMAIL - DOCUMENTOS VENCIDOS';
            $email->detalle = $mensajeVencimiento;
            $email->fecha_emision = now();
            $email->id_operador_minero = $operador->id_operador_minero;
            $email->estado = 1;
            $email->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Notificación enviada exitosamente a ' . $operador->email_op_min
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al enviar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar notificacion por Email (documentos por vencer)
     */
    public function notificarEmailPorVencer($id)
    {
        try {
            $operador = operador_minero::find($id);

            if (!$operador) {
                return response()->json([
                    'success' => false,
                    'message' => 'Operador no encontrado.'
                ], 404);
            }

            if (!$operador->email_op_min) {
                return response()->json([
                    'success' => false,
                    'message' => 'El operador no tiene correo electronico registrado.'
                ], 400);
            }

            $mensajePorVencer = $this->mensajeOperadorPorVencer($operador);

            if (empty($mensajePorVencer)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El operador no tiene documentos por vencer en los proximos 10 dias.'
                ], 400);
            }

            $htmlCorreo = $this->generarHTMLCorreo($operador, $mensajePorVencer, 'por_vencer');

            Mail::send([], [], function ($message) use ($operador, $htmlCorreo) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($operador->email_op_min)
                    ->subject('NOTIFICACION IMPORTANTE - DOCUMENTOS POR VENCER')
                    ->html($htmlCorreo);
            });

            $email = new Email();
            $email->destino = $operador->email_op_min;
            $email->asunto = 'NOTIFICACION EMAIL - DOCUMENTOS POR VENCER';
            $email->detalle = $mensajePorVencer;
            $email->fecha_emision = now();
            $email->id_operador_minero = $operador->id_operador_minero;
            $email->estado = 1;
            $email->save();

            return response()->json([
                'success' => true,
                'message' => 'Notificacion enviada exitosamente a ' . $operador->email_op_min
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener mensaje para WhatsApp
     */
    public function obtenerMensajeWhatsApp($id)
    {
        try {
            $operador = operador_minero::find($id);

            if (!$operador) {
                return response()->json([
                    'success' => false,
                    'message' => 'Operador no encontrado.'
                ], 404);
            }

            // Validar que tenga al menos un número de celular
            $celular = $operador->cel_op_min ?: $operador->cel_rep_legal;

            if (!$celular) {
                return response()->json([
                    'success' => false,
                    'message' => 'El operador no tiene número de celular registrado.'
                ], 400);
            }

            // Limpiar número (quitar espacios, guiones, etc.)
            $celularLimpio = preg_replace('/[^0-9]/', '', $celular);

            // Si no tiene código de país, agregar Bolivia (+591)
            if (strlen($celularLimpio) == 8) {
                $celularLimpio = '591' . $celularLimpio;
            }

            // Generar mensaje para WhatsApp
            $mensaje = $this->generarMensajeWhatsApp($operador);

            return response()->json([
                'success' => true,
                'numero' => $celularLimpio,
                'mensaje' => $mensaje,
                'operador' => $operador->razon_social,
                'celular_original' => $celular
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar envío por WhatsApp en la base de datos
     */
/**
 * Registrar envío por WhatsApp en la base de datos
 */
public function registrarWhatsAppEnvio(Request $request, $id)
{
    try {
        $operador = operador_minero::find($id);

        if (!$operador) {
            return response()->json([
                'success' => false,
                'message' => 'Operador no encontrado.'
            ], 404);
        }

        // Número destino
        $numero = $request->numero
            ?: ($operador->cel_op_min ?: $operador->cel_rep_legal);

        if (!$numero) {
            return response()->json([
                'success' => false,
                'message' => 'No hay número de WhatsApp para registrar.'
            ], 422);
        }

        // Acción (abrir | copiar)
        $accion = $request->accion ?? 'abrir';

        // Usar la MISMA función que usa el email
        $detalle = $this->mensajeOperador($operador);

        // Si está vacío, significa que no hay documentos vencidos
        if (empty($detalle)) {
            $detalle = "El operador no tiene documentos vencidos";
        }

        // Registrar como notificación WhatsApp (tabla email)
        $registro = new Email();
        $registro->destino = $numero;
        $registro->asunto = 'NOTIFICACIÓN WHATSAPP - DOCUMENTOS VENCIDOS';
        $registro->detalle = $detalle;
        $registro->fecha_emision = now();
        $registro->id_operador_minero = $operador->id_operador_minero;
        $registro->estado = 1;
        $registro->save();

        return response()->json([
            'success' => true,
            'message' => 'Notificación WhatsApp registrada en la base de datos.'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al registrar WhatsApp: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Generar mensaje de documentos vencidos
     */
    public function mensajeOperador(operador_minero $operador): string
    {
        $mensajes = [];

        // Fecha actual sin hora
        $hoy = Carbon::now()->startOfDay();

        // ---------- IDOM ----------
        if ($operador->fecha_expiracion) {
            $fecha = Carbon::parse($operador->fecha_expiracion)->startOfDay();

            if ($fecha->lt($hoy)) {
                $dias = $fecha->diffInDays($hoy); // entero
                $mensajes[] = "IDOM vencido hace {$dias} días (fecha: {$fecha->format('d/m/Y')})";
            }
        }

        // ---------- NIM ----------
        if ($operador->fecha_exp_nim) {
            $fecha = Carbon::parse($operador->fecha_exp_nim)->startOfDay();

            if ($fecha->lt($hoy)) {
                $dias = $fecha->diffInDays($hoy); // entero
                $mensajes[] = "NIM vencido hace {$dias} días (fecha: {$fecha->format('d/m/Y')})";
            }
        }

        // ---------- SEPREC (solo privadas) ----------
        if (
            $operador->actor_minero == 3 &&
            $operador->fecha_exp_funda
        ) {
            $fecha = Carbon::parse($operador->fecha_exp_funda)->startOfDay();

            if ($fecha->lt($hoy)) {
                $dias = $fecha->diffInDays($hoy); // entero
                $mensajes[] = "SEPREC vencido hace {$dias} días (fecha: {$fecha->format('d/m/Y')})";
            }
        }

        return implode('. ', $mensajes);
    }

    /**
     * Generar mensaje de documentos por vencer (proximos 10 dias)
     */
    public function mensajeOperadorPorVencer(operador_minero $operador): string
    {
        $mensajes = [];
        $hoy = Carbon::now()->startOfDay();
        $limite = Carbon::now()->addDays(10)->startOfDay();

        if ($operador->fecha_expiracion) {
            $fecha = Carbon::parse($operador->fecha_expiracion)->startOfDay();
            if ($fecha->gte($hoy) && $fecha->lte($limite)) {
                $dias = $hoy->diffInDays($fecha, false);
                $mensajes[] = "IDOM por vencer en {$dias} dias (fecha: {$fecha->format('d/m/Y')})";
            }
        }

        if ($operador->fecha_exp_nim) {
            $fecha = Carbon::parse($operador->fecha_exp_nim)->startOfDay();
            if ($fecha->gte($hoy) && $fecha->lte($limite)) {
                $dias = $hoy->diffInDays($fecha, false);
                $mensajes[] = "NIM por vencer en {$dias} dias (fecha: {$fecha->format('d/m/Y')})";
            }
        }

        if (
            $operador->actor_minero == 3 &&
            $operador->fecha_exp_funda
        ) {
            $fecha = Carbon::parse($operador->fecha_exp_funda)->startOfDay();
            if ($fecha->gte($hoy) && $fecha->lte($limite)) {
                $dias = $hoy->diffInDays($fecha, false);
                $mensajes[] = "SEPREC por vencer en {$dias} dias (fecha: {$fecha->format('d/m/Y')})";
            }
        }

        return implode('. ', $mensajes);
    }

    /**
     * Generar HTML profesional para el correo
     */
    private function generarHTMLCorreo(operador_minero $operador, string $mensajeVencimiento, string $tipo = 'vencidos'): string
    {
        $esPorVencer = $tipo === 'por_vencer';
        $tituloCorreo = $esPorVencer
            ? 'Notificacion - Documentos por Vencer'
            : 'Notificacion - Documentos Vencidos';
        $descripcionCorreo = $esPorVencer
            ? 'Le informamos que tiene documentos por vencer en los proximos 10 dias:'
            : 'Le informamos que tiene documentos vencidos que requieren su atencion inmediata:';
        $accionCorreo = $esPorVencer
            ? 'Por favor, actualice sus documentos antes de la fecha de vencimiento para evitar inconvenientes.'
            : 'Por favor, actualice sus documentos a la brevedad posible para evitar inconvenientes en sus tramites.';
        $detalleCorreo = $esPorVencer
            ? $this->formatearListaHtml($mensajeVencimiento)
            : "<span>{$mensajeVencimiento}</span>";

        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$tituloCorreo}</title>
        </head>
        <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
            <table width='100%' cellpadding='0' cellspacing='0' style='background-color: #f4f4f4; padding: 20px;'>
                <tr>
                    <td align='center'>
                        <table width='600' cellpadding='0' cellspacing='0' style='background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                            <!-- Header -->
                            <tr>
                                <td style='background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center;'>
                                    <h1 style='color: #ffffff; margin: 0; font-size: 24px;'>
                                        ⚠️ NOTIFICACIÓN IMPORTANTE
                                    </h1>
                                    <p style='color: #e0e7ff; margin: 10px 0 0 0; font-size: 14px;'>
                                        Gobernación de Oruro - Dirección de Minería
                                    </p>
                                </td>
                            </tr>

                            <!-- Content -->
                            <tr>
                                <td style='padding: 40px 30px;'>
                                    <h2 style='color: #1e3a8a; margin: 0 0 20px 0; font-size: 20px;'>
                                        Estimado(a): {$operador->nombre_rep_legal}
                                    </h2>

                                    <p style='color: #374151; line-height: 1.6; margin: 0 0 20px 0; font-size: 15px;'>
                                        {$descripcionCorreo}
                                    </p>

                                    <div style='background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; margin: 20px 0; border-radius: 4px; color: #991b1b; font-weight: bold; font-size: 15px;'>
                                        {$detalleCorreo}
                                    </div>

                                    <div style='background-color: #f9fafb; padding: 20px; border-radius: 6px; margin: 25px 0;'>
                                        <h3 style='color: #1e3a8a; margin: 0 0 15px 0; font-size: 16px;'>
                                            📋 Información del Operador Minero
                                        </h3>
                                        <table width='100%' style='color: #374151; font-size: 14px;'>
                                            <tr>
                                                <td style='padding: 5px 0;'><strong>Razón Social:</strong></td>
                                                <td style='padding: 5px 0;'>{$operador->razon_social}</td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 5px 0;'><strong>NIT:</strong></td>
                                                <td style='padding: 5px 0;'>{$operador->nit}</td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 5px 0;'><strong>Rep. Legal:</strong></td>
                                                <td style='padding: 5px 0;'>{$operador->nombre_rep_legal}</td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 5px 0;'><strong>CI Rep. Legal:</strong></td>
                                                <td style='padding: 5px 0;'>{$operador->ci_rep_legal}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div style='background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px;'>
                                        <p style='color: #1e40af; margin: 0; font-size: 14px;'>
                                            <strong>⚡ Acción requerida:</strong> {$accionCorreo}
                                        </p>
                                    </div>

                                    <p style='color: #6b7280; font-size: 13px; line-height: 1.6; margin: 25px 0 0 0;'>
                                        Para más información, puede comunicarse con nosotros:
                                    </p>
                                </td>
                            </tr>

                            <!-- Contact Info -->
                            <tr>
                                <td style='background-color: #f9fafb; padding: 25px 30px; border-top: 1px solid #e5e7eb;'>
                                    <table width='100%'>
                                        <tr>
                                            <td align='center' style='padding-bottom: 15px;'>
                                                <p style='color: #1e3a8a; margin: 0; font-weight: bold; font-size: 15px;'>
                                                    📧 Correo: <a href='mailto:mineria@oruro.gob.bo' style='color: #3b82f6; text-decoration: none;'>mineria@oruro.gob.bo</a>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align='center'>
                                                <p style='color: #1e3a8a; margin: 0; font-weight: bold; font-size: 15px;'>
                                                    📱 Teléfonos: 61831994 - 72435656
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td style='background-color: #1e3a8a; padding: 20px; text-align: center;'>
                                    <p style='color: #e0e7ff; margin: 0; font-size: 12px;'>
                                        © " . date('Y') . " Gobierno Autónomo Departamental de Oruro
                                    </p>
                                    <p style='color: #93c5fd; margin: 5px 0 0 0; font-size: 11px;'>
                                        Este es un correo automático, por favor no responder directamente.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";
    }

    /**
     * Generar mensaje formateado para WhatsApp
     */
    private function generarMensajeWhatsApp(operador_minero $operador): string
    {
        $mensajeVencimiento = $this->mensajeOperador($operador);

        $mensaje = "🏛️ *GOBIERNO AUTONOMO DEPARTAMENTAL DE ORURO*\n";
        $mensaje .= "📋 *SECRETARIA DEPARTAMENTAL DE MINERIA, METALURGIA Y RECURSOS ENERGETICOS*\n\n";
        $mensaje .= "⚠️ *NOTIFICACIÓN IMPORTANTE*\n\n";
        $mensaje .= "Estimado(a): *{$operador->nombre_rep_legal}*\n\n";
        $mensaje .= "Le informamos que tiene documentos vencidos:\n\n";
        $mensaje .= "🔴 *{$mensajeVencimiento}*\n\n";
        $mensaje .= "📌 *Datos del Operador:*\n";
        $mensaje .= "• Razón Social: {$operador->razon_social}\n";
        $mensaje .= "• NIT: {$operador->nit}\n";
        $mensaje .= "• Rep. Legal: {$operador->nombre_rep_legal}\n";
        $mensaje .= "• CI: {$operador->ci_rep_legal}\n\n";
        $mensaje .= "⚡ Por favor, actualice sus documentos a la brevedad para evitar inconvenientes.\n\n";
        $mensaje .= "📧 Correo: mineria@oruro.gob.bo\n";
        $mensaje .= "📱 Teléfonos: 61831994 - 72435656\n\n";
        $mensaje .= "_Este es un mensaje automático del GADOR._";

        return $mensaje;
    }

    private function formatearListaHtml(string $mensaje): string
    {
        $items = array_filter(array_map('trim', explode('.', $mensaje)));
        if (empty($items)) {
            return $mensaje;
        }

        $lista = "<ul style='margin: 0; padding-left: 18px;'>";
        foreach ($items as $item) {
            $lista .= "<li>{$item}</li>";
        }
        $lista .= "</ul>";

        return $lista;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storeoperador_mineroRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(operador_minero $operador_minero)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(operador_minero $operador_minero)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateoperador_mineroRequest $request, operador_minero $operador_minero)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(operador_minero $operador_minero)
    {
        //
    }
}
