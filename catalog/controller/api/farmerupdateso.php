<?php

class ControllerApiFarmerupdateso extends Controller {
	public function index() {
            $log=new Log("farmerupdateso.log");
		$this->load->language('api/farmerregistration');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                    'SID',
                    'FARMER_NAME',
                    'CAR_ID',
                    'REMARKS',
                    'FAR_SEGMENT',
                    'FAR_POSSITION'
                  
		);
  

   
	/*	foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}
*/
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

				$this->model_account_activity->addActivity('farmerupdateso', $activity_data);
			
       
        //
		$this->load->model('farmer/farmerregistration');
              	$api_info = $this->model_farmer_farmerregistration->updatefarmerinfoso($this->request->post);
                

		if ($api_info) {
			
                       // $customer_info = $this->model_account_customer->getCustomerByUserId($this->request->post['username']);
                        //$this->sms->sendsms($this->request->post['username'],"1",$customer_info );
			$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                       $j='1';
                        /* data return*/
                        //$json['data'] =$mcrypt->decrypt($api_info);
                        /* end data return*/
		} else {
			$json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
                        $j='0';
		}
                
            
            
        }else {
		 $json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
                  $j='0';
		 }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($j);
	}
}