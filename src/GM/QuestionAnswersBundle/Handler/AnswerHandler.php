<?php

namespace GM\QuestionAnswersBundle\Handler;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\MessageGenerator;
use AppBundle\Service\WatchDogLogger;
use AppBundle\Service\FormManager;
use GM\QuestionAnswersBundle\Entity\Answer;
use GM\QuestionAnswersBundle\Form\AnswerType;

/**
 * Answer Handler.
 *
 */
class AnswerHandler
{
    protected $answer;
    protected $requestStack;
    protected $em;
    protected $msgGenerator;
    protected $watchdoglogger;
    protected $formManager;
    protected $form;

    public function __construct(RequestStack $requestStack, EntityManager $em, WatchDogLogger $watchdoglogger, MessageGenerator $msgGenerator, FormManager $formManager){
        $this->setRequestStack($requestStack);
        $this->setEntityManager($em);
        $this->setMsgGenerator($msgGenerator);
        $this->setWatchdoglogger($watchdoglogger);
        $this->setFormManager($formManager);
    }

    public function onReadBy($readBy = 'id', $attrVal = null){
        $answers = $this->getEntityManager()->getRepository('GMQuestionAnswersBundle:Answer')->onReadBy($readBy, $attrVal);
        return $answers;
    }

    public function onCreate(){
        $this->createAnswer(); // Create an Answer entity
        $this->setForm($this->getFormManager()->createForm(AnswerType::class, $this->getAnswer(), array(), 'create')); // Create the new form of entity created above, and set the form in the form attribute of Answer Handler
        $this->getForm()->handleRequest($this->getRequestStack()->getCurrentRequest()); // Attach the request on the form of handler
        if ($this->getForm()->isSubmitted() && $this->getForm()->isValid()) { // Test the submittion and validation of the form beforme try to save the data in the database
            $this->getEntityManager()->persist($this->getForm()->getData()); // Get the entity manager and persist 
            $resultInsertData = $this->getEntityManager()->flush(); // Try saving data in the data base;
            $this->getWatchdoglogger()->process("onCreate", "Answer", $this->getForm()->getData()->getId(), $this->getForm()->getData()->getWording(), "Answer Handler"); // Save the action in the log watchdog
            $this->SetFlashBag($this->getMsgGenerator()->Msg_InsertionDB_OK()); // Set a flashbag message to confirm the creation of the new answer.
            return true; // Return true as everything (mostly answer's creation) is OK.
        }
        return false; // Return false if no submittion or validation form failed.
    }

    public function onUpdate($answer){
        $this->setForm($this->getFormManager()->createForm(AnswerType::class, $answer, array(), 'update')); // Create the edit form of entity created above, and set the form in the form attribute of Answer Handler
        $this->getForm()->handleRequest($this->getRequestStack()->getCurrentRequest()); // Attach the request on the form of handler
        if ($this->getForm()->isSubmitted() && $this->getForm()->isValid()) { // Test the submittion and validation of the form beforme try to save the data in the database
            $resultInsertData = $this->getEntityManager()->flush(); // Try saving data in the data base;
            $this->getWatchdoglogger()->process("onUpdate", "Answer", $this->getForm()->getData()->getId(), $this->getForm()->getData()->getWording(), "Answer Handler");// Save the action in the log watchdog
            $this->SetFlashBag($this->getMsgGenerator()->Msg_UpdateDB_OK()); // Set a flashbag message to confirm the updating of the new answer.
            return true; // Return true as everything (mostly answer's creation) is OK.
        }
        return false; // Return false if no submittion or validation form failed.
    }

    public function OnDelete($entity, $appMsg){
        $id = $entity->getId(); // Keep the id data before that the object will be deleted.
        $anIdentifyEntity = $entity->__toString(); // Keep the wording data before that the object will be deleted.
        $this->getEntityManager()->remove($entity); // Get the entity manager and prepare removing of the answer.
        $this->getEntityManager()->flush();  // Execute query to remove the answer from the database
        $this->SetFlashBag($this->getMsgGenerator()->Msg_DeleteDB_OK()); // Set a flashbag message to confirm the deleting of the answer.
        $this->getWatchdoglogger()->process("onDelete", $entity->whoIAm(), $id, $anIdentifyEntity, $appMsg); //Save in watcher after the data has been deleted well from the database.
        return true;
    }

    public function isValidCreateData($form){
        // Code for specif validation form data
        return true;
    }

    public function setRequestStack($requestStack){
        return $this->requestStack = $requestStack;
    }

    public function getRequestStack(){
        return $this->requestStack;
    }

    public function setEntityManager($em){
        return $this->em = $em;
    } 

    public function getEntityManager(){
        return $this->em;
    }    

    public function getAnswer(){
        return $this->answer;
    }

    public function setAnswer(Answer $answer){
        $this->answer = $answer;
    }

    public function createAnswer(){
        $this->setAnswer(new Answer());
    }

    public function getAnswerId(){
        return $this->getAnswer()->getId();
    }

    public function getMsgGenerator(){
        return $this->msgGenerator;
    }

    public function setMsgGenerator($msgGenerator){
        $this->msgGenerator = $msgGenerator;
    }

    public function getWatchdoglogger(){
		return $this->watchdoglogger;
	}

	public function setWatchdoglogger($watchdoglogger){
		$this->watchdoglogger = $watchdoglogger;
	}

    public function SetFlashBag(String $msgGen, $type = "success"){
        $this->getRequestStack()->getCurrentRequest()->getSession()->getFlashBag()->add($type, $msgGen);
    }

    public function getFormManager(){
		return $this->formManager;
	}

	public function setFormManager($formManager){
		$this->formManager = $formManager;
    }
    
    public function getForm(){
		return $this->form;
	}

	public function setForm($form){
		$this->form = $form;
	}
}
