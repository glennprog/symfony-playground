<?php

namespace GM\VideothequeBundle\Repository;

use GM\VideothequeBundle\Entity\Categorie;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{

    public function getOffsetLimit(array $criteria_pagination = null){
        // Get pagination elements
        $page = $criteria_pagination['page'];
        $count = $criteria_pagination['count'];

        // Process pagination variables and set the limit and offset
        $init_read = false;
        if($page < 1 || $count < 1){
            $init_read = true;
        }
        $limit = ($init_read) ? 1 :  $count;
        $offset = ($init_read) ? 1 : ($count * $page) - $count; // $count($page - 1)
        $init_read = false;

        dump("offset = " . $offset);
        dump("limit = " . $limit);

        return array('limit'=>$limit, 'offset'=>$offset);
    }

    public function buildQueryByCriterias(array $criteria = null){        
        // Get the entity class for setting repository by entity manager
        $entityClass = $criteria['entity_class']['class'];
        
        // Get the entity Alias
        $entityAlias = $criteria['entity_class']['alias'];

        // Build select query
        $querySelect_all_flag = false;
        $querySelect = "select";
        foreach ($criteria['criteria-select'] as $criteria_select) {
            if($criteria_select == '*'){
                $querySelect_all_flag = true;
                $querySelect .= " " . $entityAlias;
            }
            else if(stristr($criteria_select, "(") != false){
                /* example
                str = "COUNT(id)";
                Match 1
                Full match	0-9	`COUNT(id)`
                Group 1.	0-6	`COUNT(`
                Group 2.	6-8	`id`
                Group 3.	8-9	`)`
                */
                $pattern = '/(.{1,}\()(.{1,})(\))/i';
                $replacement = '${1} ' . $entityAlias . '.$2 $3';
                $res = preg_replace($pattern, $replacement, $criteria_select);
                $querySelect .= 
                             " "
                             . $res
                             . ",";
            }
            else{
                $querySelect .= 
                             " "
                             . $entityAlias
                             . "."
                             . $criteria_select
                             . ",";
            }
        }
        if($querySelect_all_flag == false){
            $querySelect = substr($querySelect, 0, -1);
        }

        // Build from query
        $queryFrom = "from";
        $queryFrom .= " " . $criteria['criteria-from']['class']  . " " . $criteria['criteria-from']['alias'];

        // Build and set where criteria : criteria-where
        $queryWhere = "where";
        foreach ($criteria['criteria-where'] as $criteria_where) {
            if($criteria_where['criterias-condition'] == null){
                $queryWhere .= " ( ";
                for ($i=0; $i < count($criteria_where['criterias']); $i++) {
                    $queryWhere .= 
                        $entityAlias
                        . "." 
                        . $criteria_where['criterias'][$i]['column']['name'] 
                        . " "
                        . $criteria_where['criterias'][$i]['operator']['affectation']
                        . " "
                        . "'" 
                        . $criteria_where['criterias'][$i]['column']['value'] . "'";
                }
            }
            else{
                $queryWhere .= $criteria_where['criterias-condition'] . " ( ";
                for ($i=0; $i < count($criteria_where['criterias']); $i++) {
                    if($criteria_where['criterias'][$i]['operator']['condition'] != null){
                        $queryWhere .= 
                                    " "
                                    . $criteria_where['criterias'][$i]['operator']['condition']
                                    . " ";
                    }
                    $queryWhere .= 
                            $entityAlias
                            . "." 
                            . $criteria_where['criterias'][$i]['column']['name'] 
                            . " "
                            . $criteria_where['criterias'][$i]['operator']['affectation']
                            . " "
                            . "'" 
                            . $criteria_where['criterias'][$i]['column']['value'] . "'";
                }
            }
            $queryWhere .= " ) ";
        }

        // Build query
        $query = $querySelect . " ". $queryFrom . " " . $queryWhere;
        dump($query);

        /**** Using pure DQL without setParameters function ****/
        $query_em = $this->_em->createQuery($query);

        // Return query built
        return $query_em;
    }

    public function executeQuery($qb){
        $resutl = $qb->getResult();
        return $resutl;
    }

    public function findByCriterias(array $criteria = null){
        $qb = $this->buildQueryByCriterias($criteria);
        $offsetLimit = $this->getOffsetLimit($criteria['pagination']);
        $result = $this->executeQuery($qb->setFirstResult($offsetLimit['offset'])->setMaxResults($offsetLimit['limit']));
        return $result;
    }

    public function maxEntitiesByCriterias(array $criteria = null){
        $criteria['criteria-select'] = array('count(id)');
        $qb = $this->buildQueryByCriterias($criteria);
        $result = $this->executeQuery($qb);
        return $result[0]['1'];
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
