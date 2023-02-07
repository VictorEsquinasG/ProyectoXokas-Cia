<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservasController extends AbstractController
{
    #[Route('/reservar', name: 'reservar')]
    public function index(): Response
    {
        return $this->render('reservas/index.html.twig', [
            'controller_name' => 'ReservasController',
        ]);
    }
}
