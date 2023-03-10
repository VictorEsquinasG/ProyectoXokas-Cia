<?php

namespace App\Form;

use App\Entity\Juego;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JuegoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('minJugadores')
            ->add('maxJugadores')
            //  tipo file cuando tiene una imagen no nos carga porque es 'STRING'
            ->add('imagen', FileType::class,[
                'data_class' => null
                ])->setRequired(false)
            // ->
            ->add('anchoTablero')
            ->add('largoTablero')
            ->add('descripcion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
        ]);
    }
}
