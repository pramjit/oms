<?php
class Modelcustomercreatecustomer extends Model {
 
    public function RegisterEmp($data){
       
       $log=new Log("RegisterEmp :".date('Y_m_d').".log");
       $log->write($data);
       $salt='A@$it1N07';
       $cr_date=date('Y-m-d');
       
       $UserId=$data['UserId'];
       $fname=addslashes($data['fname']);
       $lname=addslashes($data['lname']);
       $email='emp@khandelwa.com';
       $passwd=$data['passwd'];
       
       $sapid=$data['sapid'];
       $erole=$data['erole'];
       $estate=$data['estate'];
       $edist=$data['edist']; // District Array
       $addrs= addslashes($data['addrs']);
       
       $MoAmId=$data['MoAmId'];
       if(empty($MoAmId)){$MoAmId=0;}
       $HotCon=$data['HotCon'];
       if(empty($HotCon)){$HotCon=0;}
       $LocCon=$data['LocCon'];
       if(empty($LocCon)){$LocCon=0;}
       $OutCon=$data['OutCon'];
       if(empty($OutCon)){$OutCon=0;}
       $MotCon=$data['MotCon'];
       if(empty($MotCon)){$MotCon=0;}
       
        $ak_sql="INSERT INTO `ak_customer` SET 
            `customer_group_id`='".$erole."',
            `User_id`='".$UserId."',
            `firstname`='".$fname."',
            `lastname`='".$lname."',
            `email`='".$email."',
            `telephone`='".$UserId."',
            `address`='".$addrs."',
            `password`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
            `salt`='".$salt."',
            `ip`='127.0.0.1',
            `status`='1',
            `approved`='1',
            `safe`='0',
            `token`='1',
            `date_added`='".$cr_date."',
            `nation_id`='1',
            `area_id`='0',
            `state_id`='".$estate."',
            `appid`='1001',
            `lat`='0',
            `longg`='0',
            `reg_date`='".$cr_date."',
            `sap_id`='".$sapid."',
            `local_allow`='".$LocCon."',
            `outstation_allow`='".$OutCon."',
            `vehicle_allow`='".$MotCon."',
            `hotel_allow`='".$HotCon."'";
        $log->write('AK_CUSTOMER_SQL :'.$ak_sql);
        $this->db->query($ak_sql);
        $UniId = $this->db->getLastId();
        $log->write('EMP_UNI_ID :'.$UniId);
        
        
        
        //=========================== Check Emp As Distributor OR Not ========================//
        if($erole!=10){                                                         // Not A Distributor
            $oc_sql="INSERT INTO `oc_user` SET 
                `user_group_id`='".$erole."',
                `username`='".$UserId."',
                `password`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                `salt`='".$salt."',
                `firstname`='".$fname."',
                `lastname`='".$lname."',
                `email`='".$email."',
                `store_id`='0',
                `image`='0',
                `code`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                `ip`='127.0.0.1',
                `card`='100',
                `cash`='0',
                `status`='1',
                `date_added`='".$cr_date."',
                `customer_id`='".$UniId."',
                `sap_id`='".$sapid."'";
            $log->write('OC_USER_SQL :'.$oc_sql);
            $this->db->query($oc_sql);
        }else{                                                                  // for distributor
            
            $name=$fname.' '.$lname;
            $sto_sql="INSERT INTO `oc_store` SET 
                name='".$name."',
                url='http://khandelwal.com',
                ssl='0',
                creditlimit='0.00',
                currentcredit='0.00',
                company='".$fname."'";
            $log->write('OC_STORE_SQL :'.$sto_sql);
            $this->db->query($sto_sql);
            $store_id = $this->db->getLastId();
            
            
            $oc_sql="INSERT INTO `oc_user` SET 
                `user_group_id`='".$erole."',
                `username`='".$UserId."',
                `password`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                `salt`='".$salt."',
                `firstname`='".$fname."',
                `lastname`='".$lname."',
                `email`='".$email."',
                `store_id`='".$store_id."',
                `image`='0',
                `code`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                `ip`='127.0.0.1',
                `card`='100',
                `cash`='0',
                `status`='1',
                `date_added`='".$cr_date."',
                `customer_id`='".$UniId."',
                `sap_id`='".$sapid."'";
            $log->write('OC_USER_WS_SQL :'.$oc_sql);
            $this->db->query($oc_sql);
        }
       
       
            $sqlmap_I="INSERT INTO `ak_customer_emp_map` SET 
                    CUSTOMER_ID= '".$UniId."',
                    LEVEL_TYPE = '".$erole."', 
                    GEO_ID = '0',
                    GEO_LEVEL_ID = '1', 
                    ACT_ID = '1',
                    START_DATE = '0000-00-00',
                    END_DATE = '0000-00-00'";
            $log->write("AK_CUSTOMER_EMP_MAP_I : ".$sqlmap_I);
            $this->db->query($sqlmap_I);
            
            $sqlmap_II="INSERT INTO `ak_customer_emp_map` SET 
                    CUSTOMER_ID= '".$UniId."',
                    LEVEL_TYPE = '".$erole."', 
                    GEO_ID = '0',
                    GEO_LEVEL_ID = '2', 
                    ACT_ID = '1',
                    START_DATE = '0000-00-00',
                    END_DATE = '0000-00-00'";
            $log->write("AK_CUSTOMER_EMP_MAP_II : ".$sqlmap_II);
            $this->db->query($sqlmap_II);
      
            $sqlmap_III="INSERT INTO `ak_customer_emp_map` SET 
                    CUSTOMER_ID= '".$UniId."',
                    LEVEL_TYPE = '".$erole."', 
                    GEO_ID = '".$estate."',
                    GEO_LEVEL_ID = '3', 
                    ACT_ID = '1',
                    START_DATE = '0000-00-00',
                    END_DATE = '0000-00-00'";
            $log->write("AK_CUSTOMER_EMP_MAP_III : ".$sqlmap_III);
            $this->db->query($sqlmap_III);

            foreach($edist as $did){
                $sqlmap_IV="INSERT INTO `ak_customer_emp_map` SET 
                    CUSTOMER_ID= '".$UniId."',
                    LEVEL_TYPE = '".$erole."', 
                    GEO_ID = '".$did."',
                    GEO_LEVEL_ID = '4', 
                    ACT_ID = '1',
                    START_DATE = '0000-00-00',
                    END_DATE = '0000-00-00'";
                $log->write("AK_CUSTOMER_EMP_MAP_IV_".$did." : ".$sqlmap_IV);
                $this->db->query($sqlmap_IV);
            }
            
            $sqlmap_V="INSERT INTO `ak_customer_map` SET 
			CUSTOMER_ID= '".$UniId."',
			PARENT_USER_ID = '".$MoAmId."', 
			CUSTOMER_GROUP_ID = '".$erole."'";
            $log->write("AK_CUSTOMER__MAP_SQL: ".$sqlmap_V);
            $this->db->query($sqlmap_V);
            
            //On Successful Employee Registration
            return $UniId;
    }
 
        public function vldUserId(){
            $UsrId=$this->request->post['UsrId'];
            $query=$this->db->query("SELECT customer_id FROM ak_customer WHERE User_id='".$UsrId."' LIMIT 1");
            return $query->row['customer_id']; 
        }
   	public function  getState(){
           	$query = $this->db->query("SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE='2' order by NAME");
           	return $query->rows;   
   	}
 	public function  getrole(){
           	$query = $this->db->query("SELECT name,customer_group_id FROM `ak_customer_group` order by name");
           	return $query->rows;   
   	}
     
    	function getdistrict(){
         	// $id=$this->customer->getid();
        	//  $role_id=$this->customer->getGroupId();
        	$stid=$this->request->post['state_id'];
          	$query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE='4' and STATE_ID='$stid'order by NAME");
       		return $query->rows;  
    	}
     	function getAreaManager(){
        	//$srole=$this->request->post['role'];
          	$query = $this->db->query(" SELECT customer_id,firstname FROM `ak_customer` where customer_group_id='3' order by firstname" );
                return $query->rows;  
    	}
}
