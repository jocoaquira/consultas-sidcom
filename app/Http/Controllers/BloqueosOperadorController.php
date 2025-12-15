<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Resend\Laravel\Facades\Resend;
use Illuminate\Support\Facades\Log;

class BloqueosOperadorController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('bloqueo_operadors as b')
            ->leftJoin('operador_minero as o', 'b.operador_minero_id', '=', 'o.id_operador_minero')
            ->select(
                'b.id',
                'b.operador_minero_id',
                'b.estado',
                'b.motivo',
                'b.fecha',
                'b.created_at',
                'b.updated_at',
                'o.razon_social'
            )
            ->orderBy('b.fecha', 'desc');

        if ($request->filled('estado_filtro')) {
            $query->where('b.estado', $request->estado_filtro);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('b.fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('b.fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('operador_minero_id')) {
            $query->where('b.operador_minero_id', $request->operador_minero_id);
        }

        if ($request->filled('motivo_filtro')) {
            $query->where('b.motivo', 'LIKE', "%{$request->motivo_filtro}%");
        }

        $bloqueos = $query->paginate(20);

        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        return view('bloqueo-operadors.index', compact('bloqueos', 'operadores'));
    }

    public function create()
    {
        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        return view('bloqueo-operadors.create', compact('operadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'operador_minero_id' => 'required|exists:operador_minero,id_operador_minero',
            'estado' => 'required|in:activo,bloqueado',
            'motivo' => 'required|string|min:10|max:500',
            'fecha' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $operador = DB::table('operador_minero')
                ->where('id_operador_minero', $request->operador_minero_id)
                ->first();

            if (!$operador) {
                throw new \Exception('Operador no encontrado.');
            }

            DB::table('bloqueo_operadors')->insert([
                'operador_minero_id' => $request->operador_minero_id,
                'estado' => $request->estado,
                'motivo' => $request->motivo,
                'fecha' => $request->fecha,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $whatsappData = null;
            $emailEnviado = false;
            $emailDestino = $operador->email_op_min ?: 'No tiene email';

            if ($request->estado === 'bloqueado') {
                $this->registrarNotificacionBloqueo($operador, $request->motivo);
                if ($operador->email_op_min) {
                    $emailEnviado = $this->enviarEmailBloqueo($operador, $request->motivo);
                }
                $whatsappData = $this->generarURLWhatsApp($operador, $request->motivo, 'bloqueado');
            } else {
                $this->registrarNotificacionDesbloqueo($operador, $request->motivo);
                if ($operador->email_op_min) {
                    $emailEnviado = $this->enviarEmailDesbloqueo($operador, $request->motivo);
                }
                $whatsappData = $this->generarURLWhatsApp($operador, $request->motivo, 'activo');
            }

            DB::commit();

            // CAMBIO IMPORTANTE: Enviar datos JSON para que JavaScript construya la URL
            return redirect()->route('bloqueo-operadors.create', [
                'success' => '1',
                'estado' => $request->estado,
                'operador' => $operador->razon_social,
                'email_enviado' => $emailEnviado ? '1' : '0',
                'email_destino' => $emailDestino,
                'whatsapp_data' => base64_encode($whatsappData ?? '')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear bloqueo: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function registrarNotificacionBloqueo($operador, $motivo)
    {
        try {
            $detalle = "BLOQUEO DE CUENTA SIDCOM - " . $motivo;

            DB::table('emails')->insert([
                'destino' => $operador->email_op_min ?: ($operador->cel_op_min ?: $operador->cel_rep_legal ?: 'Sin contacto'),
                'asunto' => 'DESABILITACIÓN DE CUENTA SIDCOM',
                'detalle' => $detalle,
                'fecha_emision' => now(),
                'id_operador_minero' => $operador->id_operador_minero,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al registrar notificación de bloqueo: ' . $e->getMessage());
        }
    }

    private function registrarNotificacionDesbloqueo($operador, $motivo)
    {
        try {
            $detalle = "DESBLOQUEO DE CUENTA SIDCOM - " . $motivo;

            DB::table('emails')->insert([
                'destino' => $operador->email_op_min ?: ($operador->cel_op_min ?: $operador->cel_rep_legal ?: 'Sin contacto'),
                'asunto' => 'HABILITACIÓN DE CUENTA SIDCOM',
                'detalle' => $detalle,
                'fecha_emision' => now(),
                'id_operador_minero' => $operador->id_operador_minero,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al registrar notificación de desbloqueo: ' . $e->getMessage());
        }
    }

    private function enviarEmailBloqueo($operador, $motivo)
    {
        try {
            if (!$operador->email_op_min) {
                Log::warning('Operador sin email para notificación de bloqueo: ' . $operador->razon_social);
                return false;
            }

            $htmlCorreo = $this->generarHTMLEmailBloqueo($operador, $motivo);

            Resend::emails()->send([
                'from' => 'Secretaría Departamental de Mineria, Metalurgia y Recursos Energéticos <onboarding@resend.dev>',
                'to' => $operador->email_op_min,
                //'to' => 'jocrock.cga@gmail.com',
                'subject' => 'DESABILITACIÓN DE CUENTA SIDCOM',
                'html' => $htmlCorreo
            ]);

            Log::info('Email de bloqueo enviado a: ' . $operador->email_op_min);
            return true;

        } catch (\Exception $e) {
            Log::error('Error al enviar email de bloqueo: ' . $e->getMessage());
            return false;
        }
    }

    private function enviarEmailDesbloqueo($operador, $motivo)
    {
        try {
            if (!$operador->email_op_min) {
                Log::warning('Operador sin email para notificación de desbloqueo: ' . $operador->razon_social);
                return false;
            }

            $htmlCorreo = $this->generarHTMLEmailDesbloqueo($operador, $motivo);

            Resend::emails()->send([
                'from' => 'Secretaría Departamental de Mineria, Metalurgia y Recursos Energéticos <onboarding@resend.dev>',
                'to' => $operador->email_op_min,
                //'to' => 'jocrock.cga@gmail.com',
                'subject' => 'HABILITACIÓN DE CUENTA SIDCOM',
                'html' => $htmlCorreo
            ]);

            Log::info('Email de desbloqueo enviado a: ' . $operador->email_op_min);
            return true;

        } catch (\Exception $e) {
            Log::error('Error al enviar email de desbloqueo: ' . $e->getMessage());
            return false;
        }
    }

    private function generarHTMLEmailBloqueo($operador, $motivo): string
    {
        $fechaActual = now()->format('d/m/Y H:i');

        return "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Notificación de Bloqueo - SIDCOM</title></head><body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'><table width='100%' cellpadding='0' cellspacing='0' style='background-color: #f4f4f4; padding: 20px;'><tr><td align='center'><table width='600' cellpadding='0' cellspacing='0' style='background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'><tr><td style='background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%); padding: 30px; text-align: center;'><h1 style='color: #ffffff; margin: 0; font-size: 24px;'>⚠️ NOTIFICACIÓN IMPORTANTE - CUENTA BLOQUEADA</h1><p style='color: #e0e7ff; margin: 10px 0 0 0; font-size: 14px;'>Gobernación de Oruro - Sistema SIDCOM</p></td></tr><tr><td style='padding: 40px 30px;'><h2 style='color: #1e3a8a; margin: 0 0 20px 0; font-size: 20px;'>Estimado(a): {$operador->nombre_rep_legal}</h2><p style='color: #374151; line-height: 1.6; margin: 0 0 20px 0; font-size: 15px;'>Le informamos que su cuenta en el Sistema SIDCOM ha sido <strong>BLOQUEADA</strong> por el siguiente motivo:</p><div style='background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; margin: 20px 0; border-radius: 4px;'><p style='color: #991b1b; margin: 0; font-weight: bold; font-size: 15px;'>{$motivo}</p></div><div style='background-color: #f9fafb; padding: 20px; border-radius: 6px; margin: 25px 0;'><h3 style='color: #1e3a8a; margin: 0 0 15px 0; font-size: 16px;'>📋 Información del Operador Minero</h3><table width='100%' style='color: #374151; font-size: 14px;'><tr><td style='padding: 5px 0;'><strong>Razón Social:</strong></td><td style='padding: 5px 0;'>{$operador->razon_social}</td></tr><tr><td style='padding: 5px 0;'><strong>NIT:</strong></td><td style='padding: 5px 0;'>{$operador->nit}</td></tr><tr><td style='padding: 5px 0;'><strong>Rep. Legal:</strong></td><td style='padding: 5px 0;'>{$operador->nombre_rep_legal}</td></tr><tr><td style='padding: 5px 0;'><strong>CI Rep. Legal:</strong></td><td style='padding: 5px 0;'>{$operador->ci_rep_legal}</td></tr></table></div></td></tr><tr><td style='background-color: #f9fafb; padding: 25px 30px; border-top: 1px solid #e5e7eb;'><table width='100%'><tr><td align='center' style='padding-bottom: 15px;'><p style='color: #1e3a8a; margin: 0; font-weight: bold; font-size: 15px;'>📧 Correo: <a href='mailto:mineria@oruro.gob.bo' style='color: #3b82f6; text-decoration: none;'>mineria@oruro.gob.bo</a></p></td></tr><tr><td align='center'><p style='color: #1e3a8a; margin: 0; font-weight: bold; font-size: 15px;'>📱 Teléfonos: 61831994 - 64050564</p></td></tr></table></td></tr><tr><td style='background-color: #1e3a8a; padding: 20px; text-align: center;'><p style='color: #e0e7ff; margin: 0; font-size: 12px;'>Fecha y hora: {$fechaActual}</p><p style='color: #e0e7ff; margin: 0; font-size: 12px;'>© " . date('Y') . " Gobierno Autónomo Departamental de Oruro</p></td></tr></table></td></tr></table></body></html>";
    }

    private function generarHTMLEmailDesbloqueo($operador, $motivo): string
    {
        $fechaActual = now()->format('d/m/Y H:i');

        return "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Notificación de Habilitación - SIDCOM</title></head><body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'><table width='100%' cellpadding='0' cellspacing='0' style='background-color: #f4f4f4; padding: 20px;'><tr><td align='center'><table width='600' cellpadding='0' cellspacing='0' style='background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'><tr><td style='background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center;'><h1 style='color: #ffffff; margin: 0; font-size: 24px;'>✅ NOTIFICACIÓN - CUENTA HABILITADA</h1><p style='color: #e0e7ff; margin: 10px 0 0 0; font-size: 14px;'>Gobernación de Oruro - Sistema SIDCOM</p></td></tr><tr><td style='padding: 40px 30px;'><h2 style='color: #1e3a8a; margin: 0 0 20px 0; font-size: 20px;'>Estimado(a): {$operador->nombre_rep_legal}</h2><p style='color: #374151; line-height: 1.6; margin: 0 0 20px 0; font-size: 15px;'>Le informamos que su cuenta en el Sistema SIDCOM ha sido <strong>HABILITADA</strong> nuevamente:</p><div style='background-color: #f0f9ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px;'><p style='color: #1e40af; margin: 0; font-weight: bold; font-size: 15px;'>{$motivo}</p></div></td></tr><tr><td style='background-color: #f9fafb; padding: 25px 30px; border-top: 1px solid #e5e7eb;'><table width='100%'><tr><td align='center' style='padding-bottom: 15px;'><p style='color: #1e3a8a; margin: 0; font-weight: bold; font-size: 15px;'>📧 Correo: <a href='mailto:mineria@oruro.gob.bo' style='color: #3b82f6; text-decoration: none;'>mineria@oruro.gob.bo</a></p></td></tr><tr><td align='center'><p style='color: #1e3a8a; margin: 0; font-weight: bold; font-size: 15px;'>📱 Teléfonos: 61831994 - 64050564</p></td></tr></table></td></tr><tr><td style='background-color: #1e3a8a; padding: 20px; text-align: center;'><p style='color: #e0e7ff; margin: 0; font-size: 12px;'>Fecha y hora: {$fechaActual}</p></td></tr></table></td></tr></table></body></html>";
    }

    private function generarURLWhatsApp($operador, $motivo, $estado): ?string
    {
        try {
            $celular = $operador->cel_op_min ?: $operador->cel_rep_legal;

            if (!$celular) {
                Log::warning('Operador sin número para WhatsApp: ' . $operador->razon_social);
                return null;
            }

            $celularLimpio = preg_replace('/[^0-9]/', '', $celular);

            if (strlen($celularLimpio) == 8) {
                $celularLimpio = '591' . $celularLimpio;
            }

            if (strlen($celularLimpio) < 10) {
                Log::warning('Número inválido para WhatsApp: ' . $celularLimpio);
                return null;
            }

            if ($estado === 'bloqueado') {
                $mensaje = $this->generarMensajeBloqueoWhatsApp($operador, $motivo);
            } else {
                $mensaje = $this->generarMensajeDesbloqueoWhatsApp($operador, $motivo);
            }

            // SOLUCIÓN: NO codificar aquí, JavaScript lo hará
            // Solo devolver el número y mensaje separados
            return json_encode([
                'numero' => $celularLimpio,
                'mensaje' => $mensaje
            ]);

        } catch (\Exception $e) {
            Log::error('Error al generar URL WhatsApp: ' . $e->getMessage());
            return null;
        }
    }

    private function generarMensajeBloqueoWhatsApp($operador, $motivo): string
    {
        $mensaje = "*GOBIERNO AUTONOMO DEPARTAMENTAL DE ORURO*\n";
        $mensaje .= "*SECRETARIA DEPARTAMENTAL DE MINERIA*\n\n";
        $mensaje .= "*--- DESABILITACION DE CUENTA SIDCOM ---*\n\n";
        $mensaje .= "Estimado(a): *{$operador->nombre_rep_legal}*\n\n";
        $mensaje .= "Le informamos que su cuenta en el Sistema SIDCOM ha sido *BLOQUEADA*\n\n";
        $mensaje .= "*MOTIVO:*\n{$motivo}\n\n";
        $mensaje .= "*DATOS DEL OPERADOR:*\n";
        $mensaje .= "- Razon Social: {$operador->razon_social}\n";
        $mensaje .= "- NIT: {$operador->nit}\n";
        $mensaje .= "- Representante: {$operador->nombre_rep_legal}\n\n";
        $mensaje .= "*CONTACTO:*\n";
        $mensaje .= "Email: mineria@oruro.gob.bo\n";
        $mensaje .= "Telefonos: 61831994 - 64050564\n\n";
        $mensaje .= "_Fecha: " . now()->format('d/m/Y H:i') . "_\n";
        $mensaje .= "_Mensaje automatico - GADOR_";

        return $mensaje;
    }

    private function generarMensajeDesbloqueoWhatsApp($operador, $motivo): string
    {
        $mensaje = "*GOBIERNO AUTONOMO DEPARTAMENTAL DE ORURO*\n";
        $mensaje .= "*SECRETARIA DEPARTAMENTAL DE MINERIA*\n\n";
        $mensaje .= "*--- HABILITACION DE CUENTA SIDCOM ---*\n\n";
        $mensaje .= "Estimado(a): *{$operador->nombre_rep_legal}*\n\n";
        $mensaje .= "Su cuenta en el Sistema SIDCOM ha sido *HABILITADA*:\n\n";
        $mensaje .= "*MOTIVO:* {$motivo}\n\n";
        $mensaje .= "*Datos del Operador:*\n";
        $mensaje .= "• Razon Social: {$operador->razon_social}\n";
        $mensaje .= "• NIT: {$operador->nit}\n\n";
        $mensaje .= "📞 *Contacto:*\n";
        $mensaje .= "mineria@oruro.gob.bo\n";
        $mensaje .= "61831994 - 64050564\n\n";
        $mensaje .= "_Fecha: " . now()->format('d/m/Y H:i') . "_";

        return $mensaje;
    }

    public function show($id)
    {
        $bloqueo = DB::table('bloqueo_operadors as b')
            ->leftJoin('operador_minero as o', 'b.operador_minero_id', '=', 'o.id_operador_minero')
            ->select('b.*', 'o.razon_social')
            ->where('b.id', $id)
            ->first();

        if (!$bloqueo) {
            return redirect()->route('bloqueo-operadors.index')
                ->with('error', 'Registro no encontrado.');
        }

        return view('bloqueo-operadors.show', compact('bloqueo'));
    }

    public function edit($id)
    {
        $bloqueo = DB::table('bloqueo_operadors')
            ->where('id', $id)
            ->first();

        if (!$bloqueo) {
            return redirect()->route('bloqueo-operadors.index')
                ->with('error', 'Registro no encontrado.');
        }

        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        return view('bloqueo-operadors.edit', compact('bloqueo', 'operadores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:activo,bloqueado',
            'motivo' => 'required|string|min:10|max:500',
            'fecha' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $bloqueo = DB::table('bloqueo_operadors')->where('id', $id)->first();

            if (!$bloqueo) {
                throw new \Exception('Registro no encontrado.');
            }

            $operador = DB::table('operador_minero')
                ->where('id_operador_minero', $bloqueo->operador_minero_id)
                ->first();

            DB::table('bloqueo_operadors')
                ->where('id', $id)
                ->update([
                    'estado' => $request->estado,
                    'motivo' => $request->motivo,
                    'fecha' => $request->fecha,
                    'updated_at' => now(),
                ]);

            $whatsappUrl = null;
            $emailEnviado = false;

            if ($bloqueo->estado != $request->estado) {
                if ($request->estado === 'bloqueado') {
                    $this->registrarNotificacionBloqueo($operador, $request->motivo);
                    if ($operador->email_op_min) {
                        $emailEnviado = $this->enviarEmailBloqueo($operador, $request->motivo);
                    }
                    $whatsappUrl = $this->generarURLWhatsApp($operador, $request->motivo, 'bloqueado');
                } else {
                    $this->registrarNotificacionDesbloqueo($operador, $request->motivo);
                    if ($operador->email_op_min) {
                        $emailEnviado = $this->enviarEmailDesbloqueo($operador, $request->motivo);
                    }
                    $whatsappUrl = $this->generarURLWhatsApp($operador, $request->motivo, 'activo');
                }
            }

            DB::commit();

            return redirect()->route('bloqueo-operadors.index')
                ->with('success', 'Registro actualizado exitosamente.')
                ->with('whatsapp_url', $whatsappUrl)
                ->with('operador_nombre', $operador->razon_social)
                ->with('email_enviado', $emailEnviado)
                ->with('email_destino', $operador->email_op_min ?? 'No tiene email');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('bloqueo_operadors')->where('id', $id)->delete();

            return redirect()->route('bloqueo-operadors.index')
                ->with('success', 'Registro eliminado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
