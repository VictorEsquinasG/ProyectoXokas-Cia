<?php

namespace App\Controller;

use App\Repository\DistribucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mantenimiento')]
class MantenimientoController extends AbstractController
{
    #[Route('/', name: 'app_mantenimiento')]
    public function index(): Response
    {
        return $this->render('mantenimiento/index.html.twig', [
            'controller_name' => 'MantenimientoController',
        ]);
    }

    #[Route('/mesas', name: 'mantenimiento_mesas')]
    public function mant_mesas(): Response
    {
        // TODO easeAdmin
        return $this->render('mesas/salaMesas.html.twig');
    }

    #[Route('/juegos', name: 'mantenimiento_juegos')]
    public function mant_juegos(): Response
    {
        // TODO easeAdmin
        return $this->render('mesas/salaMesas.html.twig');
    }

    #[Route('/eventos', name: 'mantenimiento_eventos')]
    public function mant_eventos(): Response
    {
        // TODO easeAdmin
        return $this->render('mesas/salaMesas.html.twig');
    }

    #[Route('/usuarios', name: 'mantenimiento_usuarios')]
    public function mant_usuarios(): Response
    {
        // TODO easeAdmin
        return $this->render('mesas/salaMesas.html.twig');
    }

    #[Route('/reservar', name: 'mantenimiento_reservas')]
    public function mant_reservas(): Response
    {
        return $this->render('reservas/index.html.twig', [
            'controller_name' => 'ReservasController',
        ]);
    }

    #[Route('/sala', name: 'mueve_mesas')]
    public function mesas(DistribucionRepository $distribucionRepository): Response
    {
        $disposiciones = [];
        $nombreDistribuciones = [];
        # Pedimos las distribuciones
        $data = $distribucionRepository->getOrderedByName();

        # Ordenamos por fecha
        foreach ($data as $dato) {
            $nombre = $dato->getAlias();
            # Cogemos el nombre que tiene cada distribucion
            if (!in_array($nombre, $nombreDistribuciones)) {
                #Si se trata de un nuevo nombre
                $nombreDistribuciones[] = $nombre;
                $disposiciones[] = ["name" => $nombre, "value" => $nombre];
            }
        }

        return $this->render('mesas/salaMesas.html.twig', [
            "disposiciones" => $disposiciones
        ]);
    }
}
