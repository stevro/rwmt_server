<?php

namespace Rwmt\Bundle\RwmtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Ride
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Rwmt\Bundle\RwmtBundle\Entity\RideRepository")
 *
 *
 *
 */
class Ride
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
     * @var string
     *
     * @ORM\Column(name="from_address", type="string", length=255)
     */
    private $fromAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="to_address", type="string", length=255)
     */
    private $toAddress;

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
     * @var integer
     *
     * @ORM\Column(name="owner_id", type="integer")
     */
    private $ownerId;

    /**
     * @var \Rwmt\Bundle\RwmtBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Rwmt\Bundle\RwmtBundle\Entity\User", inversedBy="ownedRides")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_id", referencedColumnName="id" )
     * })
     * @Assert\NotBlank(message="A ride must have an owner")
     */
    private $owner;

    /**
     * @var integer
     *
     * @ORM\Column(name="from_lat", type="float")
     * @Assert\NotBlank(message="A ride must have a fromLat")
     */
    private $fromLat;

    /**
     * @var integer
     *
     * @ORM\Column(name="from_lng", type="float")
     * @Assert\NotBlank(message="A ride must have a fromLng")
     */
    private $fromLng;

    /**
     * @var integer
     *
     * @ORM\Column(name="to_lat", type="float")
     * @Assert\NotBlank(message="A ride must have a toLat")
     */
    private $toLat;

    /**
     * @var integer
     *
     * @ORM\Column(name="to_lng", type="float")
     * @Assert\NotBlank(message="A ride must have a toLng")
     */
    private $toLng;

    /**
     *
     * @var integer
     * @ORM\Column(name="total_pax", type="integer")
     * @Assert\NotBlank(message="A ride must have a number of seats available mentioned")
     */
    private $totalSeats;

    /**
     *
     * @var integer
     * @ORM\Column(name="occupied_seats", type="integer")
     */
    private $occupiedSeats;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="RideToUser", mappedBy="ride")
     *
     */
    private $users;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pickup_date_time", type="datetime")
     * @Assert\NotBlank(message="A ride must have a pickup date & time")
     * @Assert\DateTime(message="Ride pickup date time is invalid.")
     */
    private $pickupDateTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_recursive", type="boolean")     *
     */
    private $isRecursive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recursive_days", type="string", length=255, nullable=true)
     */
    private $recursiveDays;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->totalSeats = 1;
        $this->occupiedSeats = 0;
        $this->isRecursive = false;
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
     * Set fromAddress
     *
     * @param string $fromAddress
     * @return Ride
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    /**
     * Get fromAddress
     *
     * @return string
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * Set toAddress
     *
     * @param string $toAddress
     * @return Ride
     */
    public function setToAddress($toAddress)
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    /**
     * Get toAddress
     *
     * @return string
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Ride
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
     * @return Ride
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
     * Set ownerId
     *
     * @param integer $ownerId
     * @return Ride
     */
    public function setOwnerId(User $ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * Get ownerId
     *
     * @return integer
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getFromLat()
    {
        return $this->fromLat;
    }

    public function getFromLng()
    {
        return $this->fromLng;
    }

    public function getToLat()
    {
        return $this->toLat;
    }

    public function getToLng()
    {
        return $this->toLng;
    }

    public function setFromLat($fromLat)
    {
        $this->fromLat = $fromLat;
        return $this;
    }

    public function setFromLng($fromLng)
    {
        $this->fromLng = $fromLng;
        return $this;
    }

    public function setToLat($toLat)
    {
        $this->toLat = $toLat;
        return $this;
    }

    public function setToLng($toLng)
    {
        $this->toLng = $toLng;
        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getTotalSeats()
    {
        return $this->totalSeats;
    }

    public function getOccupiedSeats()
    {
        return $this->occupiedSeats;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setOwner(\Rwmt\Bundle\RwmtBundle\Entity\User $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function setTotalSeats($totalSeats)
    {
        $this->totalSeats = $totalSeats;
        return $this;
    }

    public function setOccupiedSeats($occupiedSeats)
    {
        $this->occupiedSeats = $occupiedSeats;
        return $this;
    }

    public function setUsers(Collection $users)
    {
        $this->users = $users;
        return $this;
    }




    /**
     * Add users
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\RideToUser $users
     * @return Ride
     */
    public function addUser(\Rwmt\Bundle\RwmtBundle\Entity\RideToUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\RideToUser $users
     */
    public function removeUser(\Rwmt\Bundle\RwmtBundle\Entity\RideToUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Set pickupDateTime
     *
     * @param \DateTime $pickupDateTime
     * @return Ride
     */
    public function setPickupDateTime($pickupDateTime)
    {
        $this->pickupDateTime = $pickupDateTime;

        return $this;
    }

    /**
     * Get pickupDateTime
     *
     * @return \DateTime
     */
    public function getPickupDateTime()
    {
        return $this->pickupDateTime;
    }

    /**
     * Set isRecursive
     *
     * @param boolean $isRecursive
     * @return Ride
     */
    public function setIsRecursive($isRecursive = false)
    {
//        if(!is_bool($isRecursive)){
//            throw new \InvalidArgumentException()
//        }
        $this->isRecursive = (bool)$isRecursive;

        return $this;
    }

    /**
     * Get isRecursive
     *
     * @return boolean
     */
    public function getIsRecursive()
    {
        return $this->isRecursive;
    }

    /**
     * Set recursiveDays
     *
     * @param string $recursiveDays
     * @return Ride
     */
    public function setRecursiveDays($recursiveDays)
    {
        $this->recursiveDays = $recursiveDays;

        return $this;
    }

    /**
     * Get recursiveDays
     *
     * @return string
     */
    public function getRecursiveDays()
    {
        return $this->recursiveDays;
    }
}
