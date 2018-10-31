<?php
class ModelReportTabillreport3 extends Model {
	
    public function tadata($data){
        $log=new Log("Monthlytadata".date('Y-m-d').".log");
        $Y=date('Y');
        $sd=$Y."-".$data['month_id']."-01";
        $ed=$Y."-".$data['month_id']."-31";
        $sql="SELECT 
                DATE(dt.TA_DATE) AS tadate,
                dt.TA_EMP_ID,
                dt.PLACE_FROM,
                dt.PLACE_TO,
                dt.OPEN_MTR,
                dt.CLOSE_MTR,
                dt.TAX_RS,
                dt.FARE_RS,
                dt.PETROL_LTR,
                dt.LOCAL_CONVEYANCE,
                dt.HOTEL_RS,
                dt.POSTAGE_RS,
                dt.PRINTING_RS,
                dt.MISC_RS,
                dt.UPLOAD_1,
                dt.UPLOAD_2,
                dt.UPLOAD_3,
                dt.UPLOAD_4,
                dt.REMARKS,
                dt.STATUS,
                dt.PLACE_TO1,
                dt.PLACE_TO2,
                dt.PLACE_TO3,
                dt.PLACE_TO4,
                dt.DAILY_DA,
                dt.PRINTING_RS,
                ac.firstname,
                ac.customer_group_id
                FROM ak_daily_ta as dt
                left join ak_customer as ac on ac.customer_id=dt.TA_EMP_ID
                WHERE DATE(TA_DATE) BETWEEN '".$sd."' AND '".$ed."' and TA_EMP_ID='".$data['mo_id']."' order by dt.TA_DATE";
                $query = $this->db->query($sql);
                $log->write($sql);
        // echo $sql;
        return $query->rows; 
    }
    public function empDtls($custId){
        $sql="SELECT 
        EM.CUSTOMER_ID,
        EM.EMP_NAME,
        EM.EMP_GRP,
        GROUP_CONCAT(EM.GEO_ID) AS 'GEO_ID',
        GROUP_CONCAT(EM.GEO_NAME) AS 'GEO_NAME'
        FROM(
            SELECT MAP.CUSTOMER_ID,CONCAT(AC.firstname,' ',AC.lastname) AS 'EMP_NAME',GP.`name` AS 'EMP_GRP',MAP.GEO_ID,GEO.`NAME` AS 'GEO_NAME' 
            FROM ak_customer_emp_map MAP
            JOIN ak_mst_geo_upper GEO ON(GEO.SID=MAP.GEO_ID)
            JOIN ak_customer AC ON(AC.customer_id=MAP.CUSTOMER_ID)
            JOIN ak_customer_group GP ON(GP.customer_group_id=AC.customer_group_id)
            WHERE MAP.GEO_LEVEL_ID=4 
            ORDER BY MAP.CUSTOMER_ID
	)EM  WHERE EM.CUSTOMER_ID=".$custId." GROUP BY EM.CUSTOMER_ID";
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