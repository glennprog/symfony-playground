<?php

namespace GM\QuestionAnswersBundle\Repository;

/**
 * AnswerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnswerRepository extends \Doctrine\ORM\EntityRepository
{
    public function onReadByBy($readBy = 'id', $attrVal = null){ // put array option which contains ()
        switch ($readBy) {
            case 'id':
                $answers = $this->getEntityManager()->getRepository('GMQuestionAnswersBundle:Answer')->findById($attrVal);
                break;

            case 'all':
                $answers = $this->getEntityManager()->getRepository('GMQuestionAnswersBundle:Answer')->findAll();
                break;

            case 'answer':
                $answers = $this->getEntityManager()->getRepository('GMQuestionAnswersBundle:Answer')->findByWording($attrVal);    
                break;

            default:
                $answers = $this->getEntityManager()->findById($attrVal);
                break;
        }
        return $answers;
    }
}
