<?php
class ModelSaleordergeneratesordergenerate extends Model {
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
	$query = $this->db->query("select opo.id as 'oid', opp.id as 'pid', opp.product_id as 'prod_id', opp.`name` as 'prod_name', opo.order_date as 'order_date',opo.user_id as 'user_id',
 opp.quantity as 'quantity', opp.received_products as 'received_products', (opp.quantity - opp.received_products)as 'pending_quantity'
from oc_po_order opo
LEFT JOIN oc_po_product opp ON (opo.id = opp.order_id)
WHERE opo.order_status=2");
		
		return $query->rows;
	}
        
        public function getFilterList()
	{
            
        $stoid=$this->request->get['sto_id'];
        if(empty($stoid)){$stoid = 'NULL';}
        $maoid=$this->request->get['mao_id'];
        if(empty($maoid)){$maoid = 'NULL';}
        $proid=$this->request->get['pro_id'];
        if(empty($proid)){$proid = 'NULL';}
        $sql="SELECT opo.id AS 'oid', opp.id AS 'pid',opo.store_id as 'sto_id', ocs.`name` as 'store_name', opp.product_id AS 'prod_id', opp.`name` AS 'prod_name',
opo.order_date AS 'order_date',opo.user_id AS 'user_id',CONCAT(ocu.firstname,ocu.lastname) AS 'user_name', opp.quantity AS 'quantity', 
opp.received_products AS 'received_products', (opp.quantity - opp.so_qty)AS 'pending_quantity'
FROM oc_po_order opo
LEFT JOIN oc_po_product opp ON (opo.id = opp.order_id)
LEFT JOIN oc_store ocs ON(opo.store_id=ocs.store_id)
LEFT JOIN oc_user ocu ON(opo.user_id=ocu.user_id)
WHERE opo.order_status in (2,3,6) 
AND opo.store_id=ifnull(".$stoid.",opo.store_id) 
AND opo.user_id = ifnull(".$maoid.",opo.user_id) 
AND opp.product_id = ifnull(".$proid.",opp.product_id)";
        
	$query = $this->db->query($sql);
        
		
		return $query->rows;
	}
	public function getTotalOrders()
	{
		$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE po.order_status = 2");
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
         function getwspname($data)
        {
                 
          $query = $this->db->query("SELECT username,store_id,firstname FROM `oc_user` where user_group_id='10' and firstname like '%".$data["filter_name"]."%' order by firstname asc  limit 5  ");
       
          return $query->rows;  
        }
         function getproductname($product)
        {
                 
          $query = $this->db->query("SELECT product_id,model as 'name' FROM `oc_product` ORDER BY model");
       
          return $query->rows;  
        }
        /********************************************************/
        
        function insert_sale_order()
        {
            $log=new Log("SaleOrder.log");
            $stpid=$this->request->get['prd_ref_id']; 
            $gsqty=$this->request->get['gsqty'];
            $goid=$this->request->get['goid'];
            $gpid=$this->request->get['gpid'];
            $cust_id = $this->request->get['cust_sto_id'];
              
              
        $pstpid=explode('_',$stpid);
        $pgsqty=explode('_',$gsqty);
        $pgoid=explode('_',$goid);
        $pgpid=explode('_',$gpid);
        
        $count=count($pstpid);
        $tdate=DATE("Y-m-d");
        function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
//$sap_ref=generateRandomString();
$sapsql="SELECT id, sap_ref FROM oc_sap_ref ORDER BY id DESC LIMIT 1";
$sapdata=$this->db->query($sapsql);
$gsapid=$sapdata->row['sap_ref'];
if(empty($gsapid)){$sap_ref=date('Y').'0001';}else{$sap_ref=$gsapid+1;}

//--------------------------------------//
    $t = microtime(true);
    $micro = sprintf("%02d",($t - floor($t)) * 100);
    $utc = date('ymdHis', $t).$micro;
    $transid=$utc;
    $log->write($transid);
//--------------------------------------//       
        for($i=0;$i<$count;$i++)
        {
            $sql="select opo.id as 'oid',opo.store_id as 'stoid', opp.id as 'pid', opp.product_id as 'prod_id', opp.`name` as 'prod_name', opo.order_date as 'order_date',opo.user_id as 'user_id',
 opp.quantity as 'quantity', opp.received_products as 'received_products', (opp.quantity - opp.received_products)as 'pending_quantity'
from oc_po_product opp
LEFT JOIN oc_po_order opo ON (opo.id = opp.order_id)
WHERE opp.id=".$pstpid[$i];
       $data=$this->db->query($sql);
       $oid=$data->row['oid']; 
       $pid=$data->row['pid']; 
       $prod_id=$data->row['prod_id'];
       $qty=$data->row['quantity']; 
       $sqty=$pgsqty[$i]; 
       $ind_dt=$data->row['order_date']; 
       $usr_id=$data->row['user_id']; 
       $stoid=$data->row['stoid'];
     
       
       
       $sap_sql="insert into oc_sap_ref(Indent_id,Product_id,sap_ref,ind_qty,sap_qty,ind_date,sap_date,Store_id)values('".$oid."','".$prod_id."','".$sap_ref."','".$qty."','".$sqty."','".$ind_dt."','".$tdate."','".$stoid."')";
       $log->write( $sap_sql);
       $this->db->query($sap_sql);
       $typ_ref=$this->db->getLastId();
       //**************************//
       $this->db->query("INSERT INTO oc_oms_trans (type,user_id,trans_id,trans_date,product_id,product_qty,cr_date,type_ref,sap_ref)VALUES('3','".$this->session->data['user_id']."','".$transid."','". date('Y-m-d') ."',".$prod_id."," . $sqty.",'". date('Y-m-d') ."','".$typ_ref."','".$sap_ref."')");
       $log->write("INSERT INTO oc_oms_trans (type,user_id,trans_id,trans_date,product_id,product_qty,cr_date,type_ref,sap_ref)VALUES('3','".$this->session->data['user_id']."','".$transid."','". date('Y-m-d') ."',".$prod_id."," . $sqty.",'". date('Y-m-d') ."','".$typ_ref."','".$sap_ref."')");
       //**************************//
       
       $wst_sql="insert into oc_ws_trans(ind_date,ind_no,prod_id,ind_qty,sap_ref,sap_date,cr_by,cust_id)values('".$ind_dt."','".$oid."','".$prod_id."','".$qty."','".$sap_ref."','".$tdate."','".$this->session->data['user_id']."','".$cust_id."')";
       $log->write( $wst_sql);
       $this->db->query($wst_sql); 
       $upd_sql="update oc_po_product set so_qty = so_qty+'".$sqty."' where id='".$pid."' and product_id='".$prod_id."' and order_id='".$oid."'";
       $log->write( $upd_sql);
       $this->db->query($upd_sql); 
       
       $ret_id = $this->db->countAffected();  
       //************* Select Complete Sale Order / Partial Sale Order Start************//
       $sosql="select quantity, so_qty from oc_po_product where order_id='".$oid."'";
       $qdata=$this->db->query($sosql);
       $soqtydata=$qdata->rows;
       $eq=0;
       foreach ($soqtydata as $soqty)
       {
           if($soqty['quantity']!=$soqty['so_qty'])
           {
               $eq++;
           }
       }
       if($eq>0){
           $this->db->query("update oc_po_order set order_status = 6 where id='".$oid."'"); 
       }
       else {
           $this->db->query("update oc_po_order set order_status = 3 where id='".$oid."'");
       }
       //************* Select Complete Sale Order / Partial Sale Order End  ************//
       }
        
       
        if($ret_id=='1'){
             return $ret_id.",".$sap_ref;
        }
        else{
           return '0';
        }
              
                
   }
        
        
        
}
?>