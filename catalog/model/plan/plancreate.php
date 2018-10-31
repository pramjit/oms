<?php
class ModelPlanplancreate extends Model {
  
    public function plancreate($data){
        
	$log=new Log("plancreate.log");
      
        $sql="INSERT INTO ak_plan SET SID='".$data["SID"]."',TEHSIL='".$data["TEHSIL"]."',WHOLE_SELLER='".$data["WHOLE_SELLER"]."',CR_DATE='".$data["CR_DATE"]."',RETAILER='".$data["RETAILER"]."',APP_TR_ID='".$data["APP_TR_ID"]."',STATUS='".$data["STATUS"]."',CR_BY='".$data["CR_BY"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
    
    public function planstatusupdate($data)
    {
    $log=new Log("planstatusupdate.log");
      
        $sql="UPDATE ak_plan SET STATUS='".$data["STATUS"]."',APPROV_BY='".$data["EMP_ID"]."'  WHERE SID='".$data["SID"]."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
  function searchamplandata($data){
    $log=new Log("amplandata.log");
          $sql="SELECT ap.SID,al.NAME as TEHSIL_NAME,ac.firstname as MO_NAME,DATE(ap.CR_DATE) as DATE,os.name as WHOLE_SALE_PERSON,amr.RETAILER_NAME,ap.STATUS FROM `ak_plan`  as ap JOIN ak_customer as ac on ac.customer_id=ap.CR_BY JOIN ak_mst_geo_lower as al on al.sid=ap.TEHSIL JOIN oc_store as os on os.store_id=ap.WHOLE_SELLER JOIN ak_mst_retailer as amr on amr.sid=ap.RETAILER where ap.TEHSIL='".$data["TEHSIL"]."'  and ap.CR_BY='".$data["CR_BY"]."' and ap.STATUS='0'";
          $log->write($sql);
$query = $this->db->query($sql);
       
          return $query->rows; 
    }
      
        
}
