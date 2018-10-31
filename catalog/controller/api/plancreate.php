<?php

class ControllerApiPlancreate extends Controller {
	public function index() {
            $log=new Log("plancreate.log");
		//$this->load->language('api/jeepcampaign');



		$keys = array(
                        'username',
                        'SID',
                        'TEHSIL',
                        'WHOLE_SELLER',
                        'CR_DATE',
                        'RETAILER',
                        'APP_TR_ID',
                        'STATUS',
                        'CR_BY'
                        
                      
                   );
   
   

		$json = array();
             //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
              //$this->request->post["TEHSIL"]='b7aefdb84de75f2c2fe57124859c2cad';


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('plancreate', $activity_data);
			
       
        //
		$this->load->model('plan/plancreate');


if (isset($this->request->post["SID"])  && $this->request->post["SID"]!="0"){


		$api_info = $this->model_plan_plancreate->plancreate($this->request->post);
        $log->write($api_info);

		if ($api_info>0) {	
				$this->load->model('account/customer');
                      
                        
			$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                        $json['data'] =$mcrypt->decrypt($api_info);

		} else {
			$json['error']= $mcrypt->decrypt($this->language->get('error_login'));
		}




}else {
			$json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}