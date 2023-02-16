<?php

namespace App\EventSubscriber;

use App\Entity\Usuario;
use App\Service\Mailer;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BorradoEventoSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private Mailer $mailer;
    private string $html;

    function __construct(Security $security, Mailer $mailer)
    {
        $this->security = $security;
        $this->mailer = $mailer;
        $this->html = 
        <<<EOD
            <style>
                a {text-decoration:none}
            </style>
            <div>
                <h1>¡ALERTA! Su usuario ha sido eliminado</h1>
                <p>
                    Se ha borrado su usuario de la base de datos, se trata de un acto
                    <b>irreversible</b>.
                    Si se debe a un error, por favor contácte con nosotros para que podamos 
                    ayudarle en la medida de lo posible (nuestra información de contacto se
                    encuentra en la web).

                    <button><a href="http://127.0.0.1:8000/login">VER</a></button>
                </p> <br/> <br/>
                
                <span style="background-color:#9b0118;font-weigth:bold;">
                    Este es un mensaje informativo automático, por favor, no conteste.
                </span>


                <span style="margin-top: 4.5rem">
                    Te echaremos de menos.
                    Atte.: Xokas & Co.  
                </span> 
            </div>
        EOD;
    }

    public function onBeforeEntityDeletedEvent(BeforeEntityDeletedEvent $event): void
    {
        // Si el
        $user = $event->getEntityInstance();
        
        if ($user instanceof Usuario) {
            $mail = $user->getEmail();
            # Es un usuario, le mandamos un correo informativo
            $this->mailer->setAsunto('[USUARIO ELIMINADO]')->setHTML($this->html)->setDestinatario($mail)->send();
        }
        
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityDeletedEvent::class => 'onBeforeEntityDeletedEvent',
        ];
    }
}
