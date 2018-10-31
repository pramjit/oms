<?php

class ControllerApiFarmdemo extends Controller {
	public function index() {
            $log=new Log("farmdemo.log");
		//$this->load->language('api/jeepcampaign');



		$keys = array(
                        'username',
                            
'SID',
'VILLAGE_ID',	
'FARMER_ID',
'DEMO_ACRES',
'CROP_ID',

'PHOTO',
'PHOTO_1',
'PHOTO_2',
'PHOTO_3',
'PHOTO_4',                    
'LAT',
'LONGG',
'CR_BY',
'CR_DATE',
'STATUS',
                    'PRODUCT',
                    'QUANTITY'
                   );
   
   

		$json = array();
             //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
             // $this->request->post["username"]='b7aefdb84de75f2c2fe57124859c2cad';


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('farmer', $activity_data);
			
       
        //
		$this->load->model('farmer/farmdemo');


if (isset($this->request->post["SID"]) && isset($this->request->post["username"])!="0"){


		$api_info = $this->model_farmer_farmdemo->farmerdemodata($this->request->post);
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