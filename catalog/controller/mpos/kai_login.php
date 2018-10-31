<?php
class Cust {
        public $customer_id ="";
	public $firstname ="";
	public $lastname="";
	public $email="";
	public $telephone="";		
	public $customer_group_id="";
	public $success="";
        public $error="";
        public $state_id="";
        public $district_id="";
        public $hq_id="";
}
class resop {
    public $success="";
        public $error="";
        public $data="0";
}
class ControllerApiLogin extends Controller {
    


public  function gcm(){
        $this->load->model('account/customer');
        $api_info = $this->customer->sentgcmdata("121","del",true);
    }

	public function index() {
            $log=new Log("login.log");
        $log->write("login function");
            $this->load->language('api/login');
		$keys = array(
			'username',
			'password',
			'id'
		);

		/*foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}*/
$mcrypt = new MCrypt(); 
$cust =new Cust();
        $log->write("condition check");  
        
     //$this->request->post["username"] ="6b26187de7c948a2234a6853bfea44e6";
    // $this->request->post["password"]= "02b8639afe2f27a715acfbce0a4cbe1e";
     //$this->request->post["id"]="e6d1fddb49fe8ec10b97972c354f18ac";

                if (isset($this->request->post["password"])&& isset($this->request->post["username"]) && $this->request->post["username"]!="0")
{





foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }

		
            $this->load->model('account/customer');
 
        //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('login', $activity_data);
			
       
        //
        
        
		
		$api_info = $this->customer->login($this->request->post['username'], $this->request->post['password']);
       $log->write($this->request->post);    

        $log->write("app-".$api_info); 
		if ($api_info) {     
	if(isset($this->request->post['id'])){            						                   
		 $this->customer->updategsmid($this->request->post['id'],$this->customer->getId());
		}  
                
                        $cust->customer_id=$mcrypt->encrypt($this->customer->getId());
                        $cust->firstname=$mcrypt->encrypt($this->customer->getFirstName());
                        $cust->lastname=$mcrypt->encrypt($this->customer->getLastName());
                        $cust->email=$mcrypt->encrypt($this->customer->getEmail());
                        $cust->telephone=$mcrypt->encrypt($this->customer->getTelephone());
                        $cust->customer_group_id=$mcrypt->encrypt($this->customer->getGroupId());
                        $cust->state_id=$mcrypt->encrypt($this->customer->getStateId());
                       // $cust->district_id=$mcrypt->encrypt($this->customer->getDistrictId());
                        //$cust->hq_id=$mcrypt->encrypt($this->customer->getHqId()); 
                        $cust->success= $mcrypt->encrypt($this->language->get('text_success'));
                        $cust->error= $mcrypt->encrypt("0");
           
		} else {
                        $cust->error= $mcrypt->encrypt($this->language->get('error_login'));
			$json['error'] = $mcrypt->encrypt($this->language->get('error_login'));
		}
                }
                 else {
                       $cust->error= $mcrypt->encrypt($this->language->get('error_login'));
	               $json['error'] =$mcrypt->encrypt( $this->language->get('error_login'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($cust));
	}
        
        
         
             
        public function reset(){ 
             //otp to be send
             $log=new Log("forgetpwd.log");
            $this->load->language('api/login');
             $keys = array(
			'emp_id',
                        'otp'
			
		);
             $mcrypt = new MCrypt(); 
            foreach ($keys as $key) {
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;            
            }
            $log->write($this->request->post);
             //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('forget', $activity_data);
			
       
        //
$log->write("end data");

            
            $json=new resop(); 
            $this->load->model('account/customer');
            $this->load->model('account/custom_field');
            
            $customer_info = $this->model_account_customer->getCustomerByUserId($this->request->post['emp_id']);
            $customer_info["otp"] = $this->request->post['otp'];
             if($customer_info) {
               $checkotp = $this->model_account_custom_field->checkotp($customer_info);
                 if($checkotp) {
                     
                    		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
			$this->model_account_customer->editPasswordforget($this->request->post['emp_id'], $password);
			
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;	
			// Add to activity log
			$customer_info = $this->model_account_customer->getCustomerByUserId($this->request->post['emp_id']);
			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                                        'message'     => $message
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}                        

			
		}
                 $customer_info["otp"] = $this->request->post['otp'];
                 $updateotpstatus = $this->model_account_custom_field->updateotpstatus($customer_info);
                 $this->sms->sendsms($this->request->post['emp_id'],'2',$customer_info,$password );
                 $json->data=$mcrypt->encrypt("1");
                 $json->success =$mcrypt->encrypt($this->language->get('text_success'));
                
                 }else {
                 
                 $json->data=$mcrypt->encrypt("0");
            $json->error =$mcrypt->encrypt($this->language->get('error_login')); 
             }
                
            
             } else {
                 
                 $json->data=$mcrypt->encrypt("0");
            $json->error =$mcrypt->encrypt($this->language->get('error_login')); 
             }
             
              $this->response->addHeader('Content-Type: application/json');
              $this->response->setOutput(json_encode($json));
        }
              
        public function resetmob(){ 
             //otp to be send
            $log=new Log("changepwd.log");
            $this->load->language('api/login');
            $keys = array(
			'emp_id',
			'pass'
		);
             $mcrypt = new MCrypt(); 
             foreach ($keys as $key) {
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;            
             }  
             $log->write($this->request->post);
             //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('login', $activity_data);
			
       
        //
     $json=new resop();
             
            $this->load->model('account/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$password = $this->request->post["pass"];
			$this->model_account_customer->editPassword($this->request->post['emp_id'], $password);			
			// Add to activity log
			$customer_info = $this->model_account_customer->getCustomer($this->request->post['emp_id']);
			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);
				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}  
                        
                        $this->sms->sendsms($customer_info['User_Id'],'2',$customer_info,$password);
                        $json->data=$mcrypt->encrypt("1");
            $json->success =$mcrypt->encrypt($this->language->get('text_success'));
                }  else {
                    $json->data=$mcrypt->encrypt("0");
            $json->error =$mcrypt->encrypt($this->language->get('error_login'));    
                }       
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
        
         public function change_pwdboth(){ 
             //otp to be send
            $log=new Log("changepwd.log");
            $this->load->language('api/login');
            $keys = array(
			'emp_id',
			'pass'
		);
             $mcrypt = new MCrypt(); 
             foreach ($keys as $key) {
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;            
             }  
             $log->write($this->request->post);
             //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('change_pwdboth', $activity_data);
			
       
        //
     $json=new resop();
             
            $this->load->model('account/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$password = $this->request->post["pass"];
			$this->model_account_customer->editPasswordforget($this->request->post['emp_id'], $password);			
			// Add to activity log
			$customer_info = $this->model_account_customer->getCustomerByUserId($this->request->post['emp_id']);
			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);
				$this->model_account_activity->addActivity('change_pwdboth', $activity_data);
			}  
                        
                        //$this->sms->sendsms($customer_info['User_Id'],'2',$customer_info,$password);
                        $json->data=$mcrypt->encrypt("1");
            $json->success =$mcrypt->encrypt($this->language->get('text_success'));
                }  else {
                    $json->data=$mcrypt->encrypt("0");
            $json->error =$mcrypt->encrypt($this->language->get('error_login'));    
                }       
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
        
        public function getotp(){ 
             //otp to be send
            $log=new Log("forgetpwd.log");
            $this->load->language('api/login');
            $keys = array(
			'emp_id'
			
		);
             $mcrypt = new MCrypt(); 
             foreach ($keys as $key) {
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;            
             }
             
             $log->write($this->request->post);
             //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('login', $activity_data);
			
       
        //
             
             $this->load->model('account/custom_field'); 
             
             $this->load->model('account/customer');
             $customer_info = $this->model_account_customer->getCustomerByUserId($this->request->post['emp_id']);
             if($customer_info) {
               $otp = $this->model_account_custom_field->generateotp($customer_info);
                 
            $json=new resop();
             
            if($otp)
            {
              $this->sms->sendsms($this->request->post['emp_id'],'1',$customer_info,$otp);
              $json->data=$mcrypt->encrypt("1");
              $json->success =$mcrypt->encrypt($this->language->get('text_success'));
            
            }  else {
                $json->error =$mcrypt->encrypt($this->language->get('error_login'));        
                $json->data=$mcrypt->encrypt("0");
            }
             } else {
                  $json->data=$mcrypt->encrypt("0");
                  $json->error =$mcrypt->encrypt($this->language->get('error_login'));           
             }
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
       
        }
        
         public function getotpdealer(){ 
             //otp to be send
            $log=new Log("forgetpwd.log");
            $this->load->language('api/login');
            $keys = array(
			'emp_id'
			
		);
             $mcrypt = new MCrypt(); 
             foreach ($keys as $key) {
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;            
             }
             
             $log->write($this->request->post);
             //log to table
        
   
				$this->load->model('account/activity');

				$activity_data = $this->request->post;

				$this->model_account_activity->addActivity('login', $activity_data);
			
       
        //
             
             $this->load->model('account/custom_field'); 
             
             $this->load->model('account/customer');
             $mobile_info = $this->model_account_customer->checkmobile($this->request->post['emp_id']);
             if(!empty($mobile_info)) {
             $customer_info = $this->model_account_customer->getCustomerByUserId($this->request->post['emp_id']);
             if($customer_info) {
               $otp = $this->model_account_custom_field->generateotp($customer_info);
                 
            $json=new resop();
             
            if($otp)
            {
                $this->sms->sendsms($this->request->post['emp_id'],'1',$customer_info,$otp);
              $json->data=$mcrypt->encrypt("1");
              $json->success =$mcrypt->encrypt($this->language->get('text_success'));
            
            }  else {
                $json->error =$mcrypt->encrypt($this->language->get('error_login'));        
                $json->data=$mcrypt->encrypt("0");
            }
             } else {
                  $json->data=$mcrypt->encrypt("0");
                  $json->error =$mcrypt->encrypt($this->language->get('error_login'));           
             }
             
             } else {
                  $json->data=$mcrypt->encrypt("0");
                  $json->error =$mcrypt->encrypt($this->language->get('error_login'));           
             }
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
       
        }    
}