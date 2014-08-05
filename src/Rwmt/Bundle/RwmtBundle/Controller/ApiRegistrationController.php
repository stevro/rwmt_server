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

namespace Rwmt\Bundle\RwmtBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;
use Rwmt\Bundle\RwmtBundle\Form\UserType;
use Rwmt\Bundle\RwmtBundle\Entity\User;
use \Symfony\Component\HttpKernel\Exception\HttpException;

class ApiRegistrationController extends FOSRestController
{

    /**
     * postRegisterAction
     *
     * Registers a new user
     *
     * @param Request $request The current request
     *
     * @Post("accounts")
     * @ApiDoc(
     * section="Account",
     * input = "Rwmt\Bundle\RwmtBundle\Form\UserType",
     * )
     *
     */
    public function postRegisterAction(Request $request)
    {
        $entity = new \Rwmt\Bundle\RwmtBundle\Entity\User();
        $form = $this->createForm(new UserType(), $entity);

        $form->submit($request);

        if ($form->isValid()) {

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $entity->encodePassword($encoder);

            $em = $this->getDoctrine()->getManager();

            //TO-DO: FIX ME
            //THIS IS STUFF IS NOT SO OK
            $tenantId = $em->getFilters()->getFilter('multi_tenant')->getParameter('tenantId');
            $tenant = $em->getRepository('RwmtBundle:Tenant')->findOneBy(array('id' => str_replace("'", "", $tenantId)));
            $entity->setTenant($tenant);
            //END OF JMEN

            $em->persist($entity);
            $em->flush();

            /*
             * The email sending should be moved to a background task
             */
            $message = \Swift_Message::newInstance()
                    ->setSubject('RWMT New Account')
                    ->setFrom('no-reply@ridewithme.today')
                    ->setTo($entity->getEmail())
                    ->setBody(
                    $this->renderView(
                            'RwmtBundle:API\register:newAccount.email.twig', array('user' => $entity)
                    )
            );
            $this->get('mailer')->send($message);

            $url = $this->generateUrl('put_confirm_account', array('id' => $entity->getId()), true);

            $response = new Response();
            $response->setStatusCode(Codes::HTTP_CREATED);
            $response->headers->set('Location', $url);

            return $response;
        }

        return $form;
    }

    /**
     * putConfirmAccountAction
     *
     * Confirms a user account
     *
     * @param string token The token
     *
     * @Put("account/confirm")
     * @ApiDoc(
     * section="Account",
     * parameters={
     *  {"name"="token", "dataType"="string", "required"=true, "description"="The token received over email at registration."}
     *  })
     */
    public function putConfirmAccountAction(Request $request)
    {
        //read user with $token
        $em = $this->getDoctrine()->getManager();

        /** @var User */
        $user = $em->getRepository('RwmtBundle:User')->getUserForActivation($request->request->get('token'));

        if (!$user) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, 'User could not be found!');
        }

        $user->activateAccount();
        $em->flush();

        /*
         * The email sending should be moved to a background task
         */
        $message = \Swift_Message::newInstance()
                ->setSubject('RWMT Account Activation')
                ->setFrom('no-reply@ridewithme.today')
                ->setTo($user->getEmail())
                ->setBody(
                $this->renderView(
                        'RwmtBundle:API\register:accountActivation.email.twig', array('user' => $user)
                )
        );
        $this->get('mailer')->send($message);

        $response = new Response();
        $response->setStatusCode(Codes::HTTP_NO_CONTENT);

        return $response;
    }

    /**
     * postLogin
     *
     * Login into an user account
     *
     *
     * @Post("login")
     * @ApiDoc(
     * section="Account",
     * parameters={
     *
     *  })
     */
    public function postLoginAction(Request $request)
    {
        /* @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        if (!$user->isCredentialsNonExpired() || !$user->isAccountNonLocked() || !$user->isAccountNonExpired() || !$user->isEnabled()) {
            throw new HttpException(403);
        }

        return array('user' => $user);
    }

    /*
     * HERE we can add more controller
     * - for validating his email address
     * - for validating his mobile number
     * - etc
     */
}