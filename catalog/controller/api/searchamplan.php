<?php

class ControllerApiSearchamplan extends Controller {


	public function index() {
             $log=new Log("Searchamplandata.log");
		
		$keys = array(                 
                     	 'CR_BY',
                         'TEHSIL'
                       );
   
   

		$json = array();
           
               
               
               
             // $this->request->post["CR_BY"]='b7aefdb84de75f2c2fe57124859c2cad';
             //$this->request->post["TEHSIL"]='b7aefdb84de75f2c2fe57124859c2cad';

$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('plancreate', $activity_data);
			
       		$this->load->model('plan/plancreate');


if (isset($this->request->post["CR_BY"]) !="0"){


		$data = $this->model_plan_plancreate->searchamplandata($this->request->post);
        $log->write($api_info);

		if ($data) {	
			
 foreach($data as $val){     //print_r($val);
              $json=array();
        $json['data'][]=array(
                 'SID' => $mcrypt->encrypt($val['SID']),
            'TEHSIL_NAME' => $mcrypt->encrypt($val['TEHSIL_NAME']),
            'MO_NAME' => $mcrypt->encrypt($val['MO_NAME']),
            'DATE' => $mcrypt->encrypt($val['DATE']),
            'WHOLE_SALE_PERSON' => $mcrypt->encrypt($val['WHOLE_SALE_PERSON']),
            'RETAILER_NAME' => $mcrypt->encrypt($val['RETAILER_NAME']),
            'STATUS' => $mcrypt->encrypt($val['STATUS'])
        );
        }


		} else {
			$json = 0;//$mcrypt->decrypt($this->language->get('error_login'));
		}




}else {
			$json = 0;//$mcrypt->decrypt($this->language->get('error_login'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
                //echo "yes";
	}
        
      
}