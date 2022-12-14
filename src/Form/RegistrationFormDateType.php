<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Date;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('search',TextType::class,['required'=> false])


            ->add('SortieOrganisateur', CheckboxType::class,
                ['label_attr' => [
                    'class' => 'checkbox-inline',

                ],

                    'required'=> false
                ])
            ->add('Sortieinscrit', CheckboxType::class,
                ['label_attr' => [
                    'class' => 'checkbox-inline',

                ],

                    'required'=> false

                ])
            ->add('SortieNonInscrit', CheckboxType::class,[
                'label_attr' => [
                    'class' => 'checkbox-inline',

                ],

                'required'=> false])
            ->add('SortiePassees', CheckboxType::class,[
                'label_attr' => [
                    'class' => 'checkbox-inline',

                ],

                'required'=> false])


            ->add('Rechercher', SubmitType::class); // Ajouter le bouton submit

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
