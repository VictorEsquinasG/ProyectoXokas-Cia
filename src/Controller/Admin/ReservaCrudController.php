<?php

namespace App\Controller\Admin;

use App\Entity\Reserva;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class ReservaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reserva::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->renderSidebarMinimized()
        ->setPaginatorPageSize(30)
        ;    
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            DateField::new('fechaReserva'),
            DateField::new('fechaCancelacion'),
            BooleanField::new('asiste'),
            ChoiceField::new('usuario'),
            ChoiceField::new('juego'),
            ChoiceField::new('mesa'),
    
        ];
    }
   
}
