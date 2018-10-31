<?php

class ControllerApiFarmerregistrationlog extends Controller {
	public function index() {
             $log=new Log("farmerregistrationlog.log");
		$this->load->language('api/farmerregistration');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                        
                        'SID',
                        'FARMER_NAME',
                        'FAR_MOBILE',
                        'CR_DATE',
                        'CR_BY',
                        'VILL_ID',
                        'DIST_ID',
                        'CAN_MLK_ID',
                        'FGM_ID',
                        'KEY_FARMER',
                        'MILKING_COWS_CNT',
                        'TOTAL_COWS',
                        'CURR_SUPPILER',
                        'DAILY_MILK_PROD',
                        'REMARKS',
                        'LAST_VISIT_ID',
                        'CAR_ID',
                        'FARMER_STATUS',
                        'APP_TRX_ID'
		);
   
   
	/*	foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}
*/
		$json = array();
if (isset($this->request->post["SID"]) && isset($this->request->post["username"]) && isset($this->request->post["FARMER_NAME"]) && $this->request->post["FARMER_NAME"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('farmerregistrationlog', $activity_data);
			
       
        //
        
		$this->load->model('farmer/farmerregistration');

		//$api_info = $this->model_farmer_farmerregistration->addRegistration($this->request->post);
                $api_info_log = $this->model_farmer_farmerregistration->addRegistration_log($this->request->post);

		if ($api_info_log) {
			//$this->session->data['api_id'] = $api_info['api_id'];

			//$json['cookie'] = $this->session->getId();

			$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                       /* data return*/
                        $json['data'] =$mcrypt->decrypt($api_info_log);
                        /* end data return*/
		} else {
			$json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
		}
}else {
			$json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}