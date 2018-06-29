<?php

namespace GM\QuestionAnswersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="GM\QuestionAnswersBundle\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\Column(type="string", nullable=false)
    */
    protected $wording;

    /**
    * 
    * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answers")
    * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
    */
    protected $question;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set wording
     *
     * @param string $wording
     *
     * @return Answer
     */
    public function setWording($wording)
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * Get wording
     *
     * @return string
     */
    public function getWording()
    {
        return $this->wording;
    }

    /**
     * Set question
     *
     * @param \GM\QuestionAnswersBundle\Entity\Question $question
     *
     * @return Answer
     */
    public function setQuestion(\GM\QuestionAnswersBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \GM\QuestionAnswersBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
