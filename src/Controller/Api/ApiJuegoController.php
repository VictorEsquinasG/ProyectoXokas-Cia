<?php

namespace App\Controller\Api;

use App\Entity\Juego;
use App\Repository\JuegoRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @api de la entidad Juego
 * @author Víctor Esquinas
 */
#[Route('/api', name: 'api_juego')]
class ApiJuegoController extends AbstractController
{
    #[Route("/juego/{id}", name: "getJuego", methods: "GET")]
    public function getJuego(JuegoRepository $jr, int $id = null): Response
    {

        if ($id === null) {
            # Si es null, los quiere todos
            $juegos = $jr->findAll();
            return $this->json(["juegos" => $juegos, "Success" => true], 200);
        } else {
            // Cogemos el juego
            $juego = $jr->find($id);
            return $this->json(["juego" => [
                "id" => $juego->getId(),
                "nombre" => $juego->getNombre(),
                "descripcion" => $juego->getDescripcion(),
                "imagen" => ($juego->getImagen() !== null) ? $juego->getImagen() : null,
                "jugadores" => [
                    "min" => $juego->getMinJugadores(),
                    "max" => $juego->getMaxJugadores(),
                ],
                "tamañoTablero" => [
                    "ancho" => $juego->getAnchoTablero(),
                    "largo" => $juego->getLargoTablero()
                ],
                "reservas" => $juego->getReservas(),
                "eventos" => $juego->getEventos()
            ], "Success" => true], 200);
        }
    }

    #[Route("/juego/byJugadores/{num}", name: "getJuegoByNumJugadores", methods: "GET")]
    public function getJuegoByNumJugadores(JuegoRepository $jr, int $num): Response
    {

        # 
        $juegos = $jr->findByNumJugadores($num);
        return $this->json(["juegos" => $juegos, "Success" => true], 200);
    }

    #[Route("/juego", name: "postJuego", methods: "POST")]
    public function postJuego(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->juego;


        $juego = new Juego();
        $juego->setNombre($datos->nombre);
        $juego->setImagen($datos->imagen);
        $jugadores = $datos->jugadores;
        $juego->setMinJugadores($jugadores->min);
        $juego->setMaxJugadores($jugadores->max);
        $tablero = $datos->tablero;
        $juego->setAnchoTablero($tablero->ancho);
        $juego->setLargoTablero($tablero->largo);

        /* $reservas = $datos->reservas;
        foreach ($reservas as $reserva) {
            # Añadimos cada una de las reservas
            $juego->addReserva($reserva);
        }
        $eventos = $datos->eventos;
        foreach ($eventos as $evento) {
            # Añadimos cada evento en el array
            $juego->addEvento($evento); 
        } */

        $manager = $mr->getManager();
        try {
            //code...
            $manager->persist($juego);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json([
                'message' => $e->getMessage(),
                "Success" => false
            ], 400);
        }
        $id = $juego->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al crear el Juego " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/juego", name: "putJuego", methods: "PUT")]
    public function putJuego(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->juego;
        // Cogemos el ID de el Juego a editar
        $id = $datos->id;
        // Obtenemos el Juego
        $juego = $mr->getRepository(Juego::class)->find($id);
        // Cambiamos todos sus campos
        $juego->setNombre($datos->nombre);
        $juego->setImagen($datos->imagen);
        $jugadores = $datos->jugadores;
        $juego->setMinJugadores($jugadores->min);
        $juego->setMaxJugadores($jugadores->max);
        $tablero = $datos->tablero;
        $juego->setAnchoTablero($tablero->ancho);
        $juego->setLargoTablero($tablero->largo);

        /* $reservas = $datos->reservas;
        foreach ($reservas as $reserva) {
            # Añadimos cada una de las reservas
            $juego->addReserva($reserva);
        }
        $eventos = $datos->eventos;
        foreach ($eventos as $evento) {
            # Añadimos cada evento en el array
            $juego->addEvento($evento); 
        } */

        $manager = $mr->getManager();
        // Lo mandamos a actualizar
        $manager->persist($juego);
        $manager->flush();

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al editar el Juego " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/juego", name: "deleteJuego", methods: "DELETE")]
    public function delJuego(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->juego;
        // Cogemos el ID de el Juego a editar
        $id = $datos->id;
        // Obtenemos el Juego
        $juego = $mr->getRepository(Juego::class)->find($id);

        $manager = $mr->getManager();
        try {
            // La mandamos a borrar
            $manager->remove($juego, true);
        } catch (PDOException $e) {
            return $this->json([
                "id" => $id,
                'message' => 'Error al borrar juego ' . $id . "\n" . $e->getMessage(),
                "Success" => false
            ], 400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al borrar el Juego " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
