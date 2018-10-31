<?php
class ModelFarmerfarmobservation extends Model {
  
    public function farmdemoobservation ($data){
        
	$log=new Log("farmdemoobservation.log");
      
        $sql="INSERT INTO ak_farm_demo_observation SET SID='".$data["SID"]."',FARM_DEMO_ID='".$data["FARM_DEMO_ID"]."',VILLAGE_ID='".$data["VILLAGE_ID"]."',FARMER_ID='".$data["FARMER_ID"]."',CR_BY='".$data["CR_BY"]."',CR_DATE='".$data["CR_DATE"]."',PHOTO='".$data["PHOTO"]."',PHOTO_1='".$data["PHOTO_1"]."',PHOTO_2='".$data["PHOTO_2"]."',PHOTO_3='".$data["PHOTO_3"]."',PHOTO_4='".$data["PHOTO_4"]."',LAT='".$data["LAT"]."',LONGG='".$data["LONGG"]."',REMARKS='".$data["REMARKS"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
        
}
