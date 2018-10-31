<?php
class ModelFarmervarifyfarmer extends Model {
  
    
    
       
   public function updatefarmerinfo($data) {
        
       $cr_date=date('Y-m-d H:i:s');
       $dt=date('Y-m-d');
       //select pos status
       $q="update ak_farmer SET FARMER_STATUS='2',DIST_ID='".$data["Distric_id"]."',VILL_ID='".$data["Villag_id"]."',MILKING_COWS_CNT ='".$data["Milking_cows"]."',CURR_SUPPILER='".$data["current_Feed"]."',TOTAL_COWS='".$data["No_Of_Cows"]."',DAILY_MILK_PROD ='".$data["Milk_Production"]."',REMARKS='".$data["Remarks"]."',CAR_ID='".$data["car_id"]."',KEY_FARMER='".$data["isRetailer"]."',LAST_VISIT_ID='".$data["LoginASM"]."' where SID='".$data["SID"]."' and APP_TRX_ID='".$data["APP_TRX_ID"]."' ";
        $query = $this->db->query("SELECT FARMER_STATUS FROM ak_farmer where SID ='".$data["SID"]."' ");
        $far_status = $query->row["FARMER_STATUS"]; 
      // update pos
        $sql="update ak_farmer SET FARMER_STATUS='2',DIST_ID='".$data["Distric_id"]."',VILL_ID='".$data["Villag_id"]."',MILKING_COWS_CNT ='".$data["Milking_cows"]."',CURR_SUPPILER='".$data["current_Feed"]."',TOTAL_COWS='".$data["No_Of_Cows"]."',DAILY_MILK_PROD ='".$data["Milk_Production"]."',REMARKS='".$data["Remarks"]."',CAR_ID='".$data["car_id"]."',KEY_FARMER='".$data["isRetailer"]."',LAST_VISIT_ID='".$data["LoginASM"]."' where SID='".$data["SID"]."' and APP_TRX_ID='".$data["APP_TRX_ID"]."' ";
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        $sid=$ret_id;
        if($sid) {
            
             $s="INSERT INTO ak_can_user_visit SET VISIT_TYPE ='3',POS_ID='0',FARMER_ID='".$data["SID"]."',CR_DATE='".$cr_date."',USER_ID='".$data["LoginASM"]."',REMARKS='0',APP_TRX_ID ='".$data["APP_TRX_ID_VISIT"]."',NEXT_VISIT_DATE='0000-00-00',status='2'";         
            $sql="INSERT INTO ak_can_user_visit SET VISIT_TYPE ='3',POS_ID='0',FARMER_ID='".$data["SID"]."',CR_DATE='".$cr_date."',USER_ID='".$data["LoginASM"]."',REMARKS='0',APP_TRX_ID ='".$data["APP_TRX_ID_VISIT"]."',NEXT_VISIT_DATE='0000-00-00',status='2'";
            $this->db->query($sql);
            $ret_id = $this->db->countAffected();
            return $ret_id;
        }
        
      
    }
    
   
  public function visitfarmer($data) {
        
       $cr_date=date('Y-m-d H:i:s');
       $dt=date('Y-m-d');
       //select pos status
        $query = $this->db->query("SELECT FARMER_STATUS FROM ak_farmer where SID ='".$data["SID"]."' ");
        $far_status = $query->row["FARMER_STATUS"]; 
      // update pos
        $u="update ak_farmer SET FARMER_STATUS ='3',DIST_ID='".$data["Distric_id"]."',VILL_ID='".$data["Villag_id"]."',MILKING_COWS_CNT ='".$data["Milking_cows"]."',CURR_SUPPILER='".$data["current_Feed"]."',TOTAL_COWS='".$data["No_Of_Cows"]."',DAILY_MILK_PROD ='".$data["Milk_Production"]."',REMARKS='".$data["Remarks"]."',CAR_ID='".$data["car_id"]."',KEY_FARMER='".$data["isRetailer"]."' where SID='".$data["SID"]."' and APP_TRX_ID='".$data["APP_TRX_ID"]."' ";
        $sql="update ak_farmer SET FARMER_STATUS ='3',DIST_ID='".$data["Distric_id"]."',VILL_ID='".$data["Villag_id"]."',MILKING_COWS_CNT ='".$data["Milking_cows"]."',CURR_SUPPILER='".$data["current_Feed"]."',TOTAL_COWS='".$data["No_Of_Cows"]."',DAILY_MILK_PROD ='".$data["Milk_Production"]."',REMARKS='".$data["Remarks"]."',CAR_ID='".$data["car_id"]."',KEY_FARMER='".$data["isRetailer"]."',LAST_VISIT_ID='".$data["LoginASM"]."' where SID='".$data["SID"]."' and APP_TRX_ID='".$data["APP_TRX_ID"]."' ";
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        $sid=$ret_id;
        if($sid) {
            
            $s="INSERT INTO ak_can_user_visit SET VISIT_TYPE ='3',POS_ID='0',FARMER_ID='".$data["SID"]."',CR_DATE='".$cr_date."',USER_ID='".$data["LoginASM"]."',REMARKS='".$data["visit_Remarks"]."',APP_TRX_ID ='".$data["APP_TRX_ID_VISIT"]."',NEXT_VISIT_DATE='".$data["visit_date"]."',status='3'";         
            $sql="INSERT INTO ak_can_user_visit SET VISIT_TYPE ='3',POS_ID='0',FARMER_ID='".$data["SID"]."',CR_DATE='".$cr_date."',USER_ID='".$data["LoginASM"]."',REMARKS='".$data["visit_Remarks"]."',APP_TRX_ID ='".$data["APP_TRX_ID_VISIT"]."',NEXT_VISIT_DATE='".$data["visit_date"]."',status='3'";
            $this->db->query($sql);
            $ret_id = $this->db->countAffected();
            return $ret_id;
        }
        
      
    }
     
    
    
        
}
