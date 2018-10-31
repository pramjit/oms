<?php

class ControllerApipassbookdetails extends Controller {
	public function index() {
         
            $log=new Log("inventrytranction.log");
	    $this->load->language('api/farmerregistration');
            $this->load->model('transaction/savetransaction');
            $this->load->model('account/activity');
           
            $mcrypt = new MCrypt();
            $keys = array('emp_id');
           foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        //log to table
       // $this->request->post['emp_id']='166';
	    $log->write('function In');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('inventrytranction', $activity_data);
            $log->write($activity_data);
	//		
      
       
        
        $json=array();
       
        if (isset($this->request->post['emp_id'])){
        
          //$json['receive']=$this->model_transaction_savetransaction->getreceivetranctiondata($this->request->post);
           //$json['send']=$this->model_transaction_savetransaction->getsendtranctiondata($this->request->post);
            $data=$this->model_transaction_savetransaction->send_receive($this->request->post);
           // print_r($json['send_receive']);die;
           
        } 
        
        foreach($data as $val){
        $json['data'][]=array(
           'cr_date' => $mcrypt->encrypt($val['cr_date']),
            'qty' => $mcrypt->encrypt($val['qty']),
            'product_name' => $mcrypt->encrypt($val['product_name']),
            'firstname' => $mcrypt->encrypt($val['firstname']),
            'lastname' => $mcrypt->encrypt($val['lastname']),
            'customer_id' => $mcrypt->encrypt($val['customer_id']),
            'customer_group_id' => $mcrypt->encrypt($val['customer_group_id']),
             'User_Id' => $mcrypt->encrypt($val['User_Id']),
            'status' => $mcrypt->encrypt($val['status']) 
        );
        }
       // print_r($json['data']);die;
        $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode($json));
		
    }
}