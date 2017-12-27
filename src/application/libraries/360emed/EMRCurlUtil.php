<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/18/17
 * Time: 11:31 PM
 */
class EMRCurlUtil
{
    var $auth_key = "";
    var $server = "https://portal.unionhealthcenter.org:3000/";


    function __construct() {
        //load key from file
        $this->auth_key = file_get_contents("/var/www/keys/emedapi-key.txt");
    }

    public function getData($apipath)
    {

       //URL of targeted site
       $ch = curl_init();

       // set URL and other appropriate options
       curl_setopt($ch, CURLOPT_URL, $this->server . $apipath);
       curl_setopt($ch, CURLOPT_HEADER, array($this->auth_key));
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