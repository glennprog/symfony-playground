<?php

namespace GM\QuestionAnswersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use GM\QuestionAnswersBundle\Entity\Question;


class AnswerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('wording')->add('question');
        
        $builder
        ->add('wording')
        ->add('question', EntityType::class, array(
            'required' => false,
            'class' => Question::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.wording', 'ASC');
            },
            'required' => true,
        ));
        
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GM\QuestionAnswersBundle\Entity\Answer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gm_questionanswersbundle_answer';
    }


}
