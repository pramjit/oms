<?php
class ModelReportCash extends Model {
	
	public function getCash_report($data = array()) {
	   
//SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt RIGHT JOIN oc_store on oc_store.store_id=obt.store_id  WHERE DATE(obt.date_added) >= '2016-10-30' AND DATE(obt.date_added) <= '2017-01-18'
            $sql="SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id  ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " where DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            
            $sql.=" Order by obt.transid desc ";
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
                    //. "WHERE DATE(obt.date_added) >= '2016-10-30' "
                    //. "AND DATE(obt.date_added) <= '2017-01-18'";
            
            if (!empty($data['filter_date_start'])) {
			$sql .= " WHERE DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	
}