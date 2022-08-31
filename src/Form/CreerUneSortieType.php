<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Date;
use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Types\Integer;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreerUneSortieType extends AbstractType
{
    // form pour creer une sortie

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('dateLimiteInscritpion')
            ->add('nbInscritpionsMax')
            ->add('duree')
            ->add('infosSortie')
            ->add('campus', EntityType::class,
                [
                    'class' => Campus::class,
                    'choice_label' => 'nom',

                ])


            ->add('lieu', EntityType::class,
                [
                    'class' => Lieu::class,
                    'choice_label' => 'nom',
                ])






        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Date::class,
        ]);
    }
}
