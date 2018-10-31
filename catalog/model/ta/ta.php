<?php
class ModelTata extends Model {
public function tadata($data){
$log=new Log("ta.log");
$log->write($data);
$filerand1=rand();
$filerand2=rand();
$filerand3=rand();
$filerand4=rand();
//$tdate=date('Y-m-d', strtotime(data["TA_DATE"]));
$sql="INSERT INTO ak_daily_ta SET ";
$sql.="SID='".$data["SID"]."',TA_DATE='".$data["TA_DATE"]."',TA_EMP_ID='".$data["TA_EMP_ID"]."',PLACE_FROM='".$data["PLACE_FROM"]."'";

if($data["PLACE_TO"]!="")
{
$sql.=",PLACE_TO='".$data["PLACE_TO"]."'";
}
if($data["DAILY_DA"]!="")
{
$sql.=",DAILY_DA='".$data["DAILY_DA"]."'";
}
if($data["OPEN_MTR"]!="")
{
$sql.=",OPEN_MTR='".$data["OPEN_MTR"]."'";
}
if($data["CLOSE_MTR"]!="")
{
$sql.=",CLOSE_MTR='".$data["CLOSE_MTR"]."'";
}
if($data["TAX_RS"]!="")
{
$sql.=",TAX_RS='".$data["TAX_RS"]."'";
}
if($data["FARE_RS"]!="")
{
$sql.=",FARE_RS='".$data["FARE_RS"]."'";
}
if($data["PETROL_LTR"]!="")
{
$sql.=",PETROL_LTR='".$data["PETROL_LTR"]."'";
}

if($data["LOCAL_CONVEYANCE"]!="")
{
$sql.=",LOCAL_CONVEYANCE='".$data["LOCAL_CONVEYANCE"]."'";
}
if($data["HOTEL_RS"]!="")
{
$sql.=",HOTEL_RS='".$data["HOTEL_RS"]."'";
}
if($data["POSTAGE_RS"]!="")
{
$sql.=",POSTAGE_RS='".$data["POSTAGE_RS"]."'";
} if($data["PRINTING_RS"]!="")
{
$sql.=",PRINTING_RS='".$data["PRINTING_RS"]."'";
} if($data["MISC_RS"]!="")
{
$sql.=",MISC_RS='".$data["MISC_RS"]."'";
} 
 
if($data["REMARKS"]!="")
{
$sql.=",REMARKS='".$data["REMARKS"]."'";
} if($data["STATUS"]!="")
{
$sql.=",STATUS='".$data["STATUS"]."'";
} if($data["PLACE_TO1"]!="")
{
$sql.=",PLACE_TO1='".$data["PLACE_TO1"]."'";
} if($data["PLACE_TO2"]!="")
{
$sql.=",PLACE_TO2='".$data["PLACE_TO2"]."'";
} if($data["PLACE_TO3"]!="")
{
$sql.=",PLACE_TO3='".$data["PLACE_TO3"]."'";
} if($data["PLACE_TO4"]!="")
{
$sql.=",PLACE_TO4='".$data["PLACE_TO4"]."'";
}
$not_success="0";
if($this->request->files["UPLOAD_1"]["name"]!="")
{
$file1 = $filerand1.$this->request->files['UPLOAD_1']['name']; 
$log->write($file1);
$file11=move_uploaded_file($this->request->files['UPLOAD_1']['tmp_name'], DIR_UPLOAD . $file1);


if($file11)
{
$sql.=",UPLOAD_1='".$file1."'";

}
 else {
     if($not_success=="")
     {
       $not_success="1";
     }
 else {
     $not_success="&1";
 }
 $log->write('file 1 not uploded');
 }
 
}
if($this->request->files["UPLOAD_2"]["name"]!="")
{
$file2 = $filerand2.$this->request->files['UPLOAD_2']['name']; 
$log->write($file2);
$file22=move_uploaded_file($this->request->files['UPLOAD_2']['tmp_name'], DIR_UPLOAD . $file2); 
if($file22)
{
$sql.=",UPLOAD_2='".$file2."'";
}
else {
     if($not_success=="")
     {
       $not_success=$not_success."2";
     }
 else {
     $not_success=$not_success."&2";
 }
 $log->write('file 2 not uploded');
 }
}
if($this->request->files["UPLOAD_3"]["name"]!="")
{
$file3 = $filerand3.$this->request->files['UPLOAD_3']['name']; 
$log->write($file3);
$file33=move_uploaded_file($this->request->files['UPLOAD_3']['tmp_name'], DIR_UPLOAD . $file3);
if($file33)
{
$sql.=",UPLOAD_3='".$file3."'";
}
else {
     if($not_success=="")
     {
       $not_success=$not_success."3";
     }
 else {
     $not_success=$not_success."&3";
 }
 $log->write('file 3 not uploded');
 }
} 
if($this->request->files["UPLOAD_4"]["name"]!="")
{
$file4 = $filerand4.$this->request->files['UPLOAD_4']['name']; 
$log->write($file4);
$file44=move_uploaded_file($this->request->files['UPLOAD_4']['tmp_name'], DIR_UPLOAD . $file4);
if($file44)
{
$sql.=",UPLOAD_4='".$file4."'";
}
else {
     if($not_success=="")
     {
       $not_success=$not_success."4";
     }
 else {
     $not_success=$not_success."&4";
 }
 $log->write('file 4 not uploded');
 }
}
$log->write($not_success);
if($not_success=='0')
{


$log->write($sql);
$this->db->query($sql);
$ret_id = $this->db->countAffected();
return $ret_id.",".$not_success;
}
else{
    return "0";
}


}

public function prestatus($data){
    $log=new Log("pretastatus.log");
    $sql="select STATUS from ak_daily_ta where SID='".$data["SID"]."'";
    $log->write($sql);
    $query=$this->db->query($sql);
    $prests=$query->row['STATUS'];
    return $prests;
}

public function taupdate($data){

$filerand1=rand();
$filerand2=rand();
$filerand3=rand();
$filerand4=rand();
$log=new Log("taupdate.log");
 
 
if($data['STATUS']== 5)//Check if Ta update status leave or not
{
    $sql="UPDATE ak_daily_ta SET PLACE_FROM='NA',PLACE_TO='NA',OPEN_MTR='0',CLOSE_MTR='0',TAX_RS='0',FARE_RS='0',PETROL_LTR='0',
          LOCAL_CONVEYANCE='0',HOTEL_RS='0',POSTAGE_RS='0',PRINTING_RS='0',MISC_RS='0',UPLOAD_1='0',UPLOAD_2='0',
          UPLOAD_3='0',UPLOAD_4='0',REMARKS='0',STATUS='".$data["STATUS"]."',PLACE_TO1='0',PLACE_TO2='0',PLACE_TO3='0',PLACE_TO4='0',
          DAILY_DA='0' WHERE SID='".$data["SID"]."'";
}
 else {    // status not leave

            $sql="UPDATE ak_daily_ta SET ";
            if($data["PLACE_FROM"]!="")
            {
            $sql.="PLACE_FROM='".$data["PLACE_FROM"]."'";
            }

            if($data["PLACE_TO"]!="")
            {
            $sql.=",PLACE_TO='".$data["PLACE_TO"]."'";
            }
            if($data["OPEN_MTR"]!="")
            {
            $sql.=",OPEN_MTR='".$data["OPEN_MTR"]."'";
            }
            if($data["CLOSE_MTR"]!="")
            {
            $sql.=",CLOSE_MTR='".$data["CLOSE_MTR"]."'";
            }
            if($data["TAX_RS"]!="")
            {
            $sql.=",TAX_RS='".$data["TAX_RS"]."'";
            }
            if($data["FARE_RS"]!="")
            {
            $sql.=",FARE_RS='".$data["FARE_RS"]."'";
            }
            if($data["PETROL_LTR"]!="")
            {
            $sql.=",PETROL_LTR='".$data["PETROL_LTR"]."'";
            }
            if($data["LOCAL_CONVEYANCE"]!="")
            {
            $sql.=",LOCAL_CONVEYANCE='".$data["LOCAL_CONVEYANCE"]."'";
            }
            if($data["HOTEL_RS"]!="")
            {
            $sql.=",HOTEL_RS='".$data["HOTEL_RS"]."'";
            }
            if($data["POSTAGE_RS"]!="")
            {
            $sql.=",POSTAGE_RS='".$data["POSTAGE_RS"]."'";
            } if($data["PRINTING_RS"]!="")
            {
            $sql.=",PRINTING_RS='".$data["PRINTING_RS"]."'";
            } if($data["MISC_RS"]!="")
            {
            $sql.=",MISC_RS='".$data["MISC_RS"]."'";
            }if($this->request->files["UPLOAD_1"]["name"]!="")
            {
            $file1 = $filerand1.$this->request->files['UPLOAD_1']['name']; 
            move_uploaded_file($this->request->files['UPLOAD_1']['tmp_name'], DIR_UPLOAD . $file1);
            $sql.=",UPLOAD_1='".$file1."'";
            }
            if($this->request->files["UPLOAD_2"]["name"]!="")
            {
            $file2 = $filerand2.$this->request->files['UPLOAD_2']['name']; 
            move_uploaded_file($this->request->files['UPLOAD_2']['tmp_name'], DIR_UPLOAD . $file2); 
            $sql.=",UPLOAD_2='".$file2."'";
            }
            if($this->request->files["UPLOAD_3"]["name"]!="")
            {
            $file3 = $filerand3.$this->request->files['UPLOAD_3']['name']; 
            move_uploaded_file($this->request->files['UPLOAD_3']['tmp_name'], DIR_UPLOAD . $file3);
            $sql.=",UPLOAD_3='".$file3."'";
            } if($this->request->files["UPLOAD_4"]["name"]!="")
            {
            $file4 = $filerand4.$this->request->files['UPLOAD_4']['name']; 
            move_uploaded_file($this->request->files['UPLOAD_4']['tmp_name'], DIR_UPLOAD . $file4);
            $sql.=",UPLOAD_4='".$file4."'";
            } if($data["REMARKS"]!="")
            {
            $sql.=",REMARKS='".$data["REMARKS"]."'";
            } if($data["STATUS"]!="")
            {
            $sql.=",STATUS='".$data["STATUS"]."'";
            } if($data["PLACE_TO1"]!="")
            {
            $sql.=",PLACE_TO1='".$data["PLACE_TO1"]."'";
            } if($data["PLACE_TO2"]!="")
            {
            $sql.=",PLACE_TO2='".$data["PLACE_TO2"]."'";
            } if($data["PLACE_TO3"]!="")
            {
            $sql.=",PLACE_TO3='".$data["PLACE_TO3"]."'";
            } if($data["PLACE_TO4"]!="")
            {
            $sql.=",PLACE_TO4='".$data["PLACE_TO4"]."'";
            }
            if($data["DAILY_DA"]!="")
            {
            $sql.=",DAILY_DA='".$data["DAILY_DA"]."'";
            }
            if($data["STATUS"]!="")
            {
            $sql.=",STATUS='".$data["STATUS"]."'";
            }
            $sql.=" WHERE SID='".$data["SID"]."'";
 }
// echo $sql;
$log->write($sql);
$this->db->query($sql);
$ret_id = $this->db->countAffected();
return $ret_id;
}





function tadataempid($data){
$log=new Log("tadataempid.log");
$sql=" SELECT SID,TA_DATE,TA_EMP_ID,PLACE_FROM,PLACE_TO,OPEN_MTR,CLOSE_MTR,TAX_RS,FARE_RS,PETROL_LTR,LOCAL_CONVEYANCE,HOTEL_RS,POSTAGE_RS,PRINTING_RS,MISC_RS,UPLOAD_1,UPLOAD_2,UPLOAD_3,UPLOAD_4,REMARKS,STATUS,PLACE_TO1,PLACE_TO2,PLACE_TO3,PLACE_TO4 FROM ak_daily_ta where TA_EMP_ID='".$data["CR_BY"]."'";
$log->write($sql);
$query = $this->db->query($sql);
return $query->rows; 
}

    function searchtadata($data){
    $log=new Log("searchtadata.log");
    $sql="SELECT 
        adt.SID,adt.TA_DATE,adt.TA_EMP_ID,adt.PLACE_FROM,adt.PLACE_TO,adt.OPEN_MTR,adt.CLOSE_MTR,
        adt.TAX_RS,adt.FARE_RS,adt.PETROL_LTR,adt.LOCAL_CONVEYANCE,adt.HOTEL_RS,adt.POSTAGE_RS,
        adt.PRINTING_RS,adt.MISC_RS,adt.UPLOAD_1,adt.UPLOAD_2,adt.UPLOAD_3,adt.UPLOAD_4,adt.REMARKS,
        adt.`STATUS`,adt.PLACE_TO1,adt.PLACE_TO2,adt.PLACE_TO3,
        adt.PLACE_TO4,adt.DAILY_DA,adt.POST_DATE,
        (adt.TAX_RS+adt.FARE_RS+adt.PETROL_LTR+adt.LOCAL_CONVEYANCE+adt.HOTEL_RS+adt.POSTAGE_RS+adt.PRINTING_RS+adt.MISC_RS+adt.DAILY_DA) AS TOTAL,
        akc.local_allow,akc.outstation_allow,
        IFNULL((CASE WHEN adt.`STATUS`=1 AND (adt.CLOSE_MTR-adt.OPEN_MTR)>40 THEN akc.local_allow
                    WHEN adt.`STATUS`=2 THEN akc.local_allow
                    WHEN adt.`STATUS`=3 THEN akc.outstation_allow
                    WHEN adt.`STATUS`=4 THEN akc.outstation_allow
        END
        ),0) AS 'CONV',
        ROUND((CASE WHEN (adt.CLOSE_MTR-adt.OPEN_MTR)>0 THEN (adt.CLOSE_MTR-adt.OPEN_MTR) * akc.vehicle_allow ELSE 0 END),2) AS 'MOT_CONV'
        FROM ak_daily_ta adt
        LEFT JOIN ak_customer akc ON(adt.TA_EMP_ID=akc.customer_id)
        WHERE TA_EMP_ID='".$data["CR_BY"]."'";
    
    
    
    /*$sql="SELECT SID,TA_DATE,TA_EMP_ID,PLACE_FROM,PLACE_TO,OPEN_MTR,CLOSE_MTR,TAX_RS,FARE_RS,PETROL_LTR,LOCAL_CONVEYANCE,HOTEL_RS,
    POSTAGE_RS,PRINTING_RS,MISC_RS,UPLOAD_1,UPLOAD_2,UPLOAD_3,UPLOAD_4,REMARKS,STATUS,PLACE_TO1,PLACE_TO2,PLACE_TO3,PLACE_TO4,DAILY_DA,POST_DATE,
    (TAX_RS+FARE_RS+PETROL_LTR+LOCAL_CONVEYANCE+HOTEL_RS+POSTAGE_RS+PRINTING_RS+MISC_RS+DAILY_DA) AS TOTAL FROM ak_daily_ta where TA_EMP_ID='".$data["CR_BY"]."'";*/
    if($data["TO_DATE"]!=0)
    {
    $sql.="and DATE(TA_DATE) between '".$data["FROM_DATE"]."' and '".$data["TO_DATE"]."'"; 
    $sql.= "ORDER BY TA_DATE DESC";
    }
    else
    {
    $sql.= "ORDER BY TA_DATE DESC LIMIT 10";
    }
    $log->write($sql);
    $query = $this->db->query($sql);
    return $query->rows; 
    }


    function checktadata($data)
    {
        $log=new Log("checktadata.log");
        $sql="SELECT TA_EMP_ID FROM ak_daily_ta where DATE(TA_DATE)='".$data["TA_DATE"]."' and TA_EMP_ID='".$data["TA_EMP_ID"]."'";
        $log->write($sql);
        $query = $this->db->query($sql);
        $taid= $query->row["TA_EMP_ID"]; 
        if(!empty($taid))
        {
            return '1';
        }
        else{
            return '0';
        }
    } 
    function taleavedata($data)
    {
        $log=new Log("leavetadata.log");
        $sid=$data['SID'];
        $tadate=$data['TA_DATE'];   
        $taempid=$data['TA_EMP_ID'];
        $status=$data['STATUS'];
        
        $leavesql="INSERT INTO ak_daily_ta 
            SET
            SID='".$sid."',
            TA_DATE='".$tadate."',
            TA_EMP_ID='".$taempid."',
            PLACE_FROM='NA',
            PLACE_TO='NA',
            DAILY_DA='0',
            OPEN_MTR='0',
            CLOSE_MTR='0',
            PETROL_LTR='0',
            HOTEL_RS='0',
            STATUS='".$status."'";
        $log->write($leavesql);
        $this->db->query($leavesql);
        $ret_id = $this->db->countAffected();
        if($ret_id)
        {
            return 1;
        }
        else {
            return 0;
            
        }
    }
	
	function allemployeetadtl($data){
$log=new Log("allemployeetadtl.log");


    $sql="SELECT
	ab.customer_id      AS 'CUST_ID',
	ab.month_name       AS 'MON_YR',
	ab.budget_km        AS 'TOT_KM',
	ab.add_budget_km    AS 'ADD_KM',
	IFNULL(SUM((adt.close_mtr-adt.open_mtr) ),0) AS 'USE_KM', 
        IFNULL(((ab.budget_km+ab.add_budget_km)-SUM((adt.close_mtr-adt.open_mtr) )),0) AS 'PEN_KM', 
        DATE_FORMAT(adt.ta_date,'%M%Y') AS 'YR_MON',
	akc.local_allow AS 'LOC_ALLOW',
	akc.outstation_allow AS 'OUT_ALLOW',
	akc.vehicle_allow AS 'MOT_ALLOW',
	akc.hotel_allow AS 'HOT_ALLOW'

        FROM ak_budget ab
	LEFT JOIN ak_daily_ta adt ON (adt.ta_emp_id=ab.customer_id AND date_format(adt.ta_date,'%M%Y')=ab.month_name)
	LEFT JOIN ak_customer akc ON (adt.ta_emp_id=akc.customer_id)
	WHERE ta_emp_id='".$data["USER_ID"]."' AND date_format(adt.ta_date,'%Y-%m')=date_format(curdate(),'%Y-%m')
	GROUP BY YR_MON, adt.ta_emp_id, ab.month_name";

/*
    $sql="select ab.customer_id, ab.month_name, ab.budget_km, ifnull(sum((adt.close_mtr-adt.open_mtr) ),0), ifnull((ab.budget_km-sum((adt.close_mtr-adt.open_mtr) )),0) as Total_km, date_format(adt.ta_date,'%M%Y') year_month1

    from ak_budget ab

    left join ak_daily_ta adt on adt.ta_emp_id=ab.customer_id and date_format(adt.ta_date,'%M%Y')=ab.month_name

    where ta_emp_id=".$data["USER_ID"]." and date_format(adt.ta_date,'%Y-%m')=date_format(curdate(),'%Y-%m')

    group by year_month1, adt.ta_emp_id, ab.month_name";*/

$log->write($sql);
$query = $this->db->query($sql);
return $query->row; 
}

	

}
