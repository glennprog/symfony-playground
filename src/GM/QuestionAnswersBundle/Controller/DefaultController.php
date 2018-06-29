<?php

namespace GM\QuestionAnswersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GMQuestionAnswersBundle:Default:index.html.twig');
    }
}
