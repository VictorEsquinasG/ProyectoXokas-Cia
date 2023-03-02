<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controlador para el registro de un nuevo usuario
 * @author Víctor Esquinas
 */
class RegistrationController extends AbstractController
{

    public function __construct()
    {
    }

    #[Route('/registro', name: 'registro')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,Mailer $correo, EntityManagerInterface $entityManager): Response
    {
        $user = new Usuario();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            
            // TODO revisar que funcione la plantilla
            // Ponemos plantilla HTML
            $html = $this->renderView('registration/confirmation_email.html.twig', [
                'signedUrl'=>'app_login',
                'expiresAtMessageKey' => '15',
                'expiresAtMessageData' => ' días'
            ]);
            // Mandamos el correo
            $correo->setAsunto('Bienvenido a XOKAS & CO.')
            ->setDestinatario($user->getEmail())
            ->setMensaje('Estamos encantados de contar contigo. Empieza a reservar mesa para jugar ya y gana puntos para poder participar en nuestros eventos.')
            ->setHTML($html)
            ->send();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
}
