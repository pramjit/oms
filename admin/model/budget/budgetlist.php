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
	        
                $am_or_mo=$data['am_or_mo'];
                $dist_id=$data['dist_id'];
                $month=$data['month'];
                
                
                $sql="SELECT CUST.customer_id AS 'CUST_ID',
                    (CASE
                    WHEN CUST.customer_group_id='3' THEN 'AREA MANAGER' 
                    WHEN CUST.customer_group_id='4' THEN 'MARKETING OFFICER' 
                    ELSE 'NA' END
                    )AS 'CUST_GROUP_NAME',
                    CONCAT(CUST.firstname,' ',CUST.lastname) AS 'CUST_NAME',
                    GROUP_CONCAT(GEO.DIST_ID) AS 'CUST_DIST', GROUP_CONCAT(GEO.DIST_NAME) AS 'CUST_DIST_NAME', 
                    IFNULL(BDGT.budget_km,0.00) AS 'ALL_BDGT_KM', IFNULL(BDGT.add_budget_km,0.00) AS 'ADD_BDGT_KM',IFNULL(BDGT.month_name,'') AS 'BDGT_MONTH'
                    FROM ak_customer CUST 
                    LEFT JOIN  
                    (SELECT CGEO.CUSTOMER_ID AS 'CUSTOMER_ID',CGEO.GEO_ID AS 'DIST_ID',UGEO.`NAME` AS 'DIST_NAME'
                    FROM ak_customer_emp_map CGEO
                    LEFT JOIN ak_mst_geo_upper AS UGEO ON(CGEO.GEO_ID=UGEO.SID)
                    WHERE CGEO.GEO_LEVEL_ID=4 AND CGEO.CUSTOMER_ID<>0
                    GROUP BY CGEO.CUSTOMER_ID,CGEO.GEO_ID ORDER BY CGEO.CUSTOMER_ID
                    ) AS GEO ON (CUST.customer_id=GEO.CUSTOMER_ID)
                    LEFT JOIN (SELECT customer_id,district_id,budget_km,add_budget_km,month_name FROM ak_budget WHERE month_name LIKE '%".$month."%' ) BDGT ON(CUST.customer_id = BDGT. customer_id)

                    WHERE CUST.customer_group_id IN(3,4) ";
                     if($dist_id!=NULL){
                        $sql .=" AND GEO.DIST_ID IN(".$dist_id.")";
                    }
                    if($am_or_mo!=NULL){
                        $sql .=" AND CUST.customer_group_id IN(".$am_or_mo.")";
                    }

                    $sql .=" GROUP BY CUST.customer_id";
                    
              
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
        
        
        
        
        public function getTotalOrders($data = array()){
                    $am_or_mo=$data['am_or_mo'];
                    $dist_id=$data['dist_id'];
                    $month=$data['month'];
                
                
                $sql="SELECT COUNT(CUST_ID) AS 'TOTAL' FROM
                    (SELECT CUST.customer_id AS 'CUST_ID',
                    (CASE
                    WHEN CUST.customer_group_id='3' THEN 'AREA MANAGER' 
                    WHEN CUST.customer_group_id='4' THEN 'MARKETING OFFICER' 
                    ELSE 'NA' END
                    )AS 'CUST_GROUP_NAME',
                    CONCAT(CUST.firstname,' ',CUST.lastname) AS 'CUST_NAME',
                    GROUP_CONCAT(GEO.DIST_ID) AS 'CUST_DIST', GROUP_CONCAT(GEO.DIST_NAME) AS 'CUST_DIST_NAME', 
                    IFNULL(BDGT.budget_km,0.00) AS 'ALL_BDGT_KM', IFNULL(BDGT.add_budget_km,0.00) AS 'ADD_BDGT_KM',IFNULL(BDGT.month_name,'') AS 'BDGT_MONTH'
                    FROM ak_customer CUST 
                    LEFT JOIN  
                    (SELECT CGEO.CUSTOMER_ID AS 'CUSTOMER_ID',CGEO.GEO_ID AS 'DIST_ID',UGEO.`NAME` AS 'DIST_NAME'
                    FROM ak_customer_emp_map CGEO
                    LEFT JOIN ak_mst_geo_upper AS UGEO ON(CGEO.GEO_ID=UGEO.SID)
                    WHERE CGEO.GEO_LEVEL_ID=4 AND CGEO.CUSTOMER_ID<>0
                    GROUP BY CGEO.CUSTOMER_ID,CGEO.GEO_ID ORDER BY CGEO.CUSTOMER_ID
                    ) AS GEO ON (CUST.customer_id=GEO.CUSTOMER_ID)
                    LEFT JOIN (SELECT customer_id,district_id,budget_km,add_budget_km,month_name FROM ak_budget WHERE month_name LIKE '%".$month."%' ) BDGT ON(CUST.customer_id = BDGT. customer_id)

                    WHERE CUST.customer_group_id IN(3,4) ";
                     if($dist_id!=NULL){
                        $sql .=" AND GEO.DIST_ID IN(".$dist_id.")";
                    }
                    if($am_or_mo!=NULL){
                        $sql .=" AND CUST.customer_group_id IN(".$am_or_mo.")";
                    }

                    $sql .=" GROUP BY CUST.customer_id) AS BDGT";
                    
              
                    //echo $sql;
                    $query = $this->db->query($sql);
                    return $query->row['TOTAL'];
	}
function budgetsubmitdata($order_info)
    {
    //print_r($order_info);
       $data=$this->request->post["budget"];
       
       $count=count($data);
       $month=$this->request->post["month"];
       for($a=0;$a<$count;$a++)
       {
            if(!empty($this->request->post["budget"][$a]))
            {
                $customerid=$this->request->post["customer_id"][$a];
                $geo_id=$this->request->post["geo_id"][$a];
                $budget=$this->request->post["budget"][$a];
                $add_budget=$this->request->post["add_budget"][$a];
                $bud_sql="INSERT INTO ak_budget SET customer_id = '".$customerid. "', district_id = '".$geo_id. "', month_name = '".$month."', budget_km = '".$budget."' on duplicate key update budget_km='".$budget."'";
                $this->db->query($bud_sql);
                
        
            }
            if(!empty($this->request->post["add_budget"][$a]))
            {
                $customerid=$this->request->post["customer_id"][$a];
                $geo_id=$this->request->post["geo_id"][$a];
                $budget=$this->request->post["budget"][$a];
                $add_budget=$this->request->post["add_budget"][$a];
                $add_bud_sql="UPDATE ak_budget SET add_budget_km = '".$add_budget."' where customer_id = '".$customerid. "'  AND month_name = '".$month."' AND add_budget_km='0.00' ";
                $this->db->query($add_bud_sql);
        
            }
       } 
       
	
    }
    public function avlBudgetKm($empid,$monyr){
        $sql="SELECT 
            ab.customer_id AS 'EMP_ID',
            ab.month_name AS 'MON_YR',
            (ab.budget_km+ab.add_budget_km)  AS 'ALLOW_KM',
            ifnull(sum((adt.close_mtr-adt.open_mtr) ),0.00) AS 'USE_KM', 
            ifnull(((ab.budget_km+ab.add_budget_km)-sum((adt.close_mtr-adt.open_mtr) )),0.00) as 'AVL_KM', 
            date_format(adt.ta_date,'%M%Y') year_month1
            FROM ak_budget ab
            LEFT JOIN ak_daily_ta adt ON adt.ta_emp_id=ab.customer_id AND date_format(adt.ta_date,'%M%Y')=ab.month_name
            WHERE ta_emp_id='".$empid."' AND month_name LIKE '%".$monyr."%'
            GROUP BY year_month1, adt.ta_emp_id, ab.month_name";
            $query = $this->db->query($sql);
            return $query->row;
    }
}
?>
