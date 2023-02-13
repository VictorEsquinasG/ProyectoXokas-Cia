<?php

namespace App\Controller\Api;

use App\Entity\Juego;
use App\Entity\Reserva;
use App\Repository\ReservaRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_reserva')]
class ApiReservaController extends AbstractController
{
    #[Route("/reserva/{id}", name: "getReserva", methods: "GET")]
    public function getReserva(ReservaRepository $rr, int $id): Response
    {

        if ($id === null) {
            # Si es null, las quiere todas
            $reservas = $rr->findAll();
            return $this->json(["reservas" => $reservas, "Success" => true], 200);
        } else {
            // LA RESERVA
            $reserva = $rr->find($id);
            return $this->json(
                [
                    "Reserva" => [
                        "id" => $reserva->getId(),
                        "juego" => $reserva->getJuego(),
                        "mesa" => $reserva->getMesa(),
                        "fecha" => $reserva->getFechaReserva(),
                        "fecha_cancelacion" => $reserva->getFechaCancelacion(),
                        "user" => $reserva->getUsuario(),
                        "asiste" => $reserva->isAsiste()
                    ],
                    "Success" => true
                ],
                200
            );
        }
    }

    #[Route("/reserva", name: "postReserva", methods: "POST")]
    public function postReserva(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->reserva;
        $reserva = new Reserva();

        $datetime = new DateTime();
        $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u', $datos->fecha);

        $reserva->setFechaReserva($fecha);
        $reserva->setAsiste($datos->asiste);

        if ($datos->asiste === true) {
            $reserva->setFechaCancelacion(null);
        } else {
            # Si no asiste
            $datetime = new DateTime();
            $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u', $datos->fechaCancelacion);

            $reserva->setFechaCancelacion($fecha);
        }

        $reserva->setMesa(
            $mr->getRepository(Mesa::class)->find($datos->mesa)
        );
        $reserva->setJuego(
            $mr->getRepository(Juego::class)->find($datos->juego)
        );

        try {
            // La creamos
            $manager = $mr->getManager();
            $manager->persist($reserva);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json([
                'message' => $e->getMessage(),
                "Success" => false
            ], 400);
        }
        $id = $reserva->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al crear la Reserva " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/reserva", name: "putReserva", methods: "PUT")]
    public function putReserva(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->reserva;
        // Cogemos el ID de el Reserva a editar
        $id = $datos->id;
        // Obtenemos el Reserva
        $reserva = $mr->getRepository(Reserva::class)->find($id);
        // Cambiamos todos sus campos
        $reserva->setNombre($datos->nombre);
        $reserva->setPosicionX($datos->pos_x);
        $reserva->setPosicionY($datos->pos_y);
        $reserva->setFecha($datos->fecha);
        $reserva->setMesaId($datos->mesa_id);
        $reserva->setReservada($datos->reservada);

        $manager = $mr->getManager();
        try {
            // Lo mandamos a actualizar
            $manager->persist($reserva);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al editar la reserva " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/reserva", name: "deleteReserva", methods: "DELETE")]
    public function delReserva(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de el Reserva a editar
        $id = $datos->reserva->id;
        // Obtenemos el Reserva
        $reserva = $mr->getRepository(Reserva::class)->find($id);

        $manager = $mr->getManager();
        try {
            // La mandamos a borrar
            $manager->remove($reserva);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al borrar la reserva " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
