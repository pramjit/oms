<?php
class ModelFarmerfarmerlog extends Model {
  
    public function farmerlog ($data){
        
	$log=new Log("farmerlog.log");
      
        $sql="INSERT INTO ak_farmer_log SET SID='".$data["SID"]."',FARMER_NAME='".$data["FARMER_NAME"]."',FAR_MOBILE='".$data["FAR_MOBILE"]."',DIST_ID='".$data["DIST_ID"]."',VILL_ID='".$data["VILL_ID"]."',TEHSIL_ID='".$data["TEHSIL_ID"]."',BLOCK_ID='".$data["BLOCK_ID"]."',CR_BY='".$data["CR_BY"]."',CR_DATE='".$data["CR_DATE"]."',JEEP_HALT_ID='".$data["JEEP_HALT_ID"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."',FGM_ID='".$data["FGM_ID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
        
}
