<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('general/index.html.twig');
    }

    #[Route('/reservas', name: 'reservar')]
    public function reserva(): Response
    {
        return $this->render('reservas/index.html.twig', [
            'controller_name' => 'ReservasController',
        ]);
    }
    
    #[Route('/reserva/new', name: 'make_reserva')]
    public function newReserva(): Response
    {
        return $this->render('reservas/nueva.html.twig');
    }
}
