<?php

namespace GM\VideothequeBundle\Handler;

use AppBundle\Handler\BaseHandler;

/**
 * Photo Handler.
 *
 */
class FilmHandler extends BaseHandler
{
    protected $answer;
    protected $requestStack;
    protected $em;
    protected $msgGenerator;
    protected $watchdoglogger;
    protected $formManager;
    protected $form;


    public function __construct($requestStack, $em, $watchdoglogger, $msgGenerator, $formManager){
        parent::__construct($requestStack, $em, $watchdoglogger, $msgGenerator, $formManager);
    }

    public function isValidCreateData($form){
        // Code for specif validation form data
        return true;
    }

    public function getFilmsOfCategorieForUser($owner_user_id = null, $categorieId = null){
        $reposiroty = 'GMVideothequeBundle:Film';
        $films = $this->getEntityManager()->getRepository($reposiroty)->getFilmsOfCategorieForUser($owner_user_id, $categorieId);
        return $films;
    }

    public function onDeleteAll($owner_user_id = null, $batch_size = 20){
        $reposiroty = 'GMVideothequeBundle:Film';
        $result = $this->getEntityManager()->getRepository($reposiroty)->onDeleteAll($owner_user_id, $batch_size);
        if($result == true){
            $this->SetFlashBag($this->getMsgGenerator()->Msg_DeleteDB_OK());
        }
        else{
            $this->SetFlashBag($this->getMsgGenerator()->Msg_DeleteDB_NONE(), 'info');
        }
        return $result;
    }



    public function maxFilms(array $criteria = null){
        $reposiroty = 'GMVideothequeBundle:Film';
        $result = $this->getEntityManager()->getRepository($reposiroty)->maxFilms($criteria);
        return $result;
    }


    public function paginator($page, $count, $total = null, $currentTotalReading, array $criteria = null){
        $init_read = false;
        if($page < 1 || $count < 1){
            $init_read = true;
        }
        $count = ($init_read) ? 1 : $count;
        $page = ($init_read) ? 1 : $page; // $count($page - 1)
        $total = ($total != null) ? $total : $this->maxFilms($criteria);
        $paginator['count'] = $count;
        $paginator['total_page'] = intval(ceil($total / $count));
        //$paginator['current_page'] = ($init_read || $page > $paginator['total_page']) ? 1 : $page;
        $paginator['current_page'] = ($init_read) ? 1 : $page;
        /*
        if(($page * $count) > $total && $page > 1){
            $paginator['current_page'] = null;
        }
        */
        $paginator['previous_page'] = ($paginator['current_page'] > 1) && ($paginator['current_page'] <= $paginator['total_page']) ? ($paginator['current_page'] - 1) : null;
        $paginator['next_page'] = ($total - ($page * $count) > 0) ? ($page + 1) : null;
        $paginator['next_record_to_read'] = ($paginator['next_page'] != null) ? ($total - ($count * $page)) : null;
        $init_read = false;
        $paginator['total_entities'] = $total;
        return $paginator;
    }
}