<?php

namespace App\Controller;

use App\Entity\Distribucion;
use App\Repository\DistribucionRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_distribucion')]
class ApiDistribucionController extends AbstractController
{
    #[Route("/distribucion/{id}", name: "getDistribucion", methods: "GET")]
    public function getDistribucion(DistribucionRepository $mr, int $id): Response
    {

        
        if ($id === null) {
            $distribuciones = $mr->findAll();
            # Si es null, los quiere todos
            return $this->json(["Distribucion" => $distribuciones,"Success"=>true], 202);
        } else {
            // La distribucion
            $distribucion = $mr->find($id);
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
        $datos = json_decode($request->getContent());
        $datos = $datos->distribucion;
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
        try {
            $manager->persist($distribucion);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message'=>$e->getMessage(),"Success"=>false],400);
        }
        $id = $distribucion->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al crear la distribucion " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/distribucion", name: "putDistribucion", methods: "PUT")]
    public function putDistribucion(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->distribucion;
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
        try {
            // Lo mandamos a actualizar
            $manager->persist($distribucion);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message'=>$e->getMessage(),"Success"=>false],400);
        }

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
        $datos = json_decode($request->getContent());
        $datos = $datos->distribucion;
        // Cogemos el ID de el Distribucion a editar
        $id = $datos->id;
        // Obtenemos el Distribucion
        $distribucion = $mr->getRepository(Distribucion::class)->find($id);

        $manager = $mr->getManager();
        try {
            // La mandamos a borrar
            $manager->remove($distribucion);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message'=>$e->getMessage(),"Success"=>false],400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al borrar la distribucion " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
