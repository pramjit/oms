<?php

class ControllerApiAllempta extends Controller {
	public function index() {
            $log=new Log("allemployeedata.log");
		//$this->load->language('api/farmerdtl');

		$keys = array(
                        //'username',                     
                        'USER_ID'
                  
                   );
   
   

		$json = array();
                
               $this->request->post["USER_ID"]='b7aefdb84de75f2c2fe57124859c2cad';
                                      
                       
if ($this->request->post["USER_ID"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('allempta', $activity_data);
			
       
        //
		$this->load->model('ta/ta');




		$api_info = $this->model_ta_ta->allemployeetadtl($this->request->post);
        $log->write($api_info);
      $totalkm=$api_info['Total_Km'];

		if ($api_info) {	
				$this->load->model('account/customer');
                      
                        
			$json['success'] = $mcrypt->decrypt($totalkm);
           $json['data'] =$totalkm;

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