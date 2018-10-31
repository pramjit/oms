<?php

class ModelsyncsyncDealer extends Model {
  
    
    private $file_id;
    
    public function getId() {
		return $this->file_id;
	}
      

        public  function SyncFunction($id)
        
{


    
  

  try {
    /**************************************
    * Create databases and                *
    * open connections                    *
    **************************************/
 
    // Create (connect to) SQLite database in file
  $log=new Log("er.log");
    $sqdb=DIR_DOWNLOAD.'db.sqlite';
    $file_db = new PDO('sqlite:'.$sqdb);
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);
 
    /**************************************
    * Create tables                       *
    **************************************/
 $log->write("error");
    // Create table messages   
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "customer");
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "farmer");
   
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "geo"); 
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "product"); 
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "village");
    
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "retailer_type");
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "points");
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "can_lms_qnty_transaction");
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "customer_stock");
    $file_db->exec("DROP TABLE IF EXISTS " . DB_PREFIX . "issue_master");
    
   
    
    
   
    
    
    
    
    $log->write("error1");
 $file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customer (
    
customer_id	TEXT,
customer_group_id	TEXT,
User_Id         TEXT,
firstname	TEXT,
lastname	TEXT,
district_id 	TEXT,
statusl TEXT
   

    )");
   
    
$file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "farmer (
    
SID	TEXT,
FARMER_NAME	TEXT,
FAR_MOBILE	TEXT,
CR_DATE	TEXT,
CR_BY	TEXT,
VILL_ID	TEXT,
DIST_ID	TEXT,
CAN_MLK_ID	TEXT,
FGM_ID	TEXT,
KEY_FARMER	TEXT,
MILKING_COWS_CNT	TEXT,
TOTAL_COWS	TEXT,
CURR_SUPPILER	TEXT,
DAILY_MILK_PROD	TEXT,
REMARKS	TEXT,
LAST_VISIT_ID	TEXT,
CAR_ID	TEXT,
FARMER_STATUS TEXT,
APP_TRX_ID	TEXT,
LATT	TEXT,
LONGG	TEXT,
FAR_SEGMENT	TEXT,
FAR_POSSITION 	TEXT,
FAR_CATEGORY	TEXT,
IMAGE 	TEXT,
statusl TEXT
   

    )");

 
$log->write("error2");


$file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "geo (
    
SID	TEXT,
GEO_NAME	TEXT,
GEO_TYPE	TEXT,
Nation_ID	TEXT,
STATE_ID	TEXT,
ACT	TEXT,
statusl TEXT
    
)");
$log->write("error3");

$file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product (
    
SID	TEXT,
PRODUCT_NAME	TEXT,
PRODUCT_CATEGORY  TEXT,
ACT	TEXT,
PRODUCT_IMAGE  TEXT,
statusl TEXT    
)");
$log->write("error4");



$file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "village (
    
SID	TEXT,
VILLAGE_NAME	TEXT,
VILLAGE_PIN_CODE	TEXT,
STATE_ID	TEXT,
TERRITORY_ID	TEXT,
DISTRICT_ID	TEXT,
HQ_ID	TEXT,
ACT	TEXT,
statusl TEXT
)");



$file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "retailer_type (
    
SID	TEXT,
RETAILER_TYPE	TEXT,
ACT	TEXT,
statusl TEXT
)");

$file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "points (
    
SID	TEXT,
PRODUCT_ID 	TEXT,
CONVERSION_FACTOR	TEXT,
QUANTITY 	TEXT,
START_DATE 	TEXT,
END_DATE	TEXT,
statusl TEXT
)");

 $file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customer_stock (
    
SID	TEXT,
CUSTOMER_ID 	TEXT,
PRODUCT_ID         TEXT,
QUANTITY	TEXT,
LAST_MODIFY	TEXT,
statusl TEXT
   

    )");
 $file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "issue_master (
    
SID	TEXT,
ISSUE_NAME 	TEXT,
ACT	TEXT,
statusl TEXT
)");
 
/*
$file_db->exec("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "can_lms_qnty_transaction (
    
SID	TEXT,
USER_ID 	TEXT,
TRANSACTION_TYPE	TEXT,
PRODUCT_ID 	TEXT,
QTY 	TEXT,
MULTIPLICATION_FACTOR	TEXT,
POINTS 	TEXT,
CR_DATE 	TEXT,
TRANSFERRED_BY	TEXT,
TRANSFER_TYPE 	TEXT,
LAT	TEXT,
LONGG 	TEXT,
TRANSACTION_ID 	TEXT,
statusl TEXT
   

    )");

*/

$mcrt=new MCrypt();

//save customer
    
   
$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "customer WHERE  status <>'11' and customer_group_id in (64,65,67,68) order by firstname asc");
$data=$query->rows; 
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "customer (customer_id,customer_group_id,User_Id,firstname,lastname,district_id,statusl) values('".$mcrt->encrypt($value["customer_id"])."','".$mcrt->encrypt($value["customer_group_id"])."','".$mcrt->encrypt($value["User_Id"])."','".$mcrt->encrypt($value["firstname"])."','".$mcrt->encrypt($value["lastname"])."','".$mcrt->encrypt($value["district_id"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}
//end customer

//save data geo

//state
$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "geo WHERE  ACT ='1' and SID in (SELECT GEO_ID  from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='2'  ) order by GEO_NAME asc");
$data=$query->rows; 
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "geo (SID,GEO_NAME,GEO_TYPE,Nation_ID,STATE_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["GEO_NAME"])."','".$mcrt->encrypt($value["GEO_TYPE"])."','".$mcrt->encrypt($value["Nation_ID"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}


//dist
$role_id = $this->db->query("SELECT customer_group_id  FROM " . DB_PREFIX . "customer where customer_id = '".$id."' ");
$role_id1=$role_id->row["customer_group_id"];
if($role_id1=='49' || $role_id1=='60' || $role_id1=='61') {
$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "geo WHERE  ACT ='1' and SID in (SELECT GEO_ID  from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='4'  ) order by GEO_NAME asc");
$data=$query->rows; 
} else if($role_id1=='47'){
    $query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "geo WHERE  ACT ='1' and SID in (SELECT GEO_ID  from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='4'  ) order by GEO_NAME asc");
    $data=$query->rows; 
    
} else {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo where geo_type=4 and state_id In(SELECT GEO_ID  from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='2')");
    $data=$query->rows; 
}
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "geo (SID,GEO_NAME,GEO_TYPE,Nation_ID,STATE_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["GEO_NAME"])."','".$mcrt->encrypt($value["GEO_TYPE"])."','".$mcrt->encrypt($value["Nation_ID"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}

//hq
$role_id = $this->db->query("SELECT customer_group_id  FROM " . DB_PREFIX . "customer where customer_id = '".$id."' ");
$role_id1=$role_id->row["customer_group_id"];
if($role_id1=='49' || $role_id1=='60' || $role_id1=='61') {
$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "geo WHERE  ACT ='1' and SID in (SELECT GEO_ID  from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='5' ) order by GEO_NAME asc");
$data=$query->rows; 
} else {
    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "geo` where geo_type=5 and state_id In(SELECT GEO_ID  from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='2')");
    $data=$query->rows; 
}
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "geo (SID,GEO_NAME,GEO_TYPE,Nation_ID,STATE_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["GEO_NAME"])."','".$mcrt->encrypt($value["GEO_TYPE"])."','".$mcrt->encrypt($value["Nation_ID"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}



//end save geo


//save data product
$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "product WHERE ACT='1' order by PRODUCT_NAME asc ");
$data=$query->rows; 
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "product(SID,PRODUCT_NAME,PRODUCT_CATEGORY,ACT,PRODUCT_IMAGE,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["PRODUCT_NAME"])."','".$mcrt->encrypt($value["PRODUCT_CATEGORY"])."','".$mcrt->encrypt($value["ACT"])."','".$mcrt->encrypt($value["PRODUCT_IMAGE"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}



//end save product


//save data farmer
//$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "farmer where CR_BY = '".$id."' ");
//$data=$query->rows; 

$role_id = $this->db->query("SELECT customer_group_id  FROM " . DB_PREFIX . "customer where customer_id = '".$id."' ");
$role_id1=$role_id->row["customer_group_id"];
if($role_id1=='48') {
   // $ff="SELECT *  FROM " . DB_PREFIX . "farmer where CR_BY IN (select CUSTOMER_ID from customer_map where PARENT_CUSTOMER_ID = '".$id."'";
    $query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "farmer where CR_BY IN (select CUSTOMER_ID from " . DB_PREFIX . "customer_map where PARENT_CUSTOMER_ID = '".$id."')  ");
    $data=$query->rows;
} else {

$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "farmer where CR_BY = '".$id."' ");
$data=$query->rows;
}

foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "farmer (SID,FARMER_NAME,FAR_MOBILE ,CR_DATE,CR_BY,VILL_ID,DIST_ID,CAN_MLK_ID,FGM_ID,KEY_FARMER,MILKING_COWS_CNT,TOTAL_COWS,CURR_SUPPILER,DAILY_MILK_PROD,REMARKS,LAST_VISIT_ID,CAR_ID,FARMER_STATUS,APP_TRX_ID,LATT,LONGG,FAR_SEGMENT,FAR_POSSITION,FAR_CATEGORY,IMAGE,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["FARMER_NAME"])."','".$mcrt->encrypt($value["FAR_MOBILE"])."','".$mcrt->encrypt($value["CR_DATE"])."','".$mcrt->encrypt($value["CR_BY"])."','".$mcrt->encrypt($value["VILL_ID"])."','".$mcrt->encrypt($value["DIST_ID"])."','".$mcrt->encrypt($value["CAN_MLK_ID"])."','".$mcrt->encrypt($value["FGM_ID"])."','".$mcrt->encrypt($value["KEY_FARMER"])."','".$mcrt->encrypt($value["MILKING_COWS_CNT"])."','".$mcrt->encrypt($value["TOTAL_COWS"])."','".$mcrt->encrypt($value["CURR_SUPPILER"])."','".$mcrt->encrypt($value["DAILY_MILK_PROD"])."','".$mcrt->encrypt($value["REMARKS"])."','".$mcrt->encrypt($value["LAST_VISIT_ID"])."','".$mcrt->encrypt($value[" CAR_ID"])."','".$mcrt->encrypt($value["FARMER_STATUS"])."','".$mcrt->encrypt($value["APP_TRX_ID"])."','".$mcrt->encrypt($value["LATT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["FAR_SEGMENT"])."','".$mcrt->encrypt($value["FAR_POSSITION"])."','".$mcrt->encrypt($value["FAR_CATEGORY"])."','".$mcrt->encrypt($value["IMAGE"])."','0')";
    $log->write($insert);
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}


//end save farmer



//save data village
$role_id = $this->db->query("SELECT customer_group_id  FROM " . DB_PREFIX . "customer where customer_id = '".$id."' ");
$role_id1=$role_id->row["customer_group_id"];
if($role_id1=='49' || $role_id1=='60' || $role_id1=='61') {
$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "village where HQ_ID IN (SELECT GEO_ID from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='5')");
$data=$query->rows; 
} else if($role_id1=='47'){
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "village where DISTRICT_ID IN (SELECT GEO_ID from " . DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='4')");
$data=$query->rows; 
} else {
    $query = $this->db->query("SELECT * FROM ".DB_PREFIX . "village where HQ_ID IN (SELECT sid FROM ".DB_PREFIX . "geo where geo_type=5 and state_id In(SELECT GEO_ID from ".DB_PREFIX . "customer_emp_map where EMP_ID ='".$id."' and GEO_LEVEL_ID='2'))");
    $data=$query->rows;
}
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "village (SID,VILLAGE_NAME,VILLAGE_PIN_CODE,STATE_ID,TERRITORY_ID,DISTRICT_ID,HQ_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["VILLAGE_NAME"])."','".$mcrt->encrypt($value["VILLAGE_PIN_CODE"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["TERRITORY_ID"])."','".$mcrt->encrypt($value["DISTRICT_ID"])."','".$mcrt->encrypt($value["HQ_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}


//save data Retailer Type
$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "retailer_type");
$data=$query->rows; 
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "retailer_type (SID,RETAILER_TYPE,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["RETAILER_TYPE"])."','".$mcrt->encrypt($value["ACT"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}


    $query = $this->db->query("SELECT * FROM ".DB_PREFIX . "points");
    $data=$query->rows;

foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "points (SID,PRODUCT_ID,CONVERSION_FACTOR,QUANTITY,START_DATE,END_DATE,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["PRODUCT_ID"])."','".$mcrt->encrypt($value["CONVERSION_FACTOR"])."','".$mcrt->encrypt($value["QUANTITY"])."','".$mcrt->encrypt($value["START_DATE"])."','".$mcrt->encrypt($value["END_DATE"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}
//save data customer_stock
$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "customer_stock");
$data=$query->rows; 
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "customer_stock(SID,CUSTOMER_ID,PRODUCT_ID,QUANTITY,LAST_MODIFY,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["CUSTOMER_ID"])."','".$mcrt->encrypt($value["PRODUCT_ID"])."','".$mcrt->encrypt($value["QUANTITY"])."','".$mcrt->encrypt($value["LAST_MODIFY"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}

$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "issue_master");
$data=$query->rows; 
foreach ($data as $value){
    $insert="insert into ".DB_PREFIX . "issue_master (SID,ISSUE_NAME,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["ISSUE_NAME"])."','".$mcrt->encrypt($value["ACT"])."','0')";
    $stmt = $file_db->prepare($insert);
    $stmt->execute();
}
$files_to_zip = $sqdb;

//if true, good; if false, zip creation failed
 $this->file_id=rand().".zip";
  $result =  $this-> create_zip($files_to_zip,DIR_DOWNLOAD.$this->file_id);

unlink($sqdb);


 
    /**************************************
    * Close db connections                *
    **************************************/
 
    // Close file db connection
    
    $file_db = null;
   return $result;

  }
  
catch(Exception $e) {
     return $e;
    }
    
    
}

    // creates a compressed zip file 
    public function create_zip($files,$destination,$overwrite = false) {

   try{
       
    //if the zip file already exists and overwrite is false, return false
    if(file_exists($destination) && !$overwrite) { return false; }
    //vars
    $valid_files = array();
   
    //        //make sure the file exists
        if(file_exists($files)) {
                  $valid_files[] = $files;
        }
        else
        {
        echo "file is not found";
        }

        //create the archive
        $zip = new ZipArchive();
        if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true)
        {
            return false;
        }
        //add the files
        foreach($valid_files as $file) {
            $zip->addFile($file,"db.sqlite");
        }

        
        //close the zip -- done!
        $zip->close();

        //check to make sure the file exists
        return $destination;
    }
    catch(Exception $e) {
     return 'Message: '.$e->getMessage();
    }

}



}