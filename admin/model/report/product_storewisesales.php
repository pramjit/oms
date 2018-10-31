<?php
class ModelReportProductStorewisesales extends Model {
	
	public function getSales($data = array()) {
		//$sql = "select sum(p.quantity) as quantity,sum(p.total) as total,sum(p.tax) as tax,o.store_name,p.product_id,p.name from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id";
                $sql="select sum(tt.quantity) as quantity,(sum(tt.total)+sum(tt.ttax)) as total,tt.name,tt.store_name,tt.product_id from 
                ( select sum(p.quantity) as quantity,sum(p.total) as total,(p.tax) as tax,(p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name 
               from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id ";

               $sql.="  where date(p.ORD_DATE)<= '".$data["filter_date_end"]."' ";
       
               if (!empty($data['filter_date_start'])) {
			$sql .= " and date(p.ORD_DATE)>='".$data["filter_date_start"]."' ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}

		


                $sql.=" GROUP by p.product_id,o.store_id,p.order_id ) as tt GROUP by tt.product_id,tt.store_name ";
if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalsales($data) {
		$sql="select count(*) as total from (select sum(tt.quantity) as quantity,(sum(tt.total)+sum(tt.ttax)) as total,tt.name,tt.store_name,tt.product_id from 
                ( select sum(p.quantity) as quantity,sum(p.total) as total,(p.tax) as tax,(p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name 
               from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id ";

               $sql.="  where date(p.ORD_DATE)<= '".$data["filter_date_end"]."' ";
       
               if (!empty($data['filter_date_start'])) {
			$sql .= " and date(p.ORD_DATE)>='".$data["filter_date_start"]."' ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}

		

                $sql.=" GROUP by p.product_id,o.store_id,p.order_id ) as tt GROUP by tt.product_id,tt.store_name ) as  aa";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}