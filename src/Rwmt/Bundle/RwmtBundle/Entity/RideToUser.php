<?php

namespace Rwmt\Bundle\RwmtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * RideToUser
 *
 * @ORM\Table(name="RideToUser", uniqueConstraints={@ORM\UniqueConstraint(name="unique_client_ride", columns={"user_id","ride_id"})})
 * @ORM\Entity(repositoryClass="Rwmt\Bundle\RwmtBundle\Entity\RideToUserRepository")
 * @UniqueEntity(fields={"userId","rideId"}, message="A user can only join once to a ride!")
 */
class RideToUser
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
     * @var integer
     *
     * @ORM\Column(name="ride_id", type="integer")
     */
    private $rideId;

     /**
     * @var \Rwmt\Bundle\RwmtBundle\Entity\Ride
     *
     * @ORM\ManyToOne(targetEntity="\Rwmt\Bundle\RwmtBundle\Entity\Ride", inversedBy="users")
     * @ORM\JoinColumn(name="ride_id", referencedColumnName="id", nullable=FALSE)
     */
    private $ride;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var \Rwmt\Bundle\RwmtBundle\Entity\User
     *
     *
     * @ORM\ManyToOne(targetEntity="\Rwmt\Bundle\RwmtBundle\Entity\User", inversedBy="rides")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     */
    private $user;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rideId
     *
     * @param integer $rideId
     * @return RideToUser
     */
    public function setRideId($rideId)
    {
        $this->rideId = $rideId;

        return $this;
    }

    /**
     * Get rideId
     *
     * @return integer
     */
    public function getRideId()
    {
        return $this->rideId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return RideToUser
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RideToUser
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
     * @return RideToUser
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

    public function getRide()
    {
        return $this->ride;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setRide(\Rwmt\Bundle\RwmtBundle\Entity\Ride $ride)
    {
        $this->ride = $ride;
        return $this;
    }

    public function setUser(\Rwmt\Bundle\RwmtBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }


}
