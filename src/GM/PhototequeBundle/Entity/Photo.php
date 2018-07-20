<?php

namespace GM\PhototequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\GuidGenerator;
use AppBundle\Entity\User as Owner;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="GM\PhototequeBundle\Repository\PhotoRepository")
 */
class Photo
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
     * @var guid
     *
     * @ORM\Column(name="guid", type="guid", nullable=false, unique=true)
     */
    private $guid;

    /** @ORM\Column(type="datetime", nullable=false) */
    private $create_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    private $update_date;

    /**
    * @ORM\Column(type="string", nullable=false)
    * @Assert\Length(
    *      min = 3,
    *      max = 50,
    *      minMessage = "Nam must be at least {{ limit }} characters long",
    *      maxMessage = "Name cannot be longer than {{ limit }} characters"
    * )
    * @Assert\NotNull()
    */
    protected $name;

    /**
    * @ORM\Column(type="text", nullable=false)
    * @Assert\Length(
    *      min = 0,
    *      max = 100,
    *      minMessage = "Question must be at least {{ limit }} characters long",
    *      maxMessage = "Question cannot be longer than {{ limit }} characters"
    * )
    * @Assert\NotNull()
    */
    protected $description;


    /**
    * 
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="photos")
    * @ORM\JoinColumn(name="owner_user_id", referencedColumnName="id", nullable=false)
    * @Assert\NotNull()
    */
    protected $owner;

    /**
     * Constructor
     */
    public function __construct(Owner $owner )
    {
        $this->create_date = new \DateTime("now");
        $this->update_date = new \DateTime("now"); 
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
        $guidObj = new GuidGenerator();
        $this->setGuid($guidObj->GUIDv4());
        $this->setOwner($owner);
        
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

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Photo
     */
    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;
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
     * @return Photo
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
     * Set name
     *
     * @param string $name
     *
     * @return Photo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     *
     * @return Photo
     */
    public function setOwner(\AppBundle\Entity\User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
