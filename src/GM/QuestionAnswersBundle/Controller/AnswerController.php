<?php

namespace GM\QuestionAnswersBundle\Controller;

use GM\QuestionAnswersBundle\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Answer controller.
 *
 */
class AnswerController extends Controller
{
    /**
     * Lists all answer entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $answers = $em->getRepository('GMQuestionAnswersBundle:Answer')->findAll();

        return $this->render('GMQuestionAnswersBundle:answer:index.html.twig', array(
            'answers' => $answers,
        ));
    }

    /**
     * Creates a new answer entity.
     *
     */
    public function newAction(Request $request)
    {
        $answer = new Answer();
        $form = $this->createForm('GM\QuestionAnswersBundle\Form\AnswerType', $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();

            return $this->redirectToRoute('answer_show', array('id' => $answer->getId()));
        }

        return $this->render('GMQuestionAnswersBundle:answer:new.html.twig', array(
            'answer' => $answer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a answer entity.
     *
     */
    public function showAction(Answer $answer)
    {
        $deleteForm = $this->createDeleteForm($answer);

        return $this->render('GMQuestionAnswersBundle:answer:show.html.twig', array(
            'answer' => $answer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing answer entity.
     *
     */
    public function editAction(Request $request, Answer $answer)
    {
        $deleteForm = $this->createDeleteForm($answer);
        $editForm = $this->createForm('GM\QuestionAnswersBundle\Form\AnswerType', $answer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('answer_edit', array('id' => $answer->getId()));
        }

        return $this->render('GMQuestionAnswersBundle:answer:edit.html.twig', array(
            'answer' => $answer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a answer entity.
     *
     */
    public function deleteAction(Request $request, Answer $answer)
    {
        $form = $this->createDeleteForm($answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($answer);
            $em->flush();
        }

        return $this->redirectToRoute('answer_index');
    }

    /**
     * Creates a form to delete a answer entity.
     *
     * @param Answer $answer The answer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Answer $answer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('answer_delete', array('id' => $answer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
