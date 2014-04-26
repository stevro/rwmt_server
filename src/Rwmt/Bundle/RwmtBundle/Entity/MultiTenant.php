<?php

/*
 * This code is developed and maintained by InSoftDEV
 * For details send an email to contact@insoftd.com
 * Copyright (C) 2014
 *
 */

/*
 * Code updates
 * example:
 * date: 08-12-2020   name: Developer name  ID: LCMS-843  Title: Bananas buttons to be added
 *
 *
 */
namespace Rwmt\Bundle\RwmtBundle\Entity;
use Rwmt\Bundle\RwmtBundle\Entity\Tenant;

interface MultiTenant
{
    public function setTenant(Tenant $tenant);
    public function getTenant();
}