<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Pays;
use App\Entity\Serie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('resume')
            ->add('premiereDiffusion', null, [
                'widget' => 'single_text',
            ])
            ->add('nbEpisodes')
            ->add('image')
            ->add(
                'pays',
                EntityType::class,
                [
                    'class' => Pays::class,
                    'choice_label' => 'nom',
                    'multiple' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.nom', 'ASC');
                    }
                ]
            )
            ->add(
                'lesGenres',
                EntityType::class,
                [
                    'class' => Genre::class,
                    'choice_label' => 'libelle',
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.libelle', 'ASC');
                    },
                    "expanded" => true
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
