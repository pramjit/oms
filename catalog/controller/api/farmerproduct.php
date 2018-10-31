<?php

class ControllerApifarmerproduct extends Controller {
	public function index() {
        
            $log=new Log("farmerproduct.log");
$log->write("in farmerproduct farmer");
	    $this->load->language('api/farmerregistration');
            $this->load->model('farmer/farmerregistration');
            $this->load->model('account/activity');
           
            $mcrypt = new MCrypt();
            $keys = array('SID',
                'TYPE',
                'PRODUCT_NAME',
                'PRODUCT_USAGE',
                'FARMER_ID',
                'TEMP_STATUS',
                'FARMER_PLAN_ID'
                
                );
           foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        //log to table
	
	    $log->write('function In');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('farmerproduct', $activity_data);
            $log->write($this->request->post);
	//		
     
        
        $json=array();
        if(isset($this->request->post["SID"])  && $this->request->post["SID"]!="0"){
         
          $json['data']=$this->model_farmer_farmerregistration->addproduct($this->request->post);
            $jsonout = '1';
        } else {
            $json['error'] = $this->language->get('error_upload');
            $jsonout = '0';
        }
        
       $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput($jsonout);
		
    }
}