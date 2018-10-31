<?php

class ControllerApiPosregistrationupdate extends Controller {
	public function index() {
            $log=new Log("posregistrationupdate.log");
		$this->load->language('api/posregistrationupdate');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                    'username',
                    'SID',
                    'CONTACT_PERSON',
                    'ADDRESS',
                    'EMAIL',
                    'WHOLESELLER'
             
                  
		);
  

   
	/*	foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}
*/
		$json = array();
                //$this->request->post["SID"]='61374c55ccec48a96d2e43d74f9a420c';
               //$this->request->post["CONTACT_PERSON"]='b7aefdb84de75f2c2fe57124859c2cad';
                //$this->request->post["ADDRESS"]='b7aefdb84de75f2c2fe57124859c2cad';
                // $this->request->post["EMAIL"]='b7aefdb84de75f2c2fe57124859c2cad';
                 

if (isset($this->request->post["SID"]) && isset($this->request->post["CONTACT_PERSON"])){


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
                
              	$api_info = $this->model_pos_posvisit->updateretailerpos($this->request->post);
                	
		$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                $j='1';
                     
		
            
            
        }else {
		 $json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
                  $j='2';
		 }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($j);
	}
}