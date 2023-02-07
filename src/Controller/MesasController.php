<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesasController extends AbstractController
{
    #[Route('/mesas', name: 'mantenimiento_mesas')]
    public function Mant_mesas(): Response
    {
        return $this->render('mesas/salaMesas.html.twig');
    }
}
