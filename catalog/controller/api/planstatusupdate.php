<?php

class ControllerApiPlanstatusupdate extends Controller {
	public function index() {
            $log=new Log("planstatusupdate.log");
		$this->load->language('api/planstatusupdate');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                    'SID',                    
                    'STATUS',
                    'EMP_ID'
			
                  
		);
   

   
	$json = array();
             //$this->request->post["SID"]='241f0daf03c0905cf8179d42a84b8149';
            // $this->request->post["STATUS"]='b7aefdb84de75f2c2fe57124859c2cad';
            
$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('plancreate', $activity_data);
			
       
        
		$this->load->model('plan/plancreate');


if (isset($this->request->post["SID"])  && $this->request->post["SID"]!="0"){


		$api_info = $this->model_plan_plancreate->planstatusupdate($this->request->post);
        $log->write($api_info);

		if ($api_info) {	
				$this->load->model('account/customer');
                      
                     
			$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                     //   $json['data'] =$mcrypt->decrypt($api_info);

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