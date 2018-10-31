<?php
class ModelReportStaffTaSubmission extends Model {

    
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

		
              
             
             
            $sql="select customer_id,customer_group_id,Emp_Name,Mobile,
max(case when day(TA_DATE)='01' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='01' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='01' and PLACE_FROM='' then 'Blank'end)as '01',
max(case when day(TA_DATE)='02' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='02' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='02' and PLACE_FROM='' then 'Blank'end)as '02',
max(case when day(TA_DATE)='03' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='03' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='03' and PLACE_FROM='' then 'Blank'end)as '03',
max(case when day(TA_DATE)='04' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='04' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='04' and PLACE_FROM='' then 'Blank'end)as '04',
max(case when day(TA_DATE)='05' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='05' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='05' and PLACE_FROM='' then 'Blank'end)as '05',
max(case when day(TA_DATE)='06' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='06' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='06' and PLACE_FROM='' then 'Blank'end)as '06',
max(case when day(TA_DATE)='07' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='07' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='07' and PLACE_FROM='' then 'Blank'end)as '07',
max(case when day(TA_DATE)='08' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='08' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='08' and PLACE_FROM='' then 'Blank'end)as '08',
max(case when day(TA_DATE)='09' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='09' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='09' and PLACE_FROM='' then 'Blank'end)as '09',
max(case when day(TA_DATE)='10' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='10' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='10' and PLACE_FROM='' then 'Blank'end)as '10',
max(case when day(TA_DATE)='11' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='11' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='11' and PLACE_FROM='' then 'Blank'end)as '11',
max(case when day(TA_DATE)='12' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='12' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='12' and PLACE_FROM='' then 'Blank'end)as '12',
max(case when day(TA_DATE)='13' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='13' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='13' and PLACE_FROM='' then 'Blank'end)as '13',
max(case when day(TA_DATE)='14' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='14' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='14' and PLACE_FROM='' then 'Blank'end)as '14',
max(case when day(TA_DATE)='15' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='15' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='15' and PLACE_FROM='' then 'Blank'end)as '15',
max(case when day(TA_DATE)='16' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='16' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='16' and PLACE_FROM='' then 'Blank'end)as '16',
max(case when day(TA_DATE)='17' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='17' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='17' and PLACE_FROM='' then 'Blank'end)as '17',
max(case when day(TA_DATE)='18' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='18' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='18' and PLACE_FROM='' then 'Blank'end)as '18',
max(case when day(TA_DATE)='19' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='19' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='19' and PLACE_FROM='' then 'Blank'end)as '19',
max(case when day(TA_DATE)='20' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='20' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='20' and PLACE_FROM='' then 'Blank'end)as '20',
max(case when day(TA_DATE)='21' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='21' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='21' and PLACE_FROM='' then 'Blank'end)as '21',
max(case when day(TA_DATE)='22' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='22' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='22' and PLACE_FROM='' then 'Blank'end)as '22',
max(case when day(TA_DATE)='23' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='23' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='23' and PLACE_FROM='' then 'Blank'end)as '23',
max(case when day(TA_DATE)='24' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='24' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='24' and PLACE_FROM='' then 'Blank'end)as '24',
max(case when day(TA_DATE)='25' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='25' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='25' and PLACE_FROM='' then 'Blank'end)as '25',
max(case when day(TA_DATE)='26' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='26' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='26' and PLACE_FROM='' then 'Blank'end)as '26',
max(case when day(TA_DATE)='27' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='27' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='27' and PLACE_FROM='' then 'Blank'end)as '27',
max(case when day(TA_DATE)='28' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='28' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='28' and PLACE_FROM='' then 'Blank'end)as '28',
max(case when day(TA_DATE)='29' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='29' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='29' and PLACE_FROM='' then 'Blank'end)as '29',
max(case when day(TA_DATE)='30' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='30' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='30' and PLACE_FROM='' then 'Blank'end)as '30',
max(case when day(TA_DATE)='31' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='31' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='31' and PLACE_FROM='' then 'Blank'end)as '31'

from (

select ak.customer_id,ak.customer_group_id,
concat(ak.firstname,' ',ak.lastname)as Emp_Name,ak.telephone as Mobile,ta.TA_DATE,ta.PLACE_FROM
 from 
ak_customer as ak 
left join ak_daily_ta as ta on ak.customer_id= ta.TA_EMP_ID
where ak.customer_group_id in(3,4)
and ak.status=1 and month(ta.TA_DATE)=month(curdate())
order by ak.customer_group_id asc)as a
group by customer_id";
            

          
            
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

            $sql="select customer_id,customer_group_id,Emp_Name,Mobile,
max(case when day(TA_DATE)='01' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='01' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='01' and PLACE_FROM='' then 'Blank'end)as '01',
max(case when day(TA_DATE)='02' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='02' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='02' and PLACE_FROM='' then 'Blank'end)as '02',
max(case when day(TA_DATE)='03' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='03' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='03' and PLACE_FROM='' then 'Blank'end)as '03',
max(case when day(TA_DATE)='04' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='04' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='04' and PLACE_FROM='' then 'Blank'end)as '04',
max(case when day(TA_DATE)='05' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='05' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='05' and PLACE_FROM='' then 'Blank'end)as '05',
max(case when day(TA_DATE)='06' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='06' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='06' and PLACE_FROM='' then 'Blank'end)as '06',
max(case when day(TA_DATE)='07' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='07' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='07' and PLACE_FROM='' then 'Blank'end)as '07',
max(case when day(TA_DATE)='08' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='08' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='08' and PLACE_FROM='' then 'Blank'end)as '08',
max(case when day(TA_DATE)='09' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='09' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='09' and PLACE_FROM='' then 'Blank'end)as '09',
max(case when day(TA_DATE)='10' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='10' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='10' and PLACE_FROM='' then 'Blank'end)as '10',
max(case when day(TA_DATE)='11' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='11' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='11' and PLACE_FROM='' then 'Blank'end)as '11',
max(case when day(TA_DATE)='12' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='12' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='12' and PLACE_FROM='' then 'Blank'end)as '12',
max(case when day(TA_DATE)='13' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='13' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='13' and PLACE_FROM='' then 'Blank'end)as '13',
max(case when day(TA_DATE)='14' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='14' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='14' and PLACE_FROM='' then 'Blank'end)as '14',
max(case when day(TA_DATE)='15' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='15' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='15' and PLACE_FROM='' then 'Blank'end)as '15',
max(case when day(TA_DATE)='16' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='16' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='16' and PLACE_FROM='' then 'Blank'end)as '16',
max(case when day(TA_DATE)='17' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='17' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='17' and PLACE_FROM='' then 'Blank'end)as '17',
max(case when day(TA_DATE)='18' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='18' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='18' and PLACE_FROM='' then 'Blank'end)as '18',
max(case when day(TA_DATE)='19' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='19' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='19' and PLACE_FROM='' then 'Blank'end)as '19',
max(case when day(TA_DATE)='20' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='20' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='20' and PLACE_FROM='' then 'Blank'end)as '20',
max(case when day(TA_DATE)='21' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='21' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='21' and PLACE_FROM='' then 'Blank'end)as '21',
max(case when day(TA_DATE)='22' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='22' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='22' and PLACE_FROM='' then 'Blank'end)as '22',
max(case when day(TA_DATE)='23' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='23' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='23' and PLACE_FROM='' then 'Blank'end)as '23',
max(case when day(TA_DATE)='24' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='24' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='24' and PLACE_FROM='' then 'Blank'end)as '24',
max(case when day(TA_DATE)='25' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='25' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='25' and PLACE_FROM='' then 'Blank'end)as '25',
max(case when day(TA_DATE)='26' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='26' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='26' and PLACE_FROM='' then 'Blank'end)as '26',
max(case when day(TA_DATE)='27' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='27' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='27' and PLACE_FROM='' then 'Blank'end)as '27',
max(case when day(TA_DATE)='28' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='28' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='28' and PLACE_FROM='' then 'Blank'end)as '28',
max(case when day(TA_DATE)='29' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='29' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='29' and PLACE_FROM='' then 'Blank'end)as '29',
max(case when day(TA_DATE)='30' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='30' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='30' and PLACE_FROM='' then 'Blank'end)as '30',
max(case when day(TA_DATE)='31' and PLACE_FROM='NA' then 'L' 
when day(TA_DATE)='31' and PLACE_FROM<>'NA' then 'P' 
when day(TA_DATE)='31' and PLACE_FROM='' then 'Blank'end)as '31'

from (

select ak.customer_id,ak.customer_group_id,
concat(ak.firstname,' ',ak.lastname)as Emp_Name,ak.telephone as Mobile,ta.TA_DATE,ta.PLACE_FROM
 from 
ak_customer as ak 
left join ak_daily_ta as ta on ak.customer_id= ta.TA_EMP_ID
where ak.customer_group_id in(3,4)
and ak.status=1 and month(ta.TA_DATE)=month(curdate())
order by ak.customer_group_id asc)as a
group by customer_id";
           
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
group by order_id)as a on a.order_id = opo.id ";
 

		
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