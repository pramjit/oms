<?php

class ControllerApipdfdownload extends Controller {
	public function index() {
         
            $log=new Log("approvefarmer.log");
            $log->write("in approve farmer");
	    $this->load->language('api/farmerregistration');
            $this->load->model('techfarmer/customerpdf');
            $this->load->model('account/activity');
           
            $mcrypt = new MCrypt();
            $keys = array('sid','Cr_Date' );
           foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        //log to table
	
	    $log->write('function In');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('approvefarmer', $activity_data);
            $log->write($this->request->post);
	//		
     
        
        $json=array();
        if(isset($this->request->post["sid"]) ){
          
          
          $jsonlink=$this->model_techfarmer_customerpdf->getlink($this->request->post);
          if($jsonlink=='0') {
              $jsonlink='0';
          } else {
          $jsonlink=HTTP_SERVER."system/pdf/".$jsonlink;
          }
        } else {
            $json['error'] = $this->language->get('error_upload');
            $jsonlink=0;
        }
        
       $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput($jsonlink);
		
    }
}