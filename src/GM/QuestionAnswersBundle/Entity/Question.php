<?php

namespace GM\QuestionAnswersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\GuidGenerator;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="GM\QuestionAnswersBundle\Repository\QuestionRepository")
 * @ORM\HasLifecycleCallbacks()
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
    * @Assert\Length(
    *      min = 3,
    *      max = 50,
    *      minMessage = "Question must be at least {{ limit }} characters long",
    *      maxMessage = "Question cannot be longer than {{ limit }} characters"
    * )
    * @Assert\NotNull()
    */
    protected $wording;

    /**
     * @var guid
     *
     * @ORM\Column(name="guid", type="guid", nullable=false, unique=true)
     */
    private $guid;

    /** @ORM\Column(type="datetime", nullable=false)
    * @Assert\DateTime()
    */
    private $create_date;

    /** @ORM\Column(type="datetime", nullable=false)
    * @Assert\DateTime()
    */
    private $update_date;

    /**
    * @var Collection
    *
    * @ORM\OneToMany(targetEntity=Answer::class, cascade={"remove"}, mappedBy="question")
    */
    protected $answers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
        $guidObj = new GuidGenerator();
        $this->setGuid($guidObj->GUIDv4());
        
    }

    public function __toString() {
        $format = "Question (id: %s, wording: %s)\n";
        return sprintf($format, $this->id, $this->wording);
    }

    public function whoIAm(){
        return "Question";
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

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Question
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
     * @return Question
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
     * @ORM\PrePersist
     */
    public function setCreateUpdateDateValue()
    {
        $this->create_date = new \DateTime("now");
        $this->update_date = new \DateTime("now");
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
