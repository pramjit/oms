<?php

class ControllerApicheckfarmermobile extends Controller {
	public function index() {
         
            $log=new Log("checkmobile.log");
	    $this->load->language('api/farmerregistration');
            $this->load->model('transaction/savetransaction');
            $this->load->model('account/activity');
            
           
            $mcrypt = new MCrypt();
            $keys = array('mobile_no','farmer_name');
           foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        //log to table
	    $log->write('function In');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('checkmobile', $activity_data);
            $log->write($activity_data);
	//		
      
       
        
        $json=array();
        if (isset($this->request->post['mobile_no'])){
       
          $j=$this->model_transaction_savetransaction->checfarmerkmobile($this->request->post);
          if($j=='1') {
              $i='1';
          } else if($j=='2'){
                $i='3';
              
          }else {
                $otp=rand(1111,9999);
                $this->request->post["otp"]=$otp;
                $this->model_transaction_savetransaction->tempstoreotp($this->request->post);
                $this->sms->sendsms($this->request->post['mobile_no'],'1','NA',$otp);
                $i='2';
            }
            
        } else {
             $i='0';
        }
        
        $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput($i);
		
    }
}