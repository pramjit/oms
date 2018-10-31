<?php
class ModelReportStaffTaSubmission extends Model {
    public function ChkTaAvl($fromDate,$toDate){
        $sql="SELECT * FROM ak_daily_ta WHERE TA_DATE BETWEEN '".$fromDate."' AND '".$toDate."'";
        $query = $this->db->query($sql);
        return $query->rows;
    }
    public function empList(){
        $sql="SELECT 
        AK.customer_id AS 'EMP_ID',
        CONCAT(AK.firstname,'',AK.lastname) AS 'EMP_NAME',
        AK.telephone AS 'EMP_MOB',
        CG.description AS 'EMP_ROLE' 
        FROM ak_customer AK 
        JOIN ak_customer_group CG ON(CG.customer_group_id=AK.customer_group_id)
        WHERE AK.customer_group_id IN(3,4) 
        ORDER BY AK.customer_group_id,AK.firstname";
        $query = $this->db->query($sql);
        return $query->rows;
    } 
    public function daList($fromDate,$toDate){
        $sql="SELECT 
        DATE_FORMAT(TD.TODAY,'%d') AS 'XD'
        FROM 
        (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) TODAY FROM
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) TD
        WHERE TD.TODAY BETWEEN '".$fromDate."' AND '".$toDate."'";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function empTaData($empId,$fromDate,$toDate){
        $sql="SELECT 
        TD.TODAY,
        DA.TA_DATE, 
        DA.TA_EMP_ID,
        DA.TA_EMP_NAME,
        DA.TA_EMP_MOB,
        (CASE WHEN DA.PLACE_FROM<>'NA' THEN 'P' WHEN DA.PLACE_FROM='NA' THEN 'L' ELSE 'A' END) AS 'STS'
        FROM 
        (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) TODAY FROM
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
        (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) TD
        LEFT JOIN(
                SELECT TA.TA_EMP_ID,CONCAT(AK.firstname,'',AK.lastname) AS 'TA_EMP_NAME',AK.telephone AS 'TA_EMP_MOB',TA.TA_DATE,TA.PLACE_FROM 
                FROM ak_daily_ta TA
                JOIN ak_customer AK ON(AK.customer_id=TA.TA_EMP_ID)
                WHERE TA.TA_EMP_ID=$empId
                GROUP BY TA.TA_EMP_ID,TA.TA_DATE ORDER BY TA.TA_EMP_ID,TA.TA_DATE
                ) DA ON(DA.TA_DATE=TD.TODAY)
        WHERE TD.TODAY BETWEEN '".$fromDate."' AND '".$toDate."'";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    






    public function getdailysummary($data = array()) {	  				

		
               $month=$this->request->get['gmonth_id'];
             
             
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
where  ak.customer_group_id in(3,4)
";
            $sql.="and ak.status=1 and month(ta.TA_DATE)='$month'";
$sql.="order by ak.customer_group_id,ak.firstname asc)as a
group by customer_id order by Emp_Name asc";
            

              //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

    
    
/**********************************Download eexcel Query******/
          public function getdownloadexcel($data = array()) {	
              

               $month=$this->request->get['gmonth_id'];
             
             
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
";
            $sql.="and ak.status=1 and month(ta.TA_DATE)='$month'";
$sql.="order by ak.customer_group_id asc)as a
group by customer_id";
            
           
                //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
        
        
   /*******************************************************************/     


}