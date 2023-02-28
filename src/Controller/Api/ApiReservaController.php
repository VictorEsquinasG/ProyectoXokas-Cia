<?php

namespace App\Controller\Api;

use App\Entity\Juego;
use App\Entity\Mesa;
use App\Entity\Reserva;
use App\Entity\Tramos;
use App\Entity\Usuario;
use App\Repository\ReservaRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @api de la entidad Reserva
 * @author Víctor Esquinas
 */
#[Route('/api', name: 'api_reserva')]
class ApiReservaController extends AbstractController
{
    #[Route("/reserva/{id}", name: "getReserva", methods: "GET")]
    public function getReserva(ReservaRepository $rr, int $id = null): Response
    {

        if ($id === null) {
            # Si es null, las quiere todas
            $reservas = $rr->findAll();
            return $this->json(
                [
                    "reservas" => $reservas,
                    "Success" => true
                ],
                200
            );
        } else {
            // LA RESERVA
            $reserva = $rr->find($id);  
            return $this->json(
                [
                    "reserva" => [
                        "id" => $reserva->getId(),
                        "juego" => $reserva->getJuego(),
                        "mesa" => $reserva->getMesa(),
                        "fecha" => $reserva->getFechaReserva(),
                        "fechaCancelacion" => $reserva->getFechaCancelacion(),
                        "user" => $reserva->getUsuario(),
                        "asiste" => $reserva->isAsiste(),
                        "tramo" => $reserva->getTramo()
                    ],
                    "Success" => true
                ],
                200
            );
        }
    }

    #[Route("/reserva", name: "postReserva", methods: "POST")]
    public function postReserva(ManagerRegistry $mr, Request $request, Security $security): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->reserva;
        $reserva = new Reserva();

        // dd($datos->fecha);
        $datetime = new DateTime();
        $fecha = $datetime->createFromFormat('Y-m-d', $datos->fecha);

        $reserva->setFechaReserva($fecha);

        if ($datos->asiste === true) {
            $reserva->setFechaCancelacion(null);
            $reserva->setAsiste(true);
        } else {
            # Si no asiste
            $datetime = new DateTime();
            $fecha = $datetime->createFromFormat('Y-m-d', $datos->fechaCancelacion);

            $reserva->setFechaCancelacion($fecha);
            $reserva->setAsiste(false);
        }

        // El usuario que reserva
        if ($datos->usuario !== null) {
            # Si nos da un usuario lo seteamos
            $reserva->setUsuario(
                $mr->getRepository(Usuario::class)->find($datos->usuario)
            );
        }else {
            # Si no, es el "current"
            $reserva->setUsuario(
                $security->getUser()
            );
        }

        $reserva->setMesa(
            $mr->getRepository(Mesa::class)->find($datos->mesa)
        );
        $reserva->setJuego(
            $mr->getRepository(Juego::class)->find($datos->juego)
        );
        $reserva->setTramo(
            $mr->getRepository(Tramos::class)->find($datos->tramo)
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

        // No se puede modificar el usuario que ha hecho la reserva

        $reserva->setMesa(
            $mr->getRepository(Mesa::class)->find($datos->mesa)
        );
        $reserva->setJuego(
            $mr->getRepository(Juego::class)->find($datos->juego)
        );
        $reserva->setTramo(
            $mr->getRepository(Tramos::class)->find($datos->tramo)
        );

        $manager = $mr->getManager();
        try {
            // Lo mandamos a actualizar
            $manager->persist($reserva);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json([
                'message' => $e->getMessage(),
                "Success" => false
            ], 400);
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
