<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesasController extends AbstractController
{
    #[Route('/mesas', name: 'app_mesas')]
    public function index(): Response
    {
        return $this->render('mesas/salaMesas.html.twig', [
            'controller_name' => 'MesasController',
        ]);
    }
}