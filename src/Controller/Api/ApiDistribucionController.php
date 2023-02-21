<?php

namespace App\Controller\Api;

use App\Entity\Distribucion;
use App\Entity\Mesa;
use App\Repository\DistribucionRepository;
use App\Repository\MesaRepository;
use DateTime;

use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @api de la entidad Distribucion
 * @author Víctor Esquinas
 */
#[Route('/api', name: 'api_distribucion')]
class ApiDistribucionController extends AbstractController
{

    #[Route("/distribucion/{id}", name: "getDistribucion", methods: "GET")]
    public function getDistribucion(DistribucionRepository $mr, int $id = null): Response
    {

        if ($id === null) {
            $distribuciones = $mr->findAll();
            # Si es null, los quiere todos
            return $this->json([
                "distribuciones" => $distribuciones,
                "Success" => true
            ], 202);
        } else {
            // La distribucion
            $distribucion = $mr->find($id);
            return $this->json(["distribucion" => [
                "id" => $distribucion->getId(),
                "pos_x" => $distribucion->getPosicionX(),
                "pos_y" => $distribucion->getPosicionY(),
                "fecha" => $distribucion->getFecha(),
                "mesa" => $distribucion->getMesaId()->getId(),
                "alias" => $distribucion->getAlias(),
                "reservada" => $distribucion->isReservada()
            ], "Success" => true], 200);
        }
    }

    #[Route("/distribucion", name: "postDistribucion", methods: "POST")]
    public function postDistribucion(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->distribucion;
        $distribucion = new Distribucion();

        $rep = $mr->getRepository(Mesa::class);
        $mesa = $rep->find($datos->mesa_id); 
        $distribucion->setMesaId($mesa);
        $distribucion->setPosicionX($datos->pos_x);
        $distribucion->setPosicionY($datos->pos_y);
        $distribucion->setAlias($datos->alias);
        $datetime = new DateTime();
        
        $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u',$datos->fecha);
        $distribucion->setFecha($fecha);

        if (isset($datos->reservada)) {
            # Si sabemos si está reservada o no
            $distribucion->setReservada($datos->reservada);
        } else {
            # Por defecto, está libre
            $distribucion->setReservada(false);
        }

        $manager = $mr->getManager();
        try {
            $manager->persist($distribucion);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
        }
        $id = $distribucion->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "distribucion" => $distribucion,
                "message" => "Éxito al crear la distribucion " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/distribucion", name: "putDistribucion", methods: "PUT")]
    public function putDistribucion(ManagerRegistry $mr, Request $request, MesaRepository $mesaRepository): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->distribucion;
        // Cogemos el ID de el Distribucion a editar
        $id = $datos->id;
        // Obtenemos el Distribucion
        $distribucion = $mr->getRepository(Distribucion::class)->find($id);
        // Cambiamos todos sus campos
        $distribucion->setPosicionX($datos->pos_x);
        $distribucion->setPosicionY($datos->pos_y);
        // La fecha
        $datetime = new DateTime();
        $fecha = $datetime->createFromFormat('Y-m-d H:i:s.u',$datos->fecha);
        // dd(["original" => $datos->fecha, "fecha" => $fecha]);
        $distribucion->setFecha($fecha);
        //
        $distribucion->setMesaId(
            $mesaRepository->find($datos->mesa_id)            
        );
        $distribucion->setAlias($datos->alias);
        $distribucion->setReservada($datos->reservada);

        $manager = $mr->getManager();
        try {
            // Lo mandamos a actualizar
            $manager->persist($distribucion);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(),
            "Success" => false
        ], 400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
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
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
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

    /* OTROS */

    /**
     * Método que devuelve todas las distribuciones con el nombre dado
     */
    #[Route("/distribucion/alias/{name}", name: "getDistribucionByName", methods: "GET")]
    public function getDistribucionByName(DistribucionRepository $mr, string $name): Response
    {
        // Las distribuciones que se nos pide
        $distribuciones = $mr->findBy([
            "alias" => $name
        ]);

        return $this->json([
            "distribuciones" => $distribuciones,
            "Success" => true
        ], 200);
    }
}
