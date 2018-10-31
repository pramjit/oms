<?php

class ControllerApisavetransaction extends Controller {
	public function index() {
         
            $log=new Log("savetransaction.log");
            $log->write("in approve farmer");
	    $this->load->language('api/farmerregistration');
            $this->load->model('transaction/savetransaction');
            $this->load->model('account/activity');
           
            $mcrypt = new MCrypt();
            $keys = array(
                'USER_ID',
                'PRODUCT_ID',
                'QTY',
                'TRANSFERRED_BY',
                'TRANSACTION_ID',
                'LAT',
                'LONGG'
               
                );
           foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        //log to table
	
	    $log->write('function In');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('savetransaction', $activity_data);
            $log->write($this->request->post);
	//		
     
        
        $json=array();
        if(isset($this->request->post["USER_ID"]) && isset($this->request->post["PRODUCT_ID"]) && $this->request->post["USER_ID"]!="0"){
         
          $json['data']=$this->model_transaction_savetransaction->savetransactiondetails($this->request->post);
          $jsonout = '1';
          
        } else {
            $json['error'] = $this->language->get('error_upload');
            $jsonout = '0';
        }
        
       $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput($jsonout);
		
    }
}