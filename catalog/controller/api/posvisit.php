<?php

class ControllerApiPosvisit extends Controller {
	public function index() {
            $log=new Log("posvisit.log");
		$this->load->language('api/posvisit');



		$keys = array(
                    'username',
                        'SID',
                        'VISIT_TYPE',
                        'POS_ID',
                        'FARMER_ID',
                        'CR_DATE',
                        'USER_ID',
                        'REMARKS',
                        'APP_TRX_ID',                            
                        'NEXT_VISIT_DATE',
                        'PURPOSE_ID'
                      
                   );
   
   

		$json = array();
            
                
if (isset($this->request->post["SID"]) &&  $this->request->post["VISIT_TYPE"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('posvisit', $activity_data);
			
       
        //
		$this->load->model('pos/posvisit');




		$api_info = $this->model_pos_posvisit->posvisitdata($this->request->post);
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