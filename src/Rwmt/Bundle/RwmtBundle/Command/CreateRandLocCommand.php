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

namespace Rwmt\Bundle\RwmtBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\Internal\Hydration\IterableResult;

#php -c /etc/php5/fpm/php.ini ~/sfprojects/smsApp/app/console smsServer:checkMessage check_multiple
#php -c /etc/php5/fpm/php.ini ~/sfprojects/smsApp/app/console smsServer:checkMessage check_single ID

class CreateRandLocCommand extends ContainerAwareCommand
{

    protected $em = null;
    protected $output = null;

    protected function configure()
    {
        $this->setName('rwmt:createRandLoc')
            ->setDescription('Create random locations')
            ->addArgument('total', InputArgument::REQUIRED, 'How many random locations you want to generate?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
            #echo "Memory usage before: " . (memory_get_usage() / 1024) . " KB" . PHP_EOL;
            $s = microtime(true);

            $this->output = $output;
            $date = date('d-M-Y H:i:s', time());
            $this->output->writeln("$date -- Command started!");


            //prepare dependencies
            //maybe use dependency injection for this in the future
            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getContainer()->get('doctrine')->getEntityManager('default');
            $em->getFilters()->disable('multi_tenant');

            $max = $input->getArgument('total');

            //get a user
            $user = $em->getRepository('RwmtBundle:User')->findOneBy(array('username'=>'stev'));

            /*
             * this solves the memory leak for the flush method runned in sf2
             * to be used only in commands and not in controllers as there it's being disabled by using the prod env
             */
            $em->getConnection()->getConfiguration()->setSQLLogger(null);

//            $N = array('long'=>51.687882,'lat'=>-0.14087);
//            $S = array('long'=>51.319026,'lat'=>-0.153916);
//            $W = array('long'=>51.512589,'lat'=>-0.607102);
//            $E = array('long'=>51.460425,'lat'=>0.330856);

            $N = array('long'=>57.231503,'lat'=>10.26947);
            $S = array('long'=>37.579413,'lat'=>13.960876);
            $W = array('long'=>42.875964,'lat'=>-8.627014);
            $E = array('long'=>48.04871,'lat'=>37.251892);

            $batchSize = 50;

            for($i=0;$i<=$max;$i++){
                $lat = 0; //51.319026, 51.687882
                $long = -1000; //-0.607102,0.330856

//                $lat_good = false;
//                $long_good = false;

                $fromAddress = $this->generateRandomString();
                $toAddress = $this->generateRandomString();

                $fromLat = $this->generateCoordinate($lat, $S['long'], $N['long']);
                $fromLong = $this->generateCoordinate($long, $W['lat'], $E['lat']);

                $toLat = $this->generateCoordinate($lat, $S['long'], $N['long']);
                $toLong = $this->generateCoordinate($long, $W['lat'], $E['lat']);


//                while(!$lat_good){
//                    if($lat < $S['long'] || $lat > $N['long'] ){
//                        $lat = $this->fprand(51, 52, 7);
//                        $lat_good = false;
//                    }else{
//                        $lat_good = true;
//                        return $lat;
//                    }
//                }
//                while(!$long_good){
//                    if($long < $W['lat'] || $long > $E['lat']  ){
//                         $long = $this->fprand(0,2, 9) - 1;
//                         $this->logSection('create-rand-loc', 'long: '.$long);
//                         $long_good = false;
//                    }else{
//                        $long_good = true;
//                    }
//                }

                $rideToUser = new \Rwmt\Bundle\RwmtBundle\Entity\RideToUser();
                $ride = new \Rwmt\Bundle\RwmtBundle\Entity\Ride();

                $rideToUser->setUser($user);
                $rideToUser->setRide($ride);

                $ride->setFromAddress($fromAddress);
                $ride->setToAddress($toAddress);
                $ride->setToLat($toLat);
                $ride->setToLng($toLong);
                $ride->setFromLat($fromLat);
                $ride->setFromLng($fromLong);
                $ride->setOwner($user);
                $ride->setTenant($user->getTenant());
                $ride->setPickupDateTime(new \DateTime());
                $em->persist($rideToUser);
                
                $this->output->writeln("New Ride with: FROM: $fromAddress, TO: $toAddress, toLat: $toLat, toLong: $toLong, fromLat: $fromLat, fromLong: $fromLong ");
                if (($i % $batchSize) === 0) {
                    $em->flush(); // Executes all updates.
                    $em->clear(); // Detaches all objects from Doctrine!
                    $this->output->writeln("Total rides so far: $i");
                    #echo "Memory usage during: " . (memory_get_usage() / 1024) . " KB" . PHP_EOL;
                    #$e = microtime(true);
                    #echo ' Updated  '. $i. ' messages in ' . ($e - $s) . ' seconds' . PHP_EOL;
                }

            }
        $em->flush();
        $this->output->writeln("Total rides $i");
        #echo "Memory usage after: " . (memory_get_usage() / 1024) . " KB" . PHP_EOL;
        $e = microtime(true);
        $seconds = round($e - $s, 2);
        $date = date('d-M-Y H:i:s', time());

        $this->output->writeln("$date -- Command finished in $seconds sec!");
    }


    protected function generateCoordinate($lat, $Slong, $Nlong)
    {
        if($lat < $Slong || $lat > $Nlong ){
            $lat = $this->fprand(round($Slong)-1, round($Nlong)+1, 7);

           return $this->generateCoordinate($lat, $Slong, $Nlong);
        }else{

            return $lat;
        }
    }

    // Floating point random number function

    protected function fprand($intMin,$intMax,$intDecimals) {

      if($intDecimals) {

        $intPowerTen= pow(10,$intDecimals);

        return rand($intMin*$intPowerTen,$intMax*$intPowerTen)/$intPowerTen;

      }

      else

        return rand($intMin,$intMax);

    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Sends a message to the SendMsgService and updates it
     *
     * @param Outbox $outbox
     * @param SendMsgService $sendMsg
     */

    /*
     * FIX ME - The locking mechanism consumes ~10KB per record
     */
//    protected function check(IterableResult $sentItems, CheckMsgService $checkMsg) //nu mai da SendMsg ca parametru si verifica memory leakul
//    {
//        $batchSize = 20;
//        $i = 0;
//
//        while(($row = $sentItems->next()) !== false) {
//            $sentItem = $row[0];
//            $sentItem->setIsLocked(true);
//
//            /*
//             * This will cause a memory leak if SQLLogging is enabled in Doctrine
//             * I've disabled it $this->em->getConnection()->getConfiguration()->setSQLLogger(null); in execute method above
//             */
//            $this->em->flush();
//
//            $sentItem = $checkMsg->checkSingleMsg($sentItem); //update the message status ... this takes aprox 100ms...and a memory leak of ~2KB
//            $sentItem->setIsLocked(false);
//            if (($i % $batchSize) === 0) {
//                $this->em->flush(); // Executes all updates.
//                $this->em->clear(); // Detaches all objects from Doctrine!
//
//                #echo "Memory usage during: " . (memory_get_usage() / 1024) . " KB" . PHP_EOL;
//                #$e = microtime(true);
//                #echo ' Updated  '. $i. ' messages in ' . ($e - $s) . ' seconds' . PHP_EOL;
//            }
//            ++$i;
//        }
//        $this->em->flush();
//
//        $this->output->writeln("Processing $i message(s) finished!");
//    }
}