<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add("nombre", TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Escriba su nombre por favor'
                    ]),
                    new Length([
                        'max' => 45,
                        'maxMessage' => 'Nombre demasiado largo (máximo {{ limit }} caracteres)'
                    ])
                ]
            ])
            ->add("apellido1", TextType::class, [
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
            ->add("apellido2", TextType::class, [
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
            ->add("telegram_id", TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Telegram necesario'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
