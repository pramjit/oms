<?php

class ControllerApiSearchtadata extends Controller {
	public function index() {
            $log=new Log("searchtadata.log");
		//$this->load->language('api/jeepcampaign');



		$keys = array(
                         'CR_BY',
                     	 'TA_DATE'
                       );
   
   

		$json = array();
           
               
               
               


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('ta', $activity_data);
			
       		$this->load->model('ta/ta');


if (isset($this->request->post["CR_BY"]) !="0"){


		$data = $this->model_ta_ta->searchtadata($this->request->post);
        $log->write($api_info);

		if ($data) {	
			
 foreach($data as $val){
              $json=array();
        $json['data'][]=array(
            'SID' => $mcrypt->encrypt($val['SID']),
            'TA_DATE' => $mcrypt->encrypt($val['TA_DATE']),
            'TA_EMP_ID' => $mcrypt->encrypt($val['TA_EMP_ID']),
            'PLACE_FROM' => $mcrypt->encrypt($val['PLACE_FROM']),
            'PLACE_TO' => $mcrypt->encrypt($val['PLACE_TO']),
            'OPEN_MTR' => $mcrypt->encrypt($val['OPEN_MTR']),
            'CLOSE_MTR' => $mcrypt->encrypt($val['CLOSE_MTR']),
            'TAX_RS' => $mcrypt->encrypt($val['TAX_RS']),
            'FARE_RS' => $mcrypt->encrypt($val['FARE_RS']), 
            'PETROL_LTR' => $mcrypt->encrypt($val['PETROL_LTR']),
            'LOCAL_CONVEYANCE' => $mcrypt->encrypt($val['LOCAL_CONVEYANCE']),
            'HOTEL_RS' => $mcrypt->encrypt($val['HOTEL_RS']),
            'POSTAGE_RS' => $mcrypt->encrypt($val['POSTAGE_RS']),
            'PRINTING_RS' => $mcrypt->encrypt($val['PRINTING_RS']),
            'MISC_RS' => $mcrypt->encrypt($val['MISC_RS']),
            'UPLOAD_1' => $mcrypt->encrypt($val['UPLOAD_1']),
            'UPLOAD_2' => $mcrypt->encrypt($val['UPLOAD_2']),
            'UPLOAD_3' => $mcrypt->encrypt($val['UPLOAD_3']),             	
            'UPLOAD_4' => $mcrypt->encrypt($val['UPLOAD_4']),
            'REMARKS' => $mcrypt->encrypt($val['REMARKS']), 
            'STATUS' => $mcrypt->encrypt($val['STATUS']),
            'PLACE_TO1' => $mcrypt->encrypt($val['PLACE_TO1']),
            'PLACE_TO2' => $mcrypt->encrypt($val['PLACE_TO2']),
            'PLACE_TO3' => $mcrypt->encrypt($val['PLACE_TO3']),
            'PLACE_TO4' => $mcrypt->encrypt($val['PLACE_TO4'])
            
        );
        }


		} else {
			$json = 2;//$mcrypt->decrypt($this->language->get('error_login'));
		}




}else {
			$json = 0;//$mcrypt->decrypt($this->language->get('error_login'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}