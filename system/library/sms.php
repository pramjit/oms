<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sms
 *
 * @author agent
 */
class sms {
public function __construct($registry) {
                $this->config = $registry->get('config');
		$this->db = $registry->get('db');
                //$this->request = $registry->get('request');
		//$this->session = $registry->get('session');
}

public function sendsms($mobile,$message,$customer_info)
{
    $response ="";
    if(isset($mobile)&&isset($message))
    {
		$log=new LOg("smshit.log");
		$log->write($customer_info);
                $api_info=array();
                $api_info["User"]=SMS_USERNAME;
                $api_info["passwd"]=SMS_PASSWORD;
                $api_info["sid"]=SMS_DISPLAYNAME;
                $api_info["mobilenumber"]=$mobile;
		if($message=='2')
		{
			$api_info["message"]=str_replace('*',ceil($customer_info['cash']),($this->getsms($message)["MESSAGE"]));
			
		}
		else if($message=='1')
		{
			$api_info["message"]=str_replace('*',($customer_info['card']),($this->getsms($message)["MESSAGE"]));
			
		}
		else if($message=='5')
		{
			$api_info["message"]=str_replace('*',($customer_info['ttp']),($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['rqid']),$api_info["message"]);			
		}
		else if($message=='4')
		{
			$api_info["message"]=str_replace('*',($customer_info['pass']),($this->getsms($message)["MESSAGE"]));
			
		}
		else if($message=='6')
		{			
			$api_info["message"]=str_replace('*',"farmer",($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['oid']),$api_info["message"]);			
		}	
		
			
		else{
                $api_info["message"]=$this->getsms($message)["MESSAGE"];
		}
                $curl = curl_init();
                // Set SSL if required
                if (substr(SMS_HOSTNAME, 0, 5) == 'https') {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, SMS_HOSTNAME );
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));
                $json = curl_exec($curl);                
                $response = json_decode($json, true);
                if ($mobile) {
				

				$activity_data = array(
					'customer_id' => $mobile,
					'name'        => $response,
                                    'resp'=>$response
				);
				$this->addActivity('sms', $activity_data);
			} 
                        //$this->smsinsert('sms', $activity_data);
                curl_close($curl);
    }
                return $response;
}
            
           public function addActivity($key, $data) {
		if (isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int)$customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}  
            public function getsms($id)
            {
                
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sms WHERE LOWER(SID) = '" . $this->db->escape(utf8_strtolower($id)) . "'");

		return $query->row;
            }

        public function smsinsert($data)
        {
            $msg_time=date('H:i:s');
            $sql="INSERT INTO " . DB_PREFIX . "sms_record SET MOBILE_NO='".$data["MOBILE_NO"]."',MESSAGE='".$data["MESSAGE"]."',MESSAGE_DATE='".$data['MESSAGE_DATE']."',MESSAGE_TIME='".$msg_time."',MESSAGE_PROCESSED='0',TRANSACTIONID='".$data["TRANSACTIONID"]."',STATE='0'";
            $this->db->query($sql);
            $ret_id = $this->db->countAffected();
            return $ret_id;
        }
        
        public function updateSms($msgid,$TRANSACTIONID)
        {
            
        $message_sent=$this->getsms($msgid)["MESSAGE"];
        
           
            $sql="update  " . DB_PREFIX . "sms_record SET message_sent='".$message_sent."',MESSAGE_PROCESSED='1' where TRANSACTIONID='".$TRANSACTIONID."'";
            $this->db->query($sql);
            $ret_id = $this->db->countAffected();
            return $ret_id;
        
        }
}
