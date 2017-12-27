<?php

/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/22/17
 * Time: 3:16 PM
 */
class SchedulingService extends CI_Model
{
    /**
     * Get the provider schedule from the 360 emed service
     * @param $providerID
     * @param $date
     */
    function getSchedules($providerID, $serviceID, $date)
    {
        //convert date string
        $dateObj = new DateTime($date);
        $dateStr = $dateObj->format('mm-ddd-yyyy');


        //returns the schedule
        $httputil = new CurlUtil();
        $apipath = $serviceID . '/' . $providerID . '/' . $dateStr . '/' . $dateStr;
        $results = $httputil->getData($apipath);
        print_r($results);
    }
}