<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Open Source Web Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) 2013 - 2016, Alex Tselegidis
 * @license     http://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        http://easyappointments.org
 * @since       v1.2.0
 * ---------------------------------------------------------------------------- */

require_once __DIR__ . '/API_V1_Controller.php';

use \EA\Engine\Api\V1\Response;
use \EA\Engine\Api\V1\Request;

/**
 * Schedule Controller
 *
 * @package Controllers
 * @subpackage API
 */
class Schedule extends API_V1_Controller {
    /**
     * Settings Resource Parser
     * 
     * @var \EA\Engine\Api\V1\Parsers\Settings
     */
    protected $emed_util;

    /**
     * Class Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->emed_util = new SchedulingService();
    }

    /**
     * POST API Method
     */
    public function post() {
        try {
            // Insert the schedule to the cache database.
            $request = new Request();
            $scheduleJson = $request->getBody();
            $result = json_decode($scheduleJson);
            
            $this->emed->cacheSchedule($result->eaproviderID, $result->id, $result->eacategoryID, $result->start, $result->end, $result->emrproviderID, $result->emrcategoryID);


            $response = new Response([
                'code' => 200,
                'message' => 'CacheRecord was inserted successfully!'
            ]);
            $response->output();
        } catch(\Exception $exception) {
            exit($this->_handleException($exception));
        }
    }
}

/* End of file Settings.php */
/* Location: ./application/controllers/api/v1/Settings.php */
