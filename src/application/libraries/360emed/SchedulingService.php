<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'CurlUtil.php';
require_once 'EMRCurlUtil.php';
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/22/17
 * Time: 3:16 PM
 */
class SchedulingService
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
        $dateStr = $dateObj->format('m-d-Y');


        //returns the schedule
        $httputil = new CurlUtil();
        $apipath = 'getdoctorschedule/' . $serviceID . '/' . $providerID . '/' . $dateStr . '/' . $dateStr;
        $results = $httputil->getData($apipath);
        print_r($results);
    }
}