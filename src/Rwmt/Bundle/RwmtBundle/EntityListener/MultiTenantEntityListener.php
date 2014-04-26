<?php

/**
 * Description of OutboxListener
 *
 * @author stefan
 */

namespace Rwmt\Bundle\RwmtBundle\EntityListener;

#use Symfony\Bridge\Monolog\Logger;
#use Doctrine\ORM\Mapping\PostLoad;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Rwmt\Bundle\RwmtBundle\Entity\User;

class MultiTenantEntityListener
{
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
       $user->setTenantId(1);
    }

    public function preUpdate(User $user, LifecycleEventArgs $event)
    {

    }

//    public function postPersist(Outbox $outbox, LifecycleEventArgs $event)
//    {
//        $outbox->getId();
//    }

    /**
     * Gets triggered when calling flush in the middle of it
     */
//    public function onFlush(OnFlushEventArgs $eventArgs)
//    {
//        $em = $eventArgs->getEntityManager();
//        $uow = $em->getUnitOfWork();
//
//        foreach ($uow->getScheduledEntityInsertions() AS $entity) {
//            var_dump($entity->getId());die;
//        }
//
//        foreach ($uow->getScheduledEntityUpdates() AS $entity) {
//
//        }
//
//        foreach ($uow->getScheduledEntityDeletions() AS $entity) {
//
//        }
//
//        foreach ($uow->getScheduledCollectionDeletions() AS $col) {
//
//        }
//
//        foreach ($uow->getScheduledCollectionUpdates() AS $col) {
//
//        }
//    }
//
//
//    /**
//     * Gets triggered when calling flush at the end of it
//     */
//    public function postFlush(PostFlushEventArgs $args)
//    {
//        #var_dump('AAA');die;
//        #$em = $args->getEntityManager();
//        $a = $args::getEmptyInstance();
//        var_dump($a);die;
//        // perhaps you only want to act on some "Product" entity
//        if ($entity instanceof Outbox) {
//
//        }
//        var_dump('XXX');die;
//       # var_dump($outbox->getId());die;
////        $dispatcher = $this->container->get('event_dispatcher');
////        $dispatcher->dispatch(SmsEvents::NEW_OUTBOX, new OutboxEvent($sms));
//    }
}