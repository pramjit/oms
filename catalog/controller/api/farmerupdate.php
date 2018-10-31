<?php

class ControllerApiFarmerupdate extends Controller {
	public function index() {
            $log=new Log("reatailerregistration.log");
		$this->load->language('api/farmerregistration');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                    'SID',
                    'username',
                    'VILL_ID',
                    'KEY_FARMER',
                    'MILKING_COWS_CNT',
                    'TOTAL_COWS',
                    'CURR_SUPPILER',
                    'DAILY_MILK_PROD',
                    'REMARKS',
                    'FARMER_STATUS',
			'LATT',
			'LONGG'
			
                  
		);
   

   
		$json = array();
if (isset($this->request->post["SID"]) && isset($this->request->post["username"])){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('farmerregistration', $activity_data);
			
       
        //
		$this->load->model('retailer/reatailerregistration');
              	$api_info = $this->model_retailer_reatailerregistration->reatailerregistration($this->request->post);
                

		if ($api_info) {
			
                      
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