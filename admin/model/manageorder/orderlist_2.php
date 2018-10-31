<?php
class Modelmanageorderorderlist extends Model 
{
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
		$query = $this->db->query("select 
                opo.id as 'IND_ID',
                date_format(opo.order_date,'%d-%m-%Y') as 'IND_DATE',
                concat(ocu.firstname,ocu.lastname) as 'MO_NAME',
                ocs.`name` as 'WS_NAME',
                sum(opp.quantity) as 'TOT_IND_QTY'
                from oc_po_order opo
                left join oc_user ocu on(opo.user_id=ocu.user_id)
                left join oc_store ocs on(opo.store_id=ocs.store_id)
                left join oc_po_product opp on(opo.id=opp.order_id)
                where opo.order_status=2 and(opp.so_qty=0 or opp.so_qty='')group by opo.id");
		return $query->rows;
	}
        public function getProductList($indid){
            $sql="select op.order_id as 'IND_ID',op.product_id as 'PRO_ID',os.name as wholeseller,op.store_id as 'STO_ID',op.name as 'PRO_NAME',op.quantity as 'PRO_QTY',op.product_id,op.id                                   
                  from oc_po_product as op
                  left join oc_store as os  on os.store_id=op.store_id
                  where order_id = '".$indid."'";
            $query = $this->db->query($sql);
            return $query->rows;
        }
         public function deleteprod($id,$productid){
            $sql="delete from  oc_po_product where id = '".$id."' and product_id = '".$productid."'";
            $query = $this->db->query($sql);
            echo $sql;
            //return $query->rows;
        }

        public function getTotalOrders()
	{
		$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = " . 1);
		$results = $query->row;
		return $results['total_orders'];
	}
           
        function prodname()
        {
       // $query = $this->db->query("select opd.product_id,opd.name,op.sku from oc_product_description as opd
                                   //left join oc_product as op on opd.product_id=op.product_id ");

        //return $query->rows; 
            
            $query = $this->db->query("SELECT product_id,model,sku FROM `oc_product` WHERE `status`='1'");
            
            return $query->rows;
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
        
        function getsap($data)
        {
                 
          $query = $this->db->query("SELECT distinct sap_ref FROM `oc_sap_ref`");
       
          return $query->rows;  
        }
        
        function sorderdispatch()
        {
            $log=new Log("DispOrder.log");
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
                    echo $sto_id=$odrs[$n]['store_id'];  //Store Id
                    echo $ind_qty=round($odrs[$n]['ind_qty']);  //IND Quantity
                    echo $sap_qty=round($odrs[$n]['sap_qty']);  //SO Quantity
                    echo $dis_qty=round($odrs[$n]['disp_qty']); //DISP Quantity
                    echo $req_qty = $sap_qty - $dis_qty;        //REQ Quantity
                    
                    echo $up_ind_id=$odrs[$n]['Indent_id']; //Indent Id
                    echo $up_prd_id=$odrs[$n]['product_id']; //Product Id       
                    echo $typ_ref = $odrs[$n]['id'];
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
                }
                    
            }
            
        if($ret_id=='1'){
             return $ret_id;
        }
        else{
           return '0';
        }
       
        }
        
        public function insertDeleteData($pid,$oid)
        {
            
                
                        $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                        $cdate=$date->format('Y-m-d');
                        $this->load->model('user/user');
                        $user_info = $this->model_user_user->getUser($this->user->getId());
                        $userid=$user_info['user_id'];
                        //print_r($userid);
            
                        //echo "SELECT * FROM `oc_po_product` WHERE `product_id`='".$pid."' and `order_id`='".$oid."'";

                       $query = $this->db->query("SELECT * FROM oc_po_product WHERE product_id='".$pid."' and order_id='".$oid."'");
                     
                       $status = $this->db->countAffected(); 
                       if($status==1)
                       {
                            // $sql1="INSERT INTO oc_po_trans set product_id,"store_id,store_name,name,attribute_group_id,quantity,order_id,received_products,item_status,so_qty,disp_qty,product_ws_price,customer_id,curr_date) VALUES ('".$query->row["id"]."','".$query->row["product_id"]."','".$query->row["store_id"]."','".$query->row["name"]."','".$query->row["attribute_group_id"]."','".$query->row["quantity"]."','".$query->row["order_id"]."','".$query->row["received_products"]."','".$query->row["item_status"]."','".$query->row["so_qty"]."','".$query->row["disp_qty"]."','".$query->row["product_ws_price"]."','2020','".$cdate."')"; 
                              $sql1="INSERT INTO oc_po_trans set 
                                product_id='".$query->row["product_id"]."',
                                store_id='".$query->row["store_id"]."',
                                store_name='".$query->row["store_name"]."',
                                name='".$query->row["name"]."',
                                attribute_group_id='".$query->row["attribute_group_id"]."',
                                quantity='".$query->row["quantity"]."',
                                order_id='".$query->row["order_id"]."',
                                received_products='".$query->row["received_products"]."',
                                item_status='".$query->row["item_status"]."',
                                so_qty='".$query->row["so_qty"]."',
                                disp_qty='".$query->row["disp_qty"]."',
                                product_ws_price='".$query->row["product_ws_price"]."',
                                customer_id='".$userid."',
                                curr_date='".$cdate."'";
                              //print_r($sql1);die;
                               $this->db->query($sql1);
                              $status1 = $this->db->countAffected(); 
                              if($status1==1)
                              {
                                   $sql2="delete from oc_po_product WHERE product_id='".$pid."' and order_id='".$oid."'";
                                   $this->db->query($sql2);
                                    echo $query->row["order_id"];
                              }
                            
                            
                            
                       }
           
        }
        
        function save($data){
                //print_r($data);
                $pid=$data["PRO_ID"];
                $tot=count($pid);
                $oid=$data["ORDER_ID"];
                $qty=$data["qty"];
                $presql="select store_id from oc_po_product where order_id='".$oid."'";
                $predata = $this->db->query($presql);
                $sto_id=$predata->row['store_id'];
                
             
                for($i=0;$i<$tot;$i++)
                {
                    //echo $pid[$i].'=>'.$oid.'</br>';
                    $chksql="select id , quantity from oc_po_product where order_id='".$oid."' and product_id='".$pid[$i]."'";
                    $chkdata = $this->db->query($chksql);
                    $chkdata->num_rows;
                    if($chkdata->num_rows ==0){
                     
                        $prosql="select model from oc_product where product_id='".$pid[$i]."'";
                        $prodata = $this->db->query($prosql);
                        $pro_name=$prodata->row['model'];
                       
                        $prisql="SELECT ws_price from oc_product_to_state WHERE state_id=(
                        SELECT state_id FROM ak_customer WHERE customer_id=(
                        SELECT customer_id from oc_user where user_id=(
                        SELECT user_id FROM oc_po_order WHERE id='".$oid."'))) and product_id ='".$pid[$i]."'";
                       
                        $pridata = $this->db->query($prisql);
                        $pro_ws_pri=$pridata->row['ws_price'];
                        $inssql="insert into oc_po_product set
                                product_id='".$pid[$i]."',
                                store_id='".$sto_id."',
                                name='".$pro_name."',
                                quantity='".$qty[$i]."',
                                order_id='".$oid."',
                                product_ws_price='".$pro_ws_pri."'";
                        $this->db->query($inssql);
                       $status = $this->db->countAffected();
                        
                    }
                    else{
                        if($chkdata->row['quantity']==$qty[$i]){
                            
                           $status=1;
                        }
                        else{
                        $updsql="update oc_po_product set quantity='".$qty[$i]."' where order_id='".$oid."' and product_id ='".$pid[$i]."'";
                        $this->db->query($updsql);
                        $status = $this->db->countAffected();
                        
                        }
                                
                    }
                }
                
                  return  $status;
               
           

        }
        
      
}
?>
