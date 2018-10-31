<?php

class ControllerApireceivetranction extends Controller {
	public function index() {
         
            $log=new Log("receivetranction.log");
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
            $this->model_account_activity->addActivity('receivetranction', $activity_data);
            $log->write($activity_data);
	//		
      
       
        //2$this->request->post['emp_id']='166';
        $json=array();
        if (isset($this->request->post['emp_id'])){
        
          $data=$this->model_transaction_savetransaction->getreceivetranctiondata($this->request->post);
          
        } 
        foreach($data as $val){
        $json['data'][]=array(
           'cr_date' => $mcrypt->encrypt($val['cr_date']),
            'qty' => $mcrypt->encrypt($val['qty']),
            'product_name' => $mcrypt->encrypt($val['product_name']),
            'firstname' => $mcrypt->encrypt($val['firstname']),
            'lastname' => $mcrypt->encrypt($val['lastname']),
             'User_Id' => $mcrypt->encrypt($val['User_Id']),
            'status' => $mcrypt->encrypt($val['status']) 
        );
        }
       
         //print_r($json);die; 
        
        
        $this->response->addHeader('Content-Type: application/json');
          
	$this->response->setOutput(json_encode($json));
		
    }
}