<?php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\JuegoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EventoRepository $eventoRepository, JuegoRepository $juegoRepository): Response
    {
        $events = [];
        $eventos = $eventoRepository->findAll();
        foreach ($eventos as $evento) {
            $events[]= [
                "id" =>$evento->getId(),
                "nombre"=>$evento->getNombre(),
                "juego"=> ($juegoRepository->find($evento->getJuegos()->first())->getImagen()),
                "fecha"=>$evento->getFecha(),
                "participantes"=>$evento->getUsuarios(),
                "numMaxAsistentes"=>$evento->getNumMaxAsistentes(),
            ];
        }
        return $this->render('general/index.html.twig', [
            "eventos" => $events
        ]);
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
                'id' => $datos->getId(),
                'img' => $datos->getJuego()->getImagen(),
                'juego' => $datos->getJuego() . '',
                'title' => $datos,
                'mesa' => $datos->getMesa()->getId() . '',
            ];
        }
        return $this->render('reservas/index.html.twig', [
            'reservas' => $reservas,
        ]);
    }
    #[Route('/juegos', name: 'juegos')]
    public function juegos(JuegoRepository $juegoRepository): Response
    {
        $juegos = [];

        $data = $juegoRepository->findAll(); //TODO crear juego, editar, eliminar

        foreach ($data as $datos) {
            # Rellenamos con el juego actual
            $juegos[] = [
                'id' => $datos->getId() . '', //TODO página de JUEGO_ID
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
    
    #[Route('/reserva/{id}', name: 'reserva_concreta')]
    public function reservaConcreta(int $id)
    {
        # TODO La página sobre la reserva original
    }
    
    #[Route('/evento/{id}', name: 'reserva_concreto')]
    public function eventoConcreto(int $id)
    {
        die("SI ".$id);
        # TODO La página sobre la reserva original
    }

}
