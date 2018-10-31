<?php
class ModelPosposvisit extends Model {
  
    public function posvisitdata ($data){
        
	$log=new Log("posvisit.log");
      
        $sql="INSERT INTO ak_user_visit SET SID='".$data["SID"]."',VISIT_TYPE='".$data["VISIT_TYPE"]."',POS_ID='".$data["POS_ID"]."',FARMER_ID='".$data["FARMER_ID"]."',CR_DATE='".$data["CR_DATE"]."',USER_ID='".$data["USER_ID"]."',REMARKS='".$data["REMARKS"]."',APP_TRX_ID='".$data["APP_TRX_ID"]."',NEXT_VISIT_DATE='".$data["NEXT_VISIT_DATE"]."',PURPOSE_ID='".$data["PURPOSE_ID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
       public function updateretailertaxpos($data){
        
	$log=new Log("updateretailertaxpos.log");
      
        $sql="UPDATE ak_retailer_tax SET TIN_GST_NO='".$data["TIN_GST_NO"]."',PAN_NO='".$data["PAN_NO"]."',ADDHAR_NO='".$data["ADDHAR_NO"]."',FRC_NO='".$data["FRC_NO"]."',FRC_VALID_UPTO='".$data["FRC_VALID_UPTO"]."',SEED_LICENCE='".$data["SEED_LICENCE"]."',SEED_LICENCE_UPTO='".$data["SEED_LICENCE_UPTO"]."',MFMS_ID='".$data["MFMS_ID"]."',PESTICIDE_LICENCE='".$data["PESTICIDE_LICENCE"]."',PESTICIDE_LICENCE_VALID_UPTO='".$data["PESTICIDE_LICENCE_VALID_UPTO"]."' WHERE RETAILER_ID='".$data["RETAILER_ID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    } 
       public function updateretailerpos($data){
        
	$log=new Log("updateretailerpos.log");
      
        $sql="UPDATE ak_mst_retailer SET CONTACT_PERSON='".$data["CONTACT_PERSON"]."',ADDRESS='".$data["ADDRESS"]."',EMAIL='".$data["EMAIL"]."',WHOLESELLER='".$data["WHOLESELLER"]."' WHERE SID='".$data["SID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    } 
    public function  chechpostax($data)
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
