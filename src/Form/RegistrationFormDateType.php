<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Date;



use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegistrationFormDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus',EntityType::class,
                [
                    'class' => Campus::class,
                    'choice_label' => 'nom',

                ])
            ->add('search')
            ->add('dateStart',DateTimeType::class)
            ->add('dateFin',DateTimeType::class)
            ->add('SortieOrganisateur', CheckboxType::class)
            ->add('Sortieinscrit', CheckboxType::class)
            ->add('SortieNonInscrit', CheckboxType::class)
            ->add('SortiePassees', CheckboxType::class)


            ->add('Rechercher', SubmitType::class); // Ajouter le bouton submit

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Date::class,
        ]);
    }
}
