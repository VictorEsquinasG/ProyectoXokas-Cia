<?php

namespace App\Controller\Admin;

use App\Entity\Tramos;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class TramosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tramos::class;
    }

    
    /* public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->setDisabled(true)
            ->hideOnForm(),
            DateField::new('fecha'),
        ];
    } */
    
}
