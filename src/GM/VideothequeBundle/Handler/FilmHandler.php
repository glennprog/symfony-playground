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

}