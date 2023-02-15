<?php

namespace App\Controller\Admin;

use App\Entity\Reserva;
use Doctrine\DBAL\Query\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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

                AssociationField::new('tramo')
                //TODO tramo disponible
                // ->setQueryBuilder()
                ,

                AssociationField::new('Usuario')
                ->setRequired(true)
                ->autocomplete()
                ,
                AssociationField::new('Juego')
                ->setRequired(true)
                ->autocomplete()
                ,
                AssociationField::new('Mesa')
                ->setRequired(true)
                //TODO mesa con tamaño suficiente para el juego
                // ->setQueryBuilder(tramoDisponible(QueryBuilder $qb))
                ->autocomplete()
                ,
                AssociationField::new('tramo')
                
            ];
        }else {
            return [
                IdField::new('id'),
                DateField::new('fechaReserva')
                ->setLabel('Fecha de la reserva'),
                DateField::new('fechaCancelacion')
                ->setLabel('Fecha de cancelación'),
                AssociationField::new('tramo'),
                BooleanField::new('asiste')
                ->setDisabled(true),

                AssociationField::new('Usuario'),
                AssociationField::new('Juego'),
                AssociationField::new('Mesa'),
            ];
        }
    }

    public function tramoDisponible(QueryBuilder $qb)
    {
        # code...
    }
   
}
