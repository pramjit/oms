<?php

class ControllerApiFarmerdtl extends Controller {
	public function index() {
            $log=new Log("farmerdtl.log");
		$this->load->language('api/farmerdtl');

		$keys = array(
                        'username',
                        'SID', 
                        'USER_ID',
                        'USER_NAME',                        
                        'DISTRICT_ID',
                        'TEHSIL_ID',
                        'BLOCK_ID',
                        'VILL_ID',                                                 
                        'CR_DATE',                        
                        'APP_TRX_ID',
                        'IMAGE',
                        'LATT',
                        'LONGG',
                        'RETAILER'       
                      
                   );
   
   

		$json = array();
                //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
                //$this->request->post["USER_ID"]='b7aefdb84de75f2c2fe57124859c2cad';
                                      
                       
if (isset($this->request->post["SID"]) &&  $this->request->post["USER_ID"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('farmerdtl', $activity_data);
			
       
        //
		$this->load->model('farmer/farmerdtl');




		$api_info = $this->model_farmer_farmerdtl->farmerdtl($this->request->post);
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