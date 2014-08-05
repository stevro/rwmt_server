<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiCarsControler
 *
 * @author stefan
 */

namespace Rwmt\Bundle\RwmtBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rwmt\Bundle\RwmtBundle\Entity\Car;
use Rwmt\Bundle\RwmtBundle\Form\CarType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiCarsController extends FOSRestController
{

    /**
     * Retrieves one car of a user
     * @ApiDoc(
     * section="Car",
     * parameters={
     *  {"name"="id", "dataType"="integer", "required"=true, "description"="The id of the car you want to read"},
     * })
     * @param string $id
     * @View()
     */
    public function getCarAction($id)
    {
        try {
            if (!$id) {
                throw new HttpException(400, 'Id must be provided!');
            }

            $user = $this->get('security.context')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();

            $userCar = $em->getRepository('RwmtBundle:Car');

            $car = $userCar->getUserCar($user->getId(), $id);

            if ($car === null) {
                throw new HttpException(404, 'Car could not be found!');
            }
            #var_dump($singleRide);die;
            return array('cars' => $car);
        } catch (HttpException $e) {
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME -- it returns an ugly message
        }
    }

    /**
     * Retrieves all cars of a user
     * @ApiDoc(
     * section="Car"
     * )
     * @param string $id
     * @View()
     */
    public function getCarsAction()
    {
        try {
            $user = $this->get('security.context')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();

            $userCar = $em->getRepository('RwmtBundle:Car');

            $car = $userCar->getUserCars($user->getId());

            if ($car === NULL) {
                throw new HttpException(404, 'Car could not be found!');
            }
            #var_dump($singleRide);die;
            return array('cars' => $car);
        } catch (HttpException $e) {
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME -- it returns an ugly message
        }
    }

    /**
     * Add a new car
     * @ApiDoc(
     * section="Car",
     * input="Rwmt\Bundle\RwmtBundle\Form\CarType",
     * parameters={
     *  {"name"="maker", "dataType"="string", "required"=true, "description"="The car manufacturer desired"},
     *  {"name"="model", "dataType"="string", "required"=true, "description"="The model of the new car"},
     *  {"name"="color", "dataType"="string", "required"=true, "description"="The color of the new car"},
     *  {"name"="year", "dataType"="string", "required"=true, "description"="The year when the car was produced"},
     *  {"name"="licencePlate", "dataType"="string", "required"=true, "description"="The car's licence plate"},     *
     *  })
     * @View()
     */
    public function postCarAction(Request $request)
    {
        try {

            $user = $this->get('security.context')->getToken()->getUser();

            $car = new Car();
            $car->setOwner($user);

            $form = $this->createForm(new CarType(), $car);
            $form->submit($request->request->all());
            #$form->get('userId')->submit($user);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($car);
                $em->flush();

                $url = $this->generateUrl('get_car', array('id' => $car->getId()), true);

                $response = new Response();
                $response->setStatusCode(Codes::HTTP_CREATED);
                $response->headers->set('Location', $url);

                return $response;
            } else {
                $response = new Response();
                $response->setStatusCode(Codes::HTTP_BAD_REQUEST);
                $response->setContent($form->getErrors(true, true));

                return $response;
            }
        } catch (HttpException $e) {
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME
        }
    }

    /**
     * Update a car
     * @ApiDoc(
     * section="Car",
     * input="Rwmt\Bundle\RwmtBundle\Form\CarType"
     * )
     * @View()
     */
    public function putCarAction(Request $request, $id)
    {

        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository('RwmtApiBundle:Car')->findOneBy(array('id' => $id, 'owner' => $user));

        if (!$car) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, 'Car not found!');
        }

        $form = $this->createForm(new CarType(), $car);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($car);
            $em->flush();

            $url = $this->generateUrl('get_car', array('id' => $car->getId()), true);

            return $this->routeRedirectView('get_car', array('id' => $car->getId()), Codes::HTTP_NO_CONTENT);
        }

        return $form;
    }

}