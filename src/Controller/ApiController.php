<?php

namespace App\Controller;

use App\Entity\Mesa;
use App\Entity\Distribucion;
use App\Entity\Juego;
use App\Repository\JuegoRepository;
use App\Repository\MesaRepository;
use App\Repository\DistribucionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: "app_api")]
class ApiController extends AbstractController
{
    /* #[Route('/mesa', name: 'getMesas')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    } */

    #[Route("/prueba", name: "proob", methods: "GET")]
    public function pruebaPlantilla(): Response
    {

        return $this->render("salaMesas.html.twig");
    }
    
    #[Route("/mesa/{id}", name: "getMesa", methods: "GET")]
    public function getMesa(MesaRepository $mr, int $id): Response
    {

        $mesa = $mr->find($id);

        if ($mesa->getId() === null) {
            # Si es null
            return $this->json(["message" => "No hay mesas"], 400);
        } else {
            return $this->json(["mesa" => ["id" => $mesa->getId(), "largo" => $mesa->getLargo(), "ancho" => $mesa->getAncho()], "Success" => true]);
        }
    }

    #[Route("/mesa", name: "postMesa", methods: "POST")]
    public function postMesa(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('mesa'));
        $mesa = new Mesa();
        $mesa->setAncho($datos->ancho);
        $mesa->setLargo($datos->largo);
        $mesa->setSillas($datos->sillas);
        $mesa->setPosicionX($datos->posicion_x);
        $mesa->setPosicionY($datos->posicion_y);

        $manager = $mr->getManager();
        try {
            $manager->persist($mesa,true);
        } catch (\Throwable $th) {
            //throw $th;
        }
        $id = 0; //TODO
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al crear la mesa " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/mesa", name: "putMesa", methods: "PUT")]
    public function putMesa(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('mesa'));
        // Cogemos el ID de la mesa a editar
        $id = $datos->id;
        // Obtenemos la mesa
        $mesa = $mr->getRepository(Mesa::class)->find($id);
        // Cambiamos todos sus campos
        $mesa->setAncho($datos->ancho);
        $mesa->setLargo($datos->largo);
        $mesa->setSillas($datos->sillas);
        $mesa->setPosicionX($datos->posicion_x);
        $mesa->setPosicionY($datos->posicion_y);

        $manager = $mr->getManager();
        // La mandamos a actualizar
        $manager->persist($mesa);
        $manager->flush();

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al editar la mesa " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/mesa", name: "deleteMesa", methods: "DELETE")]
    public function delMesa(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('mesa'));
        // Cogemos el ID de la mesa a editar
        $id = $datos->id;
        // Obtenemos la mesa
        $mesa = $mr->getRepository(Mesa::class)->find($id);

        $manager = $mr->getManager();
        // La mandamos a borrar
        $manager->remove($mesa);
        $manager->flush();

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al borrar la mesa " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
    #[Route("/juego/{id}", name: "getJuego", methods: "GET")]
    public function getJuego(JuegoRepository $mr, int $id): Response
    {

        $juego = $mr->find($id);

        if ($juego->getId() === null) {
            # Si es null
            return $this->json(["message" => "No hay Juegos"], 400);
        } else {
            return $this->json(["Juego" => [
                "id" => $juego->getId(),
                "nombre" => $juego->getNombre(),
                "minJugadores" => $juego->getMinJugadores(),
                "maxJugadores" => $juego->getMaxJugadores(),
                "tamañoTablero" => $juego->getTamanioTablero(),
                "reservas" => $juego->getReservas(),
                "eventos" => $juego->getEventos()
            ], "Success" => true]);
        }
    }

    #[Route("/Juego", name: "postJuego", methods: "POST")]
    public function postJuego(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('Juego'));
        $juego = new Juego();
        $juego->setNombre($datos->nombre);
        $juego->setMinJugadores($datos->minJugadores);
        $juego->setMaxJugadores($datos->maxJugadores);
        $juego->setTamanioTablero($datos->tamañoTablero);
        $reservas = $datos->reservas;
        foreach ($reservas as $reserva) {
            # Añadimos cada una de las reservas
            $juego->addReserva($reserva);
        }
        $eventos = $datos->eventos;
        foreach ($eventos as $evento) {
            # Añadimos cada evento en el array
            $juego->addEvento($evento); 
        }

        $manager = $mr->getManager();
        $manager->persist($juego);
        $manager->flush();
        $id = 0; //TODO
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al crear el Juego " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/Juego", name: "putJuego", methods: "PUT")]
    public function putJuego(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('juego'));
        // Cogemos el ID de el Juego a editar
        $id = $datos->id;
        // Obtenemos el Juego
        $juego = $mr->getRepository(Juego::class)->find($id);
        // Cambiamos todos sus campos
        $juego->setNombre($datos->nombre);
        $juego->setMinJugadores($datos->minJugadores);
        $juego->setMaxJugadores($datos->maxJugadores);
        $juego->setTamanioTablero($datos->tamañoTablero);
        $reservas = $datos->reservas;
        foreach ($reservas as $reserva) {
            # Añadimos cada una de las reservas
            $juego->addReserva($reserva);
        }
        $eventos = $datos->eventos;
        foreach ($eventos as $evento) {
            # Añadimos cada evento en el array
            $juego->addEvento($evento); 
        }

        $manager = $mr->getManager();
        // Lo mandamos a actualizar
        $manager->persist($juego);
        $manager->flush();

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al editar el Juego " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/Juego", name: "deleteJuego", methods: "DELETE")]
    public function delJuego(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('Juego'));
        // Cogemos el ID de el Juego a editar
        $id = $datos->id;
        // Obtenemos el Juego
        $juego = $mr->getRepository(Juego::class)->find($id);

        $manager = $mr->getManager();
        // La mandamos a borrar
        $manager->remove($juego);
        $manager->flush();

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al borrar el Juego " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/distribucion/{id}", name: "getDistribucion", methods: "GET")]
    public function getDistribucion(DistribucionRepository $mr, int $id): Response
    {

        $distribucion = $mr->find($id);

        if ($distribucion->getId() === null) {
            # Si es null
            return $this->json(["message" => "No hay distribuciones"], 400);
        } else {
            return $this->json(["Distribucion" => [
                "id" => $distribucion->getId(),
                "pos_x" => $distribucion->getPosicionX(),
                "pos_y" => $distribucion->getPosicionY(),
                "fecha" => $distribucion->getFecha(),
                "mesa_id" => $distribucion->getMesaId(),
                "reservada" => $distribucion->isReservada()
            ], "Success" => true]);
        }
    }

    #[Route("/distribucion", name: "postDistribucion", methods: "POST")]
    public function postDistribucion(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('Distribucion'));
        $distribucion = new Distribucion();
        $distribucion->setFecha($datos->fecha);
        $distribucion->setMesaId($datos->mesa_id);
        $distribucion->setPosicionX($datos->pos_x);
        $distribucion->setPosicionY($datos->pos_y);
        if (isset($datos->reservada)) {
            # Si sabemos si está reservada o no
            $distribucion->setReservada($datos->reservada);
        }else {
            # Por defecto, está libre
            $distribucion->setReservada(false);
        }

        $manager = $mr->getManager();
        $manager->persist($distribucion);
        $manager->flush();
        $id = 0; //TODO
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al crear la distribucion " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/distribucion", name: "putDistribucion", methods: "PUT")]
    public function putDistribucion(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('Distribucion'));
        // Cogemos el ID de el Distribucion a editar
        $id = $datos->id;
        // Obtenemos el Distribucion
        $distribucion = $mr->getRepository(Distribucion::class)->find($id);
        // Cambiamos todos sus campos
        $distribucion->setNombre($datos->nombre);
        $distribucion->setPosicionX($datos->pos_x);
        $distribucion->setPosicionY($datos->pos_y);
        $distribucion->setFecha($datos->fecha);
        $distribucion->setMesaId($datos->mesa_id);
        $distribucion->setReservada($datos->reservada);

        $manager = $mr->getManager();
        // Lo mandamos a actualizar
        $manager->persist($distribucion);
        $manager->flush();

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al editar la distribucion " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/distribucion", name: "deleteDistribucion", methods: "DELETE")]
    public function delDistribucion(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->request->get('Distribucion'));
        // Cogemos el ID de el Distribucion a editar
        $id = $datos->id;
        // Obtenemos el Distribucion
        $distribucion = $mr->getRepository(Distribucion::class)->find($id);

        $manager = $mr->getManager();
        // La mandamos a borrar
        $manager->remove($distribucion);
        $manager->flush();

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al borrar la distribucion " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
