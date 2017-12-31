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
    var $refreshfilePath = '/cube/apps/360emed/oauth_info.json';

    function __construct() {
        //load key from file
        $this->auth_key = file_get_contents($this->keyfilePath);
    }

    /**
     * reload the authorization token from EMR
     */
    function refreshToken()
    {
        //getSecret Json
        $authBody = file_get_contents($this->refreshfilePath);
        //URL of targeted site
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $this->authRefreshURL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                                               'Content-Length: ' . strlen($authBody)));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $authBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // grab URL and pass it to the browser
        $output = curl_exec($ch);

        $output = json_decode($output);
        curl_close($ch);
        //get the auth key and save it to file
        $authKey = 'Authorization: ' . $output->token_type . ' ' . $output->access_token;

        file_put_contents($this->keyfilePath,$authKey);
        $this->auth_key = file_get_contents($this->keyfilePath);
        
    }

    /**
     * Send GET data requests to EMR
     *
     * @param $apipath
     * @param int $retry
     * @return mixed
     * @throws Exception
     */
    public function getData($apipath, $retry=0)
    {

       if ($retry>=2)
       {
           throw new \Exception("Unable to authenticate to EMR server.");
       }
       //URL of targeted site
       $ch = curl_init();

       // set URL and other appropriate options
       curl_setopt($ch, CURLOPT_URL, $this->server . $apipath);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array($this->auth_key));
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

       // grab URL and pass it to the browser
       $output = curl_exec($ch);

       // get status code
       $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
       if ($status !=200)
       {
           $this->refreshToken();
           $retryCount = $retry+1;
           return $this->getData($apipath, $retryCount);

       }
       //echo $output;
       // close curl resource, and free up system resources


       return $output;
    }


    public function postData($apipath, $postData, $retry=0)
    {

        if ($retry>=2)
        {
            throw new \Exception("Unable to authenticate to EMR server.");
        }
        //URL of targeted site
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $this->server . $apipath);
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Content-Length: ' . strlen($postData),
            $this->auth_key));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // grab URL and pass it to the browser
        $output = curl_exec($ch);
        print_r($output);
        // get status code
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($status !=200)
        {
            $this->refreshToken();
            $retryCount = $retry+1;
            return $this->postData($apipath, $postData, $retryCount);

        }
        //echo $output;
        return $output;
    }
}