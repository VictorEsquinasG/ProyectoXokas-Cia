<?php

namespace App\Controller\Api;

use App\Entity\Tramos;
use App\Repository\TramosRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @api de la entidad Tramo
 * @author Víctor Esquinas
 */
#[Route("/api", name: "api_tramos")]
class ApiTramosController extends AbstractController
{
    #[Route("/tramo/{id}", name: "getTramo", methods: "GET")]
    public function getTramo(TramosRepository $mr, int $id = null): Response
    {

        if ($id === null) {
            # Si es null, las quiere todas
            $tramos = $mr->findAll();

            return $this->json([
                "tramos" => $tramos,
                "Success" => true
            ],
            200
        );
        } else {
            // cogemos el tramo
            $tramo = $mr->find($id);

            return $this->json([
                "tramo" => [
                    "id" => $tramo->getId(),
                    "hora" => [
                        "inicio" => $tramo->getHoraInicio(),
                        "fin" => $tramo->getHoraFin(),
                    ]
                ],
                "Success" => true
            ], 200);
        }
    }

    #[Route("/tramo", name: "postTramo", methods: "POST")]
    public function postTramo(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->tramo;

        $tramo = new Tramos();
        $tramo->setHoraInicio($datos->horaInicio);
        $tramo->setHoraFin($datos->horaFin);

        $manager = $mr->getManager();
        try {
            $manager->persist($tramo);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
        }
        $id = $tramo->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al crear el tramo " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/tramo", name: "putTramo", methods: "PUT")]
    public function putTramo(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de la tramo a editar
        $tramoNuevo = $datos->tramo;
        $id = $tramoNuevo->id;
        // Obtenemos el tramo
        $tramo = $mr->getRepository(Tramos::class)->find($id);
        // Cambiamos todos sus campos
        $tramo->setAncho($tramoNuevo->horaInicio);
        $tramo->setLargo($tramoNuevo->horaFin);
        

        $manager = $mr->getManager();
        try {
            // La mandamos a actualizar
            $manager->persist($tramo);
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
                "message" => "Éxito al editar el tramo " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/tramo", name: "deleteTramo", methods: "DELETE")]
    public function delTramo(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de la tramo a editar
        $id = $datos->tramo->id;
        // Obtenemos el tramo
        $tramo = $mr->getRepository(Tramos::class)->find($id);

        $manager = $mr->getManager();
        // La mandamos a borrar
        try {
            // Borramos
            $manager->remove($tramo);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al borrar el tramo " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
