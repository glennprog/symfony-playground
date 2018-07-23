<?php

namespace GM\VideothequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\GuidGenerator;
use AppBundle\Entity\User as Owner;

/**
 * Film
 *
 * @ORM\Table(name="film")
 * @ORM\Entity(repositoryClass="GM\VideothequeBundle\Repository\FilmRepository")
 */
class Film
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
    protected $guid;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $create_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $update_date;

    /**
    * @ORM\Column(type="string", nullable=false)
    * @Assert\Length(
    *      min = 10,
    *      minMessage = "Le titre doit avoir {{ limit }} caractère minimum !"
    * )
    */
    protected $titre;

    /**
    * @ORM\Column(type="text", nullable=false)
    * @Assert\NotNull()
    */
    protected $description;

    /** 
     * @ORM\Column(type="date", nullable=false)
     *  @Assert\NotNull()
     */
    protected $date_sortie;

    /**
    * 
    * @ORM\ManyToOne(targetEntity="GM\VideothequeBundle\Entity\Categorie", inversedBy="films")
    * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id", nullable=false)
    * @Assert\NotNull()
    */
    protected $categorie;

    /**
    * 
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="videotheque_films")
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
     * @return Film
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
     * @return Film
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
     * @return Film
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Film
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Film
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
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     *
     * @return Film
     */
    public function setDateSortie($dateSortie)
    {
        $this->date_sortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime
     */
    public function getDateSortie()
    {
        return $this->date_sortie;
    }

    /**
     * Set categorie
     *
     * @param \GM\VideothequeBundle\Entity\Categorie $categorie
     *
     * @return Film
     */
    public function setCategorie(\GM\VideothequeBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \GM\VideothequeBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     *
     * @return Film
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

    /**
     * Check if the user is the owner
     */
    public function isOwner($id){

        if($this->getOwner()->getId() === $id){
            return true;
        }
        else{
            return false;
        }
    }

    public function whoIAm(){
        return "Film";
    }

    public function __toString() {
        $format = "%s\n";
        return sprintf($format, $this->titre);
    }
}
