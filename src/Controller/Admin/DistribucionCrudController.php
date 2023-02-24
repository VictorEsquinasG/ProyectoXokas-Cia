<?php

namespace App\Controller\Admin;

use App\Entity\Distribucion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DistribucionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Distribucion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm()
            ,
            TextField::new('alias')
            ->setLabel('Disposición (nombre)'),
            DateField::new('fecha')
            ->setLabel('Disposición (fecha)'),
            // TextEditorField::new('description'),
            BooleanField::new('reservada')
            ->hideOnForm(),
            IntegerField::new('posicion_x')
            ->setLabel('Posición (horizontal)'),
            IntegerField::new('posicion_y')
            ->setLabel('Posición (vertical)'),
            AssociationField::new('mesa_id')
            ->setLabel('Mesa')
            ,
        ];
    }
   
}
