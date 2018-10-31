<?php

class ControllerApiRetailerregistrationtax extends Controller {
	public function index() {
            $log=new Log("retailerregistrationtax.log");
		//$this->load->language('api/retailerregistrationtax');



		$keys = array(
                        'username',
                        'SID',
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
   
$json = array();
               //$this->request->post["SID"]='b7aefdb84de75f2c2fe57124859c2cad';
               //$this->request->post["RETAILER_ID"]='b7aefdb84de75f2c2fe57124859c2cad';
if (isset($this->request->post["SID"]) &&  $this->request->post["RETAILER_ID"]!="0"){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('retailerregistration', $activity_data);
			
       
        //
		$this->load->model('retailer/retailerregistration');

		$api_chek = $this->model_retailer_retailerregistration->chechretailertax($this->request->post);
        
		 if($api_chek=='0'){
              	$api_info = $this->model_retailer_retailerregistration->reatailerregistrationtax($this->request->post);
                

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