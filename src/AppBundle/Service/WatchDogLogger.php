<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use AppBundle\Service\MessageGenerator;
use AppBundle\Entity\WatchDogLogger as WatchDogLoggerEntity;

class WatchDogLogger
{
    protected $requestStack;
    protected $em;
    protected $tokenStorage;
    protected $authorizationChecker;
    protected $userLoginHandled;
    protected $idUserHandled;
    protected $objHandled;
    protected $idObjHandled;
    protected $actionHandled;
    protected $dateTimeHandled;
    protected $logMsg;
    protected $applogMsg;
    protected $msgGenerator;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, MessageGenerator $msgGenerator){
        $this->setRequestStack($requestStack);
        $this->setEntityManager($em);
        $this->setTokenStorage($tokenStorage);
        $this->setAuthorizationChecker($authorizationChecker);
        $this->setMsgGenerator($msgGenerator);
    }

    public function process($process = null ,$objHandled = null, $idObjHandled = null, $nameOrUniqueNameObjHandled = null, $applogMsg = null){
        if($process == null){
            return false;
        }
        $this->setUserLoginHandled($this->getTokenStorage()->getToken()->getUser());
        if($this->getTokenStorage()->getToken()->getUser() == "anon."){
            $this->setIdUserHandled(0);
        }
        else{
            $this->setIdUserHandled(1); /** To Do : Have to develop the stuff to retrieve user id */
        }
        $this->setObjHandled($objHandled);
        $this->setIdObjHandled($idObjHandled);
        $this->setNameOrUniqueNameObjHandled($nameOrUniqueNameObjHandled);
        $this->setActionHandled($process);
        if ($applogMsg != null) {
            $this->setAppLogMsg($applogMsg);
        }
        $this->setDateTimeHandled(new \DateTime("now"));
        $logMsg = "Date Handled : ". $this->getDateTimeHandled()->format('m/d/Y');
        $logMsg .= " - Time Handled : ". $this->getDateTimeHandled()->format('h:i:s A');
        $logMsg .= " - Action Handled : ". $this->getActionHandled();
        $logMsg .= " - Object Handled : ". $this->getObjHandled();
        $logMsg .= " - Id Object Handled : ". $this->getIdObjHandled();
        $logMsg .= " - User Login Handled : ". $this->getUserLoginHandled();
        $logMsg .= " - Id User Handled : ". $this->getIdUserHandled();
        $this->setLogMsg($logMsg);
        $this->persistInDatabase();
        return true;
    }

    protected function persistInDatabase(){
        $watchDogLogger = new WatchDogLoggerEntity();
        $watchDogLogger->setDateTimeHandled($this->getDateTimeHandled());
        $watchDogLogger->setActionHandled($this->getActionHandled());
        $watchDogLogger->setObjHandled($this->getObjHandled());
        $watchDogLogger->setIdObjHandled($this->getIdObjHandled());
        $watchDogLogger->setNameOrUniqueNameObjHandled($this->getNameOrUniqueNameObjHandled());
        $watchDogLogger->setUserLoginHandled($this->getUserLoginHandled());
        $watchDogLogger->setIdUserHandled($this->getIdUserHandled());
        $watchDogLogger->setLogMsg($this->getLogMsg());
        $watchDogLogger->setAppLogMsg($this->getAppLogMsg());
        $this->getEntityManager()->persist($watchDogLogger);
        $this->getEntityManager()->flush();
    }

    public function cleanWatchDogLoggerData(){
        if (false === $this->getAuthorizationChecker()->isGranted('ROLE_ADMIN')){
            $this->SetFlashBag($this->getMsgGenerator()->Msg_ActionAdmin_FAIL());
            return false;
        }
        $deletor = $this->getEntityManager()->getRepository('GMQuestionAnswersBundle:WatchDogLogger');
        $result_deletor = $deletor->removeAll();
        return true;
    }

    protected function getRequestStack(){
		return $this->requestStack;
	}

	protected function setRequestStack($requestStack){
		$this->requestStack = $requestStack;
	}

	protected function getEntityManager(){
		return $this->em;
	}

	protected function setEntityManager($em){
		$this->em = $em;
	}

    protected function getUserLoginHandled(){
		return $this->userLoginHandled;
	}

	protected function setUserLoginHandled($userLoginHandled){
		$this->userLoginHandled = $userLoginHandled;
	}

	protected function getIdUserHandled(){
		return $this->idUserHandled;
	}

	protected function setIdUserHandled($idUserHandled){
		$this->idUserHandled = $idUserHandled;
	}

	protected function getObjHandled(){
		return $this->objHandled;
	}

	protected function setObjHandled($objHandled){
		$this->objHandled = $objHandled;
	}

	protected function getIdObjHandled(){
		return $this->idObjHandled;
	}

	protected function setIdObjHandled($idObjHandled){
		$this->idObjHandled = $idObjHandled;
	}

	protected function getActionHandled(){
		return $this->actionHandled;
	}

	protected function setActionHandled($actionHandled){
		$this->actionHandled = $actionHandled;
	}

	protected function getDateTimeHandled(){
		return $this->dateTimeHandled;
	}

	protected function setDateTimeHandled($dateTimeHandled){
		$this->dateTimeHandled = $dateTimeHandled;
	}

	protected function getLogMsg(){
		return $this->logMsg;
	}

	protected function setLogMsg($logMsg){
		$this->logMsg = $logMsg;
    }

    protected function getAppLogMsg(){
		return $this->applogMsg;
	}

	protected function setAppLogMsg($applogMsg){
		$this->applogMsg = $applogMsg;
    }

    public function getNameOrUniqueNameObjHandled(){
		return $this->nameOrUniqueNameObjHandled;
	}

	public function setNameOrUniqueNameObjHandled($nameOrUniqueNameObjHandled){
		$this->nameOrUniqueNameObjHandled = $nameOrUniqueNameObjHandled;
	}

    public function getTokenStorage(){
		return $this->tokenStorage;
	}

	public function setTokenStorage($tokenStorage){
		$this->tokenStorage = $tokenStorage;
	}

	public function getAuthorizationChecker(){
		return $this->authorizationChecker;
	}

	public function setAuthorizationChecker($authorizationChecker){
		$this->authorizationChecker = $authorizationChecker;
    }
    
    public function getMsgGenerator(){
		return $this->msgGenerator;
	}

	public function setMsgGenerator($msgGenerator){
		$this->msgGenerator = $msgGenerator;
	}

    public function SetFlashBag(String $msgGen, $type = "danger"){
        $this->getRequestStack()->getCurrentRequest()->getSession()->getFlashBag()->add($type, $msgGen);
    }
}
