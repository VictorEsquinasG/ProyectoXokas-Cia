<?php

namespace App\Controller\Admin;

use App\Entity\Mesa;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
        ->setPaginatorPageSize(30)
        ;    
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
