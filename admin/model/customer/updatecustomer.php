<?php
class Modelcustomerupdatecustomer extends Model {
 
    public function  getRole(){
        $query = $this->db->query("SELECT name,customer_group_id FROM `ak_customer_group` order by name");
        return $query->rows;   
    }
    public function  getState(){
        $sql="SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE='2'";
        $query = $this->db->query($sql);
        return $query->rows;   
    }
    public function getDistList($stid){
        $query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE='4' and STATE_ID='".$stid."'" );
        return $query->rows;  
    }
    public function getAcDist($custId){
        $query = $this->db->query("SELECT GEO_ID FROM ak_customer_emp_map WHERE GEO_LEVEL_ID=4 AND CUSTOMER_ID='".$custId."'" );
        return $query->rows; 
    }
    function getAreaMngr(){
         
        $query = $this->db->query(" SELECT customer_id,firstname FROM `ak_customer` where customer_group_id='3'" );
        return $query->rows;  
    }
    function ParAm($empId){
        $query = $this->db->query("SELECT PARENT_USER_ID FROM ak_customer_map WHERE CUSTOMER_ID='".$empId."'");
        return $query->row['PARENT_USER_ID'];
    }


    
    public function  getVehicle(){
        $sql="SELECT vehicle_allow FROM ak_customer where customer_id='".$cust_id."'";
        $query = $this->db->query($sql);       
        return $query->rows;
    }
   
    
    public function  getam(){
           $query = $this->db->query("SELECT customer_id,firstname FROM `ak_customer` ");
           return $query->rows;   
    }
     
    function getdistrict(){
        
        $stid=$this->request->post['state_id'];
        $query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE='4' and STATE_ID='$stid'" );
        return $query->rows;  
    }
    public function  getEmpDtls(){  
        $fdata=$this->request->get['search'];
        if(empty($fdata)){$fname = 'NULL';} else {$fname = "'".$fdata."%'";}
        $sql="SELECT 
            AC.customer_id AS 'EMP_ID',
            GRP.`name` AS 'EMP_GROUP',
            AC.User_id AS 'EMP_UID',
            CONCAT(AC.firstname,' ',AC.lastname) AS 'EMP_NAME',
            IFNULL(ST.`NAME`,'NA') AS 'EMP_STATE',
            IFNULL(group_concat(DT.NAME),'NA') AS 'EMP_DIST'
            FROM `ak_customer` AC
            LEFT JOIN ak_mst_geo_upper AS ST ON ST.SID=AC.state_id
            LEFT JOIN ak_customer_emp_map AS EMP ON AC.customer_id=EMP.CUSTOMER_ID and EMP.GEO_LEVEL_ID='4'
            LEFT JOIN ak_mst_geo_upper AS DT ON EMP.GEO_ID=DT.SID
            LEFT JOIN ak_customer_group GRP ON(AC.customer_group_id=GRP.customer_group_id)
            WHERE AC.firstname LIKE  IFNULL($fname,AC.firstname) 
            GROUP BY AC.User_id 
            ORDER BY AC.customer_group_id,AC.firstname";    
            $query = $this->db->query($sql);
            return $query->rows;
    }
    public function  getEmptotal($data = array()){
        $sql="SELECT count(customer_id) as 'total' FROM `ak_customer`";
        $query = $this->db->query($sql);
        return $query->row['total'];
    }
    public function  getOneEmpdetl(){    
        $UniId= $this->request->get['UniId'];
        $Usql="SELECT * FROM `ak_customer` where customer_id='$UniId' ";          
        $query = $this->db->query($Usql);
        return $query->row;
    }
    public function  getOneEmpdist($cust_id){      
        $s="SELECT ac.GEO_ID  FROM ak_customer_emp_map as ac
        join ak_mst_geo_lower as aml on ac.GEO_ID=aml.SID
        where ac.CUSTOMER_ID='".$cust_id."' and ac.GEO_LEVEL_ID='4'";          
        $query = $this->db->query($s);
        return $query->rows;
    }
   
    public function  getamlastdata($mid){       
        $s="SELECT PARENT_USER_ID from ak_customer_map where CUSTOMER_ID='".$mid."'";          
        $query = $this->db->query($s);
        return $query->row;
    }
    public function getamanager(){
         
        $query = $this->db->query(" SELECT customer_id,firstname FROM `ak_customer` where customer_group_id='3'" );
        return $query->rows;  
    }
    public function UPDATEEmpData($data){
        
       
        $log=new Log("UpdateEmp :".date('Y_m_d').".log");
        $log->write($data);
        $salt='A@$it1N07';
        $cr_date=date('Y-m-d');
        $CustId=$data['CustId'];
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
       
        $ak_sql="UPDATE `ak_customer` SET 
            `customer_group_id`='".$erole."',
            `User_id`='".$UserId."',
            `firstname`='".$fname."',
            `lastname`='".$lname."',
            `email`='".$email."',
            `telephone`='".$UserId."',
            `address`='".$addrs."'";
            if(!empty($passwd)){
                $ak_sql.=" ,`password`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                `salt`='".$salt."'";
            }
            $ak_sql.=" ,`ip`='127.0.0.1',
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
            `hotel_allow`='".$HotCon."' WHERE customer_id='".$CustId."'";
        $log->write('AK_CUSTOMER_UPD_SQL :'.$ak_sql);
        if($this->db->query($ak_sql)){
            $log->write('UPDATE SUCCESS');
            $UpdOk =1;
            $UniId=$CustId;
        }else{
            $UpdOk =0;
        }
        $log->write('EMP_UNI_ID :'.$UniId);
        
        
        
        //=========================== Check Emp As Distributor OR Not ========================//
        if($erole!=10){                                                         // Not A Distributor
            $oc_sql="UPDATE `oc_user` SET 
                `user_group_id`='".$erole."',
                `username`='".$UserId."'";
                    
                if(!empty($passwd)){
                    $oc_sql.=" ,`password`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                    `code`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                    `salt`='".$salt."'";
                 }  

                $oc_sql.=" ,`firstname`='".$fname."',
                `lastname`='".$lname."',
                `email`='".$email."',
                `store_id`='0',
                `image`='0',
                
                `ip`='127.0.0.1',
                `card`='100',
                `cash`='0',
                `status`='1',
                `date_added`='".$cr_date."',
                `sap_id`='".$sapid."' WHERE `customer_id`='".$UniId."'";
            $log->write('OC_USER_SQL :'.$oc_sql);
            $this->db->query($oc_sql);
        }else{                                                                  // for distributor
            $oc_sql="UPDATE `oc_user` SET 
                `user_group_id`='".$erole."',
                `username`='".$UserId."'";
                    
                if(!empty($passwd)){
                    $oc_sql.=" ,`password`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                    `code`='" . $this->db->escape(sha1($salt . sha1($salt . sha1($passwd)))) . "',
                    `salt`='".$salt."'";
                 }  

                $oc_sql.=" ,`firstname`='".$fname."',
                `lastname`='".$lname."',
                `email`='".$email."',
              
                `image`='0',
                `ip`='127.0.0.1',
                `card`='100',
                `cash`='0',
                `status`='1',
                `date_added`='".$cr_date."',
                `sap_id`='".$sapid."' WHERE `customer_id`='".$UniId."'";
            $log->write('OC_USER_WS_SQL :'.$oc_sql);
            $this->db->query($oc_sql);
            
            
            $name=$fname.' '.$lname;
            $sto_sql="UPDATE `oc_store` SET 
                name='".$name."',
                company='".$fname."' WHERE store_id=(SELECT store_id from `oc_user` WHERE customer_id='".$UniId."' LIMIT 1)";
            $log->write('OC_STORE_SQL :'.$sto_sql);
            $this->db->query($sto_sql);
            
            
        }
            
            $sqlmap_V="UPDATE `ak_customer_map` SET 
			PARENT_USER_ID = '".$MoAmId."', 
			CUSTOMER_GROUP_ID = '".$erole."' WHERE CUSTOMER_ID= '".$UniId."'";
            $log->write("AK_CUSTOMER__MAP_SQL: ".$sqlmap_V);
            $this->db->query($sqlmap_V);
            
                        
            $sqlDelDst=$this->db->query("DELETE FROM `ak_customer_emp_map` WHERE CUSTOMER_ID= '".$UniId."' AND  GEO_LEVEL_ID = '4'");
            
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
            //On Successful Employee Registration
            
            
            
            
            return $UpdOk;
      
    }
   
   
}
