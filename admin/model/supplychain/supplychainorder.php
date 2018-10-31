<?php
class Modelsupplychainsupplychainorder extends Model {
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
        public function getFilterList()
	{
		$stoid=$this->request->get['sto_id'];
                if(empty($stoid)){$stoid = 'NULL';}
                $sapid=$this->request->get['sap_id'];
                if(empty($sapid)){$sapid = 'NULL';}else{$sapid= "'".$sapid."'";}
                $proid=$this->request->get['pro_id'];
                if(empty($proid)){$proid = 'NULL';}
                $sql_sco="select os.sap_date as 'SAP_DATE',os.sap_ref as 'SAP_REF',os.Store_id as 'STO_ID',ocs.`name` as 'STO_NAME', os.product_id as 'PROD_ID',
pp.model as 'PROD_NAME',sum(os.sap_qty) as 'ORD_QTY', sum(os.disp_qty) as 'DIS_QTY', (sum(os.sap_qty)-sum(os.disp_qty)) as 'SAP_QTY',CONCAT(ocu.firstname,ocu.lastname) as 'MO_NAME'

FROM `oc_sap_ref` as os 
LEFT JOIN oc_product as pp on pp.product_id=os.product_id
LEFT JOIN oc_store as ocs on os.Store_id=ocs.store_id
LEFT JOIN oc_po_order opp on os.Indent_id=opp.id
LEFT JOIN oc_user ocu on opp.user_id=ocu.user_id
WHERE os.sap_ref = ifnull(".$sapid.",os.sap_ref) 
AND os.product_id = ifnull(".$proid.",os.product_id)
AND os.Store_id = ifnull(".$stoid.",os.Store_id )
GROUP BY os.product_id, os.sap_ref, os.sap_date,os.Store_id";
		$query = $this->db->query($sql_sco);
		return $query->rows;
	}
        public function getTotalOrders()
	{
		$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = " . 1);
		$results = $query->row;
		return $results['total_orders'];
	}
	public function view_order_details($order_id)
	{
		$query = $this->db->query("SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
				FROM oc_po_order
					LEFT JOIN ".DB_PREFIX."user
						ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
							WHERE id = " . $order_id . " AND delete_bit = " . 1);
		$order_info = $query->row;

		$view_order_details="SELECT
        oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,
                oc_po_receive_details.price,oc_po_supplier.first_name,
                oc_po_supplier.last_name,oc_po_supplier.id as supplier_id,
                oc_po_receive_details.order_id
        FROM
            oc_po_receive_details
        LEFT JOIN oc_po_product
            ON (oc_po_receive_details.order_id = oc_po_product.order_id)
            AND (oc_po_receive_details.product_id = oc_po_product.product_id)
        INNER JOIN oc_po_supplier
            ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
                WHERE (oc_po_receive_details.order_id =".$order_id.")";
	
		$query = $this->db->query($view_order_details);

		/*$query = $this->db->query("SELECT
		oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,oc_po_receive_details.price,oc_po_supplier.first_name,oc_po_supplier.last_name,oc_po_supplier.id as supplier_id,oc_po_receive_details.order_id
		FROM
			oc_po_receive_details
		INNER JOIN oc_po_product 
			ON (oc_po_receive_details.order_id = oc_po_product.order_id)
		INNER JOIN oc_po_supplier 
			ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
				WHERE (oc_po_receive_details.order_id =".$order_id.")");*/

	if($this->db->countAffected() > 0)
	{
		$products = $query->rows;
		$quantities = array();
		$all_quantities = array();
		$prices = array();
		$all_prices = array();
		$suppliers = array();
		$all_suppliers = array();
		$supplier_names = array();
		$all_supplier_names = array();
		$index = 0;
		$index1 = 0;
		for($i =0; $i<count($products); $i++)
		{
			if($products[$i] != "")
			{
				for($j = 0; $j<count($products); $j++)
				{
					if($products[$j] != "")
					{
						if($products[$i]['id'] == $products[$j]['id'])
						{
							$quantities[$index] = $products[$j]['rd_quantity'];
							$supplier_names[$index] = $products[$j]['first_name'] ." ". $products[$j]['last_name'];
							$suppliers[$index] = $products[$j]['supplier_id'];
							$prices[$index] = $products[$j]['price'];
							if($j!=$i)
							{
								$products[$j] = "";
							}
							$index++;
						}
					}
				}
				$index = 0;
				$all_quantities[$index1] = $quantities;
				$all_suppliers[$index1] = $suppliers;
				$all_prices[$index1] = $prices;
				$all_supplier_names[$index1] = $supplier_names;
				unset($quantities);
				unset($suppliers);
				unset($prices);
				unset($supplier_names);
				$quantities = array();
				$suppliers = array();
				$prices = array();
				$supplier_names = array();
				$index1++;
			}
		}
		$products = array_values(array_filter($products));
		for($i = 0; $i<count($products); $i++)
		{
			unset($products[$i]['rd_quantity']);
			unset($products[$i]['first_name']);
			unset($products[$i]['last_name']);
			$products[$i]['quantities'] = $all_quantities[$i];
			$products[$i]['suppliers'] = $all_suppliers[$i];
			$products[$i]['prices'] = $all_prices[$i];
			$products[$i]['supplier_names'] = $all_supplier_names[$i];
		}
	}
	else
	{
		$query = $this->db->query("SELECT * FROM oc_po_product WHERE order_id = " . $order_info['id']);
		$products = $query->rows;
	}
		$i = 0;
		foreach($products as $product)
		{
			$query = $this->db->query("SELECT * FROM oc_po_attribute_group WHERE product_id = ". $product['id']);
			$attribute_groups[$i] = $query->rows;
			$i++;
		}
		
		$i = 0;
		foreach($attribute_groups as $attribute_group)
		{
			for($j = 0; $j<count($attribute_group);$j++)
			{
				$query = $this->db->query("SELECT * FROM oc_po_attribute_category WHERE attribute_group_id = ". $attribute_group[$j]['id']);
				$attribute_categories[$i] = $query->row;
				$i++;
			}
		}
		for($i=0;$i<count($products); $i++)
		{
			for($j=0; $j<count($attribute_groups[$i]);$j++)
			{
				$products[$i]['attribute_groups'][$j] = $attribute_groups[$i][$j]['name'];
			}
		}
		$start_loop = 0;
		//$attribute_categories = array_values(array_filter($attribute_categories));
		//print_r($attribute_categories);
		//exit;
		for($i=0; $i<count($products); $i++)
		{
			for($j=$start_loop; $j<($start_loop + count($products[$i]['attribute_groups']));$j++)
			{
				$products[$i]['attribute_category'][$j] = $attribute_categories[$j]['name'];
			}
			$start_loop = $j;
		}
		$order_information['products'] = $products;
		$order_information['order_info'] = $order_info;
		return $order_information;
	}
	public function delete($ids)
	{
		$deleted = false;
		foreach($ids as $id)
		{
			if($this->db->query("UPDATE oc_po_order SET delete_bit = " . 0 ." WHERE id = " . $id))
				$deleted = true;
		}
		if($deleted)
		{
			return $deleted;
		}
		else
		{
			return false;
		}
	}
	public function filterCount($filter)
	{
		if(isset($filter['from']))
		{
			$filter['from'] = strtotime($filter['from']);
			$filter['from'] = date('Y-m-d',$filter['from']);
		}
		
		if(isset($filter['to']))
		{
			$filter['to'] = strtotime($filter['to']);
			$filter['to'] = date('Y-m-d',$filter['to']);
		}
		
		$query = "SELECT
			oc_po_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			FROM
			oc_po_order
			INNER JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
			INNER JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.delete_bit = 1";
		
		if(isset($filter['filter_id']))
		{
			$query = $query . " AND oc_po_order.id = " . $filter['filter_id'];
		}
		
		if(isset($filter['status']))
		{
			$query = $query . " AND receive_bit = " . $filter['status'];
		}
		
		if(isset($filter['from']) && isset($filter['to']))
		{
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['from']))
		{
			$filter['to'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['to']))
		{
			$filter['from'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		
		$query = $query . " GROUP BY (oc_po_order.id) ORDER BY oc_po_order.id DESC";
		
		$query = $this->db->query($query);
		
		return count($query->rows);
	}
	public function filter($filter,$start,$limit){
		
		if(isset($filter['from']))
		{
			$filter['from'] = strtotime($filter['from']);
			$filter['from'] = date('Y-m-d',$filter['from']);
		}
		
		if(isset($filter['to']))
		{
			$filter['to'] = strtotime($filter['to']);
			$filter['to'] = date('Y-m-d',$filter['to']);
		}
		
		$query = "SELECT
			oc_po_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			FROM
			oc_po_order
			INNER JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
			INNER JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.delete_bit = 1";
		
		if(isset($filter['filter_id']))
		{
			$query = $query . " AND oc_po_order.id = " . $filter['filter_id'];
		}
		
		if(isset($filter['status']))
		{
			$query = $query . " AND receive_bit = " . $filter['status'];
		}
		
		if(isset($filter['from']) && isset($filter['to']))
		{
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['from']))
		{
			$filter['to'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['to']))
		{
			$filter['from'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		
		$query = $query . " GROUP BY (oc_po_order.id) ORDER BY oc_po_order.id DESC LIMIT ". $start ."," . $limit;
		$query = $this->db->query($query);
		return $query->rows;
	}

	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function insert_receive_order($received_order_info,$order_id)
	{
		if($received_order_info['order_receive_date'] != '')
		{
			$received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
			$received_order_info['order_receive_date'] = date('Y-m-d',$received_order_info['order_receive_date']);
		}
		$inner_loop_limit = count($received_order_info['received_quantities']);
		$quantities = array();
		$quantity = 0;
		//$this->db->query("UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 0 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);
		// 	order_sup_send
$this->db->query("UPDATE oc_po_order SET order_sup_send = '" .$received_order_info['order_receive_date']."', receive_bit = " . 0 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);

		//if pre selected supplier
		if(count($received_order_info['received_quantities']) != count($received_order_info['suppliers_ids']))
		{
			for($i =0; $i<count($received_order_info['prices']); $i++)
			{
				if($received_order_info['prices'][$i] != "next product")
				{
					$prices[$i] = $received_order_info['prices'][$i];
				}
			}
			
			for($i =0; $i<count($received_order_info['received_quantities']); $i++)
			{
				if($received_order_info['received_quantities'][$i] != "next product")
				{
					$received_quantities[$i] = $received_order_info['received_quantities'][$i];
				}
			}
			
			$prices = array_values($prices);
			$received_quantities = array_values($received_quantities);
			
			for($i =0; $i<count($prices); $i++)
			{
				$this->db->query("UPDATE oc_po_receive_details SET price =" .$prices[$i]. ", quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
				$query = $this->db->query("SELECT quantity FROM oc_po_receive_details WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id =" . $order_id);
				$quantities[$i] = $query->row['quantity'];
			}
		}
		else
		{
			$query = $this->db->query("SELECT * FROM oc_po_receive_details WHERE order_id=".$order_id);
			
			if(count($query->rows) > 0)
			{
				$this->db->query("DELETE FROM oc_po_receive_details WHERE order_id=".$order_id);
			}
		
			for($j = 0; $j<count($received_order_info['received_product_ids']); $j++)
			{
				for($k = 0; $k<$inner_loop_limit; $k++)
				{
					
					if($received_order_info['received_quantities'][$k] != 'next product')
					{
						$this->db->query("INSERT INTO oc_po_receive_details (quantity,price,product_id,supplier_id,order_id,store_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['prices'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.",".$query->rows[$j]["store_id"].")");
						$quantity = $quantity + $received_order_info['received_quantities'][$k];
						unset($received_order_info['received_quantities'][$k]);
						unset($received_order_info['suppliers_ids'][$k]);
						unset($received_order_info['prices'][$k]);
					}
					else
					{
						unset($received_order_info['received_quantities'][$k]);
						unset($received_order_info['suppliers_ids'][$k]);
						unset($received_order_info['prices'][$k]);
						$received_order_info['received_quantities'] = array_values($received_order_info['received_quantities']);
						$received_order_info['suppliers_ids'] = array_values($received_order_info['suppliers_ids']);
						$received_order_info['prices'] = array_values($received_order_info['prices']);
						break;
					}
				}
				$quantities[$j] = $quantity;
				$quantity = 0;
			}
		}
		$bool = false;
		for($i=0; $i<count($quantities); $i++)
		{
			$query = $this->db->query("SELECT DISTINCT product_id FROM oc_po_product WHERE id = " . $received_order_info['received_product_ids'][$i]);
			$product_ids[$i] = $query->row;
			$query1 = $this->db->query("UPDATE oc_po_product SET received_products = " . $quantities[$i] . " WHERE id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
		}
		for($i=0; $i<count($product_ids); $i++)
		{
			$query = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id = " . $product_ids[$i]['product_id']);
			$quantity = $query->row['quantity'] + $quantities[$i];
			$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
			if($query && $query1)
				$bool = true;
		}
		if($bool)
			return true;
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
        
        
        /********************************************************/
        function getmoname($data)
        {
                 
          $query = $this->db->query("SELECT username,user_id,firstname FROM `oc_user` where user_group_id='4' and firstname like '%".$data["filter_name"]."%' order by firstname asc  limit 5  ");
       
          return $query->rows;  
        }
         function getwspname()
        {
                 
         $query = $this->db->query("SELECT username,store_id,firstname FROM `oc_user` where user_group_id='10' and firstname like '%".$data["filter_name"]."%' order by firstname asc  ");
       
          return $query->rows;  
        }
        
        function getsap()
        {
                 
          $query = $this->db->query("SELECT distinct sap_ref FROM `oc_sap_ref`");
       
          return $query->rows;  
        }
        
        public function getWS(){
            $query = $this->db->query("SELECT store_id AS 'WS_ID',CONCAT(firstname,' (',username,')') AS 'WS_NAME' FROM `oc_user` WHERE user_group_id='10' ORDER BY firstname ASC");
            return $query->rows; 
        }
        public function getSP(){
            $query = $this->db->query("SELECT DISTINCT sap_ref AS 'SAP_ID', ind_date FROM `oc_sap_ref` ORDER BY Indent_id DESC");
            return $query->rows;
        }
        public function getPRO(){
            $query = $this->db->query("SELECT product_id AS 'PRO_ID', model AS 'PRO_NAME' FROM oc_product WHERE quantity<>0 ORDER BY model ASC");
            return $query->rows;
        }
                
        
        function sorderdispatch()
        {
           $log=new Log("Disp_Po_Mod".date('Ymd').".log");
            //print_r($_REQUEST);
            $dispqty    =   $_REQUEST['disp_order']; //Dipatch Quantity 
            $dispprd    =   $_REQUEST['dis_prod_id'];//Product Id
            $disp       =   $_REQUEST['dis_sap_id']; //Sap Reference Id
            
            $rand_disp_id = "DISP".DATE('Ymd').rand(1111, 9999); 
            $tname      =   $_REQUEST['tname'];
            $vnum       =   $_REQUEST['vnum'];
            $grdtl      =   $_REQUEST['grdtl'];
            $dnum       =   $_REQUEST['dnum'];
            $inum       =   $_REQUEST['inum'];
            
            
            
            //print_r($_FILES['UPLOAD']);
            $exp=explode('_',$disp);
            $expdisp=explode('_',$dispqty);
            $expprdid= explode('_', $dispprd);
            $count=count($exp);
            $log->write($count);
            $tdate=DATE("Y-m-d");
            //****************Insert OC_DISPATCH & OC_DISPATCH_DETAILS Table*****************//
            if($_FILES['UPLOAD']['name']!="")
            {   
                $file = $rand_disp_id.'_'.$_FILES['UPLOAD']['name']; 
                move_uploaded_file($_FILES['UPLOAD']['tmp_name'], DIR_UPLOAD . $file);
            } 
            $sqldisp="insert into oc_dispatch set dispatch_id='".$rand_disp_id."' ,transport_name='".$tname."' ,vehicle_no='".$vnum."' ,grr_details='".$grdtl."' ,driver_mob='".$dnum."' ,invoice_no='".$inum."' ,upload_doc='".$_FILES['UPLOAD']['name']."'";
            $this->db->query($sqldisp);
            
            //****************Insert OC_DISPATCH & OC_DISPATCH_DETAILS Table Ends*****************//
            $msgArr=array();
            for($i=0;$i<$count-1;$i++)
            {
               
                $odr_sql="select id, Indent_id, product_id, ind_qty, sap_qty, disp_qty,store_id from oc_sap_ref where sap_ref='".$exp[$i]."' and product_id='".$expprdid[$i]."' order by id asc";
                $log->write($odr_sql);
                $data=$this->db->query($odr_sql);
                $odrs=$data->rows;
                $tot=count($odrs);
                $sup_qty=$expdisp[$i]; //SUPPLY QUANTITY
                //--------------------------------------//
                $t = microtime(true);
                $micro = sprintf("%02d",($t - floor($t)) * 100);
                $utc = date('ymdHis', $t).$micro;
                $transid=$utc;
                $log->write($transid);
                //--------------------------------------//
                
                for($n=0; $n<$tot;$n++)
                {   
                    $sto_id=$odrs[$n]['store_id'];  //Store Id
                    $ind_qty=round($odrs[$n]['ind_qty']);  //IND Quantity
                    $sap_qty=round($odrs[$n]['sap_qty']);  //SO Quantity
                    $dis_qty=round($odrs[$n]['disp_qty']); //DISP Quantity
                    $req_qty = $sap_qty - $dis_qty;        //REQ Quantity
                    
                    $up_ind_id=$odrs[$n]['Indent_id']; //Indent Id
                    $up_prd_id=$odrs[$n]['product_id']; //Product Id       
                    $typ_ref = $odrs[$n]['id'];
                    if($req_qty!= 0 && $sup_qty!=0 && $req_qty > $sup_qty)
                    {   
                        $up_dis_qty = $sup_qty;
                        $sql="update oc_sap_ref SET disp_qty=disp_qty+'".$up_dis_qty."',disp_date='".$tdate."' WHERE sap_ref='".$exp[$i]."' and Indent_id='".$up_ind_id."' and product_id='".$up_prd_id."'";
                        $log->write($sql);
                        $this->db->query($sql);
                        
                        //**************************//
                        $sqldtls="insert into oc_dispatch_details set dispatch_id='".$rand_disp_id."' ,sap_ref_no='".$exp[$i]."' ,indent_no='".$up_ind_id."' ,product_id='".$up_prd_id."' ,ind_qty='".$ind_qty."',so_qty='".$sap_qty."' ,disp_qty='".$up_dis_qty."', store_id='".$sto_id."'";
                        $log->write($sqldtls);
                        $this->db->query($sqldtls);
                        $this->db->query("INSERT INTO oc_oms_trans (type,user_id,trans_id,trans_date,product_id,product_qty,cr_date,type_ref,sap_ref)VALUES('4','".$this->session->data['user_id']."','".$transid."','". date('Y-m-d') ."',".$up_prd_id."," . $up_dis_qty.",'". date('Y-m-d') ."','".$typ_ref."','".$exp[$i]."')");
                        $log->write("INSERT INTO oc_oms_trans (type,user_id,trans_id,trans_date,product_id,product_qty,cr_date,type_ref,sap_ref)VALUES('4','".$this->session->data['user_id']."','".$transid."','". date('Y-m-d') ."',".$up_prd_id."," . $up_dis_qty.",'". date('Y-m-d') ."','".$typ_ref."','".$exp[$i]."')");
                    
                        //**************************//
                        
                        $up_sql="update oc_po_product set received_products = received_products+'".$up_dis_qty."', item_status = 2 where order_id='".$up_ind_id."' and product_id='".$up_prd_id."'";
                        $log->write($up_sql);
                        $this->db->query($up_sql);
                        $ret_id = $this->db->countAffected();  
                        //Added for Message
                        $indArr=array('IndId'=>$up_ind_id,'DisQty'=>$up_dis_qty,'StoId'=>$sto_id);
                        $sup_qty = $sup_qty-$up_dis_qty;
                        
                    }
                    if($req_qty!= 0 && $sup_qty!=0 && $req_qty <= $sup_qty)
                    {
                       $up_dis_qty=$req_qty;
                       $sql="update  oc_sap_ref SET disp_qty=disp_qty+'".$up_dis_qty."',disp_date='".$tdate."' WHERE sap_ref='".$exp[$i]."' and Indent_id='".$up_ind_id."' and product_id='".$up_prd_id."'";
                       $log->write($sql);
                       $this->db->query($sql);
                        //**************************//
                        $sqldtls="insert into oc_dispatch_details set dispatch_id='".$rand_disp_id."' ,sap_ref_no='".$exp[$i]."' ,indent_no='".$up_ind_id."' ,product_id='".$up_prd_id."' ,ind_qty='".$ind_qty."',so_qty='".$sap_qty."' ,disp_qty='".$up_dis_qty."', store_id='".$sto_id."'";
                        $log->write($sqldtls);
                        $this->db->query($sqldtls);
                        $this->db->query("INSERT INTO oc_oms_trans (type,user_id,trans_id,trans_date,product_id,product_qty,cr_date,type_ref,sap_ref)VALUES('4','".$this->session->data['user_id']."','".$transid."','". date('Y-m-d') ."',".$up_prd_id."," . $up_dis_qty.",'". date('Y-m-d') ."','".$typ_ref."','".$exp[$i]."')");
                        $log->write("INSERT INTO oc_oms_trans (type,user_id,trans_id,trans_date,product_id,product_qty,cr_date,type_ref,sap_ref)VALUES('4','".$this->session->data['user_id']."','".$transid."','". date('Y-m-d') ."',".$up_prd_id."," . $up_dis_qty.",'". date('Y-m-d') ."','".$typ_ref."','".$exp[$i]."')");
                    
                        //**************************//                       
                       $up_sql="update oc_po_product set received_products = received_products+'".$up_dis_qty."', item_status = 2  where order_id='".$up_ind_id."' and product_id='".$up_prd_id."'";
                       $log->write($up_sql);
                       $this->db->query($up_sql);
                       $ret_id = $this->db->countAffected(); 
                       //Added for Message
                       $indArr=array('IndId'=>$up_ind_id,'DisQty'=>$up_dis_qty,'StoId'=>$sto_id);
                       $sup_qty = $sup_qty - $up_dis_qty;
                    }
                //**************** CHECK AND UPDATE INDENT STATUS START*******************//
                $chksts="select opo.order_status as 'ORD_STS', opp.so_qty as 'SO_QTY', opp.received_products as 'DIS_QTY' 
                         from oc_po_order opo
                         left join oc_po_product opp on(opo.id=opp.order_id)
                         where opo.id='".$up_ind_id."'";  
                $qdata=$this->db->query($chksts);
                $chkdata=$qdata->rows;
                $ord_sts = $chkdata[0]['ORD_STS'];
                $eq=0;
                foreach ($chkdata as $result)
                {
                    if(($result['ORD_STS']== 3) && ($result['SO_QTY']!=$result['DIS_QTY']))
                    {
                        $eq++;
                    }
                }
                if($ord_sts == 3 && $eq>0){
                    $this->db->query("update oc_po_order set order_status = 4 where id='".$up_ind_id."'"); 
                }
                if(($ord_sts == 3 || $ord_sts == 4 ) && $eq == 0){
                    $this->db->query("update oc_po_order set order_status = 9 where id='".$up_ind_id."'");
                }
                
                //**************** CHECK AND UPDATE INDENT STATUS END*******************//  
                array_push($msgArr, $indArr);
                
                }
                    
            }
            
        if($ret_id=='1'){
             return $msgArr;
        }
        else{
           return '0';
        }
       
        }
        /********************************************************/
        public function msgDtls($indId){
            $sql="SELECT 
            OPO.id AS 'IND_ID',
            MO.telephone AS 'MO_MOB',
            MO.firstname AS 'MO_NAME',
            AM.telephone AS 'AM_MOB',
            AM.firstname AS 'AM_NAME',
            OPO.store_id AS 'STO_ID',
            DL.telephone AS 'STO_MOB',
            CONCAT(DL.firstname,' ',DL.lastname) AS 'STO_NAME'
            FROM oc_po_order OPO
            JOIN(SELECT user_id,customer_id FROM oc_user WHERE user_group_id IN(3,4)) EMP ON(OPO.user_id=EMP.user_id)
            JOIN(SELECT CUSTOMER_ID, PARENT_USER_ID FROM ak_customer_map) MAP ON(EMP.customer_id=MAP.CUSTOMER_ID)
            JOIN(SELECT customer_id,telephone,firstname FROM ak_customer) MO ON( MAP.CUSTOMER_ID=MO.customer_id)
            JOIN(SELECT customer_id,telephone,firstname FROM ak_customer) AM ON( MAP.PARENT_USER_ID=AM.customer_id)
            JOIN(SELECT user_id,customer_id,store_id FROM oc_user WHERE user_group_id IN(10)) STO ON(OPO.store_id=STO.store_id)
            JOIN(SELECT customer_id,telephone,firstname,lastname FROM ak_customer) DL ON( STO.customer_id =DL.customer_id)
            WHERE id='".$indId."'";
            $query=$this->db->query($sql);
            return $query->row;
        }
        function msgMaster($OrderId,$mType,$CrId,$StoId,$textmsg,$numbers,$responseBody){
            $crData=date('Y-m-d H:i:s');
            $sql="INSERT INTO `oc_msg_master` SET
            `CR_DATE` ='".$crData."',
            `TRANS_TYPE`  ='".$mType."',
            `ORDER_ID`  ='".$OrderId."',
            `CR_BY`  ='".$CrId."',
            `STORE_ID`  ='".$StoId."',
            `MESSAGE`  ='".$textmsg."',
            `SENT_TO`  ='".$numbers."',
            `SENT_RESPONSE`  ='".$responseBody."'";
            if($this->db->query($sql)){
                return 1;
            }
        }
}

?>
