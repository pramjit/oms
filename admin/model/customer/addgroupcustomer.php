<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createdealer
 *
 * @author agent
 */
class Modelcustomeraddgroupcustomer extends Model {
   
    
    /*public function  adddealer($data)
    {
        $sql="INSERT INTO " . DB_PREFIX . "channel_partner SET CHANNEL_CODE = '" . $data['channel_code'] . "', `CHANNEL_TYPE` = '" . (isset($data['chanel_type']) ? $data['chanel_type'] : 0) . "', `FIRM_NAME` = '" . $data['firm_name'] . "', OWNER_NAME = '" . $data['owner_name'] . "', MOBILE = '" . $data['mobile_number'] . "',  EMAIL_ID = '" . $data['email_id'] . "', HO_ID = '" .$data['ho_id'] . "', ZONE_ID = '" . $data['zone_id'] . "', REGION_ID = '" .$data['region_id'] . "', AREA_ID = '" . $data['area_id'] . "', TERRITORY_ID= '" .$data['territory_id'] . "', DMR_ID= '" .$data['dmr_id'] . "',DMR_NAME= '" . $data['dmr_name'] . "', FMR_ID= '" . $data['fmr_id'] . "', FMR_NAME= '" . $data['fmr_name'] . "', ACT= '" . $data['act'] . "'";
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
    }*/
 public function addGroup($data){
     $sql="INSERT INTO " . DB_PREFIX . "customer_group SET name = '" . $data['name'] . "',LEVEL_ID = '" . $data['addlevel'] . "'";
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
 }
  public function getGroupNameShow(){
         $query = $this->db->query("SELECT name as 'name',customer_group_id as 'id', LEVEL_NAME as level FROM " . DB_PREFIX . "customer_group left join ".DB_PREFIX."user_level on SID=Level_ID  order by name");

        
        return $query->rows;   
    }
    
    public function getUserLevel(){
       $query = $this->db->query("SELECT SID as id,LEVEL_NAME as name  FROM " . DB_PREFIX . "user_level");
        return $query->rows;   
    }
}
