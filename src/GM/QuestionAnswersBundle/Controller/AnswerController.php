<?php

namespace GM\QuestionAnswersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GM\QuestionAnswersBundle\Entity\Answer;
use GM\QuestionAnswersBundle\Handler\AnswerHandler;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Answer controller.
 * Author : Glenn Milingui : Architect, Developer
 */
class AnswerController extends Controller
{
    /**
     * Lists all answer entities.
     */
    public function indexAction(){
        $answers = $this->getDoctrine()->getManager()->getRepository('GMQuestionAnswersBundle:Answer')->findAll();
        return $this->render($this->getTwig('index'), array('answers' => $answers));
    }

    /**
     * Creates a new answer entity.
     */
    public function newAction(Request $request){
        $answerHandler = $this->get('answer_handler');
        if ($answerHandler->onCreate()) {
            return $this->redirectToRoute('answer_show', array('id' => $answerHandler->getAnswer()->getId()));
        }
        return $this->render($this->getTwig('new'), array('form' => $answerHandler->getForm()->createView()));
    }

    /**
     * Finds and displays only one answer entity. By Id.
     */
    public function showAction($id, Answer $answer){
        return $this->render($this->getTwig('show'), array('answer' => $answer, 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }

    /**
     * Displays a form to edit only one existing answer entity. By Id.
     */
    public function editAction($id, Answer $answer){
        $answerHandler = $this->get('answer_handler');
        if ($answerHandler->onUpdate($answer)) {
            return $this->redirectToRoute('answer_edit', array('id' => $answer->getId()));
        }
        return $this->render($this->getTwig('edit'), array('answer' => $answer, 'edit_form' => $answerHandler->getForm()->createView(), 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }
    
    /**
     * Deletes only one answer entity. By Id.
     */
    public function deleteAction($id, Answer $answer){
        $this->get('answer_handler')->OnDelete($answer, "Deleting of the answer entity with id = ".$id);
        return $this->redirectToRoute('answer_index');
    }

    public function getDeleteFormById($id){
        $option_delete_form = array('action' => $this->generateUrl('answer_delete', array('id' => $id)), 'method' => 'DELETE');
        $delete_form = $this->get('form_manager')->createForm(FormType::class, new Answer(), $option_delete_form, 'delete');
        return $delete_form;
    }


    /* other version to work : to list an set of answer entity (for exemple by wording, etc)
    public function showAction($id){
        $answerHandler = $this->get('answer_handler');
        $answer = $answerHandler->OnRead($readBy = 'id', $id);
        $option_delete_form = array('action' => $this->generateUrl('answer_delete', array('id' => $id)), 'method' => 'DELETE');
        $delete_form = $this->get('form_manager')->createForm(FormType::class, $answer, $option_delete_form, 'delete');
        return $this->render($this->getTwig('show'), array('answer' => $answer[0], 'delete_form' => $answerHandler->getFormManager()->getDeleteForm()->createView()));
    }
    */

    public function getTwig($template = 'index'){
        $listTemplates = array(
            'new' => 'GMQuestionAnswersBundle:answer:new.html.twig',
            'index' => 'GMQuestionAnswersBundle:answer:index.html.twig',
            'show' => 'GMQuestionAnswersBundle:answer:show.html.twig',
            'edit' => 'GMQuestionAnswersBundle:answer:edit.html.twig',
            'email-registration' => 'GMQuestionAnswersBundle:answer:email.html.twig',
        );
        return $listTemplates[$template];
    }
}