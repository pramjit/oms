<?php

class ControllerApiPosregistrationtaxupdate extends Controller {
	public function index() {
            $log=new Log("posregistrationtaxupdate.log");
		$this->load->language('api/posregistrationtaxupdate');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                    'username',
                   
                    'RETAILER_ID',
                    'TIN_GST_NO',
                    'PAN_NO',
                    'ADDHAR_NO',
                    'FRC_NO',
                    'FRC_VALID_UPTO',
                    'SEED_LICENCE',
                    'SEED_LICENCE_UPTO',
                    'MFMS_ID',
                    'PESTICIDE_LICENCE',
                    'PESTICIDE_LICENCE_VALID_UPTO'
             
                  
		);
  

   
	/*	foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}
*/
		$json = array();
                     //$this->request->post["SID"]='1366b02d29c43ac8468699cfe865cf32';
                     //$this->request->post["TIN_GST_NO"]='b7aefdb84de75f2c2fe57124859c2cad';
                     //$this->request->post["PAN_NO"]='b7aefdb84de75f2c2fe57124859c2cad';
                     //$this->request->post["ADDHAR_NO"]='b7aefdb84de75f2c2fe57124859c2cad';

if (isset($this->request->post["RETAILER_ID"]) && isset($this->request->post["TIN_GST_NO"])){


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
                $api_chek = $this->model_pos_posvisit->chechpostax($this->request->post);
                if($api_chek=='0'){
              	$api_info = $this->model_pos_posvisit->updateretailertaxpos($this->request->post);
                

               	$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                $j='1';
                } else {
                  $j='2';  
                }     
		
            
            
        }else {
		 $json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
                  $j='3';
		 }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($j);
	}
}