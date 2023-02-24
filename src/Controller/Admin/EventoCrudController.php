<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Evento::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->renderSidebarMinimized()
            ->setPaginatorPageSize(30);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setDisabled(),
            TextField::new('nombre')
                ->setRequired(false),
            DateField::new('fecha'),
            IntegerField::new('num_max_asistentes')
                ->setLabel('Nº máximo de asistentes')
                ->setRequired(true),
            AssociationField::new('usuarios')
                ->autocomplete()
                ->setRequired(true),
            AssociationField::new('juegos')
                ->autocomplete()
                ->setRequired(true),
            AssociationField::new('tramo'),
        ];
    }
}
