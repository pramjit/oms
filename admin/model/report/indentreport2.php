<?php
class ModelReportIndentreport extends Model {

    
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
    public function getWs(){
         
          $query = $this->db->query("select name,store_id from oc_store");//where PARENT_USER_ID='12'
          return $query->rows;  
    }
      public function getAm(){
         
          $query = $this->db->query("select firstname,customer_id from ak_customer where customer_group_id=3");//where PARENT_USER_ID='12'
          return $query->rows;  
    }
    public function getdistrict(){
          
          
        $query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE = 4");
        return $query->rows;  
    }
    
    public function getorder($orddata){ 
       //echo $orddata;
          
          $query = $this->db->query("SELECT 
    opo.id AS indnumber,
    ou.firstname AS Mo,
    opo.user_id,
    opo.order_status,
    opo.order_date AS indateorder,
    opo.store_id,
    os.name AS wholesaler_name,
    opp.product_id,
    opp.name AS product_name,
    opp.quantity,
    ou.customer_id,
    ref.sap_ref AS sap_code,
    opo.order_status
FROM
    oc_po_order AS opo
        LEFT JOIN
    oc_user AS ou ON ou.user_id = opo.user_id
        LEFT JOIN
    oc_store AS os ON os.store_id = opo.store_id
        LEFT JOIN
    oc_po_product AS opp ON opp.order_id = opo.id
        LEFT JOIN
    oc_sap_ref AS ref ON ref.Indent_id = opo.id

where opo.id='$orddata' group by opp.name ");
       
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
                 $ws_id=$this->request->get['ws_id'];
                 $am_id=$this->request->get['am_id'];
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
              
             
             
            $sql="SELECT 
    opo.id AS indnumber,
    ou.firstname AS Mo,
    ou.customer_id,
    map.PARENT_USER_ID,
    c1.firstname AS am,
    opo.user_id,
    opo.order_status,
    DATE_FORMAT(opo.order_date,'%d-%m-%Y') AS indateorder,
    opo.store_id,
    os.name AS wholesaler_name,
    opp.product_id,
    opp.name AS product_name,
    a.sum_of_qnty,
    
    ref.sap_ref AS sap_code,
    opo.order_status
FROM
    oc_po_order AS opo
        LEFT JOIN
    oc_user AS ou ON ou.user_id = opo.user_id
        LEFT JOIN
    oc_store AS os ON os.store_id = opo.store_id
        LEFT JOIN
    oc_po_product AS opp ON opp.order_id = opo.id
        LEFT JOIN
    oc_sap_ref AS ref ON ref.Indent_id = opo.id
        LEFT JOIN
    ak_customer_map AS map ON map.customer_id = ou.customer_id
        LEFT JOIN
    ak_customer AS c1 ON c1.customer_id = map.PARENT_USER_ID
    left join 
    (SELECT order_id,sum(quantity)as sum_of_qnty FROM oc_po_product
group by order_id)as a on a.order_id = opo.id 
where ";
            
if(($mo_id!=NULL))
{
$sql.="  ou.customer_id in (".$mo_id.") and ";
}
if($am_id!=NULL)
{
$sql.="  c1.customer_id in (".$am_id.") and ";
}
if($ws_id!=NULL)
{
$sql.="  opo.store_id in (".$ws_id.") and ";
}
$sql.=" 
opo.order_date between ifnull(".$strt_date.",opo.order_date) and ifnull(".$end_date.",opo.order_date) group by ou.customer_id,opo.id order by opo.order_date desc ";
 
          
            
             if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
              // echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

    
    
    
        public function getdailyam($data = array()) {	  				

		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $mo_id=$this->request->get['mo_id'];
                 $ws_id=$this->request->get['ws_id'];
                 $am_id=$this->request->get['am_id'];
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
              
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
             //  $CUSTOMER_IDs;
             
             $sql="SELECT 
    opo.id AS indnumber,
    ou.firstname AS Mo,
    ou.customer_id,
    map.PARENT_USER_ID,
    c1.firstname AS am,
    opo.user_id,
    opo.order_status,
      DATE_FORMAT(opo.order_date,'%d-%m-%Y') AS indateorder,
    opo.store_id,
    os.name AS wholesaler_name,
    opp.product_id,
    opp.name AS product_name,
    a.sum_of_qnty,
    
    ref.sap_ref AS sap_code,
    opo.order_status
FROM
    oc_po_order AS opo
        LEFT JOIN
    oc_user AS ou ON ou.user_id = opo.user_id
        LEFT JOIN
    oc_store AS os ON os.store_id = opo.store_id
        LEFT JOIN
    oc_po_product AS opp ON opp.order_id = opo.id
        LEFT JOIN
    oc_sap_ref AS ref ON ref.Indent_id = opo.id
        LEFT JOIN
    ak_customer_map AS map ON map.customer_id = ou.customer_id
        LEFT JOIN
    ak_customer AS c1 ON c1.customer_id = map.PARENT_USER_ID
    left join 
    (SELECT order_id,sum(quantity)as sum_of_qnty FROM oc_po_product
group by order_id)as a on a.order_id = opo.id 
where ou.customer_id in ('".$data['usrid']."',".$CUSTOMER_IDs.") and";
            
if(($mo_id!=NULL))
{
$sql.="  ou.customer_id in (".$mo_id.") and ";
}
if($am_id!=NULL)
{
$sql.="  c1.customer_id in (".$am_id.") and ";
}
if($ws_id!=NULL)
{
$sql.="  opo.store_id in (".$ws_id.") and ";
}
$sql.=" 
opo.order_date between ifnull(".$strt_date.",opo.order_date) and ifnull(".$end_date.",opo.order_date) group by ou.customer_id,opo.id order by opo.order_date desc ";
 
          
            
             if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
              //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

        
        
         public function getadmindtl($data = array()) {	  				

		                      
		
                 $mo_id=$this->request->get['mo_id'];
                 $ws_id=$this->request->get['ws_id'];
                 $am_id=$this->request->get['am_id'];
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
              
                         $sql1="SELECT CUSTOMER_ID FROM ak_customer_map where PARENT_USER_ID='".$data['usrid']."'";
             $query1 = $this->db->query($sql1);
           $lcd = $query1->row['CUSTOMER_ID']; 
             
            $sql="SELECT 
    opo.id AS indnumber,
    ou.firstname AS Mo,
    ou.customer_id,
    map.PARENT_USER_ID,
    c1.firstname AS am,
    opo.user_id,
    opo.order_status,
     DATE_FORMAT(opo.order_date,'%d-%m-%Y') AS indateorder,
    opo.store_id,
    os.name AS wholesaler_name,
    opp.product_id,
    opp.name AS product_name,
    a.sum_of_qnty,
    
    ref.sap_ref AS sap_code,
    opo.order_status
FROM
    oc_po_order AS opo
        LEFT JOIN
    oc_user AS ou ON ou.user_id = opo.user_id
        LEFT JOIN
    oc_store AS os ON os.store_id = opo.store_id
        LEFT JOIN
    oc_po_product AS opp ON opp.order_id = opo.id
        LEFT JOIN
    oc_sap_ref AS ref ON ref.Indent_id = opo.id
        LEFT JOIN
    ak_customer_map AS map ON map.customer_id = ou.customer_id
        LEFT JOIN
    ak_customer AS c1 ON c1.customer_id = map.PARENT_USER_ID
    left join 
    (SELECT order_id,sum(quantity)as sum_of_qnty FROM oc_po_product
group by order_id)as a on a.order_id = opo.id 
where ";
            
if(($mo_id!=NULL))
{
$sql.="  ou.customer_id in (".$mo_id.") and ";
}
if($am_id!=NULL)
{
$sql.="  c1.customer_id in (".$am_id.") and ";
}
if($ws_id!=NULL)
{
$sql.="  opo.store_id in (".$ws_id.") and ";
}
$sql.=" 
opo.order_date between ifnull(".$strt_date.",opo.order_date) and ifnull(".$end_date.",opo.order_date) group by ou.customer_id,opo.id order by opo.order_date desc ";
 
          
            
             if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
             // echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

        
    /**********************************Download eexcel Query******/
          public function getdownloadexcel($data = array()) {	  				

		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		
                $mo_id=$this->request->get['mo_id'];
                 $ws_id=$this->request->get['ws_id'];
                
               
                
                $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
                $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }
                
               
             
                
                
            $sql="SELECT 
    opo.id AS indnumber,
    ou.firstname AS Mo,
    opo.user_id,
    opo.order_status,
    opo.order_date AS indateorder,
    opo.store_id,
    os.name AS wholesaler_name,
    opp.product_id,
    opp.name AS product_name,
    opp.quantity,
    ou.customer_id,
    map.PARENT_USER_ID,
c1.firstname as am,
    ref.sap_ref AS sap_code,
    opo.order_status
FROM
    oc_po_order AS opo
        LEFT JOIN
    oc_user AS ou ON ou.user_id = opo.user_id
        LEFT JOIN
    oc_store AS os ON os.store_id = opo.store_id
        LEFT JOIN
    oc_po_product AS opp ON opp.order_id = opo.id
        LEFT JOIN
    oc_sap_ref AS ref ON ref.Indent_id = opo.id
    left join ak_customer_map as map on map.customer_id= ou.customer_id 
left join ak_customer as c1 on c1.customer_id = map.PARENT_USER_ID 


where ";
            
if(($mo_id!=NULL) && ($mo_id!='null'))
{
$sql.="  ou.customer_id in (".$mo_id.") and ";
}
if($ws_id!=NULL)
{
$sql.="  opo.store_id in (".$ws_id.") and ";
}
$sql.=" 
opo.order_date between ifnull(".$strt_date.",opo.order_date) and ifnull(".$end_date.",opo.order_date) ";
 
          
            
             if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
        
        
   /*******************************************************************/     
        
        
	public function getTotalSale($data = array()) {

            $sql="SELECT 
    count(opo.id) AS total
FROM
    oc_po_order AS opo
        LEFT JOIN
    oc_user AS ou ON ou.user_id = opo.user_id
        LEFT JOIN
    oc_store AS os ON os.store_id = opo.store_id
        LEFT JOIN
    oc_po_product AS opp ON opp.order_id = opo.id
        LEFT JOIN
    oc_sap_ref AS ref ON ref.Indent_id = opo.id
        LEFT JOIN
    ak_customer_map AS map ON map.customer_id = ou.customer_id
        LEFT JOIN
    ak_customer AS c1 ON c1.customer_id = map.PARENT_USER_ID
    left join 
    (SELECT order_id,sum(quantity)as sum_of_qnty FROM oc_po_product
group by order_id)as a on a.order_id = opo.id 
";
 
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