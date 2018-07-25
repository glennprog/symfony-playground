<?php

namespace GM\VideothequeBundle\Repository;

/**
 * FilmRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FilmRepository extends \Doctrine\ORM\EntityRepository
{
    public function onReadBy($readBy = 'id', $attrVal = null){ // put array option which contains ()
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null); 
        if ($readBy == 'all'){
            $films = $this->getEntityManager()->getRepository('GMVideothequeBundle:Film')->findAll();
            return $films;
        }
        $films = $this->getEntityManager()->getRepository('GMVideothequeBundle:Film')->findBy(array($readBy => $attrVal));
        return $films;
    }

    public function getFilmsOfCategorieForUser($owner_user_id = null, $categorieId = null){ // put array option which contains () 
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null); 
        $films = $this->createQueryBuilder('f')
        ->where("f.owner = :owner_user_id")
        ->andWhere('f.categorie = :categorieId')
        ->setParameter('owner_user_id', $owner_user_id)
        ->setParameter('categorieId', $categorieId)
        ->orderBy('f.titre', 'ASC')
        ->getQuery()
        ->getResult()
        ;
        return $films;
    }

    public function onDeleteAll($owner_user_id, $batch_size = 20){
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null); 
        $offset = 0;
        $flag_delete_less_one_entity = false;
        $batch_has_alive = true;
        while ($batch_has_alive)
        {
            $batch_has_alive = false;
            $films = $this->createQueryBuilder('c')
               ->select('c')
               ->where("c.owner = :owner_user_id")
               ->setParameter('owner_user_id', $owner_user_id)
               ->setMaxResults( $batch_size )
               ->getQuery()
               ->setFirstResult($offset)
               ->setMaxResults($batch_size)
               ->getResult()
               
            ;
            $batch_has_alive = ($films != null) ? true : false ;
            foreach ($films as $film) {
                $em_remove = $this->_em->remove($film);
                $em_flush = $this->_em->flush(); // Executes all deletions.
                $flag_delete_less_one_entity = true;
            }
        }

        return $flag_delete_less_one_entity;
    }
}
