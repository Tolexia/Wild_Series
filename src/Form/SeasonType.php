<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, ['label' => "Numéro de Saison"])
            ->add('year', IntegerType::class, ['label' => "Année de Sortie"])
            ->add('description', TextareaType::class, ['label' => "Description"])
            ->add('program', null, ['choice_label' => 'title'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
