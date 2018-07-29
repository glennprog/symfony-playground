<?php

namespace GM\VideothequeBundle\Repository;

use GM\VideothequeBundle\Entity\Categorie;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
    /*
    public function onReadBy($readBy = 'id', $attrVal = null, $page, $count){ // put array option which contains ()
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null);
        if ($readBy == 'all'){
            $categories = $this->getEntityManager()->getRepository('GMVideothequeBundle:Categorie')->findAll();
            return $categories;
        }
        $categories = $this->getEntityManager()->getRepository('GMVideothequeBundle:Categorie')->findBy(array($readBy => $attrVal));
        return $categories;
    }
    */

    public function onReadBy($criteria = array(), $page = 1, $count = 10, $orderBy = null){ // put array option which contains ()
        $init_read = false;
        if($page < 1 || $count < 1){
            $init_read = true;
        }
        $limit = ($init_read) ? 1 :  $count;
        $offset = ($init_read) ? 1 : ($count * $page) - $count; // $count($page - 1)
        $init_read = false;
        $categories = $this->getEntityManager()->getRepository('GMVideothequeBundle:Categorie')
            ->findBy(
                $criteria,
                $orderBy,
                $limit,
                $offset
            );
        return $categories;
    }

    public function maxCategorie(array $criteria = null){
        $owner_user_id = $criteria['owner'];
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(categorie.id)');
        $qb->from('GMVideothequeBundle:Categorie','categorie')->where("categorie.owner = :owner_user_id");
        $qb->setParameter('owner_user_id', $owner_user_id);
        $maxCategories = $qb->getQuery()->getSingleScalarResult();
        return $maxCategories;
    }

    public function onDeleteAll($owner_user_id, $batch_size = 20){
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null); 
        $flag_delete_less_one_entity = false;
        $batch_has_alive = true;
        while ($batch_has_alive)
        {
            $batch_has_alive = false;
            $categories = $this->createQueryBuilder('c')
               ->select('c')
               ->where("c.owner = :owner_user_id")
               ->setParameter('owner_user_id', $owner_user_id)
               ->setMaxResults( $batch_size )
               ->getQuery()
               ->getResult()
            ;
            $batch_has_alive = ($categories != null) ? true : false ;
            foreach ($categories as $categorie) {
                $em_remove = $this->_em->remove($categorie);
                $em_flush = $this->_em->flush(); // Executes all deletions.
                $flag_delete_less_one_entity = true;
            }
        }

        return $flag_delete_less_one_entity;
    }

    // ######################  TODO  ###################### //
    public function onDeleteBy($owner_user_id, $deleteBy, $batch_size = 20){
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null); 
        $flag_delete_less_one_entity = false;
        $batch_has_alive = true;
        while ($batch_has_alive)
        {
            $batch_has_alive = false;
            $categories = $this->createQueryBuilder('c')
               ->select('c')
               ->where("c.owner = :owner_user_id")
               ->setParameter('owner_user_id', $owner_user_id)
               >setMaxResults( $batch_size )
               ->getQuery()
               ->getResult()
            ;
            $batch_has_alive = ($categories != null) ? true : false ;
            foreach ($categories as $categorie) {
                $em_remove = $this->_em->remove($categorie);
                $em_flush = $this->_em->flush(); // Executes all deletions.
                $flag_delete_less_one_entity = true;
            }
        }

        return $flag_delete_less_one_entity;
    }
}
