<?php

namespace App\Controller\Admin;

use App\Entity\Mesa;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MesaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mesa::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->renderSidebarMinimized()
            ->setPaginatorPageSize(30);
    }


    public function configureFields(string $pageName): iterable
    {

        if (Crud::PAGE_EDIT == $pageName || Crud::PAGE_NEW == $pageName) {
            # 
            return [
                IntegerField::new('largo'),
                IntegerField::new('ancho'),
                IntegerField::new('posicionX')
                    ->setHelp("-1 si está almacenada")
                    ->setValue(-1),
                IntegerField::new('posicionY')
                    ->setHelp("-1 si está almacenada")
                    ->setValue(-1),
                IntegerField::new('sillas'),
            ];
        } else {
            return [
                IdField::new('id'),
                IntegerField::new('largo'),
                IntegerField::new('ancho'),
                IntegerField::new('posicionX'),
                IntegerField::new('posicionY'),
                IntegerField::new('sillas'),
                IntegerField::new('largo'),
                IntegerField::new('ancho'),
                IntegerField::new('posicionX'),
                IntegerField::new('posicionY'),
                IntegerField::new('sillas'),
                // TextEditorField::new('description'),
            ];
        }
    }
}
