<?php
class Modelfgmaddfgm extends Model {
  
    public function addfgm($data){
        
      $log=new Log("fgm.log");
        $sql="INSERT INTO ak_can_fgm_dtl SET SID='".$data["SID"]."',USER_ID='".$data["USER_ID"]."',USER_NAME='".$data["USER_NAME"]."',VILL_ID='".$data["VILL_ID"]."',CR_DATE='".$data["CR_DATE"]."',FARMER_CNT='".$data["FARMER_CNT"]."',REMARKS='".$data["REMARKS"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."',IMAGE='".$data["IMAGE"]."',LATT='".$data["LATT"]."',LONGG='".$data["LONGG"]."'";
       $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
     
    }
    
     
    
    
        
}
