<?php
class ModelReportInventory extends Model {
	
	public function getInventory_report($data = array()) { //print_r($data);
	   $sql="
               select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id 
 where s.quantity>0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}
 

		
$sql .= " group by s.product_id";

            
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

	public function getTotalInventory($data = array()) {
		
            $sql="select count(*)as total from (
                select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id  where s.quantity>0
";
            
          
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}

		
$sql .= " group by s.product_id) as a";
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
public function getInventory_report_excel($data = array()) { //print_r($data);
       $sql="
               select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id
where s.quantity>0
";
            
            
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
            
        }
 

        
$sql .= " group by s.product_id";

            
            
                //echo $sql;
        $query = $this->db->query($sql);
                
        return $query->rows;
    }
  

   public function getInventory_reportProductWise($data = array()) { //print_r($data);
       $sql="
               select p2s.store_id,os.name as store_name, p2s.product_id,p.model as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,p2s.store_price as price,sum(p2s.store_price)as Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id
                
";
            
         
if (!empty($data['filter_name_id']) ) {
    $sql .=" where p2s.product_id=".$data['filter_name_id'];
            
        }
 

        
$sql .= " GROUP by p2s.store_id";
if (empty($data['filter_name_id']) ) {
    $sql .=" ,p2s.product_id ";
            
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
        //echo $sql;
        $query = $this->db->query($sql);
                
        return $query->rows;
}
public function getTotalInventoryProductWise($data = array()) {
        
            $sql="select count(*)as total from (
                select p2s.store_id,os.name as store_name, p2s.product_id,p.model as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,p2s.store_price as price,sum(p2s.store_price)as Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id
";
            
          
if (!empty($data['filter_name_id']) ) {
    $sql .=" where p2s.product_id=".$data['filter_name_id'];
            
        }

        
$sql .= " GROUP by p2s.store_id";
            if (empty($data['filter_name_id']) ) {
    $sql .=" ,p2s.product_id ";
            
        }
                $sql.=") as a";
                //echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
	
}