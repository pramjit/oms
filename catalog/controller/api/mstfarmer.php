<?php

class ControllerApiMstfarmer extends Controller {
	public function index() {
            $log=new Log("mstfarmer.log");
		$this->load->language('api/mstfarmer');



		$keys = array(
                        'SID',
                        'FARMER_NAME',
                        'FARMER_MOBILE',                        
                        'DISTRICT_ID',
                        'VILLAGE_ID',
                        'TEHSIL_ID',
                        'BLOCK_ID',                            
                        'CR_BY',
                        'CR_DATE',
                        'JEEP_HALT_ID',
                        'APP_TRX_ID',
                        'STATUS'
                      
                   );
   
   

		$json = array();
                //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
                //$this->request->post["FARMER_NAME"]='b7aefdb84de75f2c2fe57124859c2cad';
if (isset($this->request->post["SID"]) &&  $this->request->post["FARMER_NAME"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('mstfarmer', $activity_data);
			
       
        //
		$this->load->model('farmer/mstfarmer');




		$api_info = $this->model_farmer_mstfarmer->mstfarmer($this->request->post);
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