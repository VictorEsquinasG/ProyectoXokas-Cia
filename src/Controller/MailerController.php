<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controlador para el TESTEO del servicio mailer
 * @ignore Esta página no se utiliza
 * @author Víctor Esquinas
 * @uses test
 */
class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index():Response
    {
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
    
    #[Route('/mail', name: 'app_mailer')]
    public function send(MailerInterface $correo):Respone
    {   

        
        $mail = (new TemplatedEmail())
        ->from('elpatronsupp@gmail.com')
        ->to('dbarote0812@g.educaand.es')
        ->subject('Bienvenido a Xokas & Co.')
        ->text('Quiero suicidarme! pero al menos mando correo')
        ->htmlTemplate('mailer/index.html.twig');

        $correo->send($mail);
        die("Enviado");
    }
}
