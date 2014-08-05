<?php

namespace Rwmt\Bundle\RwmtBundle\Controller;

use Doctrine\ORM\Query;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rwmt\Bundle\RwmtBundle\Entity\Ride;
use Rwmt\Bundle\RwmtBundle\Entity\RideToUser;
use Rwmt\Bundle\RwmtBundle\Form\EditUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiUserController extends FOSRestController
{
    /**
     * Requests to join a ride
     * @ApiDoc(
     * section="User",
     * requirements={
     *  {"name"="id", "dataType"="integer", "required"=true, "description"="The id of the ride you want to join"},
     * },
     * statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized",
     *         404="Returned when the ride is not found",
     *         409={"Ride is fully booked","A user tries to join his own ride"}
     *     })
     * @View()
     * @Post("/rides/{id}/join");
     */
    public function postUserJoinRideAction(Request $request, $id)
    {
        try{

            $user = $this->get('security.context')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();
            /* @var $ride Ride */
            $ride = $em->getRepository('RwmtBundle:Ride')->getRide($id, Query::HYDRATE_OBJECT);

            //TO-DO: Move all this validations to somewhere else
            //Keep It Slim
            if($ride === null){
                throw new HttpException(Codes::HTTP_NOT_FOUND, 'Ride could not be found!');
            }

            if($ride->getOccupiedSeats() >= $ride->getTotalSeats()){
                throw new HttpException(Codes::HTTP_CONFLICT, 'Ride is fully booked!');
            }

            if($ride->getOwner()->getId() == $user->getId()){
                throw new HttpException(Codes::HTTP_CONFLICT, "You can't join your own ride!");
            }

            //TO-DO: Move this update to somewhere else
            //Keep It Slim
            $rideToUser = new RideToUser();
            $rideToUser->setUser($user);
            $rideToUser->setRide($ride);
            $ride->setOccupiedSeats($ride->getOccupiedSeats() + 1);
            $em->persist($rideToUser);
            $em->flush();

            /*
            * The email sending should be moved to a background task
            * //Keep It Slim
            */
            $message = Swift_Message::newInstance()
               ->setSubject('RWMT Ride Request')
               ->setFrom('no-reply@ridewithme.today')
               ->setTo($ride->getOwner()->getEmail())
               ->setBody(
                   $this->renderView(
                       'RwmtBundle:API\rides:joinRequest.email.twig',
                       array('rideToUser' => $rideToUser)
                   )
               );
            $this->get('mailer')->send($message);

            $url = $this->generateUrl('get_user_ride',array('id' => $rideToUser->getId()), true);

            $response = new Response();
            $response->setStatusCode(Codes::HTTP_CREATED);
            $response->headers->set('Location', $url);

            return $response;

        }
        catch(HttpException $e){
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME
        }
    }

    /**
     * Retrieves all rides of a user
     * @ApiDoc(
     * section="User"
     * )
     * @View(template="RwmtBundle:API\userRides:getUserAllRides.html.twig")
     * @Get("users/rides/");
     */
    public function getUserRidesAction(){
        try{
            $user = $this->get('security.context')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();

            $userRide = $em->getRepository('RwmtBundle:User');

            $rides = $userRide->getUserAllRides($user->getId());

            if($rides === null){
                throw new HttpException(404, 'Rides could not be found!');
            }
            #var_dump($singleRide);die;
            return array('userRides'=>$rides);
        }
        catch(HttpException $e){
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME -- it returns an ugly message
        }
    }

    /**
     * Retrieves one ride of a user
     * @ApiDoc(
     * section="User",
     * parameters={
     *  {"name"="id", "dataType"="integer", "required"=true, "description"="The id of the user ride you want to read"},
     * })
     * @param string $id
     * @View(template="RwmtBundle:API\userRides:getUserRide.html.twig")
     * @Get("users/rides/{id}");
     */
    public function getUserRideAction($id){
        try{
            if(!$id){
                throw new HttpException(400, 'Id must be provided!');
            }
            $user = $this->get('security.context')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();

            $userRide = $em->getRepository('RwmtBundle:User');

            $singleRide = $userRide->getUserRide($user->getId(), $id);

            if($singleRide === NULL){
                throw new HttpException(404, 'Ride could not be found!');
            }
            #var_dump($singleRide);die;
            return array('userRide'=>$singleRide);
        }
        catch(HttpException $e){
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME -- it returns an ugly message
        }
    }




    /**
     * gets an user account
     *
     * @ApiDoc(
     * section="Account",
     * parameters={}
     * )
     * )
     */
    public function getAccountAction(Request $request, $id)
    {
        try{
            $user = $this->get('security.context')->getToken()->getUser();
            if($user->getId() != $id){
                //This could evolve in the future to allow ROLE_ADMIN to edit any account
                throw new HttpException(404, 'User cannot be found!');
            }

            return array('user'=>$user);

        } catch (HttpException $e) {
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME
        }
    }

    /**
     * update an user account
     *
     *
     * @ApiDoc(
     * section="Account",
     * input="Rwmt\Bundle\RwmtBundle\Form\EditUserType",
     * parameters={
     *  {"name"="username", "dataType"="string", "required"=true, "description"="The username desired"},
     *  {"name"="email", "dataType"="string", "required"=true, "description"="The email of the new user"},
     *  {"name"="phone", "dataType"="string", "required"=true, "description"="The phone number of the user"},
     *  {"name"="firstName", "dataType"="string", "required"=true, "description"="The first name of the user"},
     *  {"name"="lastName", "dataType"="string", "required"=true, "description"="The last name of the user"},
     *  })
     * )
     */
    public function putAccountAction(Request $request, $id)
    {
        try{
            $user = $this->get('security.context')->getToken()->getUser();

            if($user->getId() != $id){
                //This could evolve in the future to allow ROLE_ADMIN to edit any account
                throw new HttpException(404, 'User cannot be found!');
            }

            $form = $this->createForm(new EditUserType(), $user);

            $form->submit($request->request->all());

            if ($form->isValid()) {

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $user->encodePassword($encoder);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                /*
                 * The email sending should be moved to a background task
                 */
                $message = Swift_Message::newInstance()
                    ->setSubject('RWMT Account updated')
                    ->setFrom('no-reply@ridewithme.today')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'RwmtBundle:API\register:updateAccount.email.twig',
                            array('user' => $user)
                        )
                    );
                $this->get('mailer')->send($message);

                $response = new Response();
                $response->setStatusCode(Codes::HTTP_OK);

                return $response;
            }else{
                throw new HttpException(400, $form->getErrors(true, true));
            }

        }catch(HttpException $e){
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME
        }
    }
}
