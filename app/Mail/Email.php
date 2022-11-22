<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * INSTRUCCIONES PARA CREAR UN EMAIL
     *
     * En el controller desde donde se vaya a enviar el correo se convoca la clase:
     * use App\Mail\EnviaEmail;
     * use Illuminate\Support\Facades\Mail;
     *
     * Despues, en el texto se crea una instancia de objeto con lo que se va a rellenar:
     *
     * //CREAMOS UN EMAIL
     *   //Primero las cosas obligatorias: La plantilla que se va a usar, el receptor, el email del remitente y el nombre:
     *   $obj = new \stdClass();
     *   $obj->plantilla = "Has recibido un email desde una vocalía";
     *   $obj->receiver = null;
     *   $obj->emailSender = Auth::user()->email;
     *   $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (". Lang::get('text.usuario').":" . Auth::user()->username .")";
     *   //Ahora ponemos las variables que van a ir dentro del email:
     *   $obj->asunto = XXX;
     *   $obj->fecha = Carbon::now()->format('d/m/Y');
     *   $obj->texto = $request->message;
     *
     *  Y por último, se llama a la funcion para enviarlo
     *  //Ahora enviamos el email. Si es a muchas personas hacemos un bucle for. Se pone también el receptor, que estaba a Null.
     *       $obj->receiver = $socio->nombre;
     *       Mail::to($socio->email)->send(new EnviaEmail($obj));
     *
     *
     */

    /**
     * The obj object instance.
     *
     * @var Demo
     */
    public $obj;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($this->obj->plantilla);
        $plantillaemail = 'mails.'.$this->obj->plantilla;
        return $this->from('asociacion.cultural.portal.ludico@gmail.com')
                    ->view($plantillaemail)
                    ->text($plantillaemail);
    }
}
