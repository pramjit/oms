<?php
class ModelBudgetbudgetlist extends Model {
	public function insert_purchase_order($data = array()){
		
		//insert order details
		if($data['supplier_id'] != "--Supplier--")
		{
			$this->db->query('INSERT INTO oc_po_order (order_date,user_id,pre_supplier_bit) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].',1)');
			$order_id = $this->db->getLastId();
		}
		else
		{
			$this->db->query('INSERT INTO oc_po_order (order_date,user_id) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].')');
			$order_id = $this->db->getLastId();
		}
		
		//insert product details
		
		for($i = 0; $i<count($data['products']); $i++)
		{
			$this->db->query("INSERT INTO oc_po_product (product_id,name,quantity,order_id,store_id,store_name)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['stores'][$i][0]."','".$data['stores'][$i][1]."')");
			$product_ids[$i] = $this->db->getLastId();
		}
		//insert attribute group
		$start_loop = 0;
		for($j = 0; $j<count($product_ids); $j++)
		{
			for($i = $start_loop; $i<count($data['options']); $i++)
			{
				if($data['options'][$i] != "new product")
				{
					$this->db->query("INSERT INTO oc_po_attribute_group (attribute_group_id,name,product_id) VALUES(".$data['options'][$i][0].",'".$data['options'][$i][1]."',".$product_ids[$j].")");
					$attribute_group_ids[$i] = $this->db->getLastId();
				}
				else
				{
					$start_loop = $i+1;
					$attribute_group_ids[$i] = "new product";
					break;
				}
			}
		}
		
		$start_loop = 0;
		for($i = 0; $i<count($attribute_group_ids); $i++)
		{
			if($attribute_group_ids[$i] != "new product")
			{
				for($j = $start_loop; $j<count($data['option_values']); $j++)
				{
					if($data['option_values'][$j] != "new product")
					{
						$this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i].")");
						$attribute_category_ids[$j] = $this->db->getLastId();
					}
					else
					{
						$attribute_category_ids[$j] = "new product";
					}
					$start_loop = $j + 1;
					break;
				}
			}
			else
			{
				for($j = $start_loop; $j<count($data['option_values']); $j++)
				{
					if($data['option_values'][$j] != "new product")
					{
						$this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i+1].")");
						$attribute_category_ids[$j] = $this->db->getLastId();
						$i = $i+1;
					}
					else
					{
						$attribute_category_ids[$j] = "new product";
					}
					$start_loop = $j + 1;
					break;
				}
			}
		}
		
		if($data['supplier_id'] != "--Supplier--")
		{
			for($i = 0; $i<count($data['products']); $i++)
			{
				$query = $this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$data['quantity'][$i].",".$data['products'][$i][0].",".$data['supplier_id'].",".$order_id.",'".$data['stores'][$i][0]."')");
			}
		}
		else{
			for($i = 0; $i<count($data['products']); $i++)
			{
				$query = $this->db->query("INSERT INTO oc_po_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][$i][0].",-1,".$order_id.",'".$data['stores'][$i][0]."')");
			}
		}
		
		return $order_id;
	}
	
	public function getList($start,$limit)
	{
		$query = $this->db->query("select os.sap_date as 'SAP_DATE',os.sap_ref as 'SAP_REF', os.product_id as 'PROD_ID', pp.model as 'PROD_NAME' ,
sum(os.sap_qty) as 'ORD_QTY', sum(os.disp_qty) as 'DIS_QTY', (sum(os.sap_qty)-sum(os.disp_qty)) as 'SAP_QTY'
FROM `oc_sap_ref` as os 
LEFT JOIN oc_product as pp on pp.product_id=os.product_id
GROUP BY os.product_id, os.sap_ref, os.sap_date");
		
		return $query->rows;
	}
          public function getdistrict(){
          
          
        $query = $this->db->query(" SELECT NAME,SID FROM `ak_mst_geo_upper` where TYPE = 4");
        return $query->rows;  
    }
        public function getFilterList($data = array())
	{
	        //$dist_id=$this->request->get['dist_id'];
                //if(empty($dist_id)){$dist_id = 'NULL';}
                $am_or_mo=$data['am_or_mo'];
                if(empty($am_or_mo)){$am_or_mo = 'NULL';}
                
                
                  
                $dist_id=$data['dist_id'];
                 $month=$data['month'];
              
                
               
		$sql="SELECT 
    CUSTOMER_ID,
    customer_group_id,
    customer_name,
    GROUP_CONCAT(DISTINCT(geoname)) AS geoname,
    GROUP_CONCAT(DISTINCT(GEO_ID)) AS geoid,
    month_name ,
    budget_km
FROM
    (SELECT 
        ac.CUSTOMER_ID,
            c.customer_group_id,
            CONCAT(c.firstname, ' ', c.lastname) AS customer_name,
            ac.GEO_ID,
            ifnull(ab.month_name,0)as month_name,
            ifnull(ab.budget_km,0)as budget_km,
            a.name AS geoname
    FROM
        `ak_customer_emp_map` AS ac
    LEFT JOIN (SELECT 
        sid, name
    FROM
        `ak_mst_geo_upper`
    WHERE
        type = 4) AS a ON a.sid = ac.GEO_ID
    LEFT JOIN ak_customer AS c ON c.customer_id = ac.CUSTOMER_ID
    LEFT JOIN ak_budget AS ab ON ab.customer_id = ac.CUSTOMER_ID
    WHERE
        ac.GEO_LEVEL_ID = 4 ";
                
               if(($dist_id!=NULL))
                {
                $sql.=" and ac.GEO_ID in (".$dist_id.") ";
                } 
           
            $sql.=" AND c.customer_group_id = IFNULL(".$am_or_mo.", c.customer_group_id)) AS aa
WHERE
    CUSTOMER_ID <> 0";
        /*  if(empty($month)) 
                {
                    $month='null';
                }else {
                    $month="'".$month."'";
                }  */
             //and month_name=ifnull($month,month_name)
    $sql.=" 
GROUP BY CUSTOMER_ID";
                    
              
//echo $sql;
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
        public function getTotalOrders()
	{
          $am_or_mo=$data['am_or_mo'];
                if(empty($am_or_mo)){$am_or_mo = 'NULL';}
                
                
                  
                $dist_id=$data['dist_id'];
                 $month=$data['month'];
                
               
		$sql="select count(*) as total from (select CUSTOMER_ID,customer_group_id,customer_name,GROUP_CONCAT(geoname)as geoname,GROUP_CONCAT(GEO_ID)as geoid from (

SELECT ac.CUSTOMER_ID,c.customer_group_id,concat(c.firstname,' ',c.lastname)as customer_name,ac.GEO_ID,
a.name as geoname  FROM `ak_customer_emp_map`as ac 
left join 
(SELECT sid,name FROM `ak_mst_geo_upper`where type=4)as a on a.sid = ac.GEO_ID

left join ak_customer as c on c.customer_id=ac.CUSTOMER_ID

    
where ac.GEO_LEVEL_ID=4";
                if(($dist_id!=NULL))
                {
                $sql.=" and ac.GEO_ID in (".$dist_id.") ";
                } 
                //$sql.="and ac.GEO_ID in ifnull(".$dist_id.",ac.GEO_ID) ";
                
                
                $sql.= " and c.customer_group_id=ifnull(".$am_or_mo.",c.customer_group_id) )as aa

where CUSTOMER_ID<>0 group by CUSTOMER_ID) as aa";
                

                
                //echo $sql;
$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
function budgetsubmitdata($order_info)
    {
    //print_r($order_info);
        $data=$this->request->post["budget"];
        $count=count($data);
        if($count==0){
            return $cnt=0;
        }
        else{
            $month=$this->request->post["month"];
            $cnt=0;
            for($a=0;$a<$count;$a++)
            {
                if(!empty($this->request->post["budget"][$a])){

                    $customerid=$this->request->post["customer_id"][$a];
                    $geo_id=$this->request->post["geo_id"][$a];
                    $budget=$this->request->post["budget"][$a];
                    $this->db->query("INSERT INTO ak_budget SET customer_id = '".$customerid. "', district_id = '".$geo_id. "', month_name = '".$month."', budget_km = '".$budget."' on duplicate key update budget_km='".$budget."'");
                    if($this->db->countAffected()==1){
                        $cnt++;
                    }
                }

            }
            return $cnt;
        }
             
    }
}
?>
