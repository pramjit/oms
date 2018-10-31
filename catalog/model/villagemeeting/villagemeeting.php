<?php
class ModelvillagemeetingVillagemeeting extends Model {
 
      public function addVillageMeeting($data){
        
        $sql="INSERT INTO ak_village_meeting SET SID = '" . $data['SID'] . "',VILLAGE_ID = '" . $data['VILLAGE_ID'] . "',VILLAGE_NAME = '" .$data['VILLAGE_NAME'] . "',MEETING_DATE= '" .$data['MEETING_DATE'] . "',MEETING_TIME = '".$data['MEETING_TIME']."',CROP_1= '" . $data['CROP_1'] . "',CROP_2= '" . $data['CROP_2'] . "',CROP_3= '" .$data['CROP_3'] . "',PRODUCT_1 = '".$data['PRODUCT_1']."',PRODUCT_2 = '".$data['PRODUCT_2']."',PRODUCT_3 = '".$data['PRODUCT_3']."',FARMER_COUNT = '".$data['FARMER_COUNT']."',VL_MEET_IMAGE_URL = '".$data['VL_MEET_IMAGE_URL']."',LATT = '".$data['LATT']."',LONGG = '".$data['LONGG']."',CREATED_BY = '".$data['CREATED_BY']."',DEALER_ID = '".$data['DEALER_ID']."',APP_TRX_ID = '".$data['APP_TRX_ID']."'";
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
    
    
                      
    
    
    
}
