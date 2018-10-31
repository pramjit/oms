<?php
class ModelFarmermstfarmer extends Model {
  
    public function mstfarmer($data){
        
	$log=new Log("mstfarmer.log");
      
        $sql="INSERT INTO ak_mst_farmer SET SID='".$data["SID"]."',FARMER_NAME='".$data["FARMER_NAME"]."',FARMER_MOBILE='".$data["FARMER_MOBILE"]."',DISTRICT_ID='".$data["DISTRICT_ID"]."',VILLAGE_ID='".$data["VILLAGE_ID"]."',TEHSIL_ID='".$data["TEHSIL_ID"]."',BLOCK_ID='".$data["BLOCK_ID"]."',CR_BY='".$data["CR_BY"]."',CR_DATE='".$data["CR_DATE"]."',JEEP_HALT_ID='".$data["JEEP_HALT_ID"]."',STATUS='".$data["STATUS"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
        
}
