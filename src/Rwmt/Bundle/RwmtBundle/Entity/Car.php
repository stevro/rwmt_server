<?php

namespace Rwmt\Bundle\RwmtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Car
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Rwmt\Bundle\RwmtBundle\Entity\CarRepository")
 *
 */
class Car
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="maker", type="string", length=30)
     * @Assert\NotBlank(message="Car maker must not be blank")
     */
    private $maker;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=30)
     * @Assert\NotBlank(message="Car model must not be blank")
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=20)
     * @Assert\NotBlank(message="Car color must not be blank")
     */
    private $color;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=10)
     * @Assert\NotBlank(message="Year of manufacture must not be blank")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="licence_plate", type="string", length=20)
     * @Assert\NotBlank(message="Licence plate must not be blank")
     */
    private $licencePlate;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="integer")
     *
     */
    private $userId;

    /**
     * @var \Rwmt\Bundle\RwmtBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Rwmt\Bundle\RwmtBundle\Entity\User", inversedBy="carsOwned")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id" )
     * })
     * @Assert\NotBlank(message="A car must have an owner")
     */
    private $owner;

    public function __construct()
    {

        $this->isActive = true;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Car
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Car
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set maker
     *
     * @param string $maker
     * @return Car
     */
    public function setMaker($maker)
    {
        $this->maker = $maker;

        return $this;
    }

    /**
     * Get maker
     *
     * @return string
     */
    public function getMaker()
    {
        return $this->maker;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return Car
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Car
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Car
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return Car
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set licencePlate
     *
     * @param string $licencePlate
     * @return Car
     */
    public function setLicencePlate($licencePlate)
    {
        $this->licencePlate = $licencePlate;

        return $this;
    }

    /**
     * Get licencePlate
     *
     * @return string
     */
    public function getLicencePlate()
    {
        return $this->licencePlate;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Car
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set owner
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\User $owner
     * @return Car
     */
    public function setOwner(\Rwmt\Bundle\RwmtBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Rwmt\Bundle\RwmtBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
