<?php

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
        $searchFilter =  '?filter={"where":{"and":[{"first":"' . $firstname . '"},{"last":"' . $lastname . '"},{"birthdate":"' . $dob . '"},{"email":"' . $email . '"}]}}';
        $results = $cutil->getData($searchFilter);
        print_r($results);die;
        return $results;
    }
}