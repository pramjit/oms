<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of addgeo
 *
 * @author agent
 */
class Modelgeoaddgeovillage extends Model {
    
    public function  addtehsil($data)
    {
          $query = $this->db->query("SELECT NAME  FROM ak_mst_geo_lower WHERE NAME='". $data['tehsil_name'] ."' and DISTRICT_ID='". $data['select_district_territory'] ."' and STATE_ID='". $data['select_tehsil_state'] ."' and TYPE='1'");
          $nationnamr=$query->row["NAME"]; 
          if(empty($nationnamr)) {
        $sql="INSERT INTO ak_mst_geo_lower SET NAME = '" . $data['tehsil_name'] . "',DISTRICT_ID='". $data['select_district_territory'] ."', `TYPE` = '1',`STATE_ID`='". $data['select_tehsil_state'] ."', `ACT` = '1' ";
        
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
          } else {
              $ret_id='0';
              return $ret_id;
          }
    }
    
    public function  addblock($data)
    {
          $query = $this->db->query("SELECT NAME  FROM ak_mst_geo_lower WHERE NAME='". $data['Block_name'] ."' and DISTRICT_ID='". $data['select_district_territory_block'] ."' and STATE_ID='". $data['select_block_state'] ."' and TYPE='2'");
          $nationnamr=$query->row["NAME"]; 
          if(empty($nationnamr)) {
        $sql="INSERT INTO ak_mst_geo_lower SET NAME = '" . $data['Block_name'] . "',DISTRICT_ID='". $data['select_district_territory_block'] ."', `TYPE` = '2',`STATE_ID`='". $data['select_block_state'] ."', `ACT` = '1' ";
        
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
          } else {
              $ret_id='0';
              return $ret_id;
          }
    }
    
     public function  addvillage($data)
    {
          $query = $this->db->query("SELECT NAME  FROM ak_mst_geo_lower WHERE NAME='". $data['village_name'] ."' and DISTRICT_ID='". $data['select_district_territory_village'] ."' and STATE_ID='". $data['select_village_state'] ."' and TEHSIL_ID='". $data['select_tehsil'] ."' and BLOCK_ID='". $data['select_block'] ."' and TYPE='3'");
          $nationnamr=$query->row["NAME"]; 
          if(empty($nationnamr)) {
        $sql="INSERT INTO ak_mst_geo_lower SET NAME = '" . $data['village_name'] . "',DISTRICT_ID='". $data['select_district_territory_village'] ."', `TYPE` = '3',`STATE_ID`='". $data['select_village_state'] ."',`BLOCK_ID`='". $data['select_block'] ."',TEHSIL_ID='". $data['select_tehsil'] ."' , `ACT` = '1' ";
        
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
          } else {
              $ret_id='0';
              return $ret_id;
          }
    }
    
    public function  addState($data)
    {
         $query = $this->db->query("SELECT NAME  FROM ak_mst_geo_upper WHERE NAME='". $data['state_name'] ."'and Nation_ID='".$data['select_state_nation']."' and TYPE='2'");
          $nationnamr=$query->row["NAME"]; 
          if(empty($nationnamr)) {
        $sql="INSERT INTO ak_mst_geo_upper SET NAME = '" . $data['state_name'] . "',Nation_ID='".$data['select_state_nation']."',AREA_ID='0', `TYPE` = '2',`STATE_ID` = '0', `ACT` = '1'";
        
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
        } else {
              $ret_id='0';
              return $ret_id;
          }
        
    }
    
    
     public function  addHQ($data)
    {
          $query = $this->db->query("SELECT GEO_NAME  FROM " . DB_PREFIX . "geo WHERE GEO_NAME='". $data['hq_name'] ."'and Nation_ID='".$data['select_hq_nation']."' and STATE_ID='".$data['select_hq_state']."' and TERRITORY_ID='".$data['select_hq_territory']."' and district_id='".$data['select_hq_district']."' and GEO_TYPE='5'");
          $nationnamr=$query->row["GEO_NAME"]; 
          if(empty($nationnamr)) {
        $sql="INSERT INTO " . DB_PREFIX . "geo SET GEO_NAME = '" . $data['hq_name'] . "',Nation_ID='".$data['select_hq_nation']."',ZONE_ID='0',REGION_ID='0',STATE_ID='".$data['select_hq_state']."',TERRITORY_ID='".$data['select_hq_territory']."',district_id='".$data['select_hq_district']."' ,`GEO_TYPE` = '5', `ACT` = '1'";
        
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
        } else {
              $ret_id='0';
              return $ret_id;
          }
    }
    
      public function  addTerritory($data)
    {
         $query = $this->db->query("SELECT NAME  FROM " . DB_PREFIX . "mst_geo_upper WHERE NAME='". $data['territory_name'] ."'and Nation_ID='".$data['select_territory_nation']."' and STATE_ID='".$data['select_territory_state']."' and TYPE='3'");
          $nationnamr=$query->row["NAME"]; 
          if(empty($nationnamr)) {
        $sql="INSERT INTO " . DB_PREFIX . "mst_geo_upper SET NAME = '" . $data['territory_name'] . "',Nation_ID='".$data['select_territory_nation']."',STATE_ID='".$data['select_territory_state']."',AREA_ID='0', `TYPE` = '3', `ACT` = '1'";
        
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
         } else {
              $ret_id='0';
              return $ret_id;
          }
        
    }
    
      public function  addDistrict($data)
    {
         $query = $this->db->query("SELECT NAME  FROM ak_mst_geo_upper WHERE NAME='". $data['district_name'] ."'and Nation_ID='".$data['select_district_nation']."' and STATE_ID='".$data['select_district_state']."' and TERRITORY_ID='".$data['select_district_territory']."' and TYPE='4'");
          $nationnamr=$query->row["NAME"]; 
          if(empty($nationnamr)) {
        $sql="INSERT INTO ak_mst_geo_upper SET NAME = '" . $data['district_name'] . "',Nation_ID='".$data['select_district_nation']."',STATE_ID='".$data['select_district_state']."',AREA_ID='".$data['select_district_territory']."', `TYPE` = '4', `ACT` = '1'";
        
        $this->db->query($sql);
        $ret_id = $this->db->getLastId();
        return $ret_id;
        } else {
              $ret_id='0';
              return $ret_id;
          }
    }
  
    public function getNations(){
          $query = $this->db->query("SELECT NAME as 'name',SID as 'id'  FROM ak_mst_geo_upper WHERE TYPE='1' and ACT ='1' order by name asc");

        
        return $query->rows;   
    }
    
    public function getZone(){
          $query = $this->db->query("SELECT GEO_NAME as 'name',SID as 'id'  FROM ak_geo WHERE GEO_TYPE='2' and ACT ='1' order by name asc");

        
        return $query->rows;   
    }
    
    
    public function getRegion(){
          $query = $this->db->query("SELECT GEO_NAME as 'name',SID as 'id'  FROM ak_geo WHERE GEO_TYPE='3' and ACT ='1' order by name asc");

        
        return $query->rows;   
    }
    
     public function getState(){
          $query = $this->db->query("SELECT NAME as 'name',SID as 'id'  FROM ak_mst_geo_upper WHERE TYPE='2' and ACT ='1' order by name asc");

        return $query->rows;   
    }
    
     public function getArea(){
          $query = $this->db->query("SELECT NAME as 'name',SID as 'id'  FROM ak_mst_geo_upper WHERE GEO_TYPE='3' and ACT ='1' order by name asc");

        return $query->rows;   
    }
    
     public function getTerritory(){
          $query = $this->db->query("SELECT NAME as 'name',SID as 'id'  FROM ak_mst_geo_upper WHERE TYPE='3' and ACT ='1' order by name asc");

        return $query->rows;   
    }
    
    public function get_District_State(){
          $query = $this->db->query("SELECT NAME as 'name',SID as 'id'  FROM ak_mst_geo_upper WHERE STATE_ID='".$data['select_district_state']."' and  TYPE='4'  and ACT ='1' order by name asc");

        
        return $query->rows;   
    }
    
    
    public function getterritorystate($data){

        $query = $this->db->query("SELECT NAME,SID FROM ak_mst_geo_upper where  TYPE=4 and ACT ='1'");
        return $query->rows;

    }
    
    //district
    public function getdistrict($data){

        $query = $this->db->query("SELECT NAME,SID FROM ak_mst_geo_upper where STATE_ID='".$data['stateid']."' and TYPE=4");
        return $query->rows;

    }
      //district
    public function getdistrictteri($data){
        $query1="SELECT NAME,SID FROM ak_mst_geo_upper where AREA_ID='".$data['territory_id']."' and TYPE=4";
        $query = $this->db->query($query1);
        return $query->rows;

    }
    
     public function gettehsil($data){
       
        $query = $this->db->query("SELECT SID,NAME  FROM ak_mst_geo_lower WHERE DISTRICT_ID='".$data["districtid"]."' and  TYPE='1' order by NAME asc");

        return $query->rows;   
    }
      public function getblock($data){
       
        $query = $this->db->query("SELECT SID,NAME  FROM ak_mst_geo_lower WHERE DISTRICT_ID='".$data["districtid"]."' and TYPE='2' order by NAME asc");

        return $query->rows;   
    }
       
}
