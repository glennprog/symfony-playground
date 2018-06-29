<?php

namespace GM\QuestionAnswersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="GM\QuestionAnswersBundle\Repository\QuestionRepository")
 */
class Question
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
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        $format = "Question (id: %s, wording: %s)\n";
        return sprintf($format, $this->id, $this->wording);
    }

    /**
    * @var Collection
    *
    * @ORM\OneToMany(targetEntity=Answer::class, cascade={"remove"}, mappedBy="question")
    */
    protected $answers;

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
     * @return Question
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
     * Add answer
     *
     * @param \GM\QuestionAnswersBundle\Entity\Answer $answer
     *
     * @return Question
     */
    public function addAnswer(\GM\QuestionAnswersBundle\Entity\Answer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \GM\QuestionAnswersBundle\Entity\Answer $answer
     */
    public function removeAnswer(\GM\QuestionAnswersBundle\Entity\Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
