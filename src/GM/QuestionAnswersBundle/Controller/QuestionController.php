<?php

namespace GM\QuestionAnswersBundle\Controller;

use GM\QuestionAnswersBundle\Entity\Question;
use GM\QuestionAnswersBundle\Form\QuestionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Question controller.
 *
 */
class QuestionController extends Controller
{
    /**
     * Lists all question entities.
     *
     */
    public function indexAction()
    {
        $questions = $this->getDoctrine()->getManager()->getRepository('GMQuestionAnswersBundle:Question')->findAll();
        return $this->render('GMQuestionAnswersBundle:question:index.html.twig', array('questions' => $questions));
    }

    /**
     * Creates a new question entity.
     *
     */
    public function newAction(Request $request)
    {
        $questionHandler = $this->get('question_handler');
        if ($questionHandler->onCreate(new Question(), QuestionType::class)) {
            return $this->redirectToRoute('question_show', array('id' => $questionHandler->getEntityObj()->getId()));
        }
        return $this->render($this->getTwig('new'), array('form' => $questionHandler->getForm()->createView()));
    }

    /**
     * Finds and displays only one question entity. By Id.
     */
    public function showAction($id, Question $question)
    {
        $answers = $this->get('answer_handler')->onReadBy('question', $id, 'GMQuestionAnswersBundle:Answer');
        return $this->render($this->getTwig('show'), array('question' => $question, 'answers' => $answers, 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }

    /**
     * Displays a form to edit only one existing question entity. By Id.
     */
    public function editAction($id, Question $question){
        $questionHandler = $this->get('question_handler');
        if ($questionHandler->onUpdate($question, QuestionType::class)) {
            return $this->redirectToRoute('question_edit', array('id' => $id));
        }
        return $this->render($this->getTwig('edit'), array('question' => $question, 'edit_form' => $questionHandler->getForm()->createView(), 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }

    /**
     * Deletes only one question entity. By Id.
     */
    public function deleteAction($id, Question $question){
        $this->get('question_handler')->OnDelete($question, "Deleting a question entity with id = ".$id);
        return $this->redirectToRoute('question_index');
    }

    public function getDeleteFormById($id){
        $option_delete_form = array('action' => $this->generateUrl('question_delete', array('id' => $id)), 'method' => 'DELETE');
        $delete_form = $this->get('form_manager')->createForm(FormType::class, new Question(), $option_delete_form, 'delete');
        return $delete_form;
    }

    public function getTwig($template = 'index'){
        $listTemplates = array(
            'new' => 'GMQuestionAnswersBundle:question:new.html.twig',
            'index' => 'GMQuestionAnswersBundle:question:index.html.twig',
            'show' => 'GMQuestionAnswersBundle:question:show.html.twig',
            'edit' => 'GMQuestionAnswersBundle:question:edit.html.twig',
        );
        return $listTemplates[$template];
    }
}
