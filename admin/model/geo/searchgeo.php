<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createVillage
 *
 * @author agent
 */
class ModelGeosearchgeo extends Model {
    
    
   
    
   
   
   public function getGeo($data = array()) {
        
$sql = "SELECT SID as id, GEO_NAME AS name,ACT as status, GEO_TYPE as gtype,Nation_ID as nationid,STATE_ID as stateid FROM `" . DB_PREFIX . "geo`";
        



        

        if (!empty($data['filter_geo_name'])) {
            $sql .= " WHERE AND GEO_NAME LIKE '%" . $this->db->escape($data['filter_customer']) . "%' AND ACT > '0'";
        }
        else {
            $sql .= " WHERE ACT > '0'";
        }

        
        

        $sort_data = array(
            'id',
            'name',
            'status',
            'gtype',
            'nationid',
            'stateid'
                
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY SID";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
    
    
    
   public function getTotalGeo($data = array()) {
       
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "geo`";

        if (!empty($data['filter_geo_name'])) {
            $sql .= " WHERE AND GEO_NAME LIKE '%" . $this->db->escape($data['filter_customer']) . "%' AND ACT > '0'";
        }

       
         else {
            $sql .= " WHERE ACT > '0'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
    
}
