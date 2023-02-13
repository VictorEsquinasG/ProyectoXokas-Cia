<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            DateTimeField::new('fecha'),
            IntegerField::new('numMaxAsistentes')
                ->setRequired(true),
            AssociationField::new('usuarios')
                ->setRequired(true),
            AssociationField::new('juegos')
                ->setRequired(true),
            AssociationField::new('tramo'),
        ];
    }
}
