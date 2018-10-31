<?php

class ControllerApiFarmdemostausupdate extends Controller {
	public function index() {
            $log=new Log("farmdemostausupdate.log");
		$this->load->language('api/farmdemostausupdate');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                    'username',
                    'SID',
                    'STATUS'
                  
		);
     
	/*	foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}
*/
		$json = array();
                //$this->request->post["username"]='b7aefdb84de75f2c2fe57124859c2cad';
                 //$this->request->post["SID"]='d4a86947563c9b6f782f6dffa83f2a97';
                //$this->request->post["STATUS"]='5e9470fc68748c382703f50d60900d5a';
if (isset($this->request->post["SID"])!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('updatefarmdemostaus', $activity_data);
			
       
        //
		$this->load->model('farmer/farmdemo');
              	$api_info = $this->model_farmer_farmdemo->updatefarmdemostaus($this->request->post);
                

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