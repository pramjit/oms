<?php
class ModelFarmerfarmerregistration extends Model {
  
    public function addRegistration($data){
        
	$log=new Log("farmerregistration.log");
      
        $sql="INSERT INTO ak_mst_farmer SET SID='".$data["SID"]."',FARMER_NAME='".$data["FARMER_NAME"]."',FARMER_MOBILE='".$data["FARMER_MOBILE"]."',ADDRESS='".$data["ADDRESS"]."',DISTRICT_ID='".$data["DISTRICT_ID"]."',VILLAGE_ID='".$data["VILLAGE_ID"]."',TEHSIL_ID='".$data["TEHSIL_ID"]."',BLOCK_ID='".$data["BLOCK_ID"]."',PINCODE='".$data["PINCODE"]."',PHOTO='".$data["PHOTO"]."',LAND_ACRES='".$data["LAND_ACRES"]."',PROBLEM='".$data["PROBLEM"]."',SOLUTION='".$data["SOLUTION"]."',CR_BY='".$data["CR_BY"]."',CR_DATE='".$data["CR_DATE"]."',STATUS='1',LAT='".$data["LAT"]."',LONGG='".$data["LONGG"]."',CROP='".$data["CROP"]."',RETAILER='".$data["RETAILER"]."',FGM_ID='".$data["FGM_ID"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
        public function farmerposupdate($data){
        
	$log=new Log("farmerposupdate.log");
      
        $sql="UPDATE ak_mst_farmer SET ADDRESS='".$data["ADDRESS"]."',LAND_ACRES='".$data["LAND_ACRES"]."',CROP='".$data["CROP"]."',RETAILER='".$data["RETAILER"]."',PROBLEM='".$data["PROBLEM"]."',SOLUTION='".$data["SOLUTION"]."' WHERE SID='".$data["SID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    } 
        
}
