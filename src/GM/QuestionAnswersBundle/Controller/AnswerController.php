<?php

namespace GM\QuestionAnswersBundle\Controller;

use GM\QuestionAnswersBundle\Entity\Answer;
use GM\QuestionAnswersBundle\Form\AnswerType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Answer controller.
 * Author : Glenn Milingui : Architect, Developer
 */
class AnswerController extends Controller
{

    /**
     * Lists all photo entities.
     */
    public function indexAction(){
        $entity_handler = $this->get('base_handler');
        $answers = $entity_handler->onReadBy('all',null,'GMQuestionAnswersBundle:Answer');
        return $this->render($this->getTwig('index'), array('answers' => $answers));
    }


    /**
     * Creates a new answer entity.
     */
    public function newAction(Request $request){
        $answerHandler = $this->get('base_handler');
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
        $entity_handler = $this->get('base_handler');
        if ($entity_handler->onUpdate($answer, AnswerType::class)) {
            return $this->redirectToRoute('answer_edit', array('id' => $answer->getId()));
        }
        return $this->render($this->getTwig('edit'), array('answer' => $answer, 'edit_form' => $entity_handler->getForm()->createView(), 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }

    /**
     * Deletes only one answer entity. By Id.
     */
    public function deleteAction($id, Answer $answer){
        $this->get('base_handler')->OnDelete($answer, "Deleting an answer entity with id = ".$id);
        return $this->redirectToRoute('answer_index');
    }

    public function getDeleteFormById($id){
        $option_delete_form = array('action' => $this->generateUrl('answer_delete', array('id' => $id)), 'method' => 'DELETE');
        $delete_form = $this->get('form_manager')->createForm(FormType::class, new Answer(), $option_delete_form, 'delete');
        return $delete_form;
    }

    public function getTwig($template = 'index'){
        $listTemplates = array(
            'new' => 'GMQuestionAnswersBundle:answer:new.html.twig',
            'index' => 'GMQuestionAnswersBundle:answer:index.html.twig',
            'show' => 'GMQuestionAnswersBundle:answer:show.html.twig',
            'edit' => 'GMQuestionAnswersBundle:answer:edit.html.twig',
        );
        return $listTemplates[$template];
    }
}