<?php
/* 
namespace App\Controller;



use App\Form\UsuarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class formController extends AbstractController {

    #[Route("/form", name:"formUsuario")]
    public function new(Request $request): Response
    {
        // Creamos un 
        $usuario = new UsuarioType();
        
        $form = $this->createForm(TaskType::class, $usuario);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $usuario = $form->getData();

            return $this->redirectToRoute('éxito');
        }

        return $this->render('form/form.html.twig', [
            'form' => $form,
        ]);
        // ...
    }

} */