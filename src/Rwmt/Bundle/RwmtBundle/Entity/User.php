<?php

namespace Rwmt\Bundle\RwmtBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UserRepository")
 * @UniqueEntity(fields="email", message="Email already registered")
 * @UniqueEntity(fields="username", message="Username already taken")
 *
 * @ExclusionPolicy("all")
 */
class User implements AdvancedUserInterface, Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Username must not be blank")
     * @ORM\Column(name="username", type="string", length=255)
     * @Expose
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Email must not be blank")
     * @Assert\Length(min=3,max=50)
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255)
     * @Expose
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Phone must not be blank")
     * @ORM\Column(name="phone", type="string", length=255)
     * @Expose
     */
    private $phone;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Firstname must not be blank")
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Expose
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Lastname must not be blank")
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Expose
     */
    private $lastName;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=150)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=40)
     */
    private $salt;

    /**
     *
     * @Assert\NotBlank(message="Password should not be blank", groups={"registration"})
     * @Assert\Length(min=8, groups={"registration"})
     */
    private $rawPassword;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="RideToUser", mappedBy="user")
     */
    private $rides;

    /**
     * @var Ride
     *
     * @ORM\OneToMany(targetEntity="Ride", mappedBy="owner")
     */
    private $ownedRides;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     * @Expose
     */
    private $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @var string
     * @ORM\Column(name="confirmation_token", type="string")
     */
    private $confirmationToken;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @Expose
     */
    private $roles;

    /**
     * @var Car
     *
     * @ORM\OneToMany(targetEntity="Car", mappedBy="owner")
     *
     */
    private $ownedCars;

    public function __construct()
    {
        $this->salt = sha1(uniqid().microtime().rand(0, 999999));
        $this->isActive = 0;
        $this->confirmationToken = $this->generateRandomString();
        $this->rides = new ArrayCollection();
        $this->roles = new ArrayCollection();


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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
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
     * @return User
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

    public function getRides()
    {
        return $this->rides;
    }

    public function getOwnedRides()
    {
        return $this->ownedRides;
    }

    public function setRides(Collection $rides)
    {
        $this->rides = $rides;
        return $this;
    }

    public function setOwnedRides(Ride $ownedRides)
    {
        $this->ownedRides = $ownedRides;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function setRawPassword($rawPassword)
    {
        $this->rawPassword = $rawPassword;
        return $this;
    }

     public function __toString()
    {
        return $this->username;
    }

     /**
     *
     * @Assert\True(message="Your password must not contain your email", groups={"registration"})
     */
    public function isPasswordValid(){
        return 0 === preg_match('/'.preg_quote($this->email).'/i',$this->rawPassword);
    }

    public function getRoles(){
        return $this->roles->toArray();
    }

    public function eraseCredentials() {
        $this->rawPassword = null;
    }

    public function encodePassword(PasswordEncoderInterface $encoder){
        if($this->rawPassword){
            $this->password = $encoder->encodePassword($this->rawPassword, $this->salt);
            $this->eraseCredentials();
        }
    }

    public function serialize(){
        return serialize($this);
    }

    public function unserialize($serialized) {
        return unserialize($serialized);
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setLastLogin(\DateTime $lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function activateAccount()
    {
        $this->isActive = 1;
        $this->confirmationToken = $this->confirmationToken . '-USED';
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }


    /**
     * Add rides
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\RideToUser $rides
     * @return User
     */
    public function addRide(\Rwmt\Bundle\RwmtBundle\Entity\RideToUser $rides)
    {
        $this->rides[] = $rides;

        return $this;
    }

    /**
     * Remove rides
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\RideToUser $rides
     */
    public function removeRide(\Rwmt\Bundle\RwmtBundle\Entity\RideToUser $rides)
    {
        $this->rides->removeElement($rides);
    }

    /**
     * Add ownedRides
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Ride $ownedRides
     * @return User
     */
    public function addOwnedRide(\Rwmt\Bundle\RwmtBundle\Entity\Ride $ownedRides)
    {
        $this->ownedRides[] = $ownedRides;

        return $this;
    }

    /**
     * Remove ownedRides
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Ride $ownedRides
     */
    public function removeOwnedRide(\Rwmt\Bundle\RwmtBundle\Entity\Ride $ownedRides)
    {
        $this->ownedRides->removeElement($ownedRides);
    }

    /**
     * Add roles
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Rwmt\Bundle\RwmtBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Role $roles
     */
    public function removeRole(\Rwmt\Bundle\RwmtBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Add ownedCars
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Car $ownedCars
     * @return User
     */
    public function addOwnedCar(\Rwmt\Bundle\RwmtBundle\Entity\Car $ownedCars)
    {
        $this->ownedCars[] = $ownedCars;

        return $this;
    }

    /**
     * Remove ownedCars
     *
     * @param \Rwmt\Bundle\RwmtBundle\Entity\Car $ownedCars
     */
    public function removeOwnedCar(\Rwmt\Bundle\RwmtBundle\Entity\Car $ownedCars)
    {
        $this->ownedCars->removeElement($ownedCars);
    }

    /**
     * Get ownedCars
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnedCars()
    {
        return $this->ownedCars;
    }

}
