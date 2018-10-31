<?php
class ModelFarmerfarmerdtl extends Model {
  
    public function farmerdtl ($data){
        
	$log=new Log("farmerdtl.log");
      
        $sql="INSERT INTO ak_fgm_dtl SET SID='".$data["SID"]."',USER_ID='".$data["USER_ID"]."',USER_NAME='".$data["USER_NAME"]."',DISTRICT_ID='".$data["DISTRICT_ID"]."',TEHSIL_ID='".$data["TEHSIL_ID"]."',BLOCK_ID='".$data["BLOCK_ID"]."',VILL_ID='".$data["VILL_ID"]."',CR_DATE='".$data["CR_DATE"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."',IMAGE='".$data["IMAGE"]."',LATT='".$data["LATT"]."',LONGG='".$data["LONGG"]."',RETAILER='".$data["RETAILER"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
        
}
