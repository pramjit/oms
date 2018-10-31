<?php

class POS{
    public $api_id;
    public $api_name;
    public $api_store_id;
    public $api_group_id;
    public $api_cash;
    public $api_card;		
    public $success;	
    public $error;
    }
class ControllermposLogin extends Controller {

    public function adminmodel($model) {
      
        $admin_dir = DIR_SYSTEM;
        $admin_dir = str_replace('system/','admin/',$admin_dir);
        $file = $admin_dir . 'model/' . $model . '.php';      
          //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
        $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
        if (file_exists($file)) {
            include_once($file);
            $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
        } else {
            trigger_error('Error: Could not load model ' . $model . '!');
            exit();               
        }
    }
    function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
        } 
        while ($rnd > $range);
        return $min + $rnd;
    }

    function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .=$codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }
        return $token;
    }

//*****************************************Forgotten Password**************************************//
    public function forgottenpwd() {
        $mcrypt=new MCrypt();
	$this->adminmodel('user/user');
	$log=new Log("forgot_password.log");
	$json=array();
	$user=$this->model_user_user->getUserByUsername($mcrypt->decrypt($this->request->post['uid']));
        if(empty($user))
        {
            $json['error']=$mcrypt->encrypt("Sorry User not found.");
            $json['type']=$mcrypt->encrypt("0");
        }
        else{
                $result=$this->model_user_user->resetpwd($user['username']);
                if($result==1)
                {
                    $json['success']=$mcrypt->encrypt("Password Updated Successfully.");
                    $json['type']=$mcrypt->encrypt("1");
                }
                else{
                        $json['error']=$mcrypt->encrypt("Sorry Try Again.");
                        $json['type']=$mcrypt->encrypt("0");
                }
        }
        $this->response->setOutput(json_encode($json));
                
    }
//************************************** Forgotten Password End **************************************//
    public function forgotten() {

		$mcrypt=new MCrypt();
		$this->adminmodel('user/user');
		$log=new Log("forgot.log");
		$json=array();
		$json['success']=$mcrypt->encrypt("Error");
		$json['type']       =$mcrypt->encrypt("0");  		

			$user=	$this->model_user_user->getUserByUsername($mcrypt->decrypt($this->request->post['uid']));
			$log->write($user);
		if($user){
			$code = sha1(uniqid(mt_rand(), true));
			$this->model_user_user->editCode($user['email'], $code);
		$user_info = $this->model_user_user->getUserByCode($code);
					$log->write($user_info);
		if ($user_info) {	
			$pass=$this->getToken(8);
			$this->model_user_user->editPassword($user_info['user_id'], $pass);
			$user_info['pass']=$pass;
		//send sms
			$log->write("send sms");
			$this->load->library('sms');		
        	        $sms=new sms($this->registry);
			$log->write("send sms sending");
			//$user_info['telephone']
               		 $sms->sendsms("9958934064","4",$user_info);  
			$log->write("send sms done");
			$json['success']=$mcrypt->encrypt("Thanks");
			$json['type']        =$mcrypt->encrypt("1");      			
		}
	
		}
		$this->response->setOutput(json_encode($json));			

}
    public function getversion() {
            $log=new Log("Version".date('Y_m_d').".log");
            $this->load->language('api/login');
            unset($this->session->data['api_id']);
            $mcrypt=new MCrypt();
            $keys = array('username','vid');
            
            $log->write($this->request->post);
            foreach ($keys as $key) {
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            }
            $log->write($this->request->post);
            
            $json = array();
            $this->load->model('account/api');		
                    $this->load->model('account/activity');
                    $activity_data = array(
                        'customer_id' => $this->request->post['username'],
			'name'        => $this->request->post
                    );
            $this->model_account_activity->addActivity('version', $activity_data);
            //=============================Get App Version=================================//
            $url = 'https://play.google.com/store/apps/details?id=com.aksha.KhandelwalAgroInd';
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $contents = curl_exec($ch);
            if (curl_errno($ch)) {
              echo curl_error($ch);
              echo "\n<br />";
              $contents = '';
            } else {
              curl_close($ch);
            }
            if (!is_string($contents) || !strlen($contents)) {
            echo "Failed to get contents.";
            $contents = '';
            }
            $html=$contents;
            $Step_I = explode( 'Current Version' , $html );
            $Step_II = explode('<span class="htlgb"><div><span class="htlgb">' , $Step_I[1] );
            $Step_III = explode('<div class="hAyfc"><div class="BgcNfc">Requires Android</div>' , $Step_II[1] );
            $version=trim($Step_III[0]); 
				
            
            $json['ver']=$mcrypt->encrypt("0");
            $log->write($mcrypt->decrypt($json['ver']));
            $this->response->setOutput(json_encode($json));

    }


    public function change() {

		$mcrypt=new MCrypt();
		$this->adminmodel('user/user');
		$log=new Log("change-".date('Y-m-d').".log");
		$log->write($this->request->post);
		$log->write($mcrypt->decrypt($this->request->post['username']));
		$json=array();
		$json['success']=$mcrypt->encrypt("Error");
		$json['type']       =$mcrypt->encrypt("0");  		
                $user=	$this->model_user_user->getUser($mcrypt->decrypt($this->request->post['username']));
		$log->write($user);
		if($user){
                    $code = sha1(uniqid(mt_rand(), true));
                    $this->model_user_user->editCode($user['email'], $code);
                    $user_info = $this->model_user_user->getUserByCode($code);
			$log->write($user_info);
                    if ($user_info) {	
			$pass=$mcrypt->decrypt($this->request->post['pid']);//$this->getToken(8);
			$this->model_user_user->editPassword($user_info['user_id'], $pass);
			$json['success']=$mcrypt->encrypt("Thanks");
			$json['type']        =$mcrypt->encrypt("1");      			
                    }
	
		}else{
                    $json['success']=$mcrypt->encrypt("No user found");
                    $json['type']        =$mcrypt->encrypt("0");
		}
		$this->response->setOutput(json_encode($json));			
}

    public function index() {
    $log=new Log("ApiLogin".date('Y_m_d').".log");
    $this->load->language('api/login');
    $this->load->model('account/api');
    unset($this->session->data['api_id']);
    $log->write($this->request->post);//Data before Decryption
    $mcrypt=new MCrypt();
    $keys = array('username','password','utype');
    $data=new POS();
    foreach ($keys as $key) {
            $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
    }

    $log->write($this->request->post);//Data After Decryption
    
    $json = array();
    $api_info = $this->model_account_api->loginm($this->request->post['username'], $this->request->post['password']);
    if($api_info){
                    $this->load->model('account/activity');
                    $activity_data = array(
                    'customer_id' => $api_info['user_id'],
                    'name'        => $api_info['firstname'] . ' ' . $api_info['lastname']
                    );
                    $this->model_account_activity->addActivity('login', $activity_data);
    }
    $log->write($api_info);
        if ($api_info) {
                    
                    $utype=$this->request->post['utype'];
                    if(($utype=="1"&&$api_info['user_group_id']=="4")||($utype=="2"&&$api_info['user_group_id']=="3") ||($utype=="3"&&$api_info['user_group_id']=="10")){
			$data->api_id = $mcrypt->encrypt($api_info['user_id']);
                        $data->api_name =$mcrypt->encrypt($api_info['firstname']." ".$api_info['lastname']); 
                        $data->api_store_id =$mcrypt->encrypt($api_info['store_id']);
                        $data->api_group_id =$mcrypt->encrypt($api_info['user_group_id']);
                        $data->api_cash=$mcrypt->encrypt($api_info['cash']);
                        $data->api_card=$mcrypt->encrypt($api_info['card']);
			$data->telephone =$mcrypt->encrypt($api_info['username']);
                        $data->email =$mcrypt->encrypt($api_info['email']);
                        $data->pwd =$api_info['password'];
                        $data->success = $this->language->get('text_success');
                        $data->error="0";
                        
                        //return ak_customer->customer_id against oc_user->user_id
                        $ak_user_data = $this->model_account_api->akuserid($api_info['user_id']);
                        $data->user_id = $mcrypt->encrypt($ak_user_data['urole']);
                        $data->state = $mcrypt->encrypt($ak_user_data['state']);
                        $data->district = $mcrypt->encrypt($ak_user_data['district']);
                    }
                    else{
			$data->error=$this->language->get('error_login');
                        $json['error'] = $this->language->get('error_login');
                    }
    } else {
		$data->error=$this->language->get('error_login');
		$json['error'] = $this->language->get('error_login');
            }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
    }
}
