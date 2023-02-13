<?php

namespace App\Controller;

use App\Repository\DistribucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mantenimiento')]
class MantenimientoController extends AbstractController
{

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
