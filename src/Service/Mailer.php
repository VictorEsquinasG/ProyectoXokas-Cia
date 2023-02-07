<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{

    private $correo;
    private $mail;
    private $psswd;
    private  $from;
    private  $mensaje;
    private  $html;

    public function __construct(MailerInterface $mailer, string  $mail_account, string $mail_passwd)
    {
        $this->correo = $mailer; # Mailer
        $this->from = $mail_account; # Cuenta de Gmail
        $this->psswd = $mail_passwd; # Contraseña
        $this->mail =
            (new Email())
            ->from($this->from);
        # Por defecto el correo es vacío y no se escribe ni con texto ni con HTML
        $this->mensaje = null;
        $this->html = null;
    }

    /**
     * Añade texto plano al correo
     */
    public function setMensaje(string $msg)
    {
        # Guardamos el valor
        $this->mensaje = $msg;
        # Lo añadimos al correo
        $this->mail->text($msg);
    }

    /**
     * Añade un título o Asunto al correo electrónico
     */
    public function setAsunto(string $subject)
    {
        $this->mail->subject($subject);
    }

    /**
     * Añade la dirección o direcciones a las que enviará el correo
     */
    public function setDestinatario($to)
    {
        if (is_array($to)) {
            # Si es un array FOREACH
            foreach ($to as $dest) {
                $this->mail->to($dest);
            }
        } else {
            # 1 destinatario
            $this->mail->to($to);
        }
    }

    /**
     * Añade una plantilla HTML al correo
     */
    public function setHTML($html)
    {
        # Guardamos el html
        $this->html = $html;
        # Seteamos el HTML
        $this->mail->html($html);
    }

    /**
     * Devuelve el HTML asociado al correo
     */
    public function getHTML()
    {
        # Seteamos el HTML
        return $this->html;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function send()
    {
        $this->correo->send($this->mail);
    }
}
