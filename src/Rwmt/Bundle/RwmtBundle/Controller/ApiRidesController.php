<?php

namespace Rwmt\Bundle\RwmtBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rwmt\Bundle\RwmtBundle\Entity\Ride;
use Rwmt\Bundle\RwmtBundle\Form\RideType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiRidesController extends Controller
{

    /**
     * Retrieves all rides
     * @ApiDoc()
     * @View(template="RwmtBundle:API\rides:getRides.html.twig")
     *
     */
    public function getRidesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ride = $em->getRepository('RwmtBundle:Ride');

        $rides = $ride->getAllRides();

        if(($rides === NULL || count($rides) == 0)){
            throw new HttpException(404, 'Rides could not be found!');
        }

        return array('rides'=>$rides);
    }



    /**
     * Retrieves all nearby rides
     * @ApiDoc()
     * @View(template="RwmtBundle:API\rides:getClosestRidesByDistance.html.twig")
     * @Get("rides/closest/long/{long}/lat/{lat}/range/{range}/limit/{limit}");
     * #47.152763,27.588426 | lat - long
     */
    public function getRidesClosestAction($long = null, $lat = null, $range = 1, $limit = 20)
    {
        if(!$long || !$lat){
            throw new HttpException(400, 'Lat and Long must be provided!');
        }

        $em = $this->getDoctrine()->getManager();
        $ride = $em->getRepository('RwmtBundle:Ride');

        $rides = $ride->getClosestRides($long, $lat, $range, $limit);
#var_dump($rides);die;
        if(($rides === NULL || empty($rides))){
            throw new HttpException(404, 'Rides could not be found!');
        }

        return array('rides'=>$rides);
    }

    /**
     * Retrieves one ride
     * @ApiDoc(parameters={
     *  {"name"="id", "dataType"="integer", "required"=true, "description"="The id of the ride you want to retrieve"},
     * })
     * @param string $id
     * @View(template="RwmtBundle:API\rides:getRide.html.twig")
     */
    public function getRideAction($id){
        try{
            if(!$id){
                throw new HttpException(400, 'Id must be provided!');
            }

            $em = $this->getDoctrine()->getManager();

            $ride = $em->getRepository('RwmtBundle:Ride');

            $singleRide = $ride->getRide($id);

            if($singleRide === NULL){
                throw new HttpException(404, 'Ride could not be found!');
            }

            return array('ride'=>$singleRide);
        }
        catch(HttpException $e){
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME -- it returns an ugly message
        }
    }

    /**
     * postRideAction
     *
     * Adds a new ride to the database
     *
     * @param Request $request The current request
     *
     * @View(template="RwmtBundle:API\Rides:postRides.html.twig")
     * @ApiDoc(parameters={
     *  {"name"="fromAddress", "dataType"="string", "required"=true, "description"="The start address of the ride"},
     *  {"name"="toAddress", "dataType"="string", "required"=true, "description"="The end address of the ride"},
     *  {"name"="fromLat", "dataType"="double", "required"=true, "description"="The latitude of the from address"},
     *  {"name"="fromLng", "dataType"="double", "required"=true, "description"="The longitude of the from address"},
     *  {"name"="toLat", "dataType"="double", "required"=true, "description"="The latitude of the to address"},
     *  {"name"="toLng", "dataType"="double", "required"=true, "description"="The longitude of the to address"},
     *  {"name"="totalSeats", "dataType"="integer", "required"=true, "description"="The total number of seats available"},
     *  {"name"="pickupDateTime", "dataType"="datetime", "required"=true, "description"="The date & time of the pickup"},
     *  {"name"="isRecursive", "dataType"="boolean", "required"=false, "description"="If it's a recursive ride"},
     *  {"name"="recursiveDays", "dataType"="string", "required"=false, "description"="The days when the recursive ride takes place - to discuss the format"},
     *  })
     */
    #curl -X POST -d "fromAddress=Iasi,Copou&toAddress=Iasi&toLong=27.633263&toLat=47.151552&fromLat=47.151319&fromLong=27.596877" -H "Accept:application/json" http://localhost.rwmt.com/app_dev.php/api/v1/rides -v
    #47.152763,27.588426 | lat - long
    #47.150968,27.584682
    #47.151319,27.596877
    #48.516604,57.517548
    public function postRideAction(Request $request)
    {
        try{
            $user = $this->get('security.context')->getToken()->getUser();

            $ride = new Ride();
            $ride->setOwner($user);

            $form = $this->createForm(new RideType(), $ride);

            $form->submit($request->request->all());

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                //TO-DO: FIX ME
                //THIS IS STUFF IS NOT SO OK
                $tenantId = $em->getFilters()->getFilter('multi_tenant')->getParameter('tenantId');
                $tenant = $em->getRepository('RwmtBundle:Tenant')->findOneBy(array('id'=>str_replace("'", "", $tenantId)));
                $ride->setTenant($tenant);
                //END OF JMEN

                $em->persist($ride);

                $em->flush();

                //TO-DO probably call some background services or fire an event

                $url = $this->generateUrl('get_ride',array('id' => $ride->getId()), true);

                $response = new Response();
                $response->setStatusCode(Codes::HTTP_CREATED);
                $response->headers->set('Location', $url);

                return $response;
            }else{
                $response = new Response();
                $response->setStatusCode(Codes::HTTP_BAD_REQUEST);
                $response->setContent($form->getErrors(true, true));

                return $response;
            }

        }
        catch(HttpException $e){
            $response = new Response();
            $response->setStatusCode($e->getStatusCode());
            $response->setContent($e->getMessage());

            return $response; //FIX ME
        }

    }
}
