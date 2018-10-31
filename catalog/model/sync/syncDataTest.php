<?php
class ModelsyncsyncDataTest extends Model {
  
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
    $fid= date(dmYmis);
    // Create (connect to) SQLite database in file
    $log=new Log("SyncModTest".date('Ymd').".log");
    $log->write("User Id:".$id);
    
    
    $sqdb=DIR_DOWNLOAD.$fid.'.sqlite';
    $file_db = new PDO('sqlite:'.$sqdb);
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    /**************************************
    * Create tables                       *
    **************************************/
    $log->write("Drop Table If Not Exist Start");
    // Create table messages  
    $file_db->exec("DROP TABLE IF EXISTS ak_crop");
    $file_db->exec("DROP TABLE IF EXISTS ak_mst_farmer");   
    $file_db->exec("DROP TABLE IF EXISTS ak_mst_retailer");  
    $file_db->exec("DROP TABLE IF EXISTS ak_retailer_tax");
    $file_db->exec("DROP TABLE IF EXISTS ak_farmer_crop");
    $file_db->exec("DROP TABLE IF EXISTS ak_mst_geo_lower");
    $file_db->exec("DROP TABLE IF EXISTS ak_mst_geo_upper"); 
    $file_db->exec("DROP TABLE IF EXISTS ak_jeep_campaign");
    $file_db->exec("DROP TABLE IF EXISTS ak_jeep_halt");
    $file_db->exec("DROP TABLE IF EXISTS ak_farmer_log");
    $file_db->exec("DROP TABLE IF EXISTS ak_daily_ta");
    $file_db->exec("DROP TABLE IF EXISTS ak_fgm_dtl");
    $file_db->exec("DROP TABLE IF EXISTS ak_farm_demo");
    $file_db->exec("DROP TABLE IF EXISTS ak_farm_demo_observation");
    $file_db->exec("DROP TABLE IF EXISTS ak_user_visit");
    $file_db->exec("DROP TABLE IF EXISTS ak_mst_purpose"); 
    $file_db->exec("DROP TABLE IF EXISTS ak_customer"); 
    $file_db->exec("DROP TABLE IF EXISTS ak_customer_emp_map");
    $file_db->exec("DROP TABLE IF EXISTS ak_customer_map"); 
    $file_db->exec("DROP TABLE IF EXISTS oc_store");
    $file_db->exec("DROP TABLE IF EXISTS oc_product");
    $file_db->exec("DROP TABLE IF EXISTS ak_plan"); 
    $file_db->exec("DROP TABLE IF EXISTS oc_payment_ref");
    
    
    $log->write("Create Table If Not Exist Start");
    
    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_crop (
        SID         TEXT,
        CROP_NAME   TEXT,
        SEASON_NAME TEXT,
        ACT         TEXT,
        statusl     TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_mst_farmer (
        SID TEXT,
        FARMER_NAME TEXT,	
        FARMER_MOBILE TEXT,
        ADDRESS TEXT,
        DISTRICT_ID TEXT,
        VILLAGE_ID TEXT,
        TEHSIL_ID TEXT,
        BLOCK_ID TEXT,
        PINCODE TEXT,
        PHOTO TEXT,
        LAND_ACRES TEXT,
        PROBLEM TEXT,
        SOLUTION TEXT,
        CR_BY TEXT,
        CR_DATE TEXT,
        STATUS TEXT,
        LAT TEXT,
        LONGG TEXT,
        JEEP_HALT_ID TEXT,
        CROP TEXT,
        RETAILER TEXT,
        FGM_ID TEXT,
        APP_TRX_ID TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_mst_retailer (
        SID TEXT,
        RETAILER_NAME TEXT,	
        CONTACT_PERSON TEXT,
        MOBILE TEXT,
        ADDRESS TEXT,
        DISTRICT_ID TEXT,
        TEHSIL_ID TEXT,
        BLOCK_ID TEXT,
        PINCODE TEXT,
        PHOTO TEXT,
        LAT TEXT,
        LONGG TEXT,
        EMAIL TEXT,
        CR_BY TEXT,
        CR_DATE TEXT,
        VILLAGE TEXT,
        FARMER TEXT,
        WHOLESELLER TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_retailer_tax (
        SID TEXT,
        RETAILER_ID TEXT,	
        TIN_GST_NO TEXT,
        PAN_NO TEXT,
        ADDHAR_NO TEXT,
        FRC_NO TEXT,
        FRC_VALID_UPTO TEXT,
        SEED_LICENCE TEXT,
        SEED_LICENCE_UPTO TEXT,
        MFMS_ID TEXT,
        PESTICIDE_LICENCE TEXT,
        PESTICIDE_LICENCE_VALID_UPTO TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_farmer_crop (
        SID	TEXT,
        FARMER_ID	TEXT,
        CROP_ID	TEXT,
        statusl TEXT    
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_mst_geo_lower (
        SID TEXT,
        NAME TEXT,	
        TYPE TEXT,
        PINCODE TEXT,
        TEHSIL_ID TEXT,
        BLOCK_ID TEXT,
        DISTRICT_ID TEXT,
        STATE_ID TEXT,
        ACT TEXT,
        statusl TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_mst_geo_upper (
        SID TEXT,
        NAME TEXT,	
        TYPE TEXT,
        NATION_ID TEXT,
        STATE_ID TEXT,
        AREA_ID TEXT,
        ACT TEXT,
        statusl TEXT
    )");



    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_jeep_campaign (
        SID TEXT,
        CR_DATE TEXT,	
        CR_BY TEXT,
        RETAILER_ID TEXT,
        VENDOR_NAME TEXT,
        DRIVER_NAME TEXT,
        DRIVER_MOBILE TEXT,
        VEHICLE_NO TEXT,
        VILLAGE_ID TEXT,
        OPEN_KM TEXT,
        PHOTO TEXT,
        LAT TEXT,
        LONGG TEXT,
        STATUS TEXT,
        HALTS TEXT,
        DIESEL_RS TEXT,
        DIESEL_LTR TEXT,
        DIESEL_USE TEXT,
        LABOR_EXPENCE TEXT,
        MISC_EXPENCE TEXT,
        JEEP_ID,
        statusl TEXT
    )");

    
    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_jeep_halt (
        SID TEXT,
        JEEP TEXT,	
        CR_DATE TEXT,
        VILLAGE_ID TEXT,
        PHOTO TEXT,
        LAT TEXT,
        LONGG TEXT,
        CR_BY TEXT,
        statusl TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_farmer_log (
        SID TEXT,
        FARMER_NAME TEXT,	
        FAR_MOBILE TEXT,
        CR_DATE TEXT,
        CR_BY TEXT,
        DIST_ID TEXT,
        TEHSIL_ID TEXT,
        BLOCK_ID TEXT,
        VILL_ID TEXT,
        FGM_ID TEXT,
        APP_TRX_ID TEXT,
        JEEP_HALT_ID TEXT,
        statusl TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_daily_ta (
        SID TEXT,
        TA_DATE TEXT,	
        TA_EMP_ID TEXT,
        PLACE_FROM TEXT,
        PLACE_TO TEXT,
        OPEN_MTR TEXT,
        CLOSE_MTR TEXT,
        TAX_RS TEXT,
        FARE_RS TEXT,
        PETROL_LTR TEXT,
        LOCAL_CONVEYANCE TEXT,
        HOTEL_RS TEXT,
        POSTAGE_RS TEXT,
        PRINTING_RS TEXT,
        MISC_RS TEXT,
        UPLOAD_1 TEXT,
        UPLOAD_2 TEXT,
        UPLOAD_3 TEXT,
        UPLOAD_4 TEXT,
        REMARKS TEXT,
        STATUS TEXT,
        PLACE_TO1 TEXT,
        PLACE_TO2 TEXT,
        PLACE_TO3 TEXT,
        PLACE_TO4 TEXT,
        statusl TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_fgm_dtl (   
        SID TEXT,
        USER_ID TEXT,	
        USER_NAME TEXT,
        DISTRICT_ID TEXT,
        TEHSIL_ID TEXT,
        BLOCK_ID TEXT,
        VILL_ID TEXT,
        CR_DATE TEXT,
        APP_TRX_ID TEXT,
        IMAGE TEXT,
        LATT TEXT,
        LONGG TEXT,
        RETAILER TEXT,
        statusl TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_farm_demo (   
        SID TEXT,
        VILLAGE_ID TEXT,	
        FARMER_ID TEXT,
        DEMO_ACRES TEXT,
        CROP_ID TEXT,
        PHOTO TEXT,
        PHOTO_1 TEXT,
        PHOTO_2 TEXT,
        PHOTO_3 TEXT,
        PHOTO_4 TEXT,
        LAT TEXT,
        LONGG TEXT,
        CR_BY TEXT,
        CR_DATE TEXT,
        STATUS TEXT,
        PRODUCT TEXT,
        QUANTITY TEXT,
        statusl TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_farm_demo_observation (   
        SID TEXT,
        FARM_DEMO_ID TEXT,
        VILLAGE_ID TEXT,	
        FARMER_ID TEXT,
        CR_BY TEXT,
        CR_DATE TEXT,
        PHOTO TEXT,
        PHOTO_1 TEXT,
        PHOTO_2 TEXT,
        PHOTO_3 TEXT,
        PHOTO_4 TEXT,
        LAT TEXT,
        LONGG TEXT,
        REMARKS TEXT,
        statusl TEXT
    )");


    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_user_visit (   
        SID TEXT,
        VISIT_TYPE TEXT,
        POS_ID TEXT,	
        FARMER_ID TEXT,
        CR_DATE TEXT,
        USER_ID TEXT,
        REMARKS TEXT,
        APP_TRX_ID TEXT,
        NEXT_VISIT_DATE TEXT,
        PURPOSE_ID TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_mst_purpose (   
        SID TEXT,
        PURPOSE TEXT,
        TYPE TEXT,	
        ACT TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_customer (
        customer_id TEXT,
        Name	TEXT,
        statusl TEXT,
        loc_allow TEXT,
        out_allow TEXT,
        mot_allow TEXT,
        hot_allow TEXT
    )");
    

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_customer_emp_map (    
        SID	TEXT,
        CUSTOMER_ID TEXT,
        LEVEL_TYPE TEXT,
        GEO_ID TEXT,
        GEO_LEVEL_ID TEXT,
        ACT_ID TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_customer_map (    
        SID TEXT,
        CUSTOMER_ID TEXT,
        PARENT_USER_ID TEXT,
        CUSTOMER_GROUP_ID TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS oc_store (    
        store_id    TEXT,
        name TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS oc_product (    
        product_id TEXT,
        model TEXT,
        sku TEXT,
        quantity  TEXT,
        stock_status_id  TEXT,
        manufacturer_id  TEXT,
        price  TEXT,
        weight  TEXT,
        status  TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS ak_plan (   
        SID TEXT,
        TEHSIL TEXT,
        WHOLE_SELLER TEXT,	
        CR_DATE TEXT,
        RETAILER TEXT,
        APP_TR_ID TEXT,
        STATUS TEXT,
        CR_BY TEXT,
        statusl TEXT
    )");

    $file_db->exec("CREATE TABLE IF NOT EXISTS oc_payment_ref ( 
        Sid TEXT,
        Emp_id TEXT,
        Cust_id TEXT, 
        Amnt_Bank TEXT,
        Amnt_Date TEXT,
        Amnt_Type TEXT,
        Amnt_Ref TEXT,
        Amnt_Rs TEXT,
        Cr_Date TEXT,
        Pay_type TEXT,
        statusl TEXT
    )");

    
    
    $log->write("Data Insertion Started...");

    $mcrt=new MCrypt();
    
    //************************ Data Insertion Started ************************//

    $log->write("Data Insertion Started... ak_crop");
    $query = $this->db->query("SELECT *  FROM ak_crop ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_crop (SID,CROP_NAME,SEASON_NAME,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["CROP_NAME"])."','".$mcrt->encrypt($value["SEASON_NAME"])."','".$mcrt->encrypt($value["ACT"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    $log->write("Data Insertion Started... ak_mst_farmer");
    $query = $this->db->query("SELECT *  FROM ak_mst_farmer ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_mst_farmer (SID,FARMER_NAME,FARMER_MOBILE,ADDRESS,DISTRICT_ID,VILLAGE_ID,TEHSIL_ID,BLOCK_ID,PINCODE,PHOTO,LAND_ACRES,PROBLEM,SOLUTION,CR_BY,CR_DATE,LAT,LONGG,FGM_ID,JEEP_HALT_ID,CROP,RETAILER,APP_TRX_ID,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["FARMER_NAME"])."','".$mcrt->encrypt($value["FARMER_MOBILE"])."','".$mcrt->encrypt($value["ADDRESS"])."','".$mcrt->encrypt($value["DISTRICT_ID"])."','".$mcrt->encrypt($value["VILLAGE_ID"])."','".$mcrt->encrypt($value["TEHSIL_ID"])."','".$mcrt->encrypt($value["BLOCK_ID"])."','".$mcrt->encrypt($value["PINCODE"])."','".$mcrt->encrypt($value["PHOTO"])."','".$mcrt->encrypt($value["LAND_ACRES"])."','".$mcrt->encrypt($value["PROBLEM"])."','".$mcrt->encrypt($value["SOLUTION"])."','".$mcrt->encrypt($value["CR_BY"])."','".$mcrt->encrypt($value["CR_DATE"])."','".$mcrt->encrypt($value["LAT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["FGM_ID"])."','".$mcrt->encrypt($value["JEEP_HALT_ID"])."','".$mcrt->encrypt($value["CROP"])."','".$mcrt->encrypt($value["RETAILER"])."','".$mcrt->encrypt($value["APP_TRX_ID"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }
    
    $log->write("Data Insertion Started... ak_retailer_tax");
    $query = $this->db->query("SELECT *  FROM ak_retailer_tax ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_retailer_tax (SID,RETAILER_ID,TIN_GST_NO,PAN_NO,ADDHAR_NO,FRC_NO,FRC_VALID_UPTO,SEED_LICENCE,SEED_LICENCE_UPTO,MFMS_ID,PESTICIDE_LICENCE,PESTICIDE_LICENCE_VALID_UPTO,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["RETAILER_ID"])."','".$mcrt->encrypt($value["TIN_GST_NO"])."','".$mcrt->encrypt($value["PAN_NO"])."','".$mcrt->encrypt($value["ADDHAR_NO"])."','".$mcrt->encrypt($value["FRC_NO"])."','".$mcrt->encrypt($value["FRC_VALID_UPTO"])."','".$mcrt->encrypt($value["SEED_LICENCE"])."','".$mcrt->encrypt($value["SEED_LICENCE_UPTO"])."','".$mcrt->encrypt($value["MFMS_ID"])."','".$mcrt->encrypt($value["PESTICIDE_LICENCE"])."','".$mcrt->encrypt($value["PESTICIDE_LICENCE_VALID_UPTO"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }
    
    
    
    $log->write("Data Insertion Started... ak_mst_retailer");
    $query = $this->db->query("SELECT *  FROM ak_mst_retailer ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_mst_retailer (SID,RETAILER_NAME,CONTACT_PERSON,MOBILE,ADDRESS,DISTRICT_ID,TEHSIL_ID,BLOCK_ID,PINCODE,PHOTO,LAT,LONGG,EMAIL,CR_BY,CR_DATE,VILLAGE,FARMER,WHOLESELLER,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["RETAILER_NAME"])."','".$mcrt->encrypt($value["CONTACT_PERSON"])."','".$mcrt->encrypt($value["MOBILE"])."','".$mcrt->encrypt($value["ADDRESS"])."','".$mcrt->encrypt($value["DISTRICT_ID"])."','".$mcrt->encrypt($value["TEHSIL_ID"])."','".$mcrt->encrypt($value["BLOCK_ID"])."','".$mcrt->encrypt($value["PINCODE"])."','".$mcrt->encrypt($value["PHOTO"])."','".$mcrt->encrypt($value["LAT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["EMAIL"])."','".$mcrt->encrypt($value["CR_BY"])."','".$mcrt->encrypt($value["CR_DATE"])."','".$mcrt->encrypt($value["VILLAGE"])."','".$mcrt->encrypt($value["FARMER"])."','".$mcrt->encrypt($value["WHOLESELLER"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    $log->write("Data Insertion Started... ak_farmer_crop");
    $query = $this->db->query("SELECT *  FROM ak_farmer_crop ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_farmer_crop (SID,FARMER_ID,CROP_ID,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["FARMER_ID"])."','".$mcrt->encrypt($value["CROP_ID"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    
    $log->write("Data Insertion Started... ak_mst_geo_lower");
    $role_id = $this->db->query("SELECT customer_group_id  FROM ak_customer where customer_id = '".$id."' ");
    $role_id1=$role_id->row["customer_group_id"];
    if($role_id1=='3')
    {
    $query = $this->db->query("SELECT *  FROM ak_mst_geo_lower where district_id in(SELECT geo_id 
    FROM ak_customer_emp_map 
    where CUSTOMER_ID in(select ac.CUSTOMER_ID FROM ak_customer_map as ac 
    JOIN ak_customer as au on au.customer_id=ac.CUSTOMER_ID
    where ac.PARENT_USER_ID='".$id."') and GEO_LEVEL_ID=4 ) order by name asc");
    }
     else {

    $query = $this->db->query("SELECT *  FROM ak_mst_geo_lower where district_id in(SELECT geo_id FROM ak_customer_emp_map where CUSTOMER_ID='".$id."' and GEO_LEVEL_ID=4) order by name asc");
     }
    //$query = $this->db->query("SELECT *  FROM ak_mst_geo_lower ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_mst_geo_lower (SID,NAME,TYPE,PINCODE,TEHSIL_ID,BLOCK_ID,DISTRICT_ID,STATE_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["NAME"])."','".$mcrt->encrypt($value["TYPE"])."','".$mcrt->encrypt($value["PINCODE"])."','".$mcrt->encrypt($value["TEHSIL_ID"])."','".$mcrt->encrypt($value["BLOCK_ID"])."','".$mcrt->encrypt($value["DISTRICT_ID"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();

    }
    
    
    $log->write("Data Insertion Started... ak_mst_geo_upper");
    //save data mst_geo_upper(AREA)
    $query = $this->db->query("SELECT *  FROM ak_mst_geo_upper where SID in(SELECT geo_id FROM ak_customer_emp_map where CUSTOMER_ID='".$id."' and GEO_LEVEL_ID=3) order by name asc");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_mst_geo_upper (SID,NAME,TYPE,NATION_ID,STATE_ID,AREA_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["NAME"])."','".$mcrt->encrypt($value["TYPE"])."','".$mcrt->encrypt($value["NATION_ID"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["AREA_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    //save data mst_geo_upper(STATE)
    $query = $this->db->query("SELECT *  FROM ak_mst_geo_upper where SID in(SELECT geo_id FROM ak_customer_emp_map where CUSTOMER_ID='".$id."' and GEO_LEVEL_ID=2) order by name asc");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_mst_geo_upper (SID,NAME,TYPE,NATION_ID,STATE_ID,AREA_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["NAME"])."','".$mcrt->encrypt($value["TYPE"])."','".$mcrt->encrypt($value["NATION_ID"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["AREA_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    //save data mst_geo_upper(DISTRICT)
    $sqlUtyp="select customer_group_id from ak_customer where customer_id = '".$id."'";
    $query = $this->db->query($sqlUtyp);
    $CGrp = $query->row['customer_group_id'];

    if($CGrp == 4 || $CGrp == 2){
    $query = $this->db->query("SELECT *  FROM ak_mst_geo_upper where SID in(SELECT geo_id FROM ak_customer_emp_map where CUSTOMER_ID='".$id."' and GEO_LEVEL_ID=4) order by name asc");
    }
    if($CGrp == 3){
    $query = $this->db->query("SELECT *  FROM ak_mst_geo_upper where SID in(SELECT geo_id FROM ak_customer_emp_map where CUSTOMER_ID in 
    (select customer_id from ak_customer_map where parent_user_id = '".$id."')  and GEO_LEVEL_ID=4) order by name asc");
    }

    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_mst_geo_upper (SID,NAME,TYPE,NATION_ID,STATE_ID,AREA_ID,ACT,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["NAME"])."','".$mcrt->encrypt($value["TYPE"])."','".$mcrt->encrypt($value["NATION_ID"])."','".$mcrt->encrypt($value["STATE_ID"])."','".$mcrt->encrypt($value["AREA_ID"])."','".$mcrt->encrypt($value["ACT"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    
    $log->write("Data Insertion Started... ak_jeep_campaign");
    $query = $this->db->query("SELECT *  FROM ak_jeep_campaign order by CR_DATE DESC");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_jeep_campaign (SID,CR_DATE,CR_BY,RETAILER_ID,VENDOR_NAME,DRIVER_NAME,DRIVER_MOBILE,VEHICLE_NO,VILLAGE_ID,OPEN_KM,PHOTO,LAT,LONGG,STATUS,HALTS,DIESEL_RS,DIESEL_LTR,DIESEL_USE,LABOR_EXPENCE,MISC_EXPENCE,JEEP_ID,statusl) ". "values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt(date('Y-m-d', strtotime($value["CR_DATE"])))."','".$mcrt->encrypt($value["CR_BY"])."','".$mcrt->encrypt($value["RETAILER_ID"])."','".$mcrt->encrypt($value["VENDOR_NAME"])."','".$mcrt->encrypt($value["DRIVER_NAME"])."','".$mcrt->encrypt($value["DRIVER_MOBILE"])."',". "'".$mcrt->encrypt($value["VEHICLE_NO"])."','".$mcrt->encrypt($value["VILLAGE_ID"])."','".$mcrt->encrypt($value["OPEN_KM"])."',". "'".$mcrt->encrypt($value["PHOTO"])."','".$mcrt->encrypt($value["LAT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["STATUS"])."','".$mcrt->encrypt($value["HALTS"])."','".$mcrt->encrypt($value["DIESEL_RS"])."',". "'".$mcrt->encrypt($value["DIESEL_LTR"])."','".$mcrt->encrypt($value["DIESEL_USE"])."','".$mcrt->encrypt($value["LABOR_EXPENCE"])."','".$mcrt->encrypt($value["MISC_EXPENCE"])."','".$mcrt->encrypt($value["JEEP_ID"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    
    $log->write("Data Insertion Started... ak_jeep_halt");
    $query = $this->db->query("SELECT *  FROM ak_jeep_halt ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_jeep_halt (SID,JEEP,CR_DATE,VILLAGE_ID,PHOTO,LAT,LONGG,CR_BY,statusl) ". "values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["JEEP"])."','".$mcrt->encrypt($value["CR_DATE"])."','".$mcrt->encrypt($value["VILLAGE_ID"])."',". "'".$mcrt->encrypt($value["PHOTO"])."','".$mcrt->encrypt($value["LAT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["CR_BY"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }


    $log->write("Data Insertion Started... ak_farmer_log");
    $query = $this->db->query("SELECT *  FROM ak_farmer_log ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_farmer_log (SID,FARMER_NAME,FAR_MOBILE,CR_DATE,CR_BY,DIST_ID,TEHSIL_ID,BLOCK_ID,VILL_ID,FGM_ID,APP_TRX_ID,JEEP_HALT_ID,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["FARMER_NAME"])."','".$mcrt->encrypt($value["FAR_MOBILE"])."','".$mcrt->encrypt($value["CR_DATE"])."',". "'".$mcrt->encrypt($value["CR_BY"])."','".$mcrt->encrypt($value["DIST_ID"])."','".$mcrt->encrypt($value["TEHSIL_ID"])."','".$mcrt->encrypt($value["BLOCK_ID"])."','".$mcrt->encrypt($value["VILL_ID"])."','".$mcrt->encrypt($value["FGM_ID"])."','".$mcrt->encrypt($value["APP_TRX_ID"])."','".$mcrt->encrypt($value["JEEP_HALT_ID"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }


    $log->write("Data Insertion Started... ak_daily_ta");
    $query = $this->db->query("SELECT *  FROM ak_daily_ta ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_daily_ta (SID,TA_DATE,TA_EMP_ID,PLACE_FROM,PLACE_TO,OPEN_MTR,CLOSE_MTR,TAX_RS,FARE_RS,PETROL_LTR,LOCAL_CONVEYANCE,HOTEL_RS,POSTAGE_RS,PRINTING_RS,MISC_RS,UPLOAD_1,UPLOAD_2,UPLOAD_3,UPLOAD_4,REMARKS,STATUS,PLACE_TO1,PLACE_TO2,PLACE_TO3,PLACE_TO4,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["TA_DATE"])."','".$mcrt->encrypt($value["TA_EMP_ID"])."','".$mcrt->encrypt($value["PLACE_FROM"])."',". "'".$mcrt->encrypt($value["PLACE_TO"])."','".$mcrt->encrypt($value["OPEN_MTR"])."','".$mcrt->encrypt($value["CLOSE_MTR"])."','".$mcrt->encrypt($value["TAX_RS"])."','".$mcrt->encrypt($value["FARE_RS"])."','".$mcrt->encrypt($value["PETROL_LTR"])."','".$mcrt->encrypt($value["LOCAL_CONVEYANCE"])."','".$mcrt->encrypt($value["HOTEL_RS"])."','".$mcrt->encrypt($value["POSTAGE_RS"])."','".$mcrt->encrypt($value["PRINTING_RS"])."','".$mcrt->encrypt($value["MISC_RS"])."','".$mcrt->encrypt($value["UPLOAD_1"])."','".$mcrt->encrypt($value["UPLOAD_2"])."','".$mcrt->encrypt($value["UPLOAD_3"])."','".$mcrt->encrypt($value["UPLOAD_4"])."','".$mcrt->encrypt($value["REMARKS"])."','".$mcrt->encrypt($value["STATUS"])."','".$mcrt->encrypt($value["PLACE_TO1"])."','".$mcrt->encrypt($value["PLACE_TO2"])."','".$mcrt->encrypt($value["PLACE_TO3"])."','".$mcrt->encrypt($value["PLACE_TO4"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }


    $log->write("Data Insertion Started... ak_fgm_dtl");
    $query = $this->db->query("SELECT *  FROM ak_fgm_dtl ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_fgm_dtl (SID,USER_ID,USER_NAME,DISTRICT_ID,TEHSIL_ID,BLOCK_ID,VILL_ID,CR_DATE,APP_TRX_ID,IMAGE,LATT,LONGG,RETAILER,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["USER_ID"])."','".$mcrt->encrypt($value["USER_NAME"])."','".$mcrt->encrypt($value["DISTRICT_ID"])."',". "'".$mcrt->encrypt($value["TEHSIL_ID"])."','".$mcrt->encrypt($value["BLOCK_ID"])."','".$mcrt->encrypt($value["VILL_ID"])."','".$mcrt->encrypt($value["CR_DATE"])."','".$mcrt->encrypt($value["APP_TRX_ID"])."','".$mcrt->encrypt($value["IMAGE"])."','".$mcrt->encrypt($value["LATT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["RETAILER"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    $log->write("Data Insertion Started... ak_farm_demo");
    $query = $this->db->query("SELECT *  FROM ak_farm_demo order by CR_DATE DESC ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_farm_demo (SID,VILLAGE_ID,FARMER_ID,DEMO_ACRES,CROP_ID,PHOTO,PHOTO_1,PHOTO_2,PHOTO_3,PHOTO_4,LAT,LONGG,CR_BY,CR_DATE,STATUS,PRODUCT,QUANTITY,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["VILLAGE_ID"])."','".$mcrt->encrypt($value["FARMER_ID"])."','".$mcrt->encrypt($value["DEMO_ACRES"])."',". "'".$mcrt->encrypt($value["CROP_ID"])."','".$mcrt->encrypt($value["PHOTO"])."','".$mcrt->encrypt($value["PHOTO_1"])."','".$mcrt->encrypt($value["PHOTO_2"])."','".$mcrt->encrypt($value["PHOTO_3"])."','".$mcrt->encrypt($value["PHOTO_4"])."','".$mcrt->encrypt($value["LAT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["CR_BY"])."','".$mcrt->encrypt(date('Y-m-d', strtotime($value["CR_DATE"])))."','".$mcrt->encrypt($value["STATUS"])."','".$mcrt->encrypt($value["PRODUCT"])."','".$mcrt->encrypt($value["QUANTITY"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    $log->write("Data Insertion Started... ak_farm_demo_observation");
    $query = $this->db->query("SELECT *  FROM ak_farm_demo_observation ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_farm_demo_observation (SID,FARM_DEMO_ID,VILLAGE_ID,FARMER_ID,CR_BY,CR_DATE,PHOTO,PHOTO_1,PHOTO_2,PHOTO_3,PHOTO_4,LAT,LONGG,REMARKS,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["FARM_DEMO_ID"])."','".$mcrt->encrypt($value["VILLAGE_ID"])."','".$mcrt->encrypt($value["FARMER_ID"])."',". "'".$mcrt->encrypt($value["CR_BY"])."','".$mcrt->encrypt($value["CR_DATE"])."','".$mcrt->encrypt($value["PHOTO"])."','".$mcrt->encrypt($value["PHOTO_1"])."','".$mcrt->encrypt($value["PHOTO_2"])."','".$mcrt->encrypt($value["PHOTO_3"])."','".$mcrt->encrypt($value["PHOTO_4"])."','".$mcrt->encrypt($value["LAT"])."','".$mcrt->encrypt($value["LONGG"])."','".$mcrt->encrypt($value["REMARKS"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }


    $log->write("Data Insertion Started... ak_user_visit");
    $query = $this->db->query("SELECT *  FROM ak_user_visit ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_user_visit (SID,VISIT_TYPE,POS_ID,FARMER_ID,CR_DATE,USER_ID,REMARKS,APP_TRX_ID,NEXT_VISIT_DATE,PURPOSE_ID,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["VISIT_TYPE"])."','".$mcrt->encrypt($value["POS_ID"])."','".$mcrt->encrypt($value["FARMER_ID"])."',". "'".$mcrt->encrypt($value["CR_DATE"])."','".$mcrt->encrypt($value["USER_ID"])."','".$mcrt->encrypt($value["REMARKS"])."','".$mcrt->encrypt($value["APP_TRX_ID"])."','".$mcrt->encrypt($value["NEXT_VISIT_DATE"])."','".$mcrt->encrypt($value["PURPOSE_ID"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }



    $log->write("Data Insertion Started... ak_mst_purpose");
    $query = $this->db->query("SELECT *  FROM ak_mst_purpose ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_mst_purpose (SID,PURPOSE,TYPE,ACT,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["PURPOSE"])."','".$mcrt->encrypt($value["TYPE"])."',". "'".$mcrt->encrypt($value["ACT"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    $log->write("Data Insertion Started... ak_customer");
    $query = $this->db->query("select customer_id,firstname,lastname,local_allow,outstation_allow,vehicle_allow,hotel_allow  from ak_customer");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_customer (customer_id,Name,statusl,loc_allow,out_allow,mot_allow,hot_allow) values('".$mcrt->encrypt($value["customer_id"])."','".$mcrt->encrypt($value["firstname"].' '.$value["lastname"])."','0','".$mcrt->encrypt($value["local_allow"])."','".$mcrt->encrypt($value["outstation_allow"])."','".$mcrt->encrypt($value["vehicle_allow"])."','".$mcrt->encrypt($value["hotel_allow"])."')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }
    
    $log->write("Data Insertion Started... ak_customer_emp_map");
    $query = $this->db->query("SELECT *  FROM ak_customer_emp_map ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_customer_emp_map (SID,CUSTOMER_ID,LEVEL_TYPE,GEO_ID,GEO_LEVEL_ID,ACT_ID,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["CUSTOMER_ID"])."','".$mcrt->encrypt($value["LEVEL_TYPE"])."',". "'".$mcrt->encrypt($value["GEO_ID"])."',". "'".$mcrt->encrypt($value["GEO_LEVEL_ID"])."',". "'".$mcrt->encrypt($value["ACT_ID"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }


    $log->write("Data Insertion Started... ak_customer_map");
    $query = $this->db->query("SELECT *  FROM ak_customer_map ");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into ak_customer_map (SID,CUSTOMER_ID,PARENT_USER_ID,CUSTOMER_GROUP_ID,statusl)values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["CUSTOMER_ID"])."','".$mcrt->encrypt($value["PARENT_USER_ID"])."',". "'".$mcrt->encrypt($value["CUSTOMER_GROUP_ID"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    $log->write("Data Insertion Started... oc_store");
    $query = $this->db->query("SELECT *  FROM oc_store");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into oc_store (store_id,name,statusl)values('".$mcrt->encrypt($value["store_id"])."','".$mcrt->encrypt($value["name"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    $log->write("Data Insertion Started... oc_product");    
    $query = $this->db->query("select ocp.product_id as 'product_id',
    ocd.`name` as 'product_name',
    ocp.sku as 'sku',
    ocp.quantity as 'quantity', 
    ocp.stock_status_id as 'stock_status_id', 
    ocp.manufacturer_id as 'manufacturer_id', 
    ocp.price as 'price',
    ocp.weight as 'weight',
    ocp.`status` as 'status' 
    from oc_product ocp
    LEFT JOIN oc_product_description ocd 
    ON (ocd.product_id = ocp.product_id)");
    $data=$query->rows; 
    foreach ($data as $value){
        $insert="insert into oc_product (product_id,model,sku,quantity,stock_status_id,manufacturer_id,price,weight,status,statusl)values('".$mcrt->encrypt($value["product_id"])."','".$mcrt->encrypt($value["product_name"])."','".$mcrt->encrypt($value["sku"])."','".$mcrt->encrypt($value["quantity"])."','".$mcrt->encrypt($value["stock_status_id"])."','".$mcrt->encrypt($value["manufacturer_id"])."','".$mcrt->encrypt($value["price"])."','".$mcrt->encrypt($value["weight"])."','".$mcrt->encrypt($value["status"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }


    $log->write("Data Insertion Started... ak_plan"); 
    $query = $this->db->query("SELECT *  FROM ak_plan ");
    $data=$query->rows; 
    foreach ($data as $value){ 
        $insert="insert into ak_plan (SID,TEHSIL,WHOLE_SELLER,CR_DATE,RETAILER,APP_TR_ID,STATUS,CR_BY,statusl) values('".$mcrt->encrypt($value["SID"])."','".$mcrt->encrypt($value["TEHSIL"])."','".$mcrt->encrypt($value["WHOLE_SELLER"])."','".$mcrt->encrypt(DATE('Y_m-d',strtotime($value["CR_DATE"])))."','".$mcrt->encrypt($value["RETAILER"])."','".$mcrt->encrypt($value["APP_TR_ID"])."','".$mcrt->encrypt($value["STATUS"])."','".$mcrt->encrypt($value["CR_BY"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }

    
    $log->write("Data Insertion Started... oc_payment_ref"); 
    $query = $this->db->query("SELECT *  FROM oc_payment_ref ");
    $data=$query->rows; 
    foreach ($data as $value){ 
        $insert="insert into oc_payment_ref (Sid,Emp_id,Cust_id,Amnt_Bank,Amnt_Date,Amnt_Type,Amnt_Ref,Amnt_Rs,Cr_Date,Pay_type,statusl) values('".$mcrt->encrypt($value["Sid"])."','".$mcrt->encrypt($value["Emp_id"])."','".$mcrt->encrypt($value["Cust_id"])."','".$mcrt->encrypt($value["Amnt_Bank"])."','".$mcrt->encrypt($value["Amnt_Date"])."','".$mcrt->encrypt($value["Amnt_Type"])."','".$mcrt->encrypt($value["Amnt_Ref"])."','".$mcrt->encrypt($value["Amnt_Rs"])."','".$mcrt->encrypt($value["Cr_Date"])."','".$mcrt->encrypt($value["Pay_type"])."','0')";
        $stmt = $file_db->prepare($insert);
        $stmt->execute();
    }


        $files_to_zip = $sqdb;

        //if true, good; if false, zip creation failed
        $this->file_id=$fid.".zip";
        $result =  $this-> create_zip($files_to_zip,DIR_DOWNLOAD.$this->file_id);
        $log->write($result);
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