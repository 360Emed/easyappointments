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
        $patientData['facilityid'] = (string)$scheduleData['emrcategoryID'];
        $patientData['ownerid'] = (string)$customer['emrpatientID'];
        $patientData['apptstart'] = (string)$scheduleData['start'];
        $patientData['apptstop'] = (string)$scheduleData['end'];
        $patientData['emrapptstart'] = (string)$scheduleData['start'];
        $patientData['duration'] = '20';
        $patientData['doctorid'] = (string)$scheduleData['emrproviderID'];
        $patientData['resourceid'] = (string)$scheduleData['emrproviderID'];
        $patientData['appttypeid'] = '181';
        $patientData['companyid'] = '439';
        $patientData['createdby'] = 'vpatel';
        $patientData['lastmodifiedby'] = 'vpatel';
        $patientData['scheduleid'] = (string)$scheduleData['scheduleID'];
        
        //send the request
        $result = $cutil->postData('api/Appointments/ApptInsert', json_encode($patientData));

    }

}