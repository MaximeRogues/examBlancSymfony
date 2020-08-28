<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('picture', FileType::class, [
                'label' => 'Photo (en format .jpg ou .png)', 
                'required' => true,
                // 'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader en format jpg ou png',
                    ])
                ]
            ])
            // ->add('password', PasswordType::class, [
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez entrer un mot de passe'
            //         ]),
            //         new Length([
            //             'min' => 8,
            //             'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096
            //         ]),
            //     ]
            // ])
            ->add('email')
            ->add('sector')
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
