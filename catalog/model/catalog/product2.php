<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}
        //***************************** IND_SAP_REPORT_START*******************************//
        public function getSapRecords($indid) {
        $log=new Log("IND_SAP_REPORT.log");
        $sql="select oc_po_product.product_id as 'PRO_ID',
        oc_product.model as 'PRO_NAME',
        oc_po_product.quantity as 'IND_QTY',
        oc_po_product.so_qty as 'SAP_QTY',
        oc_po_order.remarks as 'REMARKS',
        oc_store.name as 'STO_NAME'
        from oc_po_product
        left join oc_product on(oc_po_product.product_id=oc_product.product_id)
        left join oc_po_order on(oc_po_product.order_id=oc_po_order.id)
        left join oc_store on(oc_po_product.store_id=oc_store.store_id)
        where oc_po_product.order_id='".$indid."'";

		/*

		if (!empty($strt) || !empty($lim)) {
			if ($strt < 0) {
				$strt = 0;
			}

			if ($lim < 1) {
				$lim = 20;
			}

			$sql .= " LIMIT " . $strt. "," . $lim;
		}
                */
                $log->write($sql);
		$query = $this->db->query($sql);
		return $query->rows;
	}
        //***************************** IND_SAP_REPORT_END ********************************// 
        //***************************** IND_REPORT_START*******************************//
        public function getIndRecords($strt, $lim) {
        $log=new Log("IND_REP_".date('Y-m-d').".log");
        $log->write("select oc_po_order.id as 'IND_ID', oc_po_order.order_date as 'IND_DATE', ifnull(oc_sap_ref.sap_ref ,'NA')as 'SAP_REF',
(CASE
WHEN oc_po_order.order_status = 1 THEN 'PENDING'
WHEN oc_po_order.order_status = 2 THEN 'APPROVED'
WHEN oc_po_order.order_status = 3 THEN 'DISPATCHED'
WHEN oc_po_order.order_status = 4 THEN 'RECEIVED'
ELSE 'NA'
END) AS 'IND_STATUS'from oc_po_order
LEFT JOIN oc_sap_ref 
ON (oc_sap_ref.Indent_id = oc_po_order.id)
GROUP BY oc_po_order.id, oc_po_order.order_date, oc_sap_ref.sap_ref, oc_po_order.order_status");
	$sql="select oc_po_order.id as 'IND_ID', oc_po_order.order_date as 'IND_DATE', ifnull(oc_sap_ref.sap_ref ,'NA')as 'SAP_REF',
(CASE
WHEN oc_po_order.order_status = 1 THEN 'PENDING'
WHEN oc_po_order.order_status = 2 THEN 'APPROVED'
WHEN oc_po_order.order_status = 3 THEN 'DISPATCHED'
WHEN oc_po_order.order_status = 4 THEN 'RECEIVED'
ELSE 'NA'
END) AS 'IND_STATUS'from oc_po_order
LEFT JOIN oc_sap_ref 
ON (oc_sap_ref.Indent_id = oc_po_order.id)
GROUP BY oc_po_order.id, oc_po_order.order_date, oc_sap_ref.sap_ref, oc_po_order.order_status";

		

		if (!empty($strt) || !empty($lim)) {
			if ($strt < 0) {
				$strt = 0;
			}

			if ($lim < 1) {
				$lim = 20;
			}

			$sql .= " LIMIT " . $strt. "," . $lim;
		}

                $log->write($sql);
		$query = $this->db->query($sql);
		return $query->rows;
	}
        //***************************** IND_REPORT_END ********************************// 
        //***************************** IND_REPORT_STORE_START*******************************//
        public function getIndRecordsStr($strt, $lim, $sto,$type,$uid) {
            
        $log=new Log("IND_REP_STO".date('Y-m-d').".log");
        if(empty($sto)){$sto='NULL';}
        if(empty($type)){$type='NULL';}
        /*$log->write("select oc_po_order.id as 'IND_ID', oc_po_order.order_date as 'IND_DATE', ifnull(oc_sap_ref.sap_ref ,'NA')as 'SAP_REF',
(CASE
WHEN oc_po_order.order_status = 1 THEN 'PENDING'
WHEN oc_po_order.order_status = 2 THEN 'APPROVED'
WHEN oc_po_order.order_status = 3 THEN 'DISPATCHED'
WHEN oc_po_order.order_status = 4 THEN 'RECEIVED'
WHEN oc_po_order.order_status = 5 THEN 'HOLD'
WHEN oc_po_order.order_status = 7 THEN 'REJECTED'
ELSE 'NA'
END) AS 'IND_STATUS'from oc_po_order
LEFT JOIN oc_sap_ref 
ON (oc_sap_ref.Indent_id = oc_po_order.id)
WHERE oc_po_order.store_id = '".$sto."'
GROUP BY oc_po_order.id, oc_po_order.order_date, oc_sap_ref.sap_ref, oc_po_order.order_status");
	$sql="select oc_po_order.id as 'IND_ID', oc_po_order.order_date as 'IND_DATE', ifnull(oc_sap_ref.sap_ref ,'NA')as 'SAP_REF', 
(CASE
WHEN oc_po_order.order_status = 1 THEN 'PENDING'
WHEN oc_po_order.order_status = 2 THEN 'APPROVED'
WHEN oc_po_order.order_status = 3 THEN 'DISPATCHED'
WHEN oc_po_order.order_status = 4 THEN 'RECEIVED'
WHEN oc_po_order.order_status = 5 THEN 'HOLD'
WHEN oc_po_order.order_status = 7 THEN 'REJECTED'
ELSE 'NA'
END) AS 'IND_STATUS'from oc_po_order
LEFT JOIN oc_sap_ref 
ON (oc_sap_ref.Indent_id = oc_po_order.id)
WHERE oc_po_order.store_id = '".$sto."'
GROUP BY oc_po_order.id, oc_po_order.order_date, oc_sap_ref.sap_ref, oc_po_order.order_status";
         
         */

$chk="select user_group_id from oc_user where customer_id ='".$uid."'";
$log->write($chk);
$query = $this->db->query($chk);
if($query->row['user_group_id']=="4")
{
$sql="select oc_po_order.id as 'IND_ID', oc_po_order.order_date as 'IND_DATE',
(CASE
WHEN oc_po_order.order_status = 1 THEN 'PENDING'
WHEN oc_po_order.order_status = 2 THEN 'APPROVED'
WHEN oc_po_order.order_status = 3 THEN 'DISPATCHED'
WHEN oc_po_order.order_status = 4 THEN 'RECEIVED'
WHEN oc_po_order.order_status = 5 THEN 'HOLD'
WHEN oc_po_order.order_status = 7 THEN 'REJECTED'
ELSE 'NA'
END) AS 'IND_STATUS'from oc_po_order
WHERE oc_po_order.store_id = ifnull($sto, oc_po_order.store_id) and oc_po_order.order_status = ifnull($type, oc_po_order.order_status) and user_id in (select user_id from oc_user where customer_id = '".$uid."')
GROUP BY oc_po_order.id, oc_po_order.order_date,oc_po_order.order_status";
}
if($query->row['user_group_id']=="3")
{
$sql="select oc_po_order.id as 'IND_ID', oc_po_order.order_date as 'IND_DATE',
(CASE
WHEN oc_po_order.order_status = 1 THEN 'PENDING'
WHEN oc_po_order.order_status = 2 THEN 'APPROVED'
WHEN oc_po_order.order_status = 3 THEN 'DISPATCHED'
WHEN oc_po_order.order_status = 4 THEN 'RECEIVED'
WHEN oc_po_order.order_status = 5 THEN 'HOLD'
WHEN oc_po_order.order_status = 7 THEN 'REJECTED'
ELSE 'NA'
END) AS 'IND_STATUS'from oc_po_order
WHERE oc_po_order.store_id = ifnull($sto, oc_po_order.store_id) and oc_po_order.order_status = ifnull($type, oc_po_order.order_status) and user_id in(
select user_id from oc_user where customer_id in(select customer_id from ak_customer_map where parent_user_id = '".$uid."') and user_group_id=4 
)
GROUP BY oc_po_order.id, oc_po_order.order_date,oc_po_order.order_status";
}
if($query->row['user_group_id']=="10")
{
$sql="select oc_po_order.id as 'IND_ID', oc_po_order.order_date as 'IND_DATE',
(CASE
WHEN oc_po_order.order_status = 1 THEN 'PENDING'
WHEN oc_po_order.order_status = 2 THEN 'APPROVED'
WHEN oc_po_order.order_status = 3 THEN 'DISPATCHED'
WHEN oc_po_order.order_status = 4 THEN 'RECEIVED'
WHEN oc_po_order.order_status = 5 THEN 'HOLD'
WHEN oc_po_order.order_status = 7 THEN 'REJECTED'
ELSE 'NA'
END) AS 'IND_STATUS'from oc_po_order
WHERE oc_po_order.store_id = ifnull($sto, oc_po_order.store_id) and oc_po_order.order_status = ifnull($type, oc_po_order.order_status)
GROUP BY oc_po_order.id, oc_po_order.order_date,oc_po_order.order_status";
}

		if (!empty($strt) || !empty($lim)) {
			if ($strt < 0) {
				$strt = 0;
			}

			if ($lim < 1) {
				$lim = 20;
			}

			$sql .= " LIMIT " . $strt. "," . $lim;
		}

                $log->write($sql);
		$query = $this->db->query($sql);
		return $query->rows;
	}
        //***************************** IND_REPORT_END ********************************//

	public function getProduct($product_id,$state_id) {
        
        $log=new Log("GetProduct:".date('Y-m-d').".log");
       
        $sql="SELECT 
p.product_id,
p.model,
p.sku,
p.upc,
p.ean,
p.jan,
p.isbn,
p.mpn,
p.location,
p.quantity,
p.stock_status_id,
p.image,
p.manufacturer_id,
p.shipping,
p.price,
p.points,
p.tax_class_id,
p.date_available,
p.weight,
p.weight_class_id,
p.length,
p.width,
p.height,
p.length_class_id,
p.subtract,
p.minimum,
p.sort_order,
p.status,
p.viewed,
p.date_added,
p.date_modified,
p.rtlr_price,pd.name AS name,
m.name AS manufacturer,
(SELECT subsidy FROM " . DB_PREFIX . "product_subsidy pds WHERE pds.product_id = p.product_id AND pds.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pds.quantity = '1' AND pds.store_id='".(int)$this->config->get('config_store_id')."'  ORDER BY pds.priority ASC LIMIT 1) AS subsidy ,
(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount,
(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, 
(SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, 
(SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, 
(SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, 
(SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, 
(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews,p2s.quantity as squantity,
pts.ws_price, pts.rt_price  
FROM " . DB_PREFIX . "product p 
LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
LEFT JOIN oc_product_to_state pts ON(p.product_id = pts.product_id and pts.state_id = '".$state_id."' and act=1) 
WHERE p.product_id = '" . (int)$product_id . "' 
AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
AND p.status = '1' 
AND p.date_available <= NOW() ";
 if($this->config->get('config_store_id')==0)
        {
            $config_sto_id='NULL';
            $sql.="";
        }
        else {
            $config_sto_id=$this->config->get('config_store_id');
           $sql.= "AND p2s.store_id = ifnull($config_sto_id,p2s.store_id)";
        }
        
        
        
$log->write($sql);
$query = $this->db->query($sql);
        //$log->write("SELECT DISTINCT *,pd.name AS name, p.image, m.name AS manufacturer,(SELECT subsidy FROM " . DB_PREFIX . "product_subsidy pds WHERE pds.product_id = p.product_id AND pds.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pds.quantity = '1' AND pds.store_id='".(int)$this->config->get('config_store_id')."'  ORDER BY pds.priority ASC LIMIT 1) AS subsidy , (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order,p2s.quantity as squantity,pts.ws_price as st_ws_price, pts.rt_price as st_rt_price  FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN oc_product_to_state pts ON(p.product_id = pts.product_id and pts.state_id = '".$state_id."') WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
	
        //$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer,(SELECT subsidy FROM " . DB_PREFIX . "product_subsidy pds WHERE pds.product_id = p.product_id AND pds.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pds.quantity = '1' AND pds.store_id='".(int)$this->config->get('config_store_id')."'  ORDER BY pds.priority ASC LIMIT 1) AS subsidy , (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order,p2s.quantity as squantity,pts.ws_price as st_ws_price, pts.rt_price as st_rt_price FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN oc_product_to_state pts ON(p.product_id = pts.product_id and pts.state_id = '".$state_id."') WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
        $log->write($query->row);
        if ($query->num_rows) {
            return array(
			'product_id'       => $query->row['product_id'],
			'name'             => $query->row['name'],
			'description'      => $query->row['description'],
			'meta_title'       => $query->row['meta_title'],
			'meta_description' => $query->row['meta_description'],
			'meta_keyword'     => $query->row['meta_keyword'],
			'tag'              => $query->row['tag'],
			'model'            => $query->row['model'],
			'sku'              => ($query->row['sku'] ? $query->row['sku'] : 'NA'),
                        'rtlr_price'       => $query->row['rtlr_price'],
                        'st_ws_price'      => $query->row['ws_price'],
                        'st_rt_price'      => $query->row['rt_price'],
			'upc'              => $query->row['upc'],
			'ean'              => $query->row['ean'],
			'jan'              => $query->row['jan'],
			'isbn'             => $query->row['isbn'],
			'mpn'              => $query->row['mpn'],
			'location'         => $query->row['location'],
			'quantity'         => $query->row['quantity'],
			'squantity'        => $query->row['squantity'],
			'stock_status'     => $query->row['stock_status'],
			'image'            => $query->row['image'],
			'manufacturer_id'  => $query->row['manufacturer_id'],
			'manufacturer'     => $query->row['manufacturer'],
			'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
			'sprice'           => ($query->row['discount'] ? $query->row['discount'] : $query->row['store_price']),
			'subsidy'          => $query->row['subsidy'],
			'special'          => $query->row['special'],
			'reward'           => $query->row['reward'],
			'points'           => $query->row['points'],
			'tax_class_id'     => $query->row['tax_class_id'],
			'date_available'   => $query->row['date_available'],
			'weight'           => $query->row['weight'],
			'weight_class_id'  => $query->row['weight_class_id'],
			'length'           => $query->row['length'],
			'width'            => $query->row['width'],
			'height'           => $query->row['height'],
			'length_class_id'  => $query->row['length_class_id'],
			'subtract'         => $query->row['subtract'],
			'rating'           => round($query->row['rating']),
			'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
			'minimum'          => $query->row['minimum'],
			'sort_order'       => $query->row['sort_order'],
			'status'           => $query->row['status'],
			'date_added'       => $query->row['date_added'],
			'date_modified'    => $query->row['date_modified'],
			'viewed'           => $query->row['viewed']
			);
            } else {
		return false;
            }
	}

	public function getProducts($data = array()) {
            $sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
            if (!empty($data['filter_category_id'])) {
		
                if (!empty($data['filter_sub_category'])) {
			$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
                } else {
			$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
		}
                
		if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

        $log=new Log("GetProducts:".date('Y-m-d').".log");
        $log->write("CATALOG_MODEL_CATLOG_PRODUCT_LINE_182");
        $log->write($sql);
	$storeid=$this->config->get('config_store_id');
        $log->write("str".$storeid);
        if(empty($storeid))
        {
            $log->write("str in".$storeid);
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() ";
        }
        else if(($storeid)=='19')
        {
            $log->write("str in".$storeid);
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW()  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        }
        else{
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        }


	if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
            } else {
		$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
            if (!empty($data['filter_filter'])) {
		$implode = array();
		$filters = explode(',', $data['filter_filter']);
		foreach ($filters as $filter_id) {
		$implode[] = (int)$filter_id;
		}
                    $sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
		}
	}

	if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";
            if (!empty($data['filter_name'])) {
		$implode = array();

		$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
		foreach ($words as $word) {
                    $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
		}
		if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
		}
		if (!empty($data['filter_description'])) {
                    $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
		$sql .= " OR ";
            }
            if (!empty($data['filter_tag'])) {
		$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
            }
            if (!empty($data['filter_name'])) {
		$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
		$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
		$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
		$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
		$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
		$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
		$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            }
		$sql .= ")";
	}

	if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
	}
            $sql .= " GROUP BY p.product_id";
            $sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                    $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
		} elseif ($data['sort'] == 'p.price') {
                    $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
		} else {
                    $sql .= " ORDER BY " . $data['sort'];
		}
		} else {
                    $sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
                    $sql .= " DESC, LCASE(pd.name) DESC";
		} else {
                    $sql .= " ASC, LCASE(pd.name) ASC";
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

                $log->write($sql);
		$product_data = array();
		$query = $this->db->query($sql);
                
                $log->write($query);
                $log->write($data['filter_state_id']);
                if(empty($data['filter_state_id']))
                {
                    $data['filter_state_id'] =  0;
                }
                foreach ($query->rows as $result) {
                    $log->write($result['product_id']);
                    $product_data[$result['product_id']] = $this->getProduct($result['product_id'],$data['filter_state_id']);
		}
		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
$log=new Log("prdcount.log");
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		$log->write($sql);
		$query = $this->db->query($sql);

		return $query->row['total'];
	}


public function getTotalQntyProducts($data = array()) {
$log=new Log("prdcount.log");
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.quantity>0 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		$log->write($sql);
		$query = $this->db->query($sql);

		return $query->row['total'];
	}


	public function getProfiles($product_id) {
		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "product_recurring` `pp` JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`recurring_id` = `pp`.`recurring_id` JOIN `" . DB_PREFIX . "recurring` `p` ON `p`.`recurring_id` = `pd`.`recurring_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->config->get('config_customer_group_id') . " ORDER BY `sort_order` ASC")->rows;
	}

	public function getProfile($product_id, $recurring_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'))->row;
	}


public function getTotalInventoryAmount( $sid){

$log=new Log("slog.log");
$sql="select sum(qnty),sum(price),(sum(total)+sum(tax)) as total from (
SELECT p.product_id,p.model,sum(p2s.quantity) as qnty,sum(CASE WHEN  p2s.store_price='0.0000' THEN p.price ELSE p2s.store_price END) as price ,(sum(p2s.quantity) * ( (CASE WHEN  p2s.store_price='0.0000' THEN p.price ELSE p2s.store_price END) )) as  total,
(  (SELECT (CASE WHEN type='F' then rate WHEN type='p' then ( (CASE WHEN  p2s.store_price='0.0000' THEN p.price ELSE p2s.store_price END) *(rate/100)) else rate end) as rate FROM `oc_tax_rule` as rl LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id WHERE `tax_class_id`=p.tax_class_id ) * sum(p2s.quantity) )as tax
FROM oc_product p LEFT JOIN  oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE  p.status = '1' 
AND p.date_available <= NOW() AND p2s.quantity > 0 AND p2s.store_id = '".$sid."' 
GROUP BY p.product_id ORDER BY p.sort_order ASC         
    ) as a";
$log->write($sql);
$query =$this->db->query($sql);
$log->write($query );
return $query->row['total'];


}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}
