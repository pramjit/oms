<?php

class ControllerApiFarmerregistration extends Controller {
	public function index() {
            $log=new Log("farmerregistration.log");
		$this->load->language('api/farmerregistration');



		$keys = array(  
                         'username',
                        'SID',
                        'FARMER_NAME',
                        'FARMER_MOBILE',
                        'ADDRESS',
                        'DISTRICT_ID',
                        'VILLAGE_ID',
                        'TEHSIL_ID',
                        'BLOCK_ID',
                        'PINCODE',
                        'PHOTO',
                        'LAND_ACRES',
                        'PROBLEM',
                        'SOLUTION',          
                        'CR_BY',
                        'CR_DATE',                       
                        'LAT',
			'LONGG',
                        'CROP',
                        'RETAILER',
                        'FGM_ID',                        
                        'APP_TRX_ID'
                   );
   
   

		$json = array();
if (isset($this->request->post["SID"]) &&  $this->request->post["FARMER_MOBILE"]!="0"){


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




		$api_info = $this->model_farmer_farmerregistration->addRegistration($this->request->post);
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