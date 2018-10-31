<?php
class ModelReportJeepcampaignreport extends Model {
	
    
     
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
                 
		if (empty($this->request->get['filter_date_start'])) {
			$sqldate="";
		}
                  else {
                     $sqldate="and DATE(ajc.CR_DATE)=ifnull('".$this->request->get['filter_date_start']."',DATE(ajc.CR_DATE))";
                 }

                $mo_id=$this->request->get['mo_id'];
                if(empty($mo_id)){$mo_id = 'NULL';}
               
                
            $sql="SELECT DATE(ajc.CR_DATE) as cr_date,ajc.OPEN_KM,ac.firstname as MO_Name,mr.RETAILER_NAME,ajc.JEEP_ID,
plan.WHOLE_SELLER,agl.NAME as START_VILLAGE,group_concat(jh.VILLAGE_ID) as VILLAGE_COVERED,
group_concat(agl1.NAME) as village_covered
FROM ak_jeep_campaign as ajc
left join ak_customer as ac on ac.customer_id=ajc.CR_BY
left join ak_mst_retailer as mr on mr.SID=ajc.RETAILER_ID
left join ak_plan as plan on plan.SID = ajc.JEEP_ID
left join ak_mst_geo_lower as agl on agl.SID=ajc.VILLAGE_ID
left join ak_jeep_halt as jh on jh.JEEP=ajc.JEEP_ID
left join ak_mst_geo_lower as agl1 on agl1.SID = jh.VILLAGE_ID
where ajc.CR_BY='".$data['usrid']."' and ajc.CR_BY=ifnull(".$mo_id.",ajc.CR_BY) ".$sqldate." 
 ";
                
		$query = $this->db->query($sql);
                //print_r($query);
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
                 
		if (empty($this->request->get['filter_date_start'])) {
			$sqldate="";
		}
                  else {
                     $sqldate="and DATE(ajc.CR_DATE)=ifnull('".$this->request->get['filter_date_start']."',DATE(ajc.CR_DATE))";
                 }

                $mo_id=$this->request->get['mo_id'];
                if(empty($mo_id)){$mo_id = 'NULL';}
               
                
            $sql="SELECT DATE(ajc.CR_DATE) as cr_date,ajc.OPEN_KM,ac.firstname as MO_Name,mr.RETAILER_NAME,ajc.JEEP_ID,
plan.WHOLE_SELLER,agl.NAME as START_VILLAGE,group_concat(jh.VILLAGE_ID) as VILLAGE_COVERED,
group_concat(agl1.NAME) as village_covered
FROM ak_jeep_campaign as ajc
left join ak_customer as ac on ac.customer_id=ajc.CR_BY
left join ak_mst_retailer as mr on mr.SID=ajc.RETAILER_ID
left join ak_plan as plan on plan.SID = ajc.JEEP_ID
left join ak_mst_geo_lower as agl on agl.SID=ajc.VILLAGE_ID
left join ak_jeep_halt as jh on jh.JEEP=ajc.JEEP_ID
left join ak_mst_geo_lower as agl1 on agl1.SID = jh.VILLAGE_ID
where ajc.CR_BY in ('".$data['usrid']."',".$CUSTOMER_IDs.") and ajc.CR_BY=ifnull(".$mo_id.",ajc.CR_BY) ".$sqldate." 
 ";
                
		$query = $this->db->query($sql);
               // print_r($sql);
                return $query->rows;
	}
        
        public function getadmindtl($data = array()) {	  				
                 
		if (empty($this->request->get['filter_date_start'])) {
			$sqldate="";
		}
                  else {
                     $sqldate="and DATE(ajc.CR_DATE)=ifnull('".$this->request->get['filter_date_start']."',DATE(ajc.CR_DATE))";
                 }

                $mo_id=$this->request->get['mo_id'];
                if(empty($mo_id)){$mo_id = 'NULL';}
               
                
            $sql="SELECT DATE(ajc.CR_DATE) as cr_date,ajc.OPEN_KM,ac.firstname as MO_Name,mr.RETAILER_NAME,ajc.JEEP_ID,
plan.WHOLE_SELLER,agl.NAME as START_VILLAGE,group_concat(jh.VILLAGE_ID) as VILLAGE_COVERED,
group_concat(agl1.NAME) as village_covered
FROM ak_jeep_campaign as ajc
left join ak_customer as ac on ac.customer_id=ajc.CR_BY
left join ak_mst_retailer as mr on mr.SID=ajc.RETAILER_ID
left join ak_plan as plan on plan.SID = ajc.JEEP_ID
left join ak_mst_geo_lower as agl on agl.SID=ajc.VILLAGE_ID
left join ak_jeep_halt as jh on jh.JEEP=ajc.JEEP_ID
left join ak_mst_geo_lower as agl1 on agl1.SID = jh.VILLAGE_ID
where ajc.CR_BY=ifnull(".$mo_id.",ajc.CR_BY) ".$sqldate." 
 ";
                
		$query = $this->db->query($sql);
                //print_r($query);
                return $query->rows;
	}
        
        

	public function getTotalSale($data = array()) {

                if (empty($data['filter_date_start'])) {
			$sqldate="";
		}
                  else {
                     $sqldate="and DATE(ap.CR_DATE)=ifnull('".$this->db->escape($data['filter_date_start'])."',DATE(ap.CR_DATE))";
                 }

                $mo_id=$this->request->get['mo_id'];
                if(empty($mo_id)){$mo_id = 'NULL';}
            



            $sql="SELECT COUNT(ajc.JEEP_ID)
FROM kai_oms.ak_jeep_campaign as ajc
left join ak_customer as ac on ac.customer_id=ajc.CR_BY
left join ak_mst_retailer as mr on mr.SID=ajc.RETAILER_ID
left join ak_plan as plan on plan.SID = ajc.JEEP_ID
left join ak_mst_geo_lower as agl on agl.SID=ajc.VILLAGE_ID
left join ak_jeep_halt as jh on jh.JEEP=ajc.JEEP_ID
left join ak_mst_geo_lower as agl1 on agl1.SID = jh.VILLAGE_ID
where ajc.CR_BY=ifnull(".$mo_id.",ajc.CR_BY) ".$sqldate." 
group by jh.JEEP ";


 

		
                //echo $sql; 
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function getjeepfarmerdtl($jeep)       
        {
            $sql="SELECT FARMER_NAME,FAR_MOBILE FROM `ak_farmer_log`  where JEEP_HALT_ID='".$jeep."'";
            
            $query = $this->db->query($sql);                
            return $query->rows;
        }
        public function getjeepOthersdtl($jeep)       
        {
            $sql="SELECT VENDOR_NAME,DRIVER_NAME,DRIVER_MOBILE,VEHICLE_NO FROM `ak_jeep_campaign`  where JEEP_ID='".$jeep."'";
            
            $query = $this->db->query($sql);                
            return $query->rows;
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
       public function getwholesalename($rmc)
       {
            $query = $this->db->query("SELECT name  FROM `oc_store` where  store_id='".$rmc."'");       
            return $query->row["name"]; 
       }

}