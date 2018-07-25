<?php

namespace GM\VideothequeBundle\Handler;

use AppBundle\Handler\BaseHandler;

/**
 * Photo Handler.
 *
 */
class CategorieHandler extends BaseHandler
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

    public function onDeleteAll($owner_user_id = null, $batch_size = 20){
        $reposiroty = 'GMVideothequeBundle:Categorie';
        $result = $this->getEntityManager()->getRepository($reposiroty)->onDeleteAll($owner_user_id, $batch_size);
        if($result == true){
            $this->SetFlashBag($this->getMsgGenerator()->Msg_DeleteDB_OK());
        }
        else{
            $this->SetFlashBag($this->getMsgGenerator()->Msg_DeleteDB_NONE(), 'info');
        }
        return $result;
    }

}