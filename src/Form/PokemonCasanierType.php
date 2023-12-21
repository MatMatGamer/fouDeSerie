<?php

namespace App\Form;

use App\Entity\Attaque;
use App\Entity\PokemonCasanier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PokemonCasanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('poids')
            ->add('taille')
            ->add('nbPattes')
            ->add('nbHeuresTv')
            ->add('lesAttaques', EntityType::class, [
                'class' => Attaque::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PokemonCasanier::class,
        ]);
    }
}
