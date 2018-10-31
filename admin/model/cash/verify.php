<?php
class ModelCashVerify extends Model {
	
	public function getCash_report($data = array()) {
	   
            $sql="SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,obt.status,oc_store.name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id  ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " where DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
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

	public function getTotalCash_transation($data = array()) {
		
            $sql="SELECT count(obt.transid) as total FROM `oc_bank_transaction` as obt ";
                    
            if (!empty($data['filter_date_start'])) {
			$sql .= " WHERE DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        public function getTransactionTypes()
        {
            $sql="SELECT bank,bank_id FROM `oc_bank` order by bank_id desc ";
            $query = $this->db->query($sql);
            //print_r($query->rows);
            return $query->rows;
        }
        public function verify_cash($data=array())
        {
            $sql1="SELECT bank FROM `oc_bank` where `bank_id`='".$data["filter_trans_type"]."' ";
            $query1 = $this->db->query($sql1);
            $bank_name=$query1->row["bank"];
            
            $sql="insert into `oc_bank_transaction` (`user_id`,`store_id`,`bank_id`,`bank_name`,`amount`,`deposit_date`,`transaction_number`,`branch_code`,`branch_location`,`remarks`,`verified_by`,`status`) VALUES ('0','".$data["filter_store"]."','".$data["filter_trans_type"]."','".$bank_name."','".$data["deposit_amount"]."','".$data["deposit_date"]."','".$data["transaction_number"]."','".$data["branch_code"]."','".$data["branch_location"]."','".$data["remarks"]."','".$data["logged_user"]."','1')";//  ,"."',`transaction_number`='',`branch_code`=,`branch_location`=,`remarks`=,`verified_by`=,`status`='1' where `transid`='".$data["transid"]."' ";
            $query = $this->db->query($sql);
            $query2 = $this->db->query("UPDATE oc_store SET currentcredit = currentcredit - '".$data["deposit_amount"]."' WHERE store_id='".$data["filter_store"]."'");
        }
        public function insert_into_store_trans($data=array())
        {
            $sql="insert into  oc_store_trans set `store_id`='".$data["store_id"]."',`amount`='".$data["deposit_amount"]."',`transaction_type`='3',`cr_db`='DB',`user_id`='".$data["logged_user"]."' ";
            $query = $this->db->query($sql);
            
        }
        public function getstoresdata($store_id)
        {
         $sql="SELECT currentcredit from oc_store  where `store_id`='".$store_id."' limit 1 ";
                  
		$query = $this->db->query($sql);
                
		return $query->row;
        }
        public function getCash_record($data=array())
        {
                $sql="SELECT oc_bank_transaction.*,oc_store.* FROM `oc_bank_transaction` join oc_store on oc_store.store_id=oc_bank_transaction.store_id  where `transid`='".$data["transid"]."' limit 1 ";
                  
		$query = $this->db->query($sql);
                
		return $query->row;
        }
	
}