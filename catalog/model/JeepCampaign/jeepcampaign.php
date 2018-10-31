<?php
class ModelJeepCampaignjeepcampaign extends Model {
  
    public function jeepcampaign($data){
        
	$log=new Log("jeepcampaign.log");
  $log->write($data);    
        $sql="INSERT INTO ak_jeep_campaign SET SID='".$data["SID"]."',CR_DATE='".$data["CR_DATE"]."',CR_BY='".$data["CR_BY"]."',RETAILER_ID='".$data["RETAILER_ID"]."',VENDOR_NAME='".$data["VENDOR_NAME"]."',DRIVER_NAME='".$data["DRIVER_NAME"]."',DRIVER_MOBILE='".$data["DRIVER_MOBILE"]."',VEHICLE_NO='".$data["VEHICLE_NO"]."',VILLAGE_ID='".$data["VILLAGE_ID"]."',OPEN_KM='".$data["OPEN_KM"]."',PHOTO='".$data["PHOTO"]."',LAT='".$data["LAT"]."',LONGG='".$data["LONGG"]."',STATUS='".$data["STATUS"]."',HALTS='".$data["HALTS"]."',DIESEL_RS='".$data["DIESEL_RS"]."',DIESEL_LTR='".$data["DIESEL_LTR"]."',DIESEL_USE='".$data["DIESEL_USE"]."',LABOR_EXPENCE='".$data["LABOR_EXPENCE"]."',MISC_EXPENCE='".$data["MISC_EXPENCE"]."',JEEP_ID='".$data["JEEP_ID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
    
    public function jeepcampaignhalt($data){
        
	$log=new Log("jeepcampaignhalt.log");
      
        $sql="INSERT INTO ak_jeep_halt SET SID='".$data["SID"]."',JEEP='".$data["JEEP"]."',CR_DATE='".$data["CR_DATE"]."',VILLAGE_ID='".$data["VILLAGE_ID"]."',PHOTO='".$data["PHOTO"]."',LAT='".$data["LAT"]."',LONGG='".$data["LONGG"]."',CR_BY='".$data["CR_BY"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
    
     public function jeepcampaignupdate($data){
        
	$log=new Log("jeepcampaignupdate.log");
      
        $sql="UPDATE ak_jeep_campaign SET STATUS='2',HALTS='1' WHERE SID='".$data["SID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
    
      public function jeepcampaignupdatedisel($data){
        
	$log=new Log("jeepcampaignupdatedisel.log");
      
        $sql="UPDATE ak_jeep_campaign SET STATUS='".$data["STATUS"]."',DIESEL_RS='".$data["DIESEL_RS"]."',DIESEL_LTR='".$data["DIESEL_LTR"]."',DIESEL_USE='".$data["DIESEL_USE"]."',LABOR_EXPENCE='".$data["LABOR_EXPENCE"]."',MISC_EXPENCE='".$data["MISC_EXPENCE"]."' WHERE SID='".$data["SID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
        
}
