<?php

class ControllerApigetdistance extends Controller {
	public function index() {
            $log=new Log("getdistance.log");
	    $this->load->language('api/farmerregistration');
            $this->load->model('distance/getdistance');
            $this->load->model('account/activity');
           
            $mcrypt = new MCrypt();
            $keys = array('EMP_ID','LATT','LONGG');
           foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        //log to table
	    $log->write('function In');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('getdistance', $activity_data);
            $log->write($activity_data);
	//		

        $json=array();
        if (isset($this->request->post['EMP_ID']) && isset($this->request->post['LATT']) && isset($this->request->post['LONGG'])){
        
          $json['FARMER']=$this->model_distance_getdistance->nearestdata($this->request->post);
          $json['POS']=$this->model_distance_getdistance->nearestdatapos($this->request->post);
          
         
       
        }
        
        $this->response->addHeader('Content-Type: application/json');
	 $this->response->setOutput(json_encode($json));
    }
}