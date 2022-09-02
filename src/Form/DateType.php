<?php

namespace App\Form;

use App\Entity\Date;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idSortie')
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscritpion')
            ->add('nbInscritpionsMax')
            ->add('nbInscrit')
            ->add('infosSortie')
            ->add('motif')
            ->add('etatSortie')
            ->add('organisateur')
            ->add('participants')
            ->add('campus')
            ->add('lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Date::class,
        ]);
    }
}
