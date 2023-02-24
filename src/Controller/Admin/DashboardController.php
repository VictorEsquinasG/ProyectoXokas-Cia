<?php

namespace App\Controller\Admin;

use App\Entity\Distribucion;
use App\Entity\Evento;
use App\Entity\FechasFestivos;
use App\Entity\Juego;
use App\Entity\Mesa;
use App\Entity\Reserva;
use App\Entity\Tramos;
use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)

        return $this->render('admin/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('XOKAS & CO.')
            ->setFaviconPath('images/logo3.png');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Menú', 'fa fa-home'),
            
            MenuItem::linkToCrud('Usuarios','fa fa-users', Usuario::class),
            MenuItem::subMenu('Mantenimiento', 'fa fa-cogs')->setSubItems([
                MenuItem::linkToCrud('Juegos','fa fa-chess', Juego::class),
                MenuItem::linkToCrud('Reservas','fa fa-paperclip', Reserva::class),
                MenuItem::linkToCrud('Eventos','fa fa-glass', Evento::class),
                // MenuItem::linkToCrud('Distribuciones','fa fa-object-group', Distribucion::class),
            ]),

            MenuItem::section('Mesas y distribución', 'fa fa-object-group'),
            MenuItem::linkToCrud('Mesas','fa fa-clone', Mesa::class),
            MenuItem::linkToCrud('Mesas en disposiciones especiales','fas fa-arrows-alt', Distribucion::class),

            MenuItem::section('Fechas', 'fa fa-calendar'),
            MenuItem::linkToCrud('Festividades','fa fa-calendar-times-o',FechasFestivos::class),
            MenuItem::linkToCrud('Tramos horarios','fa fa-clock',Tramos::class),
            MenuItem::section('Extras', 'fa fa-ellipsis-h'),
            MenuItem::linkToUrl('Buscar con google', 'fab fa-google', 'https://google.com'),
            MenuItem::linkToRoute('Más mantenimiento', 'fa fa-cog', 'app_mantenimiento'),
            MenuItem::linkToRoute('Volver al inicio', 'fa fa-reply', 'home'),
        ];
        // yield MenuItem::linkToCrud('Mesas', 'fas fa-list', Mesa::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    /* Sobreescribimos el menu de usuario (a la derecha) */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Es aconsejable llamar al 'padre' y añadir cosas
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getUserIdentifier())
            // No mostramos su email hasta que no haga clic en su imagen de perfil
            ->displayUserName(false)

            // you can return an URL with the avatar image
            // ->setAvatarUrl('https://...')
            // ->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            // ->displayUserAvatar(false)
            // you can also pass an email address to use gravatar's service
            // ->setGravatarEmail($user->getMainEmailAddress())

            // you can use any type of menu item, except submenus
            ->addMenuItems([ //TODO redireccionar a su página de perfil
                MenuItem::linkToRoute('Mi perfil', 'fa fa-id-card', '...', ['...' => '...']),
                MenuItem::linkToRoute('Ajustes', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                MenuItem::linkToLogout('Cerrar sesión', 'fa fa-sign-out'),
            ]);
    }
    

}
