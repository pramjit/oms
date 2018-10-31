<?php

class ControllerApiFarmerposupdate extends Controller {
	public function index() {
            $log=new Log("farmerposupdate.log");
		$this->load->language('api/farmerposupdate');

		// Delete old login so not to cause any issues if there is an error
//		unset($this->session->data['api_id']);

		$keys = array(
                    'SID',
                    'username',
                    'ADDRESS',
                    'LAND_ACRES',
                    'CROP',
                    'RETAILER',
                    'PROBLEM',
                    'SOLUTION'
			
                  
		);
   

   
		$json = array();
               // $this->request->post["SID"]='1b0e2f614256dcc5e2eade6b7c67331b';
                  //   $this->request->post["ADDRESS"]='b7aefdb84de75f2c2fe57124859c2cad';
                  //   $this->request->post["LAND_ACRES"]='b7aefdb84de75f2c2fe57124859c2cad';
                   //  $this->request->post["RETAILER"]='b7aefdb84de75f2c2fe57124859c2cad';
if (isset($this->request->post["SID"])){


$mcrypt = new MCrypt();


foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
        $log->write($this->request->post);
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('farmerposupdate', $activity_data);
			
       
        //
		$this->load->model('farmer/farmerregistration');
              	$api_info = $this->model_farmer_farmerregistration->farmerposupdate($this->request->post);
                
	$json['success'] = $mcrypt->decrypt($this->language->get('text_success'));
                $j='1';
                     
		
            
            
        }else {
		 $json['error'] = $mcrypt->decrypt($this->language->get('error_login'));
                  $j='2';
		 }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($j);
	}
}