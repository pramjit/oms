<?php

class ControllerApicustomerfarmerregistration extends Controller {
	public function index() {
            $log=new Log("customerfarmerregistration.log");
		$this->load->language('api/farmerregistration');



		$keys = array(
                        'username',
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
                        'APP_TRX_ID',
			'LATT',
			'LONGG',
                        'FAR_SEGMENT',
                        'FAR_POSSITION',
                        'FAR_CATEGORY',
                        'IMAGE'
		);
   
   

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

				$this->model_account_activity->addActivity('farmerregistration', $activity_data);
			
       
        //
		$this->load->model('farmer/farmerregistration');

$far_info = $this->model_farmer_farmerregistration->getfarmerbymobile($this->request->post);
                if(empty($far_info)) { 


		$api_info = $this->model_farmer_farmerregistration->addcustomerRegistration($this->request->post);
        $log->write($api_info);

		if ($api_info>0) {	
				//$this->load->model('account/customer');
                        //$customer_info = $this->model_account_customer->getCustomer($this->request->post['username']);
       // $log->write($customer_info);
                       // $this->sms->sendsms($this->request->post["FAR_MOBILE"],"3",$customer_info,$this->request->post["FARMER_NAME"] );
			$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                        $json['data'] =$mcrypt->decrypt($api_info);

		} else {
			$json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
		}

 } else {
                $json['success'] = $mcrypt->decrypt($this->language->get('text_success'));                
            }


}else {
			$json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}