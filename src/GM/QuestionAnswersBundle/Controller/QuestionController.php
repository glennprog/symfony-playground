<?php

namespace GM\QuestionAnswersBundle\Controller;

use GM\QuestionAnswersBundle\Entity\Question;
use GM\QuestionAnswersBundle\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use GM\QuestionAnswersBundle\Service\MessageGenerator;

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
        $em = $this->getDoctrine()->getManager();

        $questions = $em->getRepository('GMQuestionAnswersBundle:Question')->findAll();

        return $this->render('GMQuestionAnswersBundle:question:index.html.twig', array(
            'questions' => $questions,
        ));
    }

    /**
     * Creates a new question entity.
     *
     */
    public function newAction(Request $request)
    {
        $question = new Question();
        $form = $this->createForm('GM\QuestionAnswersBundle\Form\QuestionType', $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            $message = MessageGenerator::Msg_InsertionDB_OK();
            $this->addFlash('success', $message);

            return $this->redirectToRoute('question_show', array('id' => $question->getId()));
        }
        return $this->render('GMQuestionAnswersBundle:question:new.html.twig', array(
            'question' => $question,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a question entity.
     *
     */
    public function showAction(Question $question)
    {
        $em = $this->getDoctrine()->getManager(); // Récupération the entity manager doctrine.
        $answers = $em->getRepository('GMQuestionAnswersBundle:Answer')->findBy(
                array('question' => $question)
        );

        $deleteForm = $this->createDeleteForm($question);

        return $this->render('GMQuestionAnswersBundle:question:show.html.twig', array(
            'question' => $question,
            'delete_form' => $deleteForm->createView(),
            'answers' => $answers,
        ));
    }

    /**
     * Displays a form to edit an existing question entity.
     *
     */
    public function editAction(Request $request, Question $question)
    {
        $deleteForm = $this->createDeleteForm($question);
        $editForm = $this->createForm('GM\QuestionAnswersBundle\Form\QuestionType', $question);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $message = MessageGenerator::Msg_UpdateDB_OK();
            $this->addFlash('success', $message);

            return $this->redirectToRoute('question_edit', array('id' => $question->getId()));
        }

        return $this->render('GMQuestionAnswersBundle:question:edit.html.twig', array(
            'question' => $question,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a question entity.
     *
     */
    public function deleteAction(Request $request, Question $question)
    {
        $form = $this->createDeleteForm($question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($question);
            $em->flush();

            $message = MessageGenerator::Msg_DeleteDB_OK();
            $this->addFlash('success', $message);
        }

        return $this->redirectToRoute('question_index');
    }

    /**
     * Creates a form to delete a question entity.
     *
     * @param Question $question The question entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Question $question)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('question_delete', array('id' => $question->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
