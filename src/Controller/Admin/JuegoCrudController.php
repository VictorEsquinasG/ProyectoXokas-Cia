<?php

namespace App\Controller\Admin;

use App\Entity\Juego;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;

class JuegoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Juego::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->renderSidebarMinimized()
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {

        return
            [
                // IdField::new('id')
                // ->hideOnForm(),
                TextField::new('nombre'),
                TextEditorField::new('descripcion'),
                IntegerField::new('anchoTablero'),
                IntegerField::new('largoTablero'),
                IntegerField::new('minJugadores'),
                IntegerField::new('maxJugadores'),
                ImageField::new('imagen')
                    ->setBasePath('images/uploads/')    
                    ->setUploadDir('public/images/uploads/')
                // ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),

            ];
    }
}
