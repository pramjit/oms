<?php

class ControllerApiJeepcampaignhalt extends Controller {
	public function index() {
            $log=new Log("Jeepcampaignhalt.log");
		//$this->load->language('api/jeepcampaignhalt');



		$keys = array(
                        'username',
                        'SID',
                        'JEEP',
                        'CR_DATE',
                        'VILLAGE_ID',
                        'PHOTO',
                        'LAT',
                        'LONGG',
                        'CR_BY'                           
                       
                   );
   
   

		$json = array();
               //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
               //$this->request->post["JEEP"]='b7aefdb84de75f2c2fe57124859c2cad';



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


if (isset($this->request->post["SID"]) && isset($this->request->post["username"])!="0"){

		$api_info = $this->model_JeepCampaign_jeepcampaign->jeepcampaignhalt($this->request->post);
        $log->write($api_info);

		if ($api_info) {	
				$this->load->model('account/customer');
                      
                        
			$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                        

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