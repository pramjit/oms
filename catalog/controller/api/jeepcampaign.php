<?php

class ControllerApiJeepcampaign extends Controller {
	public function index() {
            $log=new Log("Jeepcampaign.log");
		//$this->load->language('api/jeepcampaign');



		$keys = array(
                        'username',
                        'SID',
                        'CR_DATE',
                        'CR_BY',
                        'RETAILER_ID',
                        'VENDOR_NAME',
                        'DRIVER_NAME',
                        'DRIVER_MOBILE',
                        'VEHICLE_NO',
                        'VILLAGE_ID',
                        'OPEN_KM',
                        'PHOTO',
                        'LAT',
                        'LONGG',          
                        'STATUS',
                        'HALTS',                       
                        'DIESEL_RS',
			'DIESEL_LTR',
                        'DIESEL_USE',
                        'LABOR_EXPENCE',
                        'MISC_EXPENCE',
                        'JEEP_ID'
                   );
   
   

		$json = array();
              //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
              //$this->request->post["RETAILER_ID"]='b7aefdb84de75f2c2fe57124859c2cad';


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('jeepcampaign', $activity_data);
			
       
        //
		$this->load->model('JeepCampaign/jeepcampaign');


if (isset($this->request->post["SID"]) && isset($this->request->post["username"]) && $this->request->post["RETAILER_ID"]!="0"){


		$api_info = $this->model_JeepCampaign_jeepcampaign->jeepcampaign($this->request->post);
        $log->write($api_info);

		if ($api_info>0) {	
				$this->load->model('account/customer');
                      
                        
			$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                        $json['data'] =$mcrypt->decrypt($api_info);

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