<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TenantListener
 *
 * @author stefan
 */

namespace Rwmt\Bundle\RwmtBundle\EventListener;

class TenantListener
{

    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $apiKey = $request->headers->get('rwmt-auth');

        if (null === $apiKey || 'cli' === php_sapi_name()) {
            return;
            #throw new \Symfony\Component\HttpKernel\Exception\HttpException(401, 'You must provide an API KEY!',null, array('Rwmt-Auth-Status' => 'Required'));
        }

        $conf = $this->em->getConfiguration();
        $conf->addFilter(
                'multi_tenant', 'Rwmt\Bundle\RwmtBundle\DoctrineFilters\MultiTenantFilter'
        );

        $filter = $this->em->getFilters()->enable('multi_tenant');

        $tenant = $this->em->getRepository('RwmtBundle:Tenant')->findOneBy(array('apiKey' => $apiKey));

        if (!$tenant) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(401, 'Wrong API KEY!', null,
            array('Rwmt-Auth-Status' => 'Invalid'));
        }

        $filter->setParameter('tenantId', $tenant->getId(), 'integer');
    }

}