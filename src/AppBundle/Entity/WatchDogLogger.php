<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WatchDogLogger
 *
 * @ORM\Table(name="watch_dog_logger")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WatchDogLoggerRepository")
 */
class WatchDogLogger
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

    /** @ORM\Column(type="string", nullable=false) */
    private $userLoginHandled;

    /** @ORM\Column(type="integer", nullable=false) */
    private $idUserHandled;

    /** @ORM\Column(type="string", nullable=false) */
    private $objHandled;

    /** @ORM\Column(type="integer", nullable=false) */
    private $idObjHandled;

    /** @ORM\Column(type="string", nullable=false) */
    private $nameOrUniqueNameObjHandled;
    
    /** @ORM\Column(type="string", nullable=false) */
    private $actionHandled;

    /** @ORM\Column(type="datetime", nullable=false) */
    private $dateTimeHandled;

    /** @ORM\Column(type="text", nullable=false) */
    private $logMsg;

    /** @ORM\Column(type="text", nullable=false) */
    private $appLogMsg;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->create_date = new \DateTime("now");
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
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return WatchDogLogger
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
     * Set userLoginHandled
     *
     * @param string $userLoginHandled
     *
     * @return WatchDogLogger
     */
    public function setUserLoginHandled($userLoginHandled)
    {
        $this->userLoginHandled = $userLoginHandled;

        return $this;
    }

    /**
     * Get userLoginHandled
     *
     * @return string
     */
    public function getUserLoginHandled()
    {
        return $this->userLoginHandled;
    }

    /**
     * Set idUserHandled
     *
     * @param integer $idUserHandled
     *
     * @return WatchDogLogger
     */
    public function setIdUserHandled($idUserHandled)
    {
        $this->idUserHandled = $idUserHandled;

        return $this;
    }

    /**
     * Get idUserHandled
     *
     * @return integer
     */
    public function getIdUserHandled()
    {
        return $this->idUserHandled;
    }

    /**
     * Set objHandled
     *
     * @param string $objHandled
     *
     * @return WatchDogLogger
     */
    public function setObjHandled($objHandled)
    {
        $this->objHandled = $objHandled;

        return $this;
    }

    /**
     * Get objHandled
     *
     * @return string
     */
    public function getObjHandled()
    {
        return $this->objHandled;
    }

    /**
     * Set idObjHandled
     *
     * @param integer $idObjHandled
     *
     * @return WatchDogLogger
     */
    public function setIdObjHandled($idObjHandled)
    {
        $this->idObjHandled = $idObjHandled;

        return $this;
    }

    /**
     * Get idObjHandled
     *
     * @return integer
     */
    public function getIdObjHandled()
    {
        return $this->idObjHandled;
    }


    /**
     * Get nameOrUniqueNameObjHandled
     *
     * @return string
     */
    public function getNameOrUniqueNameObjHandled(){
		return $this->nameOrUniqueNameObjHandled;
	}

    /**
     * Set actionHanameOrUniqueNameObjHandledndled
     *
     * @param string $nameOrUniqueNameObjHandled
     *
     * @return WatchDogLogger
     */
	public function setNameOrUniqueNameObjHandled($nameOrUniqueNameObjHandled){
		$this->nameOrUniqueNameObjHandled = $nameOrUniqueNameObjHandled;
	}

    /**
     * Set actionHandled
     *
     * @param string $actionHandled
     *
     * @return WatchDogLogger
     */
    public function setActionHandled($actionHandled)
    {
        $this->actionHandled = $actionHandled;

        return $this;
    }

    /**
     * Get actionHandled
     *
     * @return string
     */
    public function getActionHandled()
    {
        return $this->actionHandled;
    }

    /**
     * Set dateTimeHandled
     *
     * @param \DateTime $dateTimeHandled
     *
     * @return WatchDogLogger
     */
    public function setDateTimeHandled($dateTimeHandled)
    {
        $this->dateTimeHandled = $dateTimeHandled;

        return $this;
    }

    /**
     * Get dateTimeHandled
     *
     * @return \DateTime
     */
    public function getDateTimeHandled()
    {
        return $this->dateTimeHandled;
    }

    /**
     * Set logMsg
     *
     * @param string $logMsg
     *
     * @return WatchDogLogger
     */
    public function setLogMsg($logMsg)
    {
        $this->logMsg = $logMsg;

        return $this;
    }

    /**
     * Get logMsg
     *
     * @return string
     */
    public function getLogMsg()
    {
        return $this->logMsg;
    }

    /**
     * Set appLogMsg
     *
     * @param string $appLogMsg
     *
     * @return WatchDogLogger
     */
    public function setAppLogMsg($appLogMsg)
    {
        $this->appLogMsg = $appLogMsg;

        return $this;
    }

    /**
     * Get appLogMsg
     *
     * @return string
     */
    public function getAppLogMsg()
    {
        return $this->appLogMsg;
    }
}
