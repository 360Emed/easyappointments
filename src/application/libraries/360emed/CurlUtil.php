<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/18/17
 * Time: 11:31 PM
 */
class CurlUtil
{
    var $auth_key = "112112-1221-221212";
    var $server = "https://360emed.api.hmtrevolution.com/";

    public function getData($apipath)
    {

       //URL of targeted site
       $ch = curl_init();
       // set URL and other appropriate options
       curl_setopt($ch, CURLOPT_URL, $this->server . $apipath . '?validationToken=' . $this->auth_key);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


       // grab URL and pass it to the browser
       $output = curl_exec($ch);

       //echo $output;
       // close curl resource, and free up system resources
       curl_close($ch);

       return $output;
    }
}