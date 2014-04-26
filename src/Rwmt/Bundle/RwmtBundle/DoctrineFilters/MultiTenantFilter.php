<?php

namespace Rwmt\Bundle\RwmtBundle\DoctrineFilters;

use Doctrine\ORM\Mapping\ClassMetaData,
    Doctrine\ORM\Query\Filter\SQLFilter;

class MultiTenantFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the MultiTenant interface
        if (!$targetEntity->reflClass->implementsInterface('Rwmt\Bundle\RwmtBundle\Entity\MultiTenant')) {
            return "";
        }

        return $targetTableAlias.'.tenant_id = ' . $this->getParameter('tenantId');
    }
}

