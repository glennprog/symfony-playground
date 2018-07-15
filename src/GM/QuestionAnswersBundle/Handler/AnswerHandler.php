<?php

namespace GM\QuestionAnswersBundle\Handler;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use GM\QuestionAnswersBundle\Form\AnswerType;
use GM\QuestionAnswersBundle\Entity\Answer;

use GM\QuestionAnswersBundle\Service\MessageGenerator;

/**
 * Answer Handler.
 *
 */
class AnswerHandler
{
    protected $answer;

    protected $requestStack;
    protected $em;
    protected $formFactory;
    protected $router;

    protected $createform;
    protected $editform;
    protected $deleteform;
    protected $optionsForm;

    protected $msgGenerator;

    /**
     * Constructor to initialize the Handler.
     *
     */
    public function __construct(Router $router, RequestStack $requestStack, FormFactory $formFactory, EntityManager $em){
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->msgGenerator = new MessageGenerator();
        $this->statementFormValidation = false;
        $this->optionForm = array();
    }

    /*_______________________ Handle CRUD data _______________________*/

    /**
     * Create Answer Handle
     */
    public function OnCreate(){
        $this->createAnswer();
        
        $this->createForm(AnswerType::class, $this->getAnswer(), $this->getOptionForm(), 'create');

        $this->getCreateForm()->handleRequest($this->getRequestStack()->getCurrentRequest());

        if ($this->IsValidForm(null, $this->getCreateForm())) {

            $this->getEntityManager()->persist($this->getCreateForm()->getData());
            $this->getEntityManager()->flush();

            $this->SetFlashBag($this->getMsgGenerator()->Msg_InsertionDB_OK());

            return true;
        }
        return false;
    }
    /**
     * Read Answer Handle
     */
    public function OnRead($readBy = 'id'){
        switch ($readBy) {
            case 'id':
                $id = $this->getRequestStack()->getCurrentRequest()->attributes->get('id');
                $answers = $this->getEntityManager()->getRepository('GMQuestionAnswersBundle:Answer')->findById($id);
                break;

            case 'all':
                $answers = $this->em->getRepository('GMQuestionAnswersBundle:Answer')->findAll();
                break;
            default:
                $id = $this->getRequestStack()->getCurrentRequest()->id->get('id');
                $answers = $this->getEntityManager()->getRepository('GMQuestionAnswersBundle:Answer')->findById($id);
                break;
        }
        if($readBy == 'all'){
            return $answers;
        }
        else{
            $this->optionForm = array('action' => $this->getRouter()->generate('answer_delete', array('id' => $id)), 'method' => 'DELETE');
            $this->createForm(FormType::class, $this->getAnswer(), $this->getOptionForm(), 'delete');
            return $answers[0];
        }
    }

    /**
     * Update Answer Handle
     */
    public function OnUpdate($readBy = 'id'){
        $this->setAnswer($this->OnRead($readBy));
        $this->createEditForm();
        $this->createDeleteForm();
        $this->getEditForm()->handleRequest($this->getRequestStack()->getCurrentRequest());
        if ($this->IsValidForm(null, $this->getEditForm())) {
            $this->getEntityManager()->flush();
            $this->SetFlashBag($this->getMsgGenerator()->Msg_UpdateDB_OK());
            return true;
        }
        return false;
    }

    /**
     * Delete Answer Handle
     */
    public function OnDelete($readBy){
        $this->getEntityManager()->remove($this->OnRead($readBy));
        $this->getEntityManager()->flush();
        $this->SetFlashBag($this->getMsgGenerator()->Msg_DeleteDB_OK());
        return true;
    }

    /*_______________________ Handle System data _______________________*/

    public function getRouter(){
        return $this->router;
    }

    public function getRequestStack(){
        return $this->requestStack;
    }

    public function getFormFactory(){
        return $this->formFactory;
    }

    public function getEntityManager(){
        return $this->em;
    }    

    public function getCreateForm(){
        return $this->createform;
    }

    public function setCreateForm($createform){
        $this->createform = $createform;
    }

    public function getEditForm(){
        return $this->editform;
    }

    public function setEditForm($editform){
        $this->editform = $editform;
    }

    public function getDeleteForm(){
        return $this->deleteform;
    }

    public function setDeleteForm($deleteform){
        $this->deleteform = $deleteform;
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

    public function getOptionForm(){
        return $this->optionForm;
    }

    public function setOptionForm($optionForm){
        $this->optionForm = $optionForm;
    }

    public function setStatementFormValidation($statementFormValidation){
        $this->statementFormValidation = $statementFormValidation;
    }

    public function getStatementFormValidation(){
        return $this->statementFormValidation;
    }

    /**
     * Handler form factory (create, edit and delete form)
     */
    public function createForm($type, $data = null, array $options = array(), $formRole)
    {
        switch ($formRole) {
            case 'create':
                $this->setCreateForm($this->getFormFactory()->create($type, $data, $options));
                break;

            case 'delete':
                $this->setDeleteForm($this->getFormFactory()->create($type, $data, $options));
                break;

            case 'edit':
                $this->setEditForm($this->getFormFactory()->create($type, $data, $options));
                break;
            
            default:
                $this->setCreateForm($this->getFormFactory()->create($type, $data, $options));
                break;
        }
    }

    /**
     * Create edit form
     */
    public function createEditForm(){
        $this->optionForm = array();
        $this->createForm(AnswerType::class, $this->getAnswer(), $this->getOptionForm(), 'edit');
    }

    /**
     * Create delete form
     */
    public function createDeleteForm(){
        $this->optionForm = array('action' => $this->getRouter()->generate('answer_delete', array('id' => $this->getAnswer()->getId())), 'method' => 'DELETE');
        $this->createForm(FormType::class, $this->getAnswer(), $this->getOptionForm(), 'delete');
    }
    
    public function IsValidForm($validationLevel, $form = null){
        switch ($validationLevel) {
            case 'level-1':
                $this->setStatementFormValidation(($form->isSubmitted() && $form->isValid()));
                break;
            default:
                $this->setStatementFormValidation(($form->isSubmitted() && $form->isValid() && $this->getRequestStack()->getCurrentRequest()->isMethod('post')));
                break;
        }
        return $this->getStatementFormValidation();
    }

    public function SetFlashBag(String $msgGen){
        $this->getRequestStack()->getCurrentRequest()->getSession()->getFlashBag()->add('success', $msgGen);
    }
}
