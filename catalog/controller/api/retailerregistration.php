<?php

class ControllerApiRetailerregistration extends Controller {
	public function index() {
            $log=new Log("retailerregistration.log");
		//$this->load->language('api/farmerregistration');



		$keys = array(
                        'username',
                        'SID',
                        'RETAILER_NAME',
                        'CONTACT_PERSON',
                        'MOBILE',
                        'ADDRESS',
                        'DISTRICT_ID',                        
                        'TEHSIL_ID',
                        'BLOCK_ID',
                        'PINCODE',
                        'PHOTO',
                        'LAT',
			'LONGG',
                        'EMAIL',
                        'CR_BY',
                        'CR_DATE',
                        'VILLAGE',
                        'FARMER',
                        'WHOLESELLER'
                      );
   
   

		$json = array();
                //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
                //$this->request->post["RETAILER_NAME"]='b7aefdb84de75f2c2fe57124859c2cad';
if (isset($this->request->post["SID"]) &&  $this->request->post["RETAILER_NAME"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('retailerregistration', $activity_data);
			
       
        //
		$this->load->model('retailer/retailerregistration');




		$api_info = $this->model_retailer_retailerregistration->reatailerregistration($this->request->post);
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