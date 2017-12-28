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
    function getSchedules($providerID, $serviceID, $startdate, $enddate)
    {
        //convert date string
        $startdateObj = new DateTime($startdate);
        $startdateStr = $startdateObj->format('m-d-Y');

        $enddateObj = new DateTime($enddate);
        $enddateStr = $enddateObj->format('m-d-Y');

        //returns the schedule
        $httputil = new CurlUtil();
        $apipath = 'getdoctorschedule/' . $serviceID . '/' . $providerID . '/' . $startdateStr . '/' . $enddateStr;
        $results = $httputil->getData($apipath);

        //results is in json format
        return $results;
    }

    /**
     * returns the unavailable days by month
     * @param $providerID
     * @param $serviceID
     * @param $month
     */
    function getUnavailableDaysForMonth($providerID, $serviceID, $date)
    {
        //convert date string
        $dateObj = new DateTime($date);
        $firstDay = date('m-01-Y', $dateObj);
        $lastDay = date('m-t-Y', $dateObj);

        //get all available timeslots for this month
        $results = $this->getSchedules($providerID, $serviceID, $firstDay, $lastDay);

        //decode the schedule
        $openslots = json_decode($results);
        $openslotsDates = array();
        foreach ($openslots as $openslot)
        {
            $startTime = $openslot->start;
            $startTimeObj = new DateTime($startTime);
            $formattedDate = date('Y-m-d',$startTimeObj);
            $openslotsDates[$formattedDate] = 1;
        }

        $unavailableslots = array();

        //loop through all days in a month
        $start = new DateTime($firstDay);
        $end = new DateTime($lastDay);
        $interval = DateInterval::createFromDateString('1 day');

        $period = new DatePeriod($start, $interval, $end);

        foreach ( $period as $dt )
        {
            $dateStr = $dt->format('Y-m-d');
            if (!array_key_exists($dateStr,$openslotsDates ))
            {
                $unavailableslots[]=$dateStr;
            }
        }

        $unavailabilities = json_encode($unavailableslots);

        return $unavailabilities;
    }
}