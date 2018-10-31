<?php

class ControllerApicheckmobile extends Controller {
	public function index() {
         
            $log=new Log("checkmobile.log");
	    $this->load->language('api/farmerregistration');
            $this->load->model('transaction/savetransaction');
            $this->load->model('account/activity');
           
            $mcrypt = new MCrypt();
            $keys = array('emp_id');
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
        if (isset($this->request->post['emp_id'])){
        
          $j=$this->model_transaction_savetransaction->checkmobile($this->request->post);
            if($j>'0') {
                $i='1';
            }  else {
                $i='0';
            }
        } else {
             $i='0';
        }
        
        $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput($i);
		
    }
}