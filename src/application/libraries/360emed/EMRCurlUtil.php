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
    var $authRefreshURL = 'https://emed360.auth0.com/oauth/token';
    var $keyfilePath = '/cube/apps/360emed/auth_token';


    function __construct() {
        //load key from file
        $this->auth_key = file_get_contents($this->keyfilePath);
    }

    function refreshToken()
    {
        //getSecret Json
        $authBody = file_get_contents($this->keyfilePath);

        //URL of targeted site
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $this->authRefreshURL);
        curl_setopt($ch, CURLOPT_HEADER, array('content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $authBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // grab URL and pass it to the browser
        $output = curl_exec($ch);
        $output = json_decode($output);

        //get the auth key and save it to file
        $authKey = 'Authorization: ' . $output->token_type . ' ' . $output->access_token;
        file_put_contents($this->keyfilePath,$authKey);
        $this->auth_key = file_get_contents($this->keyfilePath);
        
    }

    public function getData($apipath, $retry=0)
    {
        print_r($this->server . $apipath);
       if ($retry==2)
       {
           throw new \Exception("Unable to authenticate to EMR server.");
       }
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

       // get status code
       $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

       if ($status = 401)
       {
           $this->refreshToken();
           $this->getData($apipath, $retry++);

       }
       //echo $output;
       // close curl resource, and free up system resources
       curl_close($ch);

       return $output;
    }
}