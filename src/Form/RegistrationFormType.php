<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'type' => PasswordType::class,
                            'first_options' => ['label' => 'Contraseña'],
                            'second_options' => ['label' => 'Repita su contraseña'],
                'invalid_message'=> 'Las contraseñas no coinciden',
                'constraints' => [
                
                    new NotBlank([
                        'message' => 'Elige una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Tu contraseña no llega a {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add("nombre", null, [
                'constraints' => [
                    
                    new Length([
                        'max' => 45,
                        'maxMessage' => 'Nombre demasiado largo (máximo {{ limit }} caracteres)'
                    ])
                ]
            ])
            ->add("apellido1", null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Escriba su primer apellido por favor'
                    ]),
                    new Length([
                        'max' => 45,
                        'maxMessage' => 'Nombre demasiado largo (máximo {{ limit }} caracteres)'
                    ])
                ]
            ])
            ->add("apellido2", null, [
                'constraints' => [
                    new Length([
                        'max' => 45,
                        'maxMessage' => 'Nombre demasiado largo (máximo {{ limit }} caracteres)'
                    ])
                ]
            ])
            ->add("telefono", TelType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Número de teléfono necesario'
                    ]),
                    new Length([
                        'max' => 45,
                        'maxMessage' => 'Nombre demasiado largo (máximo {{ limit }} caracteres)'
                    ])
                ]
            ])
            ->add("imagen", FileType    ::class)
            ->add("telegram_id", null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Telegram necesario'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
