<?php
class ModelFarmerfarmerregistration extends Model {
  
    public function addRegistration($data){
        
	$log=new Log("farmerregistration.log");
      
        $sql="INSERT INTO ak_farmer SET SID='".$data["SID"]."',FARMER_NAME='".$data["FARMER_NAME"]."',FAR_MOBILE='".$data["FAR_MOBILE"]."',CR_DATE='".$data["CR_DATE"]."',CR_BY='".$data["CR_BY"]."',VILL_ID='".$data["VILL_ID"]."',DIST_ID='".$data["DIST_ID"]."',CAN_MLK_ID='".$data["CAN_MLK_ID"]."',FGM_ID='".$data["FGM_ID"]."',KEY_FARMER='".$data["KEY_FARMER"]."',MILKING_COWS_CNT='".$data["MILKING_COWS_CNT"]."',TOTAL_COWS='".$data["TOTAL_COWS"]."',CURR_SUPPILER='".$data["CURR_SUPPILER"]."',DAILY_MILK_PROD='".$data["DAILY_MILK_PROD"]."',REMARKS='".$data["REMARKS"]."',LAST_VISIT_ID='".$data["LAST_VISIT_ID"]."',CAR_ID='".$data["CAR_ID"]."',FARMER_STATUS='".$data["FARMER_STATUS"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."',LATT='".$data["LATT"]."',LONGG='".$data["LONGG"]."'";
       $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
     
    }
    
    public function addRegistration_log($data){
        
      
        $sql="INSERT INTO ak_farmer_log SET SID='".$data["SID"]."',FARMER_NAME='".$data["FARMER_NAME"]."',FAR_MOBILE='".$data["FAR_MOBILE"]."',CR_DATE='".$data["CR_DATE"]."',CR_BY='".$data["CR_BY"]."',VILL_ID='".$data["VILL_ID"]."',DIST_ID='".$data["DIST_ID"]."',CAN_MLK_ID='".$data["CAN_MLK_ID"]."',FGM_ID='".$data["FGM_ID"]."',KEY_FARMER='".$data["KEY_FARMER"]."',MILKING_COWS_CNT='".$data["MILKING_COWS_CNT"]."',TOTAL_COWS='".$data["TOTAL_COWS"]."',CURR_SUPPILER='".$data["CURR_SUPPILER"]."',DAILY_MILK_PROD='".$data["DAILY_MILK_PROD"]."',REMARKS='".$data["REMARKS"]."',LAST_VISIT_ID='".$data["LAST_VISIT_ID"]."',CAR_ID='".$data["CAR_ID"]."',FARMER_STATUS='".$data["FARMER_STATUS"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."'";
       
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
     
    }


public function getfarmerbymobile(){
                
      $query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "farmer where FAR_MOBILE='".$data["FAR_MOBILE"]."' ");
      return $query->rows; 
    }
    
   public function updatefarmerinfo($data) {
        
        $sql="update " . DB_PREFIX . "farmer SET VILL_ID='".$data["VILL_ID"]."',KEY_FARMER='".$data["KEY_FARMER"]."',MILKING_COWS_CNT='".$data["MILKING_COWS_CNT"]."',TOTAL_COWS='".$data["TOTAL_COWS"]."',CURR_SUPPILER='".$data["CURR_SUPPILER"]."',DAILY_MILK_PROD='".$data["DAILY_MILK_PROD"]."',REMARKS='".$data["REMARKS"]."',FARMER_STATUS='".$data["FARMER_STATUS"]."',LATT='".$data["LATT"]."',LONGG='".$data["LONGG"]."' where SID='".$data["SID"]."'";
       
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
      
    }
    
    public function unregisterfarmer($data){
        
        // farmer
        $query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "farmer where FAR_MOBILE='".$data["MOBILE_NO"]."' ");
        $farinfo = $query->rows; 
        
        // can_mlk_center
        $query1 = $this->db->query("SELECT *  FROM " . DB_PREFIX . "can_mlk_center where CONTACT_NUMBER='".$data["MOBILE_NO"]."' ");
        $mcenter = $query1->rows; 
        
        //can_pos
        $query2 = $this->db->query("SELECT *  FROM " . DB_PREFIX . "can_pos where POS_MOBILE='".$data["MOBILE_NO"]."' ");
        $canpos = $query2->rows; 
        
         if($farinfo) {
             
              $sql="update " . DB_PREFIX . "farmer SET FARMER_STATUS='8' where FAR_MOBILE='".$data["MOBILE_NO"]."'";
              $this->db->query($sql);
              $ret_id = $this->db->countAffected();
              return $ret_id;
         } else if($mcenter) {
             
              $sql="update " . DB_PREFIX . "can_mlk_center SET can_status='9' where CONTACT_NUMBER='".$data["MOBILE_NO"]."'";
              $this->db->query($sql);
              $ret_id = $this->db->countAffected();
              return $ret_id;
         } else if($canpos) {
             
              $sql="update " . DB_PREFIX . "can_pos SET POS_STATUS='9' where POS_MOBILE='".$data["MOBILE_NO"]."'";
              $this->db->query($sql);
              $ret_id = $this->db->countAffected();
              return $ret_id;
         } else {
             return 0;
         }
       
    }

     
    
    
        
}
