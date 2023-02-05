<?php

namespace App\Controller;

use App\Entity\Mesa;
use App\Repository\MesaRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api",name:"api_mesa")]
class ApiMesaController extends AbstractController
{
    #[Route("/mesa/{id}", name: "getMesa", methods: "GET")]
    public function getMesa(MesaRepository $mr, int $id = null): Response
    {
        
        if ($id === null) {
            # Si es null, las quiere todas
            $mesas = $mr->findAll();
            return $this->json(["mesas" => $mesas,"Success"=>true], 200);
        } else {
            // cogemos la mesa
            $mesa = $mr->find($id);
            return $this->json(["mesa" => ["id" => $mesa->getId(),
            "largo" => $mesa->getLargo(),
            "ancho" => $mesa->getAncho(),
            "sillas" => $mesa->getSillas(),
            "posicion_x" => $mesa->getPosicionX(),
            "posicion_y" => $mesa->getPosicionY()], "Success" => true], 200);
        }
    }

    #[Route("/mesa", name: "postMesa", methods: "POST")]
    public function postMesa(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->mesa;
    
        $mesa = new Mesa();
        $mesa->setAncho($datos->ancho);
        $mesa->setLargo($datos->largo);
        $mesa->setSillas($datos->sillas);
        $mesa->setPosicionX($datos->posicion_x);
        $mesa->setPosicionY($datos->posicion_y);

        $manager = $mr->getManager();
        try {
            $manager->persist($mesa);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message'=>$e->getMessage(),"Success"=>false],400);
        }
        $id = $mesa->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al crear la mesa " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/mesa", name: "putMesa", methods: "PUT")]
    public function putMesa(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de la mesa a editar
        $mesaNueva = $datos->mesa;
        $id = $mesaNueva->id;
            // Obtenemos la mesa
        $mesa = $mr->getRepository(Mesa::class)->find($id);
            // Cambiamos todos sus campos
        $mesa->setAncho($mesaNueva->ancho);
        $mesa->setLargo($mesaNueva->largo);
        $mesa->setSillas($mesaNueva->sillas);
        $mesa->setPosicionX($mesaNueva->posicion_x);
        $mesa->setPosicionY($mesaNueva->posicion_y);

        $manager = $mr->getManager();
        try {
            // La mandamos a actualizar
            $manager->persist($mesa);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message'=>$e->getMessage(),"Success"=>false],400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
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
        try {
            // Borramos
            $manager->remove($mesa);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message'=>$e->getMessage(),"Success"=>false],400);
        }

        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "message" => "Éxito al borrar la mesa " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
