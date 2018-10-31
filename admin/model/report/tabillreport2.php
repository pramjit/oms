<?php
class ModelReportTabillreport2 extends Model {
	
    public function tadata($data){
        $log=new Log("Monthlytadata".date('Y-m-d').".log");
        $Y=date('Y');
        $sd="'".$Y."-".$data['month_id']."-01'";
        $ed="'".$Y."-".$data['month_id']."-31'";
        $cusId=$data['mo_id'];
        /*$sql="
select cus.customer_id,cus.vehicle_allow,cus.customer_group_id,concat(cus.firstname,' ',cus.lastname)as customer_name,
b.Running_km,c.sum_of_fare,c.sum_of_local_convences,c.sum_of_hotal_rs,c.sum_of_daily_da,
c.sum_of_tax_ta,c.sum_of_printing,c.sum_of_postage,c.sum_of_misc,
d.GEO_ID,group_concat(upr.NAME) as District_Name

from ak_customer as cus
left join 

(select (sum_of_close_mtr-sum_of_open_mtr)as Running_km,TA_EMP_ID from (
SELECT ta.TA_DATE,sum(ta.OPEN_MTR)as sum_of_open_mtr,
sum(ta.CLOSE_MTR)as sum_of_close_mtr,ta.TA_EMP_ID  FROM ak_daily_ta as ta

group by TA_EMP_ID,month(ta.TA_DATE)
)as a where DATE(a.TA_DATE) BETWEEN $sd AND $ed and TA_EMP_ID='".$data['mo_id']."')as b on b.TA_EMP_ID = cus.customer_id

left join 

(SELECT month(TA_DATE)as mnth,TA_EMP_ID  as id,sum(FARE_RS)as sum_of_fare,
sum(LOCAL_CONVEYANCE)as sum_of_local_convences,sum(HOTEL_RS)as sum_of_hotal_rs,
sum(DAILY_DA)as sum_of_daily_da,sum(TAX_RS)as sum_of_tax_ta,sum(printing_rs) as sum_of_printing,sum(postage_rs) as sum_of_postage,sum(misc_rs) as sum_of_misc,
TA_EMP_ID
 FROM ak_daily_ta
 
 where DATE(TA_DATE) BETWEEN $sd AND $ed and TA_EMP_ID='".$data['mo_id']."'
 group by month(TA_DATE),TA_EMP_ID)as c on c.id = cus.customer_id
 left join 
 
 (select CUSTOMER_ID,GEO_ID from ak_customer_emp_map where GEO_LEVEL_ID=4 )as d
 on d.CUSTOMER_ID = cus.customer_id
 
 left join ak_mst_geo_upper as upr on upr.SID = d.GEO_ID

where cus.customer_id='".$data['mo_id']."'
group by cus.customer_id";
         * *
         */
$sql="SELECT 
TA.TA_EMP_ID AS 'EMP_ID',
CONCAT(AC.firstname,' ',AC.lastname) AS 'EMP_NAME',
GP.`name` AS 'EMP_GRP',
MP.GEO_ID,
MP.GEO_NAME,
AC.vehicle_allow AS 'EMP_VEH_ALW', 
IFNULL(SUM(TA.OPEN_MTR),0) AS 'OPEN_MTR',
IFNULL(SUM(TA.CLOSE_MTR),0) AS 'CLOSE_MTR',
IFNULL(SUM(TA.TAX_RS),0) AS 'TAX_RS',
IFNULL(SUM(TA.FARE_RS),0) AS 'FARE_RS',
IFNULL(SUM(TA.PETROL_LTR),0) AS 'PETROL_LTR',
IFNULL(SUM(TA.LOCAL_CONVEYANCE),0) AS 'LOCAL_CONVEYANCE',
IFNULL(SUM(TA.HOTEL_RS),0) AS 'HOTEL_RS',
IFNULL(SUM(TA.POSTAGE_RS),0) AS 'POSTAGE_RS',
IFNULL(SUM(TA.PRINTING_RS),0) AS 'PRINTING_RS',
IFNULL(SUM(TA.MISC_RS),0) AS 'MISC_RS',
IFNULL(SUM(TA.DAILY_DA),0) AS 'DAILY_DA'

FROM ak_daily_ta  TA 
JOIN ak_customer AC ON(AC.customer_id=TA.TA_EMP_ID)
JOIN (
			SELECT EM.CUSTOMER_ID,GROUP_CONCAT(EM.GEO_ID) AS 'GEO_ID',GROUP_CONCAT(EM.GEO_NAME) AS 'GEO_NAME'
			FROM(
			SELECT MAP.CUSTOMER_ID,MAP.GEO_ID,GEO.`NAME` AS 'GEO_NAME' 
			FROM ak_customer_emp_map MAP
			JOIN ak_mst_geo_upper GEO ON(GEO.SID=MAP.GEO_ID)
			WHERE MAP.GEO_LEVEL_ID=4 
			ORDER BY MAP.CUSTOMER_ID) EM GROUP BY EM.CUSTOMER_ID
		)AS MP ON (MP.CUSTOMER_ID = AC.customer_id) 
JOIN ak_customer_group GP ON(GP.customer_group_id=AC.customer_group_id)
WHERE DATE(TA.TA_DATE) BETWEEN $sd AND $ed AND AC.customer_id=$cusId
GROUP BY TA.TA_EMP_ID";
$log->write($sql);
//echo $sql;
$query = $this->db->query($sql);


return $query->row; 
}
    public function userinfo($param) {
        $sql="select concat(oc.firstname,' ',oc.lastname)as 'NAME', og.name as 'GR_NAME' 
        from oc_user oc
        left join oc_user_group og on(oc.user_group_id = og.user_group_id)
        where oc.user_id = '".$param."'";
        $query = $this->db->query($sql);
        //$log->write($sql);
        //echo $sql;
        return $query->row; 
    }
     public function getMo(){
         
          $query = $this->db->query("select firstname,customer_id from ak_customer where customer_group_id!=10");//where PARENT_USER_ID='12'
          return $query->rows;  
    }

}