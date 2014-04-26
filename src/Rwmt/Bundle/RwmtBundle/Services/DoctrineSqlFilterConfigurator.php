<?php

namespace Rwmt\Bundle\RwmtBundle\Services;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class DoctrineSqlFilterConfigurator
{
    private $em; // inject the entity manager somehow (ctor is a good idea)

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
//        $filter = $this->em->getFilters()->enable('multi_tenant');
//        $filter->setParameter('tenantId', 1); #$event->getRequest()->getSession()->get('param_name')
    }
}
