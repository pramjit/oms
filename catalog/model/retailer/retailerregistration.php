<?php
class ModelRetailerretailerregistration extends Model {
  
    public function reatailerregistration($data){
        
	$log=new Log("reatailerregistration.log");
      
        $sql="INSERT INTO ak_mst_retailer SET SID='".$data["SID"]."',RETAILER_NAME='".$data["RETAILER_NAME"]."',CONTACT_PERSON='".$data["CONTACT_PERSON"]."',CR_DATE='".$data["CR_DATE"]."',MOBILE='".$data["MOBILE"]."',ADDRESS='".$data["ADDRESS"]."',DISTRICT_ID='".$data["DISTRICT_ID"]."',TEHSIL_ID='".$data["TEHSIL_ID"]."',BLOCK_ID='".$data["BLOCK_ID"]."',PINCODE='".$data["PINCODE"]."',PHOTO='".$data["PHOTO"]."',LAT='".$data["LAT"]."',LONGG='".$data["LONGG"]."',EMAIL='".$data["EMAIL"]."',CR_BY='".$data["CR_BY"]."',VILLAGE='".$data["VILLAGE"]."',FARMER='".$data["FARMER"]."',WHOLESELLER='".$data["WHOLESELLER"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
    public function reatailerregistrationtax($data){
        
        $log=new Log("reatailerregistrationtax.log");
        
        $sql="INSERT INTO ak_retailer_tax SET SID='".$data["SID"]."',RETAILER_ID='".$data["RETAILER_ID"]."',TIN_GST_NO='".$data["TIN_GST_NO"]."',PAN_NO='".$data["PAN_NO"]."',ADDHAR_NO='".$data["ADDHAR_NO"]."',FRC_NO='".$data["FRC_NO"]."',FRC_VALID_UPTO='".$data["FRC_VALID_UPTO"]."',SEED_LICENCE='".$data["SEED_LICENCE"]."',SEED_LICENCE_UPTO='".$data["SEED_LICENCE_UPTO"]."',MFMS_ID='".$data["MFMS_ID"]."',PESTICIDE_LICENCE='".$data["PESTICIDE_LICENCE"]."',PESTICIDE_LICENCE_VALID_UPTO='".$data["PESTICIDE_LICENCE_VALID_UPTO"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
        
    }
      public function  chechretailertax($data)
    {
        $sql="select RETAILER_ID from ak_retailer_tax  where TIN_GST_NO='".$data["TIN_GST_NO"]."',PAN_NO='".$data["PAN_NO"]."',ADDHAR_NO='".$data["ADDHAR_NO"]."',FRC_NO='".$data["FRC_NO"]."',FRC_VALID_UPTO='".$data["FRC_VALID_UPTO"]."',SEED_LICENCE='".$data["SEED_LICENCE"]."',SEED_LICENCE_UPTO='".$data["SEED_LICENCE_UPTO"]."',MFMS_ID='".$data["MFMS_ID"]."',PESTICIDE_LICENCE='".$data["PESTICIDE_LICENCE"]."',PESTICIDE_LICENCE_VALID_UPTO='".$data["PESTICIDE_LICENCE_VALID_UPTO"]."',RETAILER_ID='".$data["RETAILER_ID"]."'";
        $query=$this->db->query($sql);
       $RETAILER_ID=$query->row["RETAILER_ID"]; 
       if($RETAILER_ID){
           return '1';
       }
       else{ return '0';}
    }
    
   
        
}
