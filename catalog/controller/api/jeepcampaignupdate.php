<?php

class ControllerApiJeepcampaignupdate extends Controller {
	public function index() {
            $log=new Log("Jeepcampaignupdate.log");
		//$this->load->language('api/jeepcampaignhalt');



		$keys = array(
                    
                        'SID'                                                     
                       
                   );
   
   

		$json = array();
               //$this->request->post["SID"]='835d6f33ec8c2198dd7b6ba07bff4b05';
               //$this->request->post["STATUS"]='b7aefdb84de75f2c2fe57124859c2cad';
               //$this->request->post["HALTS"]='b7aefdb84de75f2c2fe57124859c2cad';



$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('jeepcampaignupdate', $activity_data);
			
       
        //
		$this->load->model('JeepCampaign/jeepcampaign');


if (isset($this->request->post["SID"])!="0"){

		$api_info = $this->model_JeepCampaign_jeepcampaign->jeepcampaignupdate($this->request->post);
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