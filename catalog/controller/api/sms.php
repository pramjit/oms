<?php

class ControllerApiSms extends Controller {
	public function index() {
            $log=new Log("unregisterfarmer.log");
	    $this->load->language('api/farmerregistration');
            $this->load->model('farmer/farmerregistration');
            $this->load->model('account/activity');
            $this->load->model('account/customer');
            $mcrypt = new MCrypt();
            
            
        //log to table
	    $log->write('function In');
            $activity_data = $this->request->get;
            $this->model_account_activity->addActivity('smsrecev', $activity_data);
	//		

              $data=array();
        $data['MOBILE_NO']=$this->request->get['mobilenumber'];
        $data['MESSAGE']=$this->request->get['message'];
        $data['MESSAGE_DATE']=$this->request->get['receviedon'];
        
        $t = microtime(true);
   	$micro = sprintf("%02d",($t - floor($t)) * 100);
   	$utc = date('ymdHis', $t).$micro;
	$data['TRANSACTIONID']=$utc;
        
        if (isset($this->request->get['mobilenumber']) && isset($this->request->get['message'])){
        
           $this->sms->smsinsert($data);
           $api_info = $this->model_farmer_farmerregistration->unregisterfarmer($data);
           if($api_info==1) {
               
               //$customer_info = $this->model_account_customer->getCustomerByUserId($this->request->get['mobilenumber']);
               $this->sms->sendsms($this->request->get['mobilenumber'],"6","" );
               $this->sms->updateSms("6",$utc);
           }
        }
    
		
    }
}