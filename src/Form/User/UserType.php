<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('name', TextType::class, [
                'label' => 'Nom(s) complet',
                'attr' => [
                    'placeholder' => 'Entrez un nom complet',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom complet.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 250
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'exemple@domaine.com'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email.',
                    ]),
                    new Email(['message' => 'L’adresse email n’est pas valide.'])
                ]
            ])*/
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Collaborateur' => 'ROLE_COLLABORATEUR',
                    'Manager' => 'ROLE_MANAGER',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le rôle est obligatoire.',
                    ]),
                ]
            ])
            ->add('lockAccess', CheckboxType::class, [
                'label' => 'Bloquer le compte',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
