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
$sql="INSERT INTO ak_daily_ta SET SID='".$data["SID"]."',TA_DATE='".$data["TA_DATE"]."',TA_EMP_ID='".$data["TA_EMP_ID"]."',PLACE_FROM='".$data["PLACE_FROM"]."'";
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
if($this->request->files["UPLOAD_1"]["name"]!="")
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
$log->write($sql);
$this->db->query($sql);
$ret_id = $this->db->countAffected();
return $ret_id;
}

public function taupdate($data){

$filerand1=rand();
$filerand2=rand();
$filerand3=rand();
$filerand4=rand();

$log=new Log("taupdate.log");

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
$sql.=" WHERE SID='".$data["SID"]."'";
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
$sql="SELECT SID,TA_DATE,TA_EMP_ID,PLACE_FROM,PLACE_TO,OPEN_MTR,CLOSE_MTR,TAX_RS,FARE_RS,PETROL_LTR,LOCAL_CONVEYANCE,HOTEL_RS,
POSTAGE_RS,PRINTING_RS,MISC_RS,UPLOAD_1,UPLOAD_2,UPLOAD_3,UPLOAD_4,REMARKS,STATUS,PLACE_TO1,PLACE_TO2,PLACE_TO3,PLACE_TO4,DAILY_DA,POST_DATE,
(TAX_RS+FARE_RS+PETROL_LTR+LOCAL_CONVEYANCE+HOTEL_RS+POSTAGE_RS+PRINTING_RS+MISC_RS+DAILY_DA) AS TOTAL FROM ak_daily_ta where TA_EMP_ID='".$data["CR_BY"]."'";
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

}
