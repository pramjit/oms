<?php

class ControllerApiFarmobservation extends Controller {
	public function index() {
            $log=new Log("farmdemo_observation.log");
		$this->load->language('api/farmobservation');



		$keys = array(  
                        'username',
                        'SID',
                        'FARM_DEMO_ID',
                        'VILLAGE_ID',
                        'FARMER_ID',
                        'CR_BY',
                        'CR_DATE',
                        'PHOTO', 
                        'PHOTO_1',
                        'PHOTO_2',
                        'PHOTO_3',
                        'PHOTO_4',                   
                        'LAT',
                        'LONGG',
                        'REMARKS'
                   );
   
   

		$json = array();
                
                 //$this->request->post["SID"]='835d6f33ec8c2198dd7b6ba07bff4b05';
                 //$this->request->post["FARM_DEMO_ID"]='b7aefdb84de75f2c2fe57124859c2cad';
                 //$this->request->post["username"]='b7aefdb84de75f2c2fe57124859c2cad';
if (isset($this->request->post["SID"]) &&  $this->request->post["username"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('farmobservation', $activity_data);
			
       
        //
		$this->load->model('farmer/farmobservation');




		$api_info = $this->model_farmer_farmobservation->farmdemoobservation($this->request->post);
        $log->write($api_info);

		if ($api_info>0) {	
				$this->load->model('account/customer');
                      
                        
			$json['success'] = $mcrypt->encrypt($this->language->get('text_success'));
                       // $json['data'] =$mcrypt->decrypt($api_info);

		} else {
			$json['error'] = $mcrypt->encrypt($this->language->get('error_login'));
		}




}else {
			$json['error'] = $mcrypt->encrypt($this->language->get('error_login'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}