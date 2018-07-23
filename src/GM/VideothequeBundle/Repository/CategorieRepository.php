<?php

namespace GM\VideothequeBundle\Repository;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
    public function onReadBy($readBy = 'id', $attrVal = null){ // put array option which contains ()
        if ($readBy == 'all'){
            $answers = $this->getEntityManager()->getRepository('GMVideothequeBundle:Categorie')->findAll();
            return $answers;
        }
        $answers = $this->getEntityManager()->getRepository('GMVideothequeBundle:Categorie')->findBy(array($readBy => $attrVal));
        return $answers;
    }
}
