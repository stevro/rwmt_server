<?php

namespace Rwmt\Bundle\RwmtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tenant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Rwmt\Bundle\RwmtBundle\Entity\TenantRepository")
 */
class Tenant
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=50)
     */
    private $apiKey;

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
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="tenant")
     */
    private $usersOwned;

    /**
     *
     * @ORM\OneToMany(targetEntity="Ride", mappedBy="tenant")
     */
    private $ridesOwned;

    public function __construct()
    {
        $this->apiKey = $this->generateRandomString();
        $this->usersOwned = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ridesOwned = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Tenant
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
     * Set apiKey
     *
     * @param string $apiKey
     * @return Tenant
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Tenant
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
     * @return Tenant
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
     * Add ownedCars
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\User $ownedCars
     * @return Tenant
     */
    public function addUserOwned(\Rwmt\Bundle\RwmtBundle\Entity\User $userOwned)
    {
        $this->usersOwned[] = $userOwned;

        return $this;
    }

    /**
     * Remove ownedCars
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\User $usersOwned
     */
    public function removeUserOwned(\Rwmt\Bundle\RwmtBundle\Entity\User $userOwned)
    {
        $this->usersOwned->removeElement($userOwned);
    }

    /**
     * Get usersOwned
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersOwned()
    {
        return $this->usersOwned;
    }

    public function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Add rideOwned
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Ride $rideOwned
     * @return Tenant
     */
    public function addRideOwned(\Rwmt\Bundle\RwmtBundle\Entity\Ride $rideOwned)
    {
        $this->ridesOwned[] = $rideOwned;

        return $this;
    }

    /**
     * Remove rideOwned
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Ride $rideOwned
     */
    public function removeRideOwned(\Rwmt\Bundle\RwmtBundle\Entity\Ride $rideOwned)
    {
        $this->usersOwned->removeElement($rideOwned);
    }

    /**
     * Get rides owned
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRidesOwned()
    {
        return $this->usersOwned;
    }
}
