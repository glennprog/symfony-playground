<?php

namespace GM\QuestionAnswersBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Service\GuidGenerator;

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

    /** @ORM\Column(type="datetime", nullable=false) */
    private $create_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    private $update_date;

    /**
     * @var guid
     *
     * @ORM\Column(name="guid", type="guid", nullable=false, unique=true)
     */
    private $guid;

    /**
    * @ORM\Column(type="string", nullable=false)
    * @Assert\Length(
    *      min = 3,
    *      max = 50,
    *      minMessage = "Answer must be at least {{ limit }} characters long",
    *      maxMessage = "Answer cannot be longer than {{ limit }} characters"
    * )
    * @Assert\NotNull()
    */
    protected $wording;

    /**
    * 
    * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answers")
    * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
    * @Assert\NotNull()
    */
    protected $question;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->create_date = new \DateTime("now");
        $this->update_date = new \DateTime("now"); 
        $guidObj = new GuidGenerator();
        $this->setGuid($guidObj->GUIDv4());
    }

    public function __toString() {
        $format = "Answer (id: %s, wording: %s)\n";
        return sprintf($format, $this->id, $this->wording);
    }

    public function whoIAm(){
        return "Answer";
    }

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

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Answer
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Answer
     */
    public function setUpdateDate($updateDate = null)
    {
        if($updateDate == null){
            $this->update_date = new \DateTime("now");
        }
        else{
            $this->update_date = $updateDate;
        }
        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Set guid
     *
     * @param guid $guid
     *
     * @return Configuration
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return guid
     */
    public function getGuid()
    {
        return $this->guid;
    }
}
