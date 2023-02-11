<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use Doctrine\DBAL\Types\BooleanType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UsuarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Usuario::class;
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
            #
            $campos =
            [
                'email',
                'nombre',
                'apellido1',
                'apellido2',
                ChoiceField::new('roles')
                ->setChoices([
                    'SUPER-ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ADMIN' => 'ROLE_ADMIN',
                    'USER' => 'ROLE_USER',
                ])
                ->allowMultipleChoices()
            ];

            $password = TextField::new('password')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repita su contraseña   '],
                'mapped' => false,
            ]);
            $campos [] = $password;

            return $campos;
        } else {
            # Cuando las liste en la tabla
            return [
                "id",
                "nombre",
                "apellido1",
                "apellido2",
                BooleanField::new('Admin')
                // Boolean ('Admin')
            ];
        }
        
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
