<?php
class ModelReportSaletargetreport extends Model {
	
    
    public function gettarget(){
         
          $query = $this->db->query("select TARGET_DESC,SID  FROM zoms.target_master");
          return $query->rows;  
    }
    public function gettargetdate($data){
          $sql="select FROM_DATE,TO_DATE  FROM zoms.target_master where SID='".$data['pdsec']."'";
          $query = $this->db->query($sql);
          return $query->row;  
    }


}