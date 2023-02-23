<?php

namespace App\Controller\Api;

use App\Entity\FechasFestivos;
use App\Repository\FechasFestivosRepository;
use App\Repository\MesaRepository;
use DateTime;

use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @api de la entidad Festivos
 * @author Víctor Esquinas
 */
#[Route('/api', name: 'api_festivos')]
class ApiFestivosController extends AbstractController
{

    #[Route("/festivos/{id}", name: "getfestivos", methods: "GET")]
    public function getfestivos(FechasFestivosRepository $mr, int $id = null): Response
    {

        if ($id === null) {
            $festivos = $mr->findAll();
            # Si es null, los quiere todos
            return $this->json([
                "festivos" => $festivos,
                "Success" => true
            ], 202);
        } else {
            // La fecha del festivo
            $festivos = $mr->find($id);
            return $this->json(
                [
                    "festivo" => [
                        "id" => $festivos->getId(),
                        "fecha" => $festivos->getFecha(),
                    ],
                    "Success" => true
                ],
                200
            );
        }
    }

    #[Route("/festivos/fecha/{date}", name: "getfestivosByFecha", methods: "GET")]
    public function getfestivosByDate(FechasFestivosRepository $mr, string $date): Response
    {

        $date = json_decode($date)->date;

        // PARAMETROS DE LA FECHA
        $ano = $date[0];
        $mes = ($date[1] > 10) ? $date[1] : "0" . $date[1];
        $dia = ($date[2] > 10) ? $date[2] : "0" . $date[2];

        $fecha = $ano . "-" . $mes . "-" . $dia . " 00:00:00";

        $festivos = $mr->getByDate($fecha);
        # festivos POR FECHA
        return $this->json(
            [
                "festivos" => $festivos,
                "Success" => true
            ],
            202
        );
    }

    #[Route("/festivos", name: "postfestivos", methods: "POST")]
    public function postfestivos(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->festivo;
        $festivos = new FechasFestivos();


        $datetime = new DateTime();

        $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u', $datos->fecha);
        $festivos->setFecha($fecha);


        $manager = $mr->getManager();
        try {
            $manager->persist($festivos);
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
        $id = $festivos->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "festivo" => $festivos,
                "message" => "Éxito al crear la festivos " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/festivos", name: "putfestivos", methods: "PUT")]
    public function putfestivos(ManagerRegistry $mr, Request $request, MesaRepository $mesaRepository): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->festivo;
        // Cogemos el ID de el festivos a editar
        $id = $datos->id;
        // Obtenemos el festivos
        $festivos = $mr->getRepository(FechasFestivos::class)->find($id);
        // Cambiamos el campo fecha
        $datetime = new DateTime();
        $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u', $datos->fecha);
        //
        $festivos->setFecha($fecha);

        // Lo mandamos a guardar
        $manager = $mr->getManager();
        try {
            // Lo mandamos a actualizar
            $manager->persist($festivos);
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
                "message" => "Éxito al editar la fecha festiva " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/festivos", name: "deletefestivos", methods: "DELETE")]
    public function delfestivos(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->festivo;
        // Cogemos el ID de el festivos a editar
        $id = $datos->id;
        // Obtenemos el festivos
        $festivos = $mr->getRepository(FechasFestivos::class)->find($id);

        $manager = $mr->getManager();
        try {
            // La mandamos a borrar
            $manager->remove($festivos);
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
                "message" => "Éxito al borrar la festivos " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
