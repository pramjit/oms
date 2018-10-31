<?php
class ModelPaymentPaymentref extends Model {
  
    public function addpaymentref($data){
        $log=new Log("MO_SUB_PAY_MOD_".date('Ymd').".log");
        $sql="INSERT INTO oc_payment_ref SET Emp_id='".$data["username"]."',Cust_id='".$data["Cust_id"]."',Amnt_Bank='".$data["Amnt_Bank"]."',Amnt_Date='".$data["Amnt_Date"]."',Amnt_Type='".$data["Amnt_Type"]."',Amnt_Ref='".$data["Amnt_Ref"]."',Amnt_Rs='".$data["Amnt_Rs"]."',Pay_type='1'";
        $log->write($sql);
        $this->db->query($sql);
        $ret_id = $this->db->countAffected();
        return $ret_id;
    }
    public function MsgDtls($moId,$stoId){
        $log=new Log("MO_SUB_PAY_MOD_".date('Ymd').".log");
        
        $sql="SELECT A.MO_ID,A.MO_NAME,A.MO_MOB,A.AM_ID,A.AM_NAME,A.AM_MOB,B.DL_ID,B.DL_NAME,B.DL_MOB 
            FROM(SELECT 
            MO.customer_id AS 'MO_ID',
            MO.firstname AS 'MO_NAME',
            MO.telephone AS 'MO_MOB' , 
            MO.customer_id AS 'AM_ID',
            MO.firstname AS 'AM_NAME',
            MO.telephone AS 'AM_MOB'
            FROM ak_customer MO 
           
            WHERE MO.customer_id='".$moId."')A,
            (SELECT DL.customer_id AS 'DL_ID', DL.firstname AS 'DL_NAME', DL.telephone AS 'DL_MOB' 
            FROM ak_customer DL 
            WHERE DL.customer_id IN(SELECT OC.customer_id FROM oc_user OC WHERE OC.store_id='".$stoId."'))B";
        
        
        $sql="SELECT A.MO_ID,A.MO_NAME,A.MO_MOB,A.AM_ID,A.AM_NAME,A.AM_MOB,B.DL_ID,B.DL_NAME,B.DL_MOB 
            FROM(SELECT 
            MO.customer_id AS 'MO_ID',
            MO.firstname AS 'MO_NAME',
            MO.telephone AS 'MO_MOB' , 
            AM.customer_id AS 'AM_ID',
            AM.firstname AS 'AM_NAME',
            AM.telephone AS 'AM_MOB'
            FROM ak_customer MO 
            JOIN ak_customer_map MP ON(MO.customer_id=MP.CUSTOMER_ID)
            JOIN ak_customer AM ON(MP.PARENT_USER_ID=AM.customer_id)
            WHERE MO.customer_id='".$moId."')A,
            (SELECT DL.customer_id AS 'DL_ID', DL.firstname AS 'DL_NAME', DL.telephone AS 'DL_MOB' 
            FROM ak_customer DL 
            WHERE DL.customer_id IN(SELECT OC.customer_id FROM oc_user OC WHERE OC.store_id='".$stoId."'))B";
        $log->write($sql);
        $query=$this->db->query($sql);
        return $query->row;
    }
    function msgMaster($OrderId,$mType,$CrId,$StoId,$textmsg,$numbers,$responseBody){
            $crData=date('Y-m-d H:i:s');
            $log=new Log("MO_SUB_PAY_MOD_".date('Ymd').".log");
            $sql="INSERT INTO `oc_msg_master` SET
            `CR_DATE` ='".$crData."',
            `TRANS_TYPE`  ='".$mType."',
            `ORDER_ID`  ='".$OrderId."',
            `CR_BY`  ='".$CrId."',
            `STORE_ID`  ='".$StoId."',
            `MESSAGE`  ='".$textmsg."',
            `SENT_TO`  ='".$numbers."',
            `SENT_RESPONSE`  ='".$responseBody."'";
            $log->write($sql);
            if($this->db->query($sql)){
                return 1;
            }
        }        
    function paymentrefdata($data){
        $log=new Log("paymentrefinsrt.log");
        $sql="SELECT pr.Cust_id,pr.Amnt_Date,pr.Amnt_Ref,pr.Amnt_Rs,ac.firstname,ac.lastname FROM `oc_payment_ref` as pr
        LEFT JOIN oc_user as ac on ac.store_id=pr.Cust_id where pr.Emp_id='".$data["Emp_id"]."'";
        if($data["Cust_id"]!=0)
        {
        $sql.="and pr.Cust_id='".$data["Cust_id"]."'"; 

        }
        if($data["TO_DATE"]!=0)
        {
        $sql.="and DATE(Amnt_Date) between '".$data["FROM_DATE"]."' and '".$data["TO_DATE"]."'"; 
        $sql.= "ORDER BY Amnt_Date DESC";
        }
        else
        {
        $sql.= "ORDER BY Amnt_Date ";
        }
        $log->write($sql);
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->rows; 
    }

      
        
}
