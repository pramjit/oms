<?php
class ModelReportTabillreport2 extends Model {
	
    public function tadata($data){
        $log=new Log("Monthlytadata".date('Y-m-d').".log");
        
        $log->write($data);
        //if($month_id<10){$month_id="0".$month_id;}
        $Y=date('Y');
        $sd=$Y."-".$data['month_id']."-01";
        $ed=$Y."-".$data['month_id']."-31";
        $sql="
select cus.customer_id,cus.vehicle_allow,cus.customer_group_id,concat(cus.firstname,' ',cus.lastname)as customer_name,
b.Running_km,c.sum_of_fare,c.sum_of_local_convences,c.sum_of_hotal_rs,c.sum_of_daily_da,
c.sum_of_tax_ta,c.sum_of_printing,c.sum_of_postage,c.sum_of_misc,
d.GEO_ID,group_concat(upr.NAME) as District_Name

from ak_customer as cus
left join 

(select mnth,(sum_of_close_mtr-sum_of_open_mtr)as Running_km,TA_EMP_ID from (
SELECT month(ta.TA_DATE)as mnth,sum(ta.OPEN_MTR)as sum_of_open_mtr,
sum(ta.CLOSE_MTR)as sum_of_close_mtr,ta.TA_EMP_ID  FROM ak_daily_ta as ta

group by TA_EMP_ID,month(ta.TA_DATE)
)as a where a.mnth='".$data['month_id']."' and TA_EMP_ID='".$data['mo_id']."')as b on b.TA_EMP_ID = cus.customer_id

left join 

(SELECT month(TA_DATE)as mnth,TA_EMP_ID  as id,sum(FARE_RS)as sum_of_fare,
sum(LOCAL_CONVEYANCE)as sum_of_local_convences,sum(HOTEL_RS)as sum_of_hotal_rs,
sum(DAILY_DA)as sum_of_daily_da,sum(TAX_RS)as sum_of_tax_ta,sum(printing_rs) as sum_of_printing,sum(postage_rs) as sum_of_postage,sum(misc_rs) as sum_of_misc,
TA_EMP_ID
 FROM ak_daily_ta
 
 where month(TA_DATE)=5 and TA_EMP_ID='".$data['mo_id']."'
 group by month(TA_DATE),TA_EMP_ID)as c on c.id = cus.customer_id
 left join 
 
 (select CUSTOMER_ID,GEO_ID from ak_customer_emp_map where GEO_LEVEL_ID=4 )as d
 on d.CUSTOMER_ID = cus.customer_id
 
 left join ak_mst_geo_upper as upr on upr.SID = d.GEO_ID

where cus.customer_id='".$data['mo_id']."'
group by cus.customer_id



";
                $query = $this->db->query($sql);
                $log->write($sql);
        // echo $sql;
        return $query->rows; 
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