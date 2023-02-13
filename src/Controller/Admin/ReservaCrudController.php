<?php

namespace App\Controller\Admin;

use App\Entity\Reserva;
use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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

        if (Crud::PAGE_EDIT == $pageName || Crud::PAGE_NEW == $pageName) {
            # Devolvemos los campos a editar
            return [
                // IdField::new('id'),
                DateField::new('fechaReserva')
                ->setLabel('Fecha de la reserva'),
                DateField::new('fechaCancelacion')
                ->setLabel('Fecha de cancelación')
                ->setRequired(false),
                BooleanField::new('asiste'),

                AssociationField::new('usuario')
                // ->setRequired(true)
                ,
                AssociationField::new('juego')
                // ->setRequired(true)
                ,
                AssociationField::new('mesa')
                // ->setRequired(true)
                ,
                
            ];
        }else {
            return [
                IdField::new('id'),
                DateField::new('fechaReserva')
                ->setLabel('Fecha de la reserva'),
                DateField::new('fechaCancelacion')
                ->setLabel('Fecha de cancelación'),
                BooleanField::new('asiste')
                ->setDisabled(true),
                ChoiceField::new('usuario'),
                ChoiceField::new('juego'),
                ChoiceField::new('mesa'),
            ];
        }
    }
   
}
