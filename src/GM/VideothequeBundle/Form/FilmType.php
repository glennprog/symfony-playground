<?php

namespace GM\VideothequeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use GM\VideothequeBundle\Entity\Categorie;

class FilmType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')->add('description')
        ->add('date_sortie', DateType::class, array(
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'yyyy-MM-dd',
            ))
        ->add('categorie', EntityType::class, array(
            'class' => Categorie::class,
            'choice_label' => function ($category) {
                return $category->getNom();
            }
            ))
        /*or ->add('categorie'); as we have __toString method defined*/
        ;
        
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GM\VideothequeBundle\Entity\Film'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gm_videothequebundle_film';
    }


}
