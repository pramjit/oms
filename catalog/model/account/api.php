<?php
class ModelAccountApi extends Model {
	public function login($username, $password) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "' AND status = '1'");

		return $query->row;
	}

	public function loginm($username, $password) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

		return $query->row;
	}
        
        public function akuserid($ocid){
            $usql="select akc.customer_id as 'urole', akc.state_id as 'state', akp.GEO_ID as 'district'
from oc_user ocu 
left join ak_customer akc on(ocu.username = akc.user_id)
left join ak_customer_emp_map akp on(akc.customer_id = akp.customer_id and akp.GEO_LEVEL_ID=4)
where ocu.user_id=".$ocid;
            $query = $this->db->query($usql);
            return $query->row;
            
        }

}