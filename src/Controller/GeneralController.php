<?php

namespace App\Controller;

use App\Repository\JuegoRepository;
use App\Repository\UsuarioRepository;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
    public function reserva(UsuarioRepository $usuarioRepository, Security $security): Response
    {
        $reservas = [];
        $idUser = $security->getUser()->getId();

        $data = $usuarioRepository->find($idUser)->getReservas();

        foreach ($data as $datos) {
            # Rellenamos
            $reservas[] = [
                'url' => $datos . '', //TODO página de RESERVA_ID
                'juego' => $datos->getJuego() . '',
                'title' => $datos->getFechaReserva() . '',
                'mesa' => $datos->getMesa()->getId() . '',
            ];
        }
        return $this->render('reservas/index.html.twig', [
            'reservas' => $reservas,
        ]);
    }
    #[Route('/juegos', name: 'juegos')]
    public function juegos(JuegoRepository $juegoRepository, Security $security): Response
    {
        $juegos = [];

        $data = $juegoRepository->findAll();

        foreach ($data as $datos) {
            # Rellenamos con el juego actual
            $juegos[] = [
                'url' => $datos->getId() . '', //TODO página de JUEGO_ID
                'nombre' => $datos->getNombre() . '',
                'desc' => $datos->getDescripcion() . '',
                'img' => $datos->getImagen() . '',
                'tablero' => [
                    'ancho' => $datos->getAnchoTablero() . '',
                    'largo' => $datos->getLargoTablero() . '',
                ],
                'jugadores' => [
                    'min' => $datos->getMinJugadores() . '',
                    'max' => $datos->getMaxJugadores(). ''
                ],
            ];
        }
        return $this->render('juegos/index.html.twig', [
            'juegos' => $juegos,
        ]);
    }

    #[Route('/reserva/{id}')]
    public function reservaConcreta(int $id)
    {
        # TODO La página sobre la reserva original
    }

    #[Route('/reserva/new', name: 'make_reserva')]
    public function newReserva(): Response
    {

        return $this->render('reservas/nueva.html.twig');
    }
}
