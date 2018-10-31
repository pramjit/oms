<?php
class ModelReportFarmdemoreport extends Model {
	
    
    public function getFarmer(){
         
          $query = $this->db->query("select FARMER_NAME,SID from ak_mst_farmer");//where PARENT_USER_ID='12'
          return $query->rows;  
    }
    public function getVillage(){
          
          
        $query = $this->db->query("SELECT SID,NAME FROM `ak_mst_geo_lower` where TYPE='3'");
        return $query->rows;  
    }
     
        public function getdailysummary($data = array()) {	  				

		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $fm_id=$this->request->get['fm_id'];
                if(empty($fm_id)){$fm_id = 'NULL';}
                $vil_id=$this->request->get['vil_id'];
                if(empty($vil_id)){$vil_id = 'NULL';}
                $stat_id=$this->request->get['stat_id'];
                if(empty($stat_id)){$stat_id = 'NULL';}
               $dat=$this->request->get['filter_date_start'];
                if(empty($dat)){$dat = 'NULL';}
                
                
            $sql="SELECT fd.CR_DATE,fd.DEMO_ACRES,mf.FARMER_NAME,mf.FARMER_MOBILE,c.CROP_NAME,fd.STATUS,mgl.NAME as Village_Name,fdo.REMARKS
                FROM ak_farm_demo as fd 
                left join ak_mst_farmer as mf on  mf.SID=fd.FARMER_ID
                left join ak_crop as c on c.SID=fd.CROP_ID
                left join ak_mst_geo_lower as mgl on mgl.SID=fd.VILLAGE_ID
                left join ak_farm_demo_observation as fdo on fd.SID=fdo.FARM_DEMO_ID
            where fd.CR_BY='".$data['usrid']."' and
            
            fd.FARMER_ID=ifnull(".$fm_id.",fd.FARMER_ID)
            and 
            
            fd.VILLAGE_ID=ifnull(".$vil_id.",fd.VILLAGE_ID)
            and
            
            fd.STATUS=ifnull(".$stat_id.",fd.STATUS)
            and
            fd.CR_DATE=ifnull(".$dat.",fd.CR_DATE)";
           //print_r($sql);


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
		
                $fm_id=$this->request->get['fm_id'];
                if(empty($fm_id)){$fm_id = 'NULL';}
                $vil_id=$this->request->get['vil_id'];
                if(empty($vil_id)){$vil_id = 'NULL';}
                $stat_id=$this->request->get['stat_id'];
                if(empty($stat_id)){$stat_id = 'NULL';}
               $dat=$this->request->get['filter_date_start'];
                if(empty($dat)){$dat = 'NULL';}
                
                
            $sql="SELECT fd.CR_DATE,fd.DEMO_ACRES,mf.FARMER_NAME,mf.FARMER_MOBILE,c.CROP_NAME,fd.STATUS,mgl.NAME as Village_Name,fdo.REMARKS
                FROM ak_farm_demo as fd 
                left join ak_mst_farmer as mf on  mf.SID=fd.FARMER_ID
                left join ak_crop as c on c.SID=fd.CROP_ID
                left join ak_mst_geo_lower as mgl on mgl.SID=fd.VILLAGE_ID
                left join ak_farm_demo_observation as fdo on fd.SID=fdo.FARM_DEMO_ID
            where fd.CR_BY in ('".$data['usrid']."',".$CUSTOMER_IDs.") and
            
            fd.FARMER_ID=ifnull(".$fm_id.",fd.FARMER_ID)
            and 
            
            fd.VILLAGE_ID=ifnull(".$vil_id.",fd.VILLAGE_ID)
            and
            
            fd.STATUS=ifnull(".$stat_id.",fd.STATUS)
            and
            fd.CR_DATE=ifnull(".$dat.",fd.CR_DATE)";
           //print_r($sql);


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
		
                $fm_id=$this->request->get['fm_id'];
                if(empty($fm_id)){$fm_id = 'NULL';}
                $vil_id=$this->request->get['vil_id'];
                if(empty($vil_id)){$vil_id = 'NULL';}
                $stat_id=$this->request->get['stat_id'];
                if(empty($stat_id)){$stat_id = 'NULL';}
               $dat=$this->request->get['filter_date_start'];
                if(empty($dat)){$dat = 'NULL';}
                
                
            $sql="SELECT fd.CR_DATE,fd.DEMO_ACRES,mf.FARMER_NAME,mf.FARMER_MOBILE,c.CROP_NAME,fd.STATUS,mgl.NAME as Village_Name,fdo.REMARKS
                FROM ak_farm_demo as fd 
                left join ak_mst_farmer as mf on  mf.SID=fd.FARMER_ID
                left join ak_crop as c on c.SID=fd.CROP_ID
                left join ak_mst_geo_lower as mgl on mgl.SID=fd.VILLAGE_ID
                left join ak_farm_demo_observation as fdo on fd.SID=fdo.FARM_DEMO_ID
            where 
            
            fd.FARMER_ID=ifnull(".$fm_id.",fd.FARMER_ID)
            and 
            
            fd.VILLAGE_ID=ifnull(".$vil_id.",fd.VILLAGE_ID)
            and
            
            fd.STATUS=ifnull(".$stat_id.",fd.STATUS)
            and
            fd.CR_DATE=ifnull(".$dat.",fd.CR_DATE)";
           //print_r($sql);


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
		
                $fm_id=$this->request->get['fm_id'];
                if(empty($fm_id)){$fm_id = 'NULL';}
                $vil_id=$this->request->get['vil_id'];
                if(empty($vil_id)){$vil_id = 'NULL';}
                $stat_id=$this->request->get['stat_id'];
                if(empty($stat_id)){$stat_id = 'NULL';}
                 $dat_id=$this->request->get['filter_date_start'];
                if(empty($dat_id)){$dat_id = 'NULL';}



            $sql="SELECT fd.CR_DATE,fd.DEMO_ACRES,mf.FARMER_NAME,mf.FARMER_MOBILE,c.CROP_NAME,fd.FERTILIZER,fd.STATUS,mgl.NAME as Village_Name,fdo.REMARKS
                FROM ak_farm_demo as fd 
                join ak_mst_farmer as mf on  mf.SID=fd.FARMER_ID
                join ak_crop as c on c.SID=fd.CROP_ID
                join ak_mst_geo_lower as mgl on mgl.SID=fd.VILLAGE_ID
                join ak_farm_demo_observation as fdo on fd.SID=fdo.FARM_DEMO_ID
            where 
            
            fd.FARMER_ID=ifnull(".$fm_id.",fd.FARMER_ID)
            and 
            
            fd.VILLAGE_ID=ifnull(".$vil_id.",fd.VILLAGE_ID)
            and
            
            fd.STATUS=ifnull(".$stat_id.",fd.STATUS)
            and
            fd.CR_DATE=ifnull(".$dat_id.",fd.CR_DATE)";
                		
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