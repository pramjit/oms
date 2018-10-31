<?php
class ModelFarmerfarmdemo extends Model {
  
    public function farmerdemodata ($data){
        
	$log=new Log("farmerdemo.log");
      
        $sql="INSERT INTO ak_farm_demo SET SID='".$data["SID"]."',VILLAGE_ID='".$data["VILLAGE_ID"]."',FARMER_ID='".$data["FARMER_ID"]."',DEMO_ACRES='".$data["DEMO_ACRES"]."',CROP_ID='".$data["CROP_ID"]."',PHOTO='".$data["PHOTO"]."',PHOTO_1='".$data["PHOTO_1"]."',PHOTO_2='".$data["PHOTO_2"]."',PHOTO_3='".$data["PHOTO_3"]."',PHOTO_4='".$data["PHOTO_4"]."',LAT='".$data["LAT"]."',LONGG='".$data["LONGG"]."',CR_BY='".$data["CR_BY"]."',CR_DATE='".$data["CR_DATE"]."',STATUS='".$data["STATUS"]."',PRODUCT='".$data["PRODUCT"]."',QUANTITY='".$data["QUANTITY"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
       public function updatefarmdemostaus($data){
        
	$log=new Log("updatefarmdemostaus.log");
      
        $sql="UPDATE ak_farm_demo SET STATUS='".$data["STATUS"]."' WHERE SID='".$data["SID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
}
