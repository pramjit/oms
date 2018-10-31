<?php
class ModelReportTasummaryreport extends Model {
	
    
    public function getMo(){
         
          $query = $this->db->query("select firstname,customer_id from ak_customer where customer_group_id!=10");//where PARENT_USER_ID='12'
          return $query->rows;  
    }
    public function getdistrict(){
          
          
        $query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE = 4");
        return $query->rows;  
    }
    
    public function gettehsil(){ 
       
          
          $query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_lower` where TYPE='1'");
       
          return $query->rows;  
    }
     function gettaupload($taupl){
         
         //echo $sql="SELECT UPLOAD_1,UPLOAD_2,UPLOAD_3,UPLOAD_4 FROM ak_daily_ta where SID='$taupl'";
        
          $query = $this->db->query("SELECT UPLOAD_1,UPLOAD_2,UPLOAD_3,UPLOAD_4 FROM ak_daily_ta where SID='$taupl'");
       
          return $query->row;  
    }
        
        //Added Download

public function getdailysummary($userid,$usergroupid) { 

if(isset($this->request->get['mo_id'])) {
$mo_id = $this->request->get['mo_id'];
} else {
$mo_id = 'NULL';
}
if(isset($this->request->get['filter_date_start'])) {
$sdate = "'".$this->request->get['filter_date_start']."'";
} else {
$sdate = 'NULL';
} 
if(isset($this->request->get['filter_date_end'])) {
$edate= "'".$this->request->get['filter_date_end']."'";
} else {
$edate = 'NULL';
} 

if($usergroupid == 4)
{
$sql="SELECT cu.firstname,cu.telephone,DATE(ta.TA_DATE) as ta_date,
ta.PLACE_FROM,ta.PLACE_TO,ta.PLACE_TO1,ta.PLACE_TO2, ta.PLACE_TO3,ta.PLACE_TO4,
ta.TA_EMP_ID, (ta.CLOSE_MTR-ta.OPEN_MTR) as TOTAL, ta.SID, ta.OPEN_MTR, ta.CLOSE_MTR,
ta.TAX_RS, ta.FARE_RS, ta.PETROL_LTR, ta.LOCAL_CONVEYANCE, ta.HOTEL_RS, ta.POSTAGE_RS,
ta.PRINTING_RS, ta.MISC_RS, ta.REMARKS 
FROM ak_daily_ta as ta 
left join ak_customer as cu on ta.TA_EMP_ID = cu.customer_id 
where ta.TA_EMP_ID='".$userid."' and TA_DATE!=''and DATE(ta.TA_DATE) between ifnull(".$sdate.",DATE(ta.TA_DATE)) and ifnull(".$edate.",DATE(ta.TA_DATE))";
}
if($usergroupid == 3)
{
$div='","';
$sql_mo_am="select GROUP_CONCAT(CUSTOMER_ID,".$div.",PARENT_USER_ID) as 'USR' from ak_customer_map
where PARENT_USER_ID ='".$userid."'";
$squery = $this->db->query($sql_mo_am);
$usrs = $squery->row['USR'];

$sql="SELECT cu.firstname,cu.telephone,DATE(ta.TA_DATE) as ta_date,
ta.PLACE_FROM,ta.PLACE_TO,ta.PLACE_TO1,ta.PLACE_TO2, ta.PLACE_TO3,ta.PLACE_TO4,
ta.TA_EMP_ID, (ta.CLOSE_MTR-ta.OPEN_MTR) as TOTAL, ta.SID, ta.OPEN_MTR, ta.CLOSE_MTR,
ta.TAX_RS, ta.FARE_RS, ta.PETROL_LTR, ta.LOCAL_CONVEYANCE, ta.HOTEL_RS, ta.POSTAGE_RS,
ta.PRINTING_RS, ta.MISC_RS, ta.REMARKS 
FROM ak_daily_ta as ta 
left join ak_customer as cu on ta.TA_EMP_ID = cu.customer_id 
where ta.TA_EMP_ID IN (".$usrs.") and TA_DATE!=''and DATE(ta.TA_DATE) between ifnull(".$sdate.",DATE(ta.TA_DATE)) and ifnull(".$edate.",DATE(ta.TA_DATE))";
} 
if($usergroupid == 8)
{
$sql="SELECT cu.firstname,cu.telephone,DATE(ta.TA_DATE) as ta_date,
ta.PLACE_FROM,ta.PLACE_TO,ta.PLACE_TO1,ta.PLACE_TO2, ta.PLACE_TO3,ta.PLACE_TO4,
ta.TA_EMP_ID, (ta.CLOSE_MTR-ta.OPEN_MTR) as TOTAL, ta.SID, ta.OPEN_MTR, ta.CLOSE_MTR,
ta.TAX_RS, ta.FARE_RS, ta.PETROL_LTR, ta.LOCAL_CONVEYANCE, ta.HOTEL_RS, ta.POSTAGE_RS,
ta.PRINTING_RS, ta.MISC_RS, ta.REMARKS 
FROM ak_daily_ta as ta 
left join ak_customer as cu on ta.TA_EMP_ID = cu.customer_id 
where ta.TA_EMP_ID=ifnull(".$mo_id.",ta.TA_EMP_ID)and TA_DATE!=''and DATE(ta.TA_DATE) between ifnull(".$sdate.",DATE(ta.TA_DATE)) and ifnull(".$edate.",DATE(ta.TA_DATE))";
}
$query = $this->db->query($sql);
return $query->rows;
}



         public function getdailyam($data = array()) {	

               $sql1="SELECT CUSTOMER_ID FROM ak_customer_map where PARENT_USER_ID='".$data['usrid']."'";
             $query1 = $this->db->query($sql1);
            // echo count($query1->rows);
            //print_r($query1->rows);
             $CUSTOMER_IDs="";
            foreach($query1->rows as $row)
            {
                if($CUSTOMER_IDs=="")
                {
                $CUSTOMER_IDs="'".$row['CUSTOMER_ID']."'";
                }
                else {
                        $CUSTOMER_IDs=$CUSTOMER_IDs.",'".$row['CUSTOMER_ID']."'";
                 }
            }             

		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $mo_id=$this->request->get['mo_id'];
                
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
               
             
                
                
            $sql="SELECT cu.firstname,cu.telephone, DATE(ta.TA_DATE) as TA_DATE,ta.PLACE_FROM,ta.PLACE_TO,ta.PLACE_TO1,ta.PLACE_TO2, ta.PLACE_TO3,ta.PLACE_TO4, ta.TA_EMP_ID, (ta.CLOSE_MTR-ta.OPEN_MTR) as TOTAL, ta.SID, ta.OPEN_MTR, ta.CLOSE_MTR, ta.TAX_RS, ta.FARE_RS, ta.PETROL_LTR, ta.LOCAL_CONVEYANCE, ta.HOTEL_RS, ta.POSTAGE_RS, ta.PRINTING_RS, ta.MISC_RS, ta.REMARKS 
FROM ak_daily_ta as ta 
left join ak_customer as cu on ta.TA_EMP_ID = cu.customer_id 
where ta.TA_EMP_ID in ('".$data['usrid']."',".$CUSTOMER_IDs.") ";
if($mo_id!=NULL)
{
$sql.=" and ta.TA_EMP_ID in (".$mo_id.") ";
}
$sql.=" and 
DATE(ta.TA_DATE) between ifnull(".$strt_date.",DATE(ta.TA_DATE)) and ifnull(".$end_date.",DATE(ta.TA_DATE))";
          // echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
        
        
        
        
        
         public function getadmindtl($data = array()) {	  				

		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $mo_id=$this->request->get['mo_id'];
                
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
            $sql="SELECT cu.firstname,cu.telephone, 
                DATE(ta.TA_DATE) AS TA_DATE,
                ta.PLACE_FROM,ta.PLACE_TO,
                ta.PLACE_TO1,ta.PLACE_TO2, 
                ta.PLACE_TO3,ta.PLACE_TO4, 
                ta.TA_EMP_ID, (ta.CLOSE_MTR-ta.OPEN_MTR) AS TOTAL, 
                ta.SID, ta.OPEN_MTR, ta.CLOSE_MTR, ta.TAX_RS, 
                ta.FARE_RS, ta.PETROL_LTR, ta.LOCAL_CONVEYANCE, 
                ta.HOTEL_RS, ta.POSTAGE_RS, ta.PRINTING_RS, 
                ta.MISC_RS, ta.REMARKS 
                FROM ak_daily_ta AS ta 
                LEFT JOIN ak_customer AS cu ON ta.TA_EMP_ID = cu.customer_id 
                WHERE TA_DATE!=''";
                if($mo_id!=NULL)
                {
                    $sql.=" and ta.TA_EMP_ID in (".$mo_id.") ";
                }
                    $sql.=" and DATE(ta.TA_DATE) between ifnull(".$strt_date.",DATE(ta.TA_DATE)) and ifnull(".$end_date.",DATE(ta.TA_DATE))";
                    
                if(isset($this->request->get['mo_id'])||isset($this->request->get['filter_date_start'])||isset($this->request->get['filter_date_end']))   {
                $query = $this->db->query($sql);
                }
                return $query->rows;
	}
        
        

	public function getTotalSale($data = array()) {


		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $mo_id=$this->request->get['mo_id'];
                if(empty($mo_id)){$mo_id = 'NULL';}
                $dist_id=$this->request->get['dist_id'];
                if(empty($dist_id)){$dist_id = 'NULL';}
                $teh_id=$this->request->get['teh_id'];
                if(empty($teh_id)){$teh_id = 'NULL';}



            $sql="SELECT count(mf.sid,) as 'total
            FROM `ak_mst_farmer` as mf
            join `ak_mst_geo_upper` u on mf.DISTRICT_ID=u.SID 
            join `ak_mst_geo_lower` l on mf.BLOCK_ID=l.SID 
            join `ak_mst_geo_lower` l1 on mf.TEHSIL_ID=l1.SID
            join `ak_mst_geo_lower` l2 on mf.VILLAGE_ID=l2.SID
            join `ak_customer` as ac on ac.customer_id=mf.CR_BY
                      
where mf.CR_BY=ifnull(".$mo_id.",mf.CR_BY)
and mf.district_id=ifnull(".$dist_id.",mf.district_id)
and mf.TEHSIL_ID=ifnull(".$teh_id.",mf.TEHSIL_ID)";
 

		
                //echo $sql; 
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