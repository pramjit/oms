<?php
class ModelReportTabillreport extends Model {
	
    public function tadata($getEmpId,$getMonth){
        $log=new Log("Monthlytadata".date('Y-m-d').".log");
       
        $sql="SELECT 
        AK.customer_id AS 'EMP_ID',
        month_name AS 'EMP_MONTH', 
        IFNULL((budget_km+add_budget_km),0.00) AS 'EMP_ALW_KM',
        CONCAT(AC.firstname,' ',AC.lastname) AS 'EMP_NAME',
        GP.`name` AS 'EMP_GRP',
        MP.GEO_NAME,
        AC.vehicle_allow AS 'EMP_VEH_ALW'  
        FROM ak_budget AK
        JOIN ak_customer AC ON(AC.customer_id=AK.customer_id)
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
        WHERE AK.customer_id='".$getEmpId."'  AND `month_name` LIKE '%".$getMonth."%';";
        $query = $this->db->query($sql);
        $log->write($sql);
       //echo $sql;
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