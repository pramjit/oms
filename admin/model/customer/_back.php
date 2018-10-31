<?php
class Modelcustomercreatecustomer extends Model {
 
    public function customerinsrt($data){
       
       $log=new Log("AddUser".date('Y_m_d').".log");
       $log->write($data);
       $salt='A@$it1N07';
        
       if(empty($data['loc_con'])){$loc_con=0;}else{$loc_con=$data['loc_con'];}
       if(empty($data['out_con'])){$out_con=0;}else{$out_con=$data['out_con'];}
       if(empty($data['hot_con'])){$hot_con=0;}else{$hot_con=$data['hot_con'];}
       if(empty($data['mot_con'])){$mot_con=0;}else{$mot_con=$data['mot_con'];}
       
      $sql="INSERT INTO ak_customer SET 
		customer_group_id= '" . $data['select_role'] . "',
		User_Id = '" . $data['email'] . "', 
		firstname = '" . $data['first_name']. "',
		lastname = '" . $data['last_name'] . "', 
		email = '" . $data['email'] . "',
		address = '" . $data['address'] . "',
		salt='".$salt."', 
		password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "',
		nation_id='1',
		status='1',
		state_id = '" . $data['stat_id'] . "',
		area_id = '" . $data['area_id'] . "',
		approved='1',
		date_added=Now(),
		sap_id='".$data['sapid']."',
		local_allow='".$loc_con."',
		outstation_allow='".$out_con."',
		vehicle_allow='".$mot_con."',
		hotel_allow='".$hot_con."'";
		$log->write("Ak_Customer Sql: ".$sql);
      		$this->db->query($sql);
      		$ret_id = $this->db->getLastId();
      
      		if($data['select_role']!=10){ // User not Belongs to Wholeseller Group.
      
	       		$sqloc="INSERT INTO oc_user SET 
			user_group_id = '" . $data['select_role'] . "', 
			username = '" . $data['email'] . "', 
			salt = '" . $salt. "', 
			password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' ,
			firstname = '" . $data['first_name'] . "',
			lastname = '" . $data['last_name'] . "',
			email = 'test@oms.com',
			status = '1',
			store_id='0',
			date_added=Now(),
			customer_id='" . $ret_id ."',
			sap_id='".$data['sapid']."'";
			$log->write("Oc_User(other) Sql: ".$sqloc);
	       		$this->db->query($sqloc); 
      
      		}else{   // User Belongs to Wholeseller Group.   
       
		      
		       	$sqlstore="INSERT INTO oc_store SET 
			name = '" . $data['first_name']. " ".$data['last_name']."',
			url = '0',
			`ssl` = '0',
			creditlimit='0.000',
			currentcredit='0.000',
			company='0'";
			$log->write("Oc_Store Sql: ".$sqlstore);
       			$this->db->query($sqlstore);
       			$store_id = $this->db->getLastId();
       
      
       			$sqloc="INSERT INTO oc_user SET 
			user_group_id = '" . $data['select_role'] . "', 
			username = '" . $data['email'] . "', 
			salt = '" . $salt. "', 
			password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' ,
			firstname = '" . $data['first_name'] . "',
			lastname = '" . $data['last_name'] . "',
			email = 'test@oms.com',status = '1',
			store_id='".$store_id."',
			date_added=Now(),
			customer_id='" . $ret_id ."',
			sap_id='".$data['sapid']."'";
			$log->write("Oc_User(ws) Sql: ".$sqloc);
       			$this->db->query($sqloc); 
      
			$sqlstoretrans="INSERT INTO oc_store_trans SET 
			store_id = '" .$store_id."',
			amount = '0',transaction_type = '0',
			cr_db='0.000',
			user_id='".$ret_id."'";
			$log->write("Oc_Store_Trans Sql: ".$sqlstoretrans);
       			$this->db->query($sqlstoretrans);
       
       		} 
       
      
      			$sqlemp1="INSERT INTO ak_customer_emp_map SET 
			CUSTOMER_ID= '" . $ret_id . "',
			LEVEL_TYPE = '" . $data['select_role'] . "', 
			GEO_ID = '0',
			GEO_LEVEL_ID = '1', 
			ACT_ID = '1',
			START_DATE = '0000-00-00',
			END_DATE = '0000-00-00'";
			$log->write("ak_customer_emp_map Sql1 : ".$sqlemp1);
      			$this->db->query($sqlemp1);
      
      			$sqlemp2="INSERT INTO ak_customer_emp_map SET 
			CUSTOMER_ID= '" .$ret_id. "',
			LEVEL_TYPE = '" . $data['select_role'] . "', 
			GEO_ID = '0',GEO_LEVEL_ID = '2', 
			ACT_ID = '1',
			START_DATE = '0000-00-00',
			END_DATE = '0000-00-00'";
			$log->write("ak_customer_emp_map Sql2 : ".$sqlemp2);
      			$this->db->query($sqlemp2);
      
      			$sqlemp3="INSERT INTO ak_customer_emp_map SET 
			CUSTOMER_ID= '" .$ret_id. "',
			LEVEL_TYPE = '" . $data['select_role'] . "', 
			GEO_ID = '" . $data['stat_id']. "',
			GEO_LEVEL_ID = '3', ACT_ID = '1',
			START_DATE = '0000-00-00',
			END_DATE = '0000-00-00'";
			$log->write("ak_customer_emp_map Sql3 : ".$sqlemp3);
      			$this->db->query($sqlemp3);


       			$cnt=count($data['dist_id']);
      			for($i=0; $i<$cnt; $i++){
	      			$sqlemp4="INSERT INTO ak_customer_emp_map SET 
				CUSTOMER_ID= '" .$ret_id. "',
				LEVEL_TYPE = '" . $data['select_role'] . "', 
				GEO_ID = '" . $data['dist_id'][$i]. "',
				GEO_LEVEL_ID = '4', ACT_ID = '1',
				START_DATE = '0000-00-00',
				END_DATE = '0000-00-00'";
				$log->write("ak_customer_emp_map Sql4_".$i." : ".$sqlemp4);
	      			$this->db->query($sqlemp4);
      			}

      			$sqlemp5="INSERT INTO ak_customer_map SET 
			CUSTOMER_ID= '" .$ret_id. "',
			PARENT_USER_ID = '" . $data['am_id'] . "', 
			CUSTOMER_GROUP_ID = '3'";
			$log->write("ak_customer_map Sql5 : ".$sqlemp5);
      			$this->db->query($sqlemp5);
     
		return $ret_id;
    }
 
 
   	public function  getState(){
           	$query = $this->db->query("SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE='2'");
           	return $query->rows;   
   	}
 	public function  getrole(){
           	$query = $this->db->query("SELECT name,customer_group_id FROM `ak_customer_group` ");
           	return $query->rows;   
   	}
     
    	function getdistrict(){
         	// $id=$this->customer->getid();
        	//  $role_id=$this->customer->getGroupId();
        	$stid=$this->request->post['state_id'];
          	$query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE='4' and STATE_ID='$stid'" );
       		return $query->rows;  
    	}
     	function getamanager(){
        	//$srole=$this->request->post['role'];
          	$query = $this->db->query(" SELECT customer_id,firstname FROM `ak_customer` where customer_group_id='3'" );
                return $query->rows;  
    	}
}
