<?php

namespace GM\QuestionAnswersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GM\QuestionAnswersBundle\Handler\AnswerHandler;

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
        $answers = $this->get('answer_handler')->OnRead($readBy = 'all');
        return $this->render($this->getTwig('index'), array('answers' => $answers));
    }

    /**
     * Creates a new answer entity.
     */
    public function newAction(){
        $answerHandler = $this->get('answer_handler');
        if ($answerHandler->OnCreate()) {

            /*
            $mailMsg = "Creation of a new question : " . $answerHandler->getAnswerId();
            $mailer = $this->get('app.mailer');
            $mailer->send('glenn.milinuig@gmail.com', $mailMsg);
            */

            $name = 'Glenn M.';
            $message = (new \Swift_Message('Hello Email'))
            ->setSubject('Hello Email')
            ->setFrom('glenn.milingui@gmail.com')
            ->setTo('glenn.milingui@crossknowlegde.com')
            ->setBody(
                $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                    $this->getTwig('email-registration'),
                    array('name' => $name, 'answer' => $answerHandler->getAnswer())
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
            ;
            $this->get('mailer')->send($message);
            return $this->redirectToRoute('answer_show', array('id' => $answerHandler->getAnswerId()));
        }
        return $this->render($this->getTwig('new'), array('form' => $answerHandler->getCreateForm()->createView()));
    }

    /**
     * Finds and displays a answer entity.
     */
    public function showAction(){
        $answerHandler = $this->get('answer_handler');
        $answer = $answerHandler->OnRead($readBy = 'id');
        return $this->render($this->getTwig('show'), array('answer' => $answer, 'delete_form' => $answerHandler->getDeleteForm()->createView()));
    }

    /**
     * Displays a form to edit an existing answer entity.
     */
    public function editAction(){
        $answerHandler = $this->get('answer_handler');
        if ($answerHandler->OnUpdate($readBy = 'id')) {
            return $this->redirectToRoute('answer_edit', array('id' => $answerHandler->getAnswerId()));
        }
        return $this->render($this->getTwig('edit'), array('answer' => $answerHandler->getanswer(), 'edit_form' => $answerHandler->getEditForm()->createView(), 'delete_form' => $answerHandler->getDeleteForm()->createView()));
    }
    
    /**
     * Deletes a answer entity.
     */
    public function deleteAction(){
        $this->get('answer_handler')->OnDelete($readBy = 'id');
        return $this->redirectToRoute('answer_index');
    }

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