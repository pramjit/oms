<?php
class ModelReportDailysummaryreport extends Model {
	
    
    public function getMo(){
         
          $query = $this->db->query("select firstname,customer_id from ak_customer where customer_group_id = 4");//where PARENT_USER_ID='12'
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

            
		 $strt_date=$this->request->get['filter_date_start'];
                if(empty($strt_date)){$strt_date = 'NULL';}else{
                    $strt_date="'".$strt_date."'";
                }
                
                
                
         $end_date=$this->request->get['filter_date_end'];
                if(empty($end_date)){$end_date = 'NULL';}else{
                    $end_date="'".$end_date."'";
                }                              
		
                $mo_id=$this->request->get['mo_id'];
                if(empty($mo_id)){$mo_id = 'NULL';}
            $dist_id=$this->request->get['dist_id'];
                if(empty($dist_id)){$dist_id = 'NULL';}
             $teh_id=$this->request->get['teh_id'];
                if(empty($teh_id)){$teh_id = 'NULL';}
                
 $sql="select concat(akc.firstname,akc.lastname) as firstname,
count(DISTINCT akf.SID) as mst_farmer_cnt,
count(DISTINCT akr.SID) as retailer_cnt,
count(DISTINCT c.USER_ID) as vill_mtng_cnt,
ifnull (sum(case when d.VISIT_TYPE=1 then 1 else 0 end),0)as pos_vist,
ifnull (sum(case when d.VISIT_TYPE=2 then 1 else 0 end),0) as farmer_vist 
from ak_customer akc 
left join ak_mst_farmer akf on akf.CR_BY=akc.customer_id  
left join ak_mst_retailer akr on akr.CR_BY=akc.customer_id  
left join ak_fgm_dtl as c on c.user_id =akc.customer_id 
left join ak_user_visit d on d.user_id = akc.customer_id 
where akc.customer_id='".$mo_id."' 
and akf.CR_DATE BETWEEN $strt_date and $end_date and  akf.DISTRICT_ID=ifnull($dist_id,akf.DISTRICT_ID) and  akf.TEHSIL_ID=ifnull($teh_id,akf.TEHSIL_ID)
    and  akr.DISTRICT_ID=ifnull($dist_id,akr.DISTRICT_ID) and  akr.TEHSIL_ID=ifnull($teh_id,akr.TEHSIL_ID) 
";   
 /* $sql="select customer_id,firstname,mst_farmer_cnt,retailer_cnt,vill_mtng_cnt,
pos_vist,farmer_vist,District,dis_id,Tehsil,t_id
from (

select cu.customer_id,cu.firstname,a.mst_farmer_cnt,b.retailer_cnt,
c.vill_mtng_cnt,d.pos_vist,d.farmer_vist,e.District,e.dis_id,f.Tehsil,f.t_id,cu.reg_date
 from ak_customer as cu

left join 
(select cr_by,count(cr_by)as mst_farmer_cnt from ak_mst_farmer 
group by cr_by)as a on a.cr_by = cu.customer_id

left join 

(select cr_by,count(cr_by)as retailer_cnt from ak_mst_retailer group by cr_by)as b
on b.cr_by = cu.customer_id

left join 

(select user_id,count(*)as vill_mtng_cnt from ak_fgm_dtl)as c
 on c.user_id = cu.customer_id
 
 left join 
 
 (select user_id,sum(case when VISIT_TYPE=1 then 1 else 0 end)as pos_vist,
sum(case when VISIT_TYPE=2 then 1 else 0 end)as farmer_vist
 from ak_user_visit 
group by user_id)as d on d.user_id = cu.customer_id

left join 

(select emp.customer_id,emp.geo_id as dis_id,emp.GEO_LEVEL_ID,Name as District
from ak_customer_emp_map as emp
join ak_mst_geo_upper as mu on emp.geo_id = mu.sid
where emp.GEO_LEVEL_ID=4)as e on e.customer_id=cu.customer_id

left join 


(select emp.customer_id,emp.geo_id ,emp.GEO_LEVEL_ID,Name as Tehsil,ml.sid as t_id
from ak_customer_emp_map as emp
join ak_mst_geo_lower as ml on emp.geo_id = ml.sid
where  ml.type=1)as f on f.customer_id = cu.customer_id

group by cu.customer_id
)as aa
where 
aa.customer_id=ifnull(".$mo_id.",aa.customer_id) and aa.reg_date between ifnull(".$strt_date.",aa.reg_date) and ifnull(".$end_date.",aa.reg_date) and aa.dis_id='".$dist_id."' ";*/

		//echo $sql="( select cu.customer_id,cu.firstname,a.mst_farmer_cnt,b.retailer_cnt, c.vill_mtng_cnt,d.pos_vist,d.farmer_vist,e.District,e.dis_id,f.Tehsil,f.t_id,cu.reg_date from ak_customer as cu left join (select cr_by,count(cr_by)as mst_farmer_cnt from ak_mst_farmer group by cr_by)as a on a.cr_by = cu.customer_id left join (select cr_by,count(cr_by)as retailer_cnt from ak_mst_retailer group by cr_by)as b on b.cr_by = cu.customer_id left join (select user_id,count(*)as vill_mtng_cnt from ak_fgm_dtl)as c on c.user_id = cu.customer_id left join (select user_id,sum(case when VISIT_TYPE=1 then 1 else 0 end)as pos_vist, sum(case when VISIT_TYPE=2 then 1 else 0 end)as farmer_vist from ak_user_visit group by user_id)as d on d.user_id = cu.customer_id left join (select emp.customer_id,emp.geo_id as dis_id,emp.GEO_LEVEL_ID,Name as District from ak_customer_emp_map as emp join ak_mst_geo_upper as mu on emp.geo_id = mu.sid where emp.GEO_LEVEL_ID=4)as e on e.customer_id=cu.customer_id left join (select emp.customer_id,emp.geo_id ,emp.GEO_LEVEL_ID,Name as Tehsil,ml.sid as t_id from ak_customer_emp_map as emp join ak_mst_geo_lower as ml on emp.geo_id = ml.sid where ml.type=1)as f on f.customer_id = cu.customer_id group by cu.customer_id )";
        
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
                
$sql="select concat(akc.firstname,akc.lastname) as firstname,count(DISTINCT akf.SID) as mst_farmer_cnt,count(DISTINCT akr.SID) as retailer_cnt,count(DISTINCT c.USER_ID) as vill_mtng_cnt,ifnull (sum(case when d.VISIT_TYPE=1 then 1 else 0 end),0)as pos_vist,ifnull (sum(case when d.VISIT_TYPE=2 then 1 else 0 end),0) as farmer_vist  from ak_customer akc
left join ak_mst_farmer akf on akf.CR_BY=akc.customer_id
left join ak_mst_retailer akr on akr.CR_BY=akc.customer_id
left join ak_fgm_dtl as c on c.user_id =akc.customer_id
left join ak_user_visit  d on d.user_id = akc.customer_id
where akc.customer_id='".$mo_id."' and akf.CR_DATE BETWEEN $strt_date and $end_date and akf.DISTRICT_ID='".$dist_id."' and akf.TEHSIL_ID='".$teh_id."'";   


            /*$sql="select count(customer_id) as 'total'
from (

select cu.customer_id,cu.firstname,a.mst_farmer_cnt,b.retailer_cnt,
c.vill_mtng_cnt,d.pos_vist,d.farmer_vist,e.District,e.dis_id,f.Tehsil,f.t_id
 from ak_customer as cu

left join 
(select cr_by,count(cr_by)as mst_farmer_cnt from ak_mst_farmer 
group by cr_by)as a on a.cr_by = cu.customer_id

left join 

(select cr_by,count(cr_by)as retailer_cnt from ak_mst_retailer group by cr_by)as b
on b.cr_by = cu.customer_id

left join 

(select user_id,count(*)as vill_mtng_cnt from ak_fgm_dtl)as c
 on c.user_id = cu.customer_id
 
 left join 
 
 (select user_id,sum(case when VISIT_TYPE=1 then 1 else 0 end)as pos_vist,
sum(case when VISIT_TYPE=2 then 1 else 0 end)as farmer_vist
 from ak_user_visit 
group by user_id)as d on d.user_id = cu.customer_id

left join 

(select emp.customer_id,emp.geo_id as dis_id,emp.GEO_LEVEL_ID,Name as District
from ak_customer_emp_map as emp
join ak_mst_geo_upper as mu on emp.geo_id = mu.sid
where emp.GEO_LEVEL_ID=4)as e on e.customer_id=cu.customer_id

left join 


(select emp.customer_id,emp.geo_id ,emp.GEO_LEVEL_ID,Name as Tehsil,ml.sid as t_id
from ak_customer_emp_map as emp
join ak_mst_geo_lower as ml on emp.geo_id = ml.sid
where  ml.type=1)as f on f.customer_id = cu.customer_id

group by cu.customer_id
)as aa
where aa.customer_id=ifnull(".$mo_id.",aa.customer_id)
and aa.dis_id=ifnull(".$dist_id.",aa.dis_id)
and aa.t_id=ifnull(".$teh_id.",aa.t_id)";*/

 

		
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

}