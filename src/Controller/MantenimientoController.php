<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Form\JuegoFormType;
use App\Repository\DistribucionRepository;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mantenimiento')]
class MantenimientoController extends AbstractController
{

    #[Route('/', name: 'app_mantenimiento')]
    public function mantenimiento(): Response
    {
        return $this->render('mantenimiento/index.html.twig');
    }

   
    
    #[Route('/reservas', name: 'mantenimiento_reservas')]
    public function mant_reservas(): Response
    {
        return $this->render('mantenimiento/editaReserva.html.twig');
    }

    #[Route('/sala', name: 'mueve_mesas')]
    public function mesas(DistribucionRepository $distribucionRepository): Response
    {
        $disposiciones = [];
        $nombreDistribuciones = [];
        # Pedimos las distribuciones
        $data = $distribucionRepository->getOrderedByName();

        # Ordenamos por fecha
        foreach ($data as $dato) {
            $nombre = $dato->getAlias();
            # Cogemos el nombre que tiene cada distribucion
            if (!in_array($nombre, $nombreDistribuciones)) {
                #Si se trata de un nuevo nombre
                $nombreDistribuciones[] = $nombre;
                $disposiciones[] = ["name" => $nombre, "value" => $nombre];
            }
        }

        return $this->render('mesas/salaMesas.html.twig', [
            "disposiciones" => $disposiciones
        ]);
    }

    #[Route('/juegos', name: 'mantenimiento_juegos')]
    public function mant_juegos(JuegoRepository $jr): Response
    {
        return $this->render('mantenimiento/juegos.html.twig', [
            "juegos" => $jr->findAll()
        ]);
    }

    #[Route('/juego/editar/{id}', name: 'app_editar_juego')]
    public function edita_juegos(Request $request, JuegoRepository $juegoRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        # Creamos el repositorio
        $juego = $juegoRepository->find($id);
        $form = $this->createForm(JuegoFormType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # Si ha subido una foto
            if ($file = $form['imagen']->getData()) {
                $nombreFichero = $file->getClientOriginalName();
                // Cogemos la imagen que ha subido
                $file->move('images/uploads', $nombreFichero);
                // Apuntamos a la imagen
                $juego->setImagen($nombreFichero);
            }
            
            // Lo persistimos
            $entityManager->persist($juego);
            $entityManager->flush();

            // Damos feedback 
            $this->addFlash(
                'notice',
                'Tus cambios se han guardado!'
            );

            return $this->redirectToRoute('mantenimiento_juegos');
        }

        return $this->render('mantenimiento/editaJuego.html.twig', [
            "juego" => $juego,
            "juegoForm" => $form->createView()
        ]);
    }

    #[Route('/juego/borrar/{id}', name: 'app_borrar_juego')]
    public function borra_juegos(EntityManagerInterface $entityManager, int $id)
    {
        # Borramos el juego seleccionado
        $jr = $entityManager->getRepository(Juego::class);
        $jogo = $jr->find($id);

        # Lo borramos
        $entityManager->remove($jogo);
        $entityManager->flush();

        # Por último, lo reenviamos al listado
        return $this->redirectToRoute('mantenimiento_juegos');
    }
    
    #[Route('/juego/crear', name: 'app_juego_nuevo')]
    public function crea_juego(EntityManagerInterface $entityManager, Request $request)
    {
        # Creamos el objeto y su formulario
        $juego = new Juego();
        $form = $this->createForm(JuegoFormType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # Si ha subido una foto
            if ($file = $form['imagen']->getData()) {
                $nombreFichero = $file->getClientOriginalName();
                // Cogemos la imagen que ha subido
                $file->move('images/uploads', $nombreFichero);
                // Apuntamos a la imagen
                $juego->setImagen($nombreFichero);
            }
            
            // Lo persistimos
            $entityManager->persist($juego);
            $entityManager->flush();

            // Damos feedback 
            $this->addFlash(
                'notice',
                'Juego creado con éxito!'
            );

            return $this->redirectToRoute('mantenimiento_juegos');
        }
        /* FORMULARIO PARA CREAR UN JUEGO NUEVO */
        return $this->render('mantenimiento/editaJuego.html.twig', [
            "juego" => $juego,
            "juegoForm" => $form->createView()
        ]);
    }
}
