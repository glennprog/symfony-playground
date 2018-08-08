<?php

namespace GM\VideothequeBundle\Handler;

use GM\VideothequeBundle\Entity\Categorie;

/**
 * Photo Handler.
 *
 */
class CategorieHandler
{
    protected $criterias;

    public function __construct(){
        $this->criterias = array( // Get Categories with rich embeded criteria-v3
                'pagination' => array(
                    'enabled' => true,
                    'entity-name' => 'Categorie',
                    'route' => array('route_name' => $this->getRoute('index')), // Get route pagination
                ),
                'entity_class' => array(
                    'class' => Categorie::class,
                    'alias' => 'c',
                ),
                'repository' => array(
                    'class' => 'GMVideothequeBundle:Categorie',
                ),
                'criteria-select' => array('*'),
                'criteria-from' => array(
                    'class' => Categorie::class,
                    'alias' => 'c',
                ),
                'criteria-where' => null
        );
    }

    public function getCriterias($criteria_update  = null){
        return $this->criterias;
    }
    
    public function getRoute($template = 'index'){
        $listRoutes = array(
            'new' => 'categorie_new',
            'index' => 'categorie_index',
            'show' => 'categorie_show',
            'edit' => 'categorie_edit',
        );
        return $listRoutes[$template];
    }

    public function getTwig($template = 'index'){
        $listTemplates = array(
            'new' => 'GMVideothequeBundle:categorie:new.html.twig',
            'index' => 'GMVideothequeBundle:categorie:index.html.twig',
            'show' => 'GMVideothequeBundle:categorie:show.html.twig',
            'edit' => 'GMVideothequeBundle:categorie:edit.html.twig',
        );
        return $listTemplates[$template];
    }

}




