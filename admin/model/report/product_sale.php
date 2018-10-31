<?php
class ModelReportProductSale extends Model {
	
	public function getOrders($data = array()) { //print_r($data['filter_store']);
		
          /*  $sql = "select product_id,store_name,No_of_orders,Total_sales,Total_tax,
                (Total_sales+Total_tax)as Total, 
                dats from ( select p.order_id,p.product_id ,
                s.store_name,count(p.order_id)as No_of_orders,
                sum(p.total)as Total_sales,sum(p.tax)as Total_tax,
                date(p.ORD_DATE)as dats from oc_order_product as p 
                left join oc_order as s on p.order_id = s.order_id 
                group by date(p.ORD_DATE) )as a 
where a.product_id=ifnull('".$data['filter_name']."',a.product_id)
and a.dats between ifnull('".$data['filter_date_start']."',a.dats) and ifnull('".$data['filter_date_end']."',a.dats)";
*/
		$sql="select p.name,p.order_id,p.product_id , s.store_name,count(p.order_id)as No_of_orders, 
                   sum(p.total)as Total_sales,(sum(p.quantity)*(p.tax))as Total_tax, date(p.ORD_DATE)as dats from oc_order_product as p 
		   left join oc_order as s on p.order_id = s.order_id  ";
		if (!empty($data['filter_name'])) {
			$sql .= " where  p.product_id= ('".$data['filter_name']."') ";
		}
                else
                {       if (!empty($data['filter_date_start']) || !empty($data['filter_name']) ) {
                        $sql.=" where  ";
                        }
                        
                }
                
                if (!empty($data['filter_date_start']) && !empty($data['filter_name'])) {
			$sql .= " and DATE(s.date_added) >= ('".$data['filter_date_start']."') ";
		}
 
		else{ if (!empty($data['filter_date_start']))
                       {
			$sql .= "  DATE(s.date_added) >= ('".$data['filter_date_start']."') ";
                       }

		}
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and DATE(s.date_added) <= ('".$data['filter_date_end']."') ";
                        
		}


		    $sql.=" group by date(p.ORD_DATE),s.store_id,p.product_id order by s.date_added desc";
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

	public function getTotalOrders($data = array()) {
		$sql="select count(*) as total from (select p.name,p.order_id,p.product_id , s.store_name,count(p.order_id)as No_of_orders, 
                   sum(p.total)as Total_sales,sum(p.tax)as Total_tax, date(p.ORD_DATE)as dats from oc_order_product as p 
		   left join oc_order as s on p.order_id = s.order_id "; 
		if (!empty($data['filter_name'])) {
			$sql .= " where  p.product_id= ('".$data['filter_name']."') ";
		}
                else
                {
                        
                        if (!empty($data['filter_date_start']) || !empty($data['filter_name']) ) {
                        $sql.=" where  ";
                        }

                }
                
                if (!empty($data['filter_date_start']) && !empty($data['filter_name'])) {
			$sql .= " and DATE(s.date_added) >= ('".$data['filter_date_start']."') ";
		}
		else{
			if (!empty($data['filter_date_start']))
                       {
			$sql .= "   DATE(s.date_added) >= ('".$data['filter_date_start']."') ";
                       }

		}
                  if (!empty($data['filter_date_end'])) {
			$sql .= " and DATE(s.date_added) <= ('".$data['filter_date_end']."') ";
		}


		    $sql.=" group by date(p.ORD_DATE),s.store_id,p.product_id ";

		   $sql.=") as data";
		
                $query = $this->db->query($sql);

		return $query->row['total'];
	}
        
}