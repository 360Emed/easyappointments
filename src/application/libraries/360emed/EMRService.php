<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'EMRCurlUtil.php';

/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/29/17
 * Time: 3:53 PM
 */
class EMRService
{
    function searchPatient($firstname, $lastname, $email, $dob)
    {
        $cutil = new EMRCurlUtil();
        $searchFilter =  'api/Patientprofiles?filter={"where":{"and":[{"first":"' . $firstname . '"},{"last":"' . $lastname . '"},{"birthdate":"' . $dob . '"},{"email":"' . $email . '"}]}}';
        $results = $cutil->getData($searchFilter);
        return $results;
    }

    /**
     *
     * @param $customer
     */
    function createAppointment($customer, $scheduleData)
    {
        //instantiate util
        $cutil = new EMRCurlUtil();
        //create post body form data
        $patientData = array();
        $patientData['facilityid'] = $scheduleData['emrcategoryID'];
        $patientData['ownerid'] = $customer['emrpatientID'];
        $patientData['appstart'] = $scheduleData['start'];
        $patientData['appstop'] = $scheduleData['end'];
        $patientData['emrapptstart'] = $scheduleData['start'];
        $patientData['durantion'] = 20;
        $patientData['doctorid'] = $scheduleData['emrproviderID'];
        $patientData['resourceid'] = $scheduleData['emrproviderID'];
        $patientData['appttypeid'] = 181;
        $patientData['companyid'] = 439;
        $patientData['createdby'] = 'vpatel';
        $patientData['lastmodifiedby'] = 'vpatel';
        $patientData['scheduleID'] = $scheduleData['scheduleID'];
        //send the request
        $result = $cutil->postData('api/Appointments/ApptInsert', json_encode($patientData));

    }

}