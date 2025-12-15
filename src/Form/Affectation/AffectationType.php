<?php

namespace App\Form\Affectation;

use App\Entity\Affectation;
use App\Form\Autocomplete\ProjetField;
use App\Form\Autocomplete\RoleManyField;
use App\Form\Autocomplete\UserField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('projet', ProjetField::class, [])
            ->add('user', UserField::class, [])
            ->add('roles', RoleManyField::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}
