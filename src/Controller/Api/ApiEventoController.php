<?php

namespace App\Controller\Api;

use App\Entity\Evento;
use App\Repository\EventoRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @api de la entidad Evento
 * @author Víctor Esquinas
 */
#[Route("/api", name: "api_evento")]
class ApiEventoController extends AbstractController
{
    #[Route("/evento/{id}", name: "getEvento", methods: "GET")]
    public function getEvento(EventoRepository $mr, int $id = null): Response
    {

        if ($id === null) {
            # Si es null, las quiere todas
            $eventos = $mr->findAll();

            return $this->json([
                "eventos" => $eventos,
                "Success" => true
            ], 200);
        } else {
            // cogemos el evento
            $evento = $mr->find($id);

            // Cogemos las colecciones en arrays
            $juegos = [];
            $asistentes = [];
            $games = $evento->getJuegos();
            $users = $evento->getUsuarios();
            foreach ($games as $game) {
                $juegos[] = $game;
            }
            foreach ($users as $user) {
                $asistentes[] = $user;
            }

            return $this->json([
                "evento" => [
                    "id" => $evento->getId(),
                    "nombre" => $evento->getNombre(),
                    "fecha" => $evento->getFecha(),
                    "juegos" => $juegos,
                    "max_asistentes" => $evento->getNumMaxAsistentes(),
                    "tramo" => $evento->getTramo(),
                    "usuarios" => $asistentes,
                ],
                "Success" => true
            ], 200);
        }
    }

    #[Route("/evento", name: "postEvento", methods: "POST")]
    public function postEvento(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->evento;

        $datetime = new DateTime();
        $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u', $datos->fecha);

        $evento = new Evento();
        $evento->setNombre($datos->nombre);
        $evento->setFecha($fecha);
        $evento->setTramo($datos->tramo);
        $evento->setNumMaxAsistentes($datos->max_asistentes);

        $manager = $mr->getManager();
        try {
            $manager->persist($evento);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
        }
        $id = $evento->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al crear el evento " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/evento", name: "putEvento", methods: "PUT")]
    public function putEvento(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de el evento a editar
        $eventoNuevo = $datos->evento;
        $id = $eventoNuevo->id;
        // Obtenemos el evento
        $evento = $mr->getRepository(Evento::class)->find($id);
        $datetime = new DateTime();

        $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u', $eventoNuevo->fecha);
        // Cambiamos todos sus campos
        $evento->setFecha($fecha);
        $evento->setTramo($eventoNuevo->tramo);
        $evento->setNombre($eventoNuevo->nombre);
        $evento->setNumMaxAsistentes($eventoNuevo->num_max_asistentes);

        // Borramos los usuarios del evento antes de editar el array
        foreach ($evento->getUsuarios() as $asistente) {
            # Lo eliminamos
            $evento->removeUsuario($asistente);
        }
        // Añadimos a los asistentes
        foreach ($eventoNuevo->asistentes as $user) {
            # lo añadimos
            $evento->addUsuario($user);
        }
        // Borramos los juegos del evento antes de editar el array
        foreach ($evento->getJuegos(0) as $juego) {
            # Lo eliminamos
            $evento->removeJuego($juego);
        }
        // Añadimos a los juegos
        foreach ($eventoNuevo->juegos as $juego) {
            # lo añadimos
            $evento->addJuego($juego);
        }


        $manager = $mr->getManager();
        try {
            // La mandamos a actualizar
            $manager->persist($evento);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(
                [
                    'message' => $e->getMessage(),
                    "Success" => false
                ],
                400
            );
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al editar el evento " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/evento", name: "deleteEvento", methods: "DELETE")]
    public function delEvento(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de el evento a editar
        $id = $datos->evento->id;
        // Obtenemos el evento
        $evento = $mr->getRepository(Evento::class)->find($id);

        $manager = $mr->getManager();
        // La mandamos a borrar
        try {
            // Borramos
            $manager->remove($evento);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(
                [
                    'message' => $e->getMessage(),
                    "Success" => false
                ],
                400
            );
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al borrar el evento " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
