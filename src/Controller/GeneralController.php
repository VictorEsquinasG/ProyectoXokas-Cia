<?php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\JuegoRepository;
use App\Repository\UsuarioRepository;
use App\Service\PdfMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controlador que maneja las redirecciones 
 * generales y no específicas de
 * la aplicación
 * @author Víctor Esquinas
 */
class GeneralController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EventoRepository $eventoRepository, JuegoRepository $juegoRepository): Response
    {
        $events = [];
        $games = [];
        $eventos = $eventoRepository->findAll();
        $juegos = $juegoRepository->findAll();
        foreach ($eventos as $evento) {
            $events[] = [
                "id" => $evento->getId(),
                "nombre" => $evento->getNombre(),
                "juego" => ($juegoRepository->find($evento->getJuegos()->first())->getImagen()),
                "fecha" => $evento->getFecha(),
                "participantes" => $evento->getUsuarios(),
                "numMaxAsistentes" => $evento->getNumMaxAsistentes(),
            ];
        }
        foreach ($juegos as $juego) {
            $games[]= [
                "id" => $juego->getId(),
                "img" => $juego->getImagen(),
                "nombre" => $juego->getNombre(),
                "desc" => $juego->getMinJugadores()."-".$juego->getMaxJugadores()." \n",
                "desc2" => "(".$juego->getAnchoTablero()."x".$juego->getLargoTablero()."cm)",
            ];
        }
        return $this->render('general/index.html.twig', [
            "eventos" => $events,
            "juegos" => $games
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

        $data = $juegoRepository->findAll();

        foreach ($data as $datos) {
            # Rellenamos con el juego actual
            $juegos[] = [
                'id' => $datos->getId() . '',
                'nombre' => $datos->getNombre() . '',
                'desc' => $datos->getDescripcion() . '',
                'img' => $datos->getImagen() . '',
                'tablero' => [
                    'ancho' => $datos->getAnchoTablero() . '',
                    'largo' => $datos->getLargoTablero() . '',
                ],
                'jugadores' => [
                    'min' => $datos->getMinJugadores() . '',
                    'max' => $datos->getMaxJugadores() . ''
                ],
            ];
        }
        return $this->render('juegos/index.html.twig', [
            'juegos' => $juegos,
        ]);
    }
    #[Route('/eventos', name: 'eventos')]
    public function eventos(EventoRepository $eventoRepository): Response
    {
        $eventos = [];

        $data = $eventoRepository->findAll();

        foreach ($data as $datos) {
            $juegos = $datos->getJuegos();
            $games = [];
            foreach ($juegos as $juego) {
                # Pasamos la colección a array
                $games []= $juego;
            }
            $i = array_rand($games);
            # Rellenamos con el juego actual
            $eventos[] = [
                'id' => $datos->getId(),
                'nombre' => $datos->getNombre() . '',
                'fecha' => $datos->getFecha()->format('dd-mm-Y'),
                'tramo' => $datos->getTramo(),
                'numMaxAsistentes' => $datos->getNumMaxAsistentes(),
                'juegos' => $games,
                'img' => $juegos[$i]->getImagen(),
                'asistentes' => $datos->getUsuarios()
            ];
        }
        return $this->render('eventos/index.html.twig', [
            'eventos' => $eventos,
        ]);
    }

    #[Route('/p', name: 'prueba_pdf')]
    public function pdf(PdfMaker $dompdf)
    {

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf/mypdf.html.twig', [
            'title' => "UN PDF PARA DOMINAR A TODOS"
        ]);

        // Cargamos el HTML al Dompdf
        $dompdf->sethtml($html)
            ->renderizar()
            ->recargaForzada("mypdf.pdf", [
                "Attachment" => true
            ]);

        // return new Response($pdf);  

    }
}
