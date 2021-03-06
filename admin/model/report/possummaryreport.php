<?php
class ModelReportPossummaryreport extends Model {
	
    
    public function getMo($usergroupid,$userid){
         
        //echo $userid; die;
        if($usergroupid=="3")
        {
            $query = $this->db->query("SELECT ac.firstname FROM ak_customer_map as mp
left join ak_customer as ac on ac.customer_id=mp.CUSTOMER_ID
where PARENT_USER_ID='".$userid."'");          
        }
        elseif($usergroupid=="4")
        {
            $query = $this->db->query("select firstname,customer_id from ak_customer where customer_id='".$userid."' and customer_group_id=4");//where PARENT_USER_ID='12'
        }
        else
        {
            $query = $this->db->query("select firstname,customer_id from ak_customer where customer_group_id=4");//where PARENT_USER_ID='12'
        }
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
        public function getdailysummary($data = array()) {	  				

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
                
                  $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
                
                
            $sql="SELECT mr.RETAILER_NAME,mr.CONTACT_PERSON,mr.MOBILE,u.NAME as DISTRICT_NAME,ac.firstname as MO_NAME,mr.CR_DATE,l1.NAME as TEHSIL_NAME,l.NAME as BLOCK_NAME,mr.PINCODE,mrt.TIN_GST_NO,mrt.PAN_NO,mrt.ADDHAR_NO,mrt.FRC_NO,mrt.FRC_VALID_UPTO,mrt.SEED_LICENCE,mrt.SEED_LICENCE_UPTO,mrt.MFMS_ID 
            FROM `ak_mst_retailer` as mr
            left join `ak_retailer_tax` as mrt on mr.sid=mrt.RETAILER_ID
            left join `ak_customer` as ac on ac.customer_id=mr.CR_BY
            left join `ak_mst_geo_upper` u on mr.DISTRICT_ID=u.SID 
            left join `ak_mst_geo_lower` l on mr.BLOCK_ID=l.SID 
            left join `ak_mst_geo_lower` l1 on mr.TEHSIL_ID=l1.SID
            where mr.CR_BY='".$data['usrid']."' and
            mr.district_id=ifnull(".$dist_id.",mr.district_id)
            and             
            mr.TEHSIL_ID=ifnull(".$teh_id.",mr.TEHSIL_ID)
            and            
            ac.customer_id=ifnull(".$mo_id.",ac.customer_id)
            and 
            mr.CR_DATE between ifnull(".$strt_date.",mr.CR_DATE) and ifnull(".$end_date.",mr.CR_DATE)";
//echo $sql; 
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
                if(empty($mo_id)){$mo_id = 'NULL';}
                $dist_id=$this->request->get['dist_id'];
                if(empty($dist_id)){$dist_id = 'NULL';}
                $teh_id=$this->request->get['teh_id'];
                if(empty($teh_id)){$teh_id = 'NULL';}
                
                  $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
                
                
            $sql="SELECT mr.RETAILER_NAME,mr.CONTACT_PERSON,mr.MOBILE,u.NAME as DISTRICT_NAME,ac.firstname as MO_NAME,mr.CR_DATE,l1.NAME as TEHSIL_NAME,l.NAME as BLOCK_NAME,mr.PINCODE,mrt.TIN_GST_NO,mrt.PAN_NO,mrt.ADDHAR_NO,mrt.FRC_NO,mrt.FRC_VALID_UPTO,mrt.SEED_LICENCE,mrt.SEED_LICENCE_UPTO,mrt.MFMS_ID 
            FROM `ak_mst_retailer` as mr
            left join `ak_retailer_tax` as mrt on mr.sid=mrt.RETAILER_ID
            left join `ak_customer` as ac on ac.customer_id=mr.CR_BY
            left join `ak_mst_geo_upper` u on mr.DISTRICT_ID=u.SID 
            left join `ak_mst_geo_lower` l on mr.BLOCK_ID=l.SID 
            left join `ak_mst_geo_lower` l1 on mr.TEHSIL_ID=l1.SID
            where mr.CR_BY in ('".$data['usrid']."',".$CUSTOMER_IDs.") and
            mr.district_id=ifnull(".$dist_id.",mr.district_id)
            and             
            mr.TEHSIL_ID=ifnull(".$teh_id.",mr.TEHSIL_ID)
            and            
            ac.customer_id=ifnull(".$mo_id.",ac.customer_id)
            and 
            mr.CR_DATE between ifnull(".$strt_date.",mr.CR_DATE) and ifnull(".$end_date.",mr.CR_DATE)";
//echo $sql; 
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
                if(empty($mo_id)){$mo_id = 'NULL';}
                $dist_id=$this->request->get['dist_id'];
                if(empty($dist_id)){$dist_id = 'NULL';}
                $teh_id=$this->request->get['teh_id'];
                if(empty($teh_id)){$teh_id = 'NULL';}
                
                  $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
                
                
            $sql="SELECT mr.RETAILER_NAME,mr.CONTACT_PERSON,mr.MOBILE,u.NAME as DISTRICT_NAME,ac.firstname as MO_NAME,mr.CR_DATE,l1.NAME as TEHSIL_NAME,l.NAME as BLOCK_NAME,mr.PINCODE,mrt.TIN_GST_NO,mrt.PAN_NO,mrt.ADDHAR_NO,mrt.FRC_NO,mrt.FRC_VALID_UPTO,mrt.SEED_LICENCE,mrt.SEED_LICENCE_UPTO,mrt.MFMS_ID 
            FROM `ak_mst_retailer` as mr
            left join `ak_retailer_tax` as mrt on mr.sid=mrt.RETAILER_ID
            left join `ak_customer` as ac on ac.customer_id=mr.CR_BY
            left join `ak_mst_geo_upper` u on mr.DISTRICT_ID=u.SID 
            left join `ak_mst_geo_lower` l on mr.BLOCK_ID=l.SID 
            left join `ak_mst_geo_lower` l1 on mr.TEHSIL_ID=l1.SID
            where
            mr.district_id=ifnull(".$dist_id.",mr.district_id)
            and             
            mr.TEHSIL_ID=ifnull(".$teh_id.",mr.TEHSIL_ID)
            and            
            ac.customer_id=ifnull(".$mo_id.",ac.customer_id)
            and 
            mr.CR_DATE between ifnull(".$strt_date.",mr.CR_DATE) and ifnull(".$end_date.",mr.CR_DATE)";
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