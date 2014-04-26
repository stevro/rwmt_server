<?php

namespace Rwmt\Bundle\RwmtBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Rwmt\Bundle\RwmtBundle\Entity\Tenant;

class RwmtBundle extends Bundle
{
    public function boot()
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $conf = $em->getConfiguration();
        $conf->addFilter(
            'multi_tenant',
            'Rwmt\Bundle\RwmtBundle\DoctrineFilters\MultiTenantFilter'
        );

        $filter = $em->getFilters()->enable('multi_tenant');

        if(!isset($_SERVER['HTTP_RWMT_AUTH'])){
            return;
        }

        $tenant = $em->getRepository('RwmtBundle:Tenant')->findOneBy(array('apiKey' => $_SERVER['HTTP_RWMT_AUTH']));

        if(!$tenant){
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(401, 'Wrong API KEY!', null, array('Rwmt-Auth-Status' => 'Invalid'));
        }

        $filter->setParameter('tenantId', $tenant->getId(), 'integer');
    }
}
