<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use App\Form\UsuarioType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Página personal de cada usuario
 * @author Víctor Esquinas
 */
class ProfileController extends AbstractController
{

    //TODO Página de MI PERFIL donde el usuario podrá editar 
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        // mail
        $mail = $authenticationUtils->getLastUsername();
        // Usuario
        $user = $entityManager->getRepository(Usuario::class)->findByMail($mail)[0];

        $form = $this->createForm(UsuarioType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // guardamos los cambios
            if ($file = $form['imagen']->getData()) {
                # Ha subido una imágen
                $nombreFichero = $file->getClientOriginalName();
                // Cogemos la imagen que ha subido
                $file->move('images/uploads', $nombreFichero);
                // Apuntamos a la imagen
                $user->setImagen($nombreFichero);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                "Usuario actualizado"
            );

            // Cambiamos 

            // Lo mandamos de nuevo aquí mismo para que vea sus cambios
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'registrationForm' => $form->createView()
        ]);
    }
}
