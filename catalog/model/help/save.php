<?php
class ModelHelpsave extends Model {
  
    public function savehelp($data){
        
	$log=new Log("helpdata.log");
        $cr_date=date('Y-m-d');
        $up_date='0000-00-00';
        $sql="INSERT INTO ak_help SET EMP_ID='".$data['empid']."',EMP_NAME='".$data['name']."',MOB_NO='".$data['mobile_no']."',CALL_STS='".$data['call']."',MSG='".$data['message']."',QRY_STS='0',CR_DATE='".$cr_date."',UP_DATE='".$up_date."'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
        
            
        
    }
    public function getdata($empid) {
        $log=new Log("gethelpdata.log");
        $sql="select EMP_ID,EMP_NAME,MOB_NO,
(CASE 
	WHEN CALL_STS= '1' THEN 'CALL ME'
	WHEN CALL_STS= '0' THEN 'DO NOT CALL ME'
ELSE 'NA'
END) as 'CALL_STS',
MSG,
(CASE 
	WHEN QRY_STS= '0' THEN 'PENDING'
	WHEN QRY_STS= '1' THEN 'REVIEWED'
	WHEN QRY_STS= '2' THEN 'ANSWERD'
	WHEN QRY_STS= '3' THEN 'REJECTED'
ELSE 'NA'
END) as 'QRY_STS',

CR_DATE,UP_DATE from ak_help where EMP_ID='".$empid."'";
        $log->write($sql);
        $query=$this->db->query($sql);
        return $query->rows;
    }
    
}
