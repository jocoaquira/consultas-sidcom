<?php

namespace App\Notifications;

use App\Models\operador_minero;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionOperador extends Notification
{
    use Queueable;

public string $cuerpo_final='Bajo el marco legal de las atribuciones y competencias que posee la SDMMRE, poder actualizar los documentos pertinentes a la brevedad, para tal efecto tiene "3 días hábiles" a partir de su legal notificación con la presente nota, ya que en su defecto será bloqueado del sistema SIDCOM.

Sin otro particular nos despedimos de usted con las consideraciones del caso.';
    /**
     * Create a new notification instance.
     */
    public function mensajeOperador(operador_minero $operador):string{
        $mensaje='';
        if($operador->fecha_expiracion<now()){
            $mensaje='IDOM vencido en fecha, '.date("d-m-Y", strtotime($operador->fecha_expiracion));
        }
        else{
            if($operador->fecha_exp_nim<now()){
                $mensaje=' NIM O NIAR vencido en fecha, '.date("d-m-Y", strtotime($operador->fecha_exp_nim)).', ';
            }
            if($operador->fecha_exp_funda<now() and $operador->nro_personalidad_juridica==''){
                $mensaje=$mensaje.' SEPREC vencido en fecha, '.date("d-m-Y", strtotime($operador->fecha_exp_funda));
            }
        }
        return $mensaje;
    }
    public function __construct(
        public $asunto,
        public operador_minero $operador_minero
        )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
        ->subject($this->asunto)
        ->greeting('Señor(a): ' . $this->operador_minero->razon_social)
        ->line('De nuestra mayor consideración:')
        ->line('Conforme al DECRETO DEPARTAMENTAL N°135 emitido por el Gobierno Autónomo Departamental de Oruro en el cual precita en sus "ARTICULOS ARTICULO 21. (ACTUALIZACION DE DOCUMENTOS)".')
        ->line('I. La actualización en la base de datos del sistema SIDCOM, es constante y permanente, bajo responsabilidad del actor productivo minero y comercializadora.')
        ->line('II. Los siguientes documentos, se consideran como sujetos de actualización constante y permanente:')
        ->line('1. Identificador de Operador Minero (IDOM)')
        ->line('2. Matricula de Comercio (SEPREC)')
        ->line('3. Número de Identificación Minera (NIM).')
        ->line('4. Contratos Administrativos Mineros y/o Contrato Cooperativo Minero protocolizados ante Notario de Fe Publica')
        ->line('5. Pago de patente minera')
        ->line('6. Cambio o modificación de representante legal')
        ->line('7. Cambio de Directorio (Cooperativas)')
        ->line('8. Cambio de domicilio legal')
        ->line('9. Otros documentos necesarios que acrediten la vigencia e idoneidad del actor productivo minero, operador minero y comercializadora.')
        ->line('III. El actor productivo minero, deberá presentar los siguientes requisitos:')
        ->line('1. Nota dirigida al Secretario Departamental de Minería, Metalurgia y Recursos Energéticos')
        ->line('2. Formulario de solicitud de actualización de datos, descargar de la página de la SDMMRE del GAD.ORU')
        ->line('3. Fotocopia simple de IDOM otorgado por la SDMMRE')
        ->line('4. Fotocopia simple de los documentos a actualizar, descritos en el párrafo anterior')
        ->line('5. Toda la documentación debe ser presentada en la SECRETARÍA DEPARTAMENTAL DE MINERIA METALURGIA Y RECURSOS ENERGETICOS ubicado en el Teatro Internacional de Oruro')
        ->line('IV. El procedimiento de actualización de datos se realizará de acuerdo al art. 19 del presente reglamento.')
        ->line('Bajo ese antecedente expresamos nuestra preocupación ya que su empresa tiene el:')
        ->line($this->mensajeOperador($this->operador_minero))
        ->line($this->cuerpo_final)     
        ->line('Atentamente,')
        ->line('SECRETARÍA DEPARTAMENTAL DE MINERIA METALURGIA Y RECURSOS ENERGETICOS')
        
        ->line('Gracias por su atención.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
