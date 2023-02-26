<?php

namespace App\Controller\Api;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @api de la entidad Usuario
 * @author Víctor Esquinas
 */
#[Route("/api", name: "api_usuario")]
class ApiUsuarioController extends AbstractController
{
    #[Route("/usuario/{id}", name: "getUsuario", methods: "GET")]
    public function getUsuario(UsuarioRepository $mr, int $id = null): Response
    {

        if ($id === null) {
            # Si es null, las quiere todas
            $usuarios = $mr->findAll();

            return $this->json([
                "usuarios" => $usuarios,
                "Success" => true
            ], 200);
        } else {
            // cogemos el usuario
            $usuario = $mr->find($id);

            return $this->json([
                "usuario" => [
                    "id" => $usuario->getId(),
                    "nombre" => $usuario->getNombre(),
                    "apellido1" => $usuario->getApellido1(),
                    "apellido2" => $usuario->getApellido2(),
                    "email" => $usuario->getEmail(),
                    "imagen" => $usuario->getImagen(),
                    "telefono" => $usuario->getTelefono(),
                    "roles" => $usuario->getRoles(),
                    "puntos" => $usuario->getPuntos(),
                    "admin" => $usuario->getAdmin()
                ],
                "Success" => true
            ], 200);
        }
    }

    #[Route("/usuario", name: "postUsuario", methods: "POST")]
    public function postUsuario(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        $datos = $datos->usuario;

        $usuario = new Usuario();
        $usuario->setEmail($datos->email);
        $usuario->setNombre($datos->nombre);
        $usuario->setApellido1($datos->apellido1);
        $usuario->setApellido2($datos->apellido2);
        $usuario->setImagen($datos->imagen);
        $usuario->setPuntos($datos->puntos);

        $manager = $mr->getManager();
        try {
            $manager->persist($usuario);
            $manager->flush();
        } catch (PDOException $e) {
            $this->json(['message' => $e->getMessage(), "Success" => false], 400);
        }
        $id = $usuario->getId();
        # Creado con éxito => Devolvemos la ID
        return $this->json(
            [
                "id" => $id,
                "message" => "Éxito al crear el usuario " . $id,
                "Success" => true
            ],
            201
        );
    }

    #[Route("/usuario", name: "putUsuario", methods: "PUT")]
    public function putUsuario(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de el usuario a editar
        $usuarioNueva = $datos->usuario;
        $id = $usuarioNueva->id;
        // Obtenemos el usuario
        $usuario = $mr->getRepository(Usuario::class)->find($id);
        // Cambiamos todos sus campos
        $usuario->setAncho($usuarioNueva->ancho);
        $usuario->setLargo($usuarioNueva->largo);
        $usuario->setSillas($usuarioNueva->sillas);
        $usuario->setPosicionX($usuarioNueva->posicion_x);
        $usuario->setPosicionY($usuarioNueva->posicion_y);

        $manager = $mr->getManager();
        try {
            // La mandamos a actualizar
            $manager->persist($usuario);
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
                "message" => "Éxito al editar el usuario " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }

    #[Route("/usuario", name: "deleteUsuario", methods: "DELETE")]
    public function delUsuario(ManagerRegistry $mr, Request $request): Response
    {
        $datos = json_decode($request->getContent());
        // Cogemos el ID de el usuario a editar
        $id = $datos->usuario->id;
        // Obtenemos el usuario
        $usuario = $mr->getRepository(Usuario::class)->find($id);

        $manager = $mr->getManager();
        // La mandamos a borrar
        try {
            // Borramos
            $manager->remove($usuario);
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
                "message" => "Éxito al borrar el usuario " . $id,
                "Success" => true
            ],
            202 // Aceptado
        );
    }
}
