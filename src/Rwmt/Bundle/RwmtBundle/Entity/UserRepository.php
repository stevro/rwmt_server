<?php

namespace Rwmt\Bundle\RwmtBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{

    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, r')
            ->leftJoin('u.roles', 'r')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active user identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {

        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
        
//        $class = get_class($user);
//        if (!$this->supportsClass($class)) {
//            throw new UnsupportedUserException(
//                sprintf(
//                    'Instances of "%s" are not supported.',
//                    $class
//                )
//            );
//        }
//
//        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }

    /**
    *
    * getUserRide
    * Returns a single UserRide from the database
    *
    * @param int $id The id of the user
    * @param int $userRideId The id of the RideToUser
    * @param \Doctrine\Orm\Query const $hydrationMode
    * @return array/object
    */
   public function getUserRide($id,$userRideId,$hydrationMode = Query::HYDRATE_ARRAY){
       $q = $this->createQueryBuilder('u')
               ->select('u, ru, r')
               ->innerJoin('u.rides', 'ru')
               ->innerJoin('ru.ride', 'r')
               ->where('u.id = :id')
               ->andWhere('ru.id = :userRideId')
               ->setParameter('id', $id)
               ->setParameter('userRideId', $userRideId)
               ->getQuery();

       return $q->getOneOrNullResult($hydrationMode);
   }

   /**
    *
    * getUserAllRides
    * Returns all rides for a user from the database
    *
    * @param int $id The id of the user
    * @param \Doctrine\Orm\Query const $hydrationMode
    * @return array/object
    */
   public function getUserAllRides($id,$hydrationMode = Query::HYDRATE_ARRAY){
       $q = $this->createQueryBuilder('u')
               ->select('u, ru, r')
               ->innerJoin('u.rides', 'ru')
               ->innerJoin('ru.ride', 'r')
               ->where('u.id = :id')
               ->setParameter('id', $id)
               ->getQuery();

       return $q->execute(null, $hydrationMode);
   }

   /**
    *
    * @param type $token
    * @return object User
    */
   public function getUserForActivation($token)
   {
       $q = $this->createQueryBuilder('u')
               #->select('u')
               ->where('u.confirmationToken = :token')
               ->andWhere('u.isActive = 0')
               ->setParameter('token', $token)
               ->getQuery();

       return $q->getOneOrNullResult();
   }
}
