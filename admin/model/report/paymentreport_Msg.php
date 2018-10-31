<?php
class ModelReportpaymentreport extends Model {
	
    public function updateStatus($data){
	  
            $log=new Log("ADM_RCV_PAY_MOD".date('Ymd').".log");
            $sql="UPDATE oc_payment_ref
            SET Pay_Status='".$data['status_name']."',Pay_Date='".$data['status_date']."'
            WHERE Sid='".$data['sid']."'
            ";
            $log->write($sql);
            $this->db->query($sql);
            return $re_id=$this->db->countAffected();
    }
    public function MsgDtls($payId){
        $log=new Log("ADM_RCV_PAY_MOD".date('Ymd').".log");
        $sql="SELECT  PAY.EMP_id AS 'MO_ID',PAY.Amnt_Rs AS 'TOT_RS', PAY.Cust_id AS 'STO_ID',EMP.MO_NAME,EMP.MO_MOB,EMP.AM_NAME,EMP.AM_MOB,DIS.DL_NAME,DIS.DL_MOB
        FROM oc_payment_ref PAY 

        JOIN(SELECT 
        MO.customer_id AS 'MO_ID',
        MO.firstname AS 'MO_NAME',
        MO.telephone AS 'MO_MOB' , 
        AM.customer_id AS 'AM_ID',
        AM.firstname AS 'AM_NAME',
        AM.telephone AS 'AM_MOB'
        FROM ak_customer MO 
        JOIN ak_customer_map MP ON(MO.customer_id=MP.CUSTOMER_ID)
        JOIN ak_customer AM ON(MP.PARENT_USER_ID=AM.customer_id)) EMP ON(PAY.EMP_id=EMP.MO_ID)

        JOIN(SELECT OC.customer_id AS 'DL_ID',OC.store_id AS 'DL_STO',AK.firstname AS 'DL_NAME',AK.telephone AS 'DL_MOB'
        FROM oc_user OC
        JOIN ak_customer AK ON(OC.customer_id=AK.customer_id)) DIS ON(PAY.Cust_id=DIS.DL_STO)

        WHERE PAY.Sid='".$payId."'";
        $log->write($sql);
        $query = $this->db->query($sql);
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
    

    public function getdistrict(){
          
          
        $query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE = 4");
        return $query->rows;  
    }
     public function getWs()
    {
         
          $query = $this->db->query("select concat(firstname,' ',lastname) as name,store_id from oc_user where user_group_id='10'");//where PARENT_USER_ID='12'
          return $query->rows;  
    }
 
        public function getdailysummary($data = array()) {	  				

		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $ws_id=$this->request->get['ws_id'];
                
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
               /* $sql="SELECT 
                pr.Amnt_Bank,
                DATE_FORMAT(pr.Amnt_Date,'%d-%m-%Y') AS 'Amnt_Date',
                pr.Amnt_Type,
                pr.Amnt_Ref,
                pr.Amnt_Rs,
                pr.Par_Addrs,
                CONCAT(ou.firstname,'',ou.lastname) AS 'party_name' 
                FROM `oc_payment_ref` AS pr
                LEFT JOIN oc_user AS ou ON ou.store_id=pr.Cust_id  
                WHERE pr.Emp_id!=''";
                if($ws_id!=NULL)
                {
                        $sql.=" and pr.Cust_id in (".$ws_id.") ";
                }
                $sql.=" and DATE(pr.Amnt_Date) between ifnull(".$strt_date.",DATE(pr.Amnt_Date)) and ifnull(".$end_date.",DATE(pr.Amnt_Date)) ORDER BY pr.Amnt_Date";
               
		*/
		$sql="SELECT 
                pr.Amnt_Bank,
                
				DATE_FORMAT(pr.Amnt_Date,'%d %M,%Y') AS 'Amnt_Date',
                pr.Amnt_Type,
                pr.Amnt_Ref,
                pr.Amnt_Rs,
                pr.Par_Addrs,
                ou.`name` AS 'party_name',
			    pr.Sid,
				(case when pr.Pay_Status='1' then 'PENDING'
				when pr.Pay_Status='2' then 'RECEVIED'
				when pr.Pay_Status=3 then 'CHEQUE BOUNCED' else 'NA'
				END ) AS 'PAYMENT_STATUS',
				DATE_FORMAT(pr.Pay_Date,'%d %M,%Y') AS 'PAYMENT_DATE'
                FROM `oc_payment_ref` AS pr
                LEFT JOIN oc_store AS ou ON ou.store_id=pr.Cust_id  
                WHERE pr.Emp_id!=''";
                if($ws_id!=0)
                {
                        $sql.=" and pr.Cust_id in (".$ws_id.") ";
                }
                $sql.=" and DATE(pr.Amnt_Date) between ifnull(".$strt_date.",DATE(pr.Amnt_Date)) and ifnull(".$end_date.",DATE(pr.Amnt_Date)) ";
                $sql.=" GROUP BY pr.Emp_id, pr.Cust_id,pr.Amnt_Rs ORDER BY pr.Amnt_Date";
                //echo $sql;
                $query = $this->db->query($sql);
		return $query->rows;
	}
        
        public function getTotalSale($data = array()) {

if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $ws_id=$this->request->get['ws_id'];
                
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
               
             
                
                
            $sql="select COUNT(*) as total from (SELECT pr.Amnt_Bank,pr.Amnt_Date,pr.Amnt_Type,pr.Amnt_Ref,pr.Amnt_Rs,concat(ac.firstname,'',ac.lastname) as party_name FROM `oc_payment_ref` as pr
join ak_customer as ac on ac.customer_id=pr.Cust_id  where pr.Emp_id!=''";
if($ws_id!=NULL)
{
$sql.=" and pr.Cust_id in (".$ws_id.") ";
}
$sql.=" and 
DATE(pr.Amnt_Date) between ifnull(".$strt_date.",DATE(pr.Amnt_Date)) and ifnull(".$end_date.",DATE(pr.Amnt_Date))";
           
$sql.=") aa";
// echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getcash($store_id,$date)       
        {
            $sql="SELECT sum(total) as 'Cash' FROM `oc_order` o where payment_method='Cash' and date(date_added)='".$date."' and `store_id`='".$store_id."' ";
            //echo $sql;
            $query = $this->db->query($sql);                
        return $query->row;
        }
        public function gettagged($store_id,$date)
        {
         $sql="SELECT sum(total) as 'Tagged' FROM `oc_order` o where payment_method='Tagged' and date(date_added)='".$date."'  and `store_id`='".$store_id."'";   
         $query = $this->db->query($sql);                
     return $query->row;
        }
      
        public function getcashmonth($store_id)       
        {
            $sdate=date('Y-m-')."01";
            $edate=date('Y-m-d');
            
            $sql="SELECT sum(total) as 'Cash' FROM `oc_order` o where payment_method='Cash' and date(date_added)<='".$edate."' and date(date_added) >='".$sdate."' and `store_id`='".$store_id."' ";
            //echo $sql;
            $query = $this->db->query($sql);                
        return $query->row;
        }
        public function gettaggedmonth($store_id)
        {
         $sdate=date('Y-m-')."01";
         $edate=date('Y-m-d');
         $sql="SELECT sum(total) as 'Tagged' FROM `oc_order` o where payment_method='Tagged' and date(date_added)<='".$edate."' and date(date_added) >='".$sdate."' and `store_id`='".$store_id."'";   
         $query = $this->db->query($sql);                
         return $query->row;
        }
         function cropname($crop_id)
    {
      $query = $this->db->query("SELECT CROP_NAME FROM `ak_crop` where  sid='".$crop_id."'");       
      return $query->row;  
    }
      function retailername($reatailer_id)
    {
      $query = $this->db->query("SELECT RETAILER_NAME FROM `ak_mst_retailer` where  sid='".$reatailer_id."'");       
      return $query->row;  
    }
    function farmervisitcount($farmercoun)
    { //echo $farmercoun;
       $sql="SELECT COUNT(SID) as NO_OF_VISIT FROM `ak_user_visit`  where  sid='".$farmercoun."'";
       $query = $this->db->query($sql);       
      return $query->row["NO_OF_VISIT"];   
    }

}