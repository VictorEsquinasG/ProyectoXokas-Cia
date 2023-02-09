<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('general/index.html.twig');
    }

    #[Route('/reservas', name: 'reservar')]
    public function reserva(UsuarioRepository $usuarioRepository, Security $security): Response
    {
        $reservas = [];
        $idUser = $security->getUser()->getId();

        $data = $usuarioRepository->find($idUser)->getReservas();

        foreach ($data as $datos) {
            # Rellenamos
            $reservas[] = [
                'url' => $datos . '', //TODO página de RESERVA_ID
                'img' => '',
                'title' => '',
            ];
        }
        return $this->render('reservas/index.html.twig', [
            'reservas' => $reservas,
        ]);
    }

    #[Route('/reserva/new', name: 'make_reserva')]
    public function newReserva(): Response
    {

        return $this->render('reservas/nueva.html.twig');
    }
}
