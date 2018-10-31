<?php
class ModelInventoryPurchaseOrder extends Model{
    
    public function insert_purchase_order($data = array()){

        $log=new Log("Req_Ind_Mo_Mod_".date('Y_m_d').".log");
        $t = microtime(true);
        $micro = sprintf("%02d",($t - floor($t)) * 100);
        $utc = date('ymdHis', $t).$micro;
        $transid=$utc;
        $log->write($transid);
        // Getting Store Id From oc_store
        $StoKey=$data['stores'][0][0];
        $stoqry=$this->db->query("SELECT store_key ,`name` FROM oc_store WHERE store_id='".$StoKey."'");
        $StoId  =$stoqry->row['store_key'];             //Store Id
        $StoName=$stoqry->row['name'];                  //Store Name
        $CrDate =date('Y-m-d');                         //Ind Date
        $CrBy   =$this->session->data['user_id'];       //Ind Create By
        //
    //******************************* OC_PO_ORDER_START*****************************//
        if($data['supplier_id'] != "--Supplier--"){

            $sql_po="INSERT INTO oc_po_order SET
                `order_date`='".$CrDate."',
                `store_id`='".$StoId."',
                `user_id`='".$CrBy."',
                `pre_supplier_bit`=1";
        }else{
            $sql_po="INSERT INTO oc_po_order SET
                `order_date`='".$CrDate."',
                `store_id`='".$StoId."',
                `user_id`='".$CrBy."'";
        }   

            $log->write($sql_po);
            $this->db->query($sql_po);
            $order_id = $this->db->getLastId();         // Unique IndId Generate
    //******************************* OC_PO_ORDER_END*******************************//

    //******************************* OC_PO_PRODUCT_START***************************//
        for($i = 0; $i<count($data['products']); $i++){
            $Sql_pp="INSERT INTO oc_po_product SET
            `product_id`='".$data['products'][$i][0]."',
            `name`='".$data['products'][$i][1]."',
            `quantity`='".$data['quantity'][$i]."',
            `order_id`='".$order_id."',
            `store_id`='".$StoId."',
            `store_name`='".$StoName."',
            `product_ws_price`='".$data['data_price'][$i]."'";
            $log->write($Sql_pp);
            $this->db->query($Sql_pp);
            $typ_ref=$this->db->getLastId();
            $product_ids[$i] = $this->db->getLastId();

            //Transction Entry
            $this->db->query("INSERT INTO oc_oms_trans SET
            `type`=1,
            `user_id`='".$CrBy."',
            `trans_id`='".$transid."',
            `trans_date`='".$CrDate."',
            `product_id`='".$data['products'][$i][0]."',
            `product_qty`='".$data['quantity'][$i]."',
            `cr_date`='".$CrDate."',
            `type_ref`='".$typ_ref."'");
        }
    //******************************* OC_PO_PRODUCT_END*****************************//

        //Insert Attributes
        $start_loop = 0;
        for($j = 0; $j<count($product_ids); $j++){
            for($i = $start_loop; $i<count($data['options']); $i++){
                if($data['options'][$i] != "new product"){
                    $this->db->query("INSERT INTO oc_po_attribute_group (attribute_group_id,name,product_id) VALUES(".$data['options'][$i][0].",'".$data['options'][$i][1]."',".$product_ids[$j].")");
                    $attribute_group_ids[$i] = $this->db->getLastId();
                }else{
                    $start_loop = $i+1;
                    $attribute_group_ids[$i] = "new product";
                    break;
                }
            }
        }


        $start_loop = 0;
        for($i = 0; $i<count($attribute_group_ids); $i++){
            if($attribute_group_ids[$i] != "new product"){
                for($j = $start_loop; $j<count($data['option_values']); $j++){
                    if($data['option_values'][$j] != "new product"){
                        $this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i].")");
                        $attribute_category_ids[$j] = $this->db->getLastId();
                    }else{
                        $attribute_category_ids[$j] = "new product";
                    }
                    $start_loop = $j + 1;
                    break;
                }
            }else{
                for($j = $start_loop; $j<count($data['option_values']); $j++){
                    if($data['option_values'][$j] != "new product"){
                        $this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i+1].")");
                        $attribute_category_ids[$j] = $this->db->getLastId();
                        $i = $i+1;
                    }else{
                        $attribute_category_ids[$j] = "new product";
                    }
                    $start_loop = $j + 1;
                    break;
                }
            }
        }

        if($data['supplier_id'] != "--Supplier--"){
            for($i = 0; $i<count($data['products']); $i++){
             $query = $this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$data['quantity'][$i].",".$data['products'][$i][0].",".$data['supplier_id'].",".$order_id.",'".$data['stores'][$i][0]."')");
            }
        }else{
            for($i = 0; $i<count($data['products']); $i++) {
                $query = $this->db->query("INSERT INTO oc_po_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][$i][0].",-1,".$order_id.",'".$data['stores'][$i][0]."')");
            }
        }
        return $order_id; // Return OrderId On Successful Order Placed.
    }

public function getListRec($start,$limit)
{
$log=new Log("getListRec_".date('Y_m_d').".log");
$query = $this->db->query("SELECT
oc_po_order.*
,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.order_sup_send <> '0000-00-00' AND oc_po_order.delete_bit = 1 AND oc_po_order.receive_bit = 0 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$log->write("SELECT
oc_po_order.*
,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.order_sup_send <> '0000-00-00' AND oc_po_order.delete_bit = 1 AND oc_po_order.receive_bit = 0 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
return $query->rows;
}


public function getListRecStore($uid,$start,$limit)
{

$log=new Log("getListRecStore_".date('Y_m_d').".log");
$query = $this->db->query("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.user_id='".$uid."' AND oc_po_order.order_sup_send <> '0000-00-00' AND oc_po_order.delete_bit = 1 AND oc_po_order.receive_bit = 0 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$log->write("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.user_id='".$uid."' AND oc_po_order.order_sup_send <> '0000-00-00' AND oc_po_order.delete_bit = 1 AND oc_po_order.receive_bit = 0 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$log->write($query->rows);
return $query->rows;
}

//********************************** AM Order List Start ************************//
public function getListRecStoreMo($uid,$start,$limit)
{

$log=new Log("MO_RCV_ORDER_".date('Y_m_d').".log");
$query = $this->db->query("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id)
LEFT JOIN oc_po_product 
ON(oc_po_order.id = oc_po_product.order_id)
WHERE oc_po_order.user_id='".$uid."' AND oc_po_product.item_status = 2 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$log->write("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id)
LEFT JOIN oc_po_product 
ON(oc_po_order.id = oc_po_product.order_id)
WHERE oc_po_order.user_id='".$uid."' AND oc_po_product.item_status = 2 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$log->write($query->rows);
return $query->rows;
}



//*********************************** WS Order List Start ***********************//
public function getListRecStoreWs($sid,$start,$limit)
{

$log=new Log("WS_RCV_".date('Y_m_d').".log");
$log->write("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
LEFT JOIN oc_po_product opp 
ON (oc_po_order.id = opp.order_id)
WHERE opp.store_id = '".$sid."' AND opp.item_status= 2 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$query = $this->db->query("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
LEFT JOIN oc_po_product opp 
ON (oc_po_order.id = opp.order_id)
WHERE opp.store_id = '".$sid."' AND opp.item_status= 2 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);

$log->write($query->rows);
return $query->rows;
}

//********************************** WS Order List End **************************//

//*********************************** AM Order List Start ***********************//
public function getListRecStoreAm($uid,$start,$limit)
{
$log=new Log("AM_PEN_IND_LIST_MOD_".date('Y_m_d').".log");
$sql="SELECT oc_po_order.*,'Indent' as 'receivetype', oc_po_product.store_id as 'store_id'
FROM oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
LEFT JOIN oc_po_product
ON (oc_po_order.id = oc_po_product.order_id)
WHERE oc_po_order.user_id in(
select ocu.user_id as 'MO_USER_ID'
from oc_user 
left join ak_customer 
on (ak_customer.User_id = oc_user.username)
left join ak_customer_map
on (ak_customer.customer_id = ak_customer_map.parent_user_id)
left join ak_customer akc
on (akc.customer_id = ak_customer_map.customer_id and akc.customer_group_id = 4)
left join oc_user ocu
on (akc.user_id = ocu.username and ocu.user_group_id = 4)
where oc_user.user_id = '".$uid."'
)
AND oc_po_order.order_status in (1,5) 
AND oc_po_order.order_sup_send = '0000-00-00' 
AND oc_po_order.delete_bit = 1 
AND oc_po_order.pending_bit = 1 
GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC";
$log->write($sql);
$query = $this->db->query($sql);
$log->write($query->rows);
return $query->rows;
}

//********************************** AM Order List End **************************//

//********************************** AM Order List End **************************//
public function updateOrderStatusAm($uid,$order_id,$order_sts,$order_rmk){
    $upDate=date('Y-m-d');
    $log=new Log("AM_UP_IND_STS_MOD_".date('Y_m_d').".log");
    $log->write('Updated By AM_ID: '.$uid);
    if($order_rmk==""){$order_rmk='NA';}
    $sql="UPDATE oc_po_order SET order_status = '".$order_sts."' , status_date = '".$upDate."',remarks='".$order_rmk."' WHERE id='".$order_id."'";
    $log->write($sql);
    if($this->db->query($sql)){
        return 1;
    }
    else{
        return 0;
    }
}
public function msgDtls($ordId){
    $upDate=date('Y-m-d');
    $log=new Log("AM_UP_IND_STS_MOD_".date('Y_m_d').".log");
    /*$sql="SELECT A.IND_ID,A.IND_QTY,A.IND_MO,B.MO_MOB,A.IND_DEL,C.DEL_MOB 
    FROM(

    SELECT OPO.id AS 'IND_ID', OPO.user_id AS 'IND_MO', OPO.store_id AS 'IND_DEL',SUM(OPP.quantity) 'IND_QTY' 
    FROM oc_po_order OPO
    JOIN oc_po_product OPP ON (OPO.id=OPP.order_id)
    WHERE OPO.id='".$ordId."' GROUP BY OPP.order_id

    ) A

    JOIN(

    SELECT oc_user.user_id AS 'MO_ID',ak_customer.telephone AS 'MO_MOB'
    FROM ak_customer
    JOIN oc_user ON(ak_customer.customer_id=oc_user.customer_id)
    WHERE ak_customer.customer_group_id IN(3,4)

    ) B ON(A.IND_MO=B.MO_ID)

    JOIN(

    SELECT ak_customer.telephone AS 'DEL_MOB',oc_user.store_id AS 'DEL_STO'
    FROM ak_customer
    JOIN oc_user ON(ak_customer.customer_id=oc_user.customer_id)
    WHERE ak_customer.customer_group_id IN(10)
    )C ON(A.IND_DEL=C.DEL_STO)";*/
    $sql="SELECT A.IND_ID,A.IND_QTY,A.IND_MO,B.MO_MOB,B.AM_NAME,A.IND_DEL,C.DEL_NAME,C.DEL_MOB,C.DEL_DID,C.DEL_DIST 
    FROM(

    SELECT OPO.id AS 'IND_ID', OPO.user_id AS 'IND_MO', OPO.store_id AS 'IND_DEL',SUM(OPP.quantity) 'IND_QTY' 
    FROM oc_po_order OPO
    JOIN oc_po_product OPP ON (OPO.id=OPP.order_id)
    WHERE OPO.id='".$ordId."' GROUP BY OPP.order_id

    ) A

    JOIN(

    SELECT oc_user.user_id AS 'MO_ID',ak_customer.telephone AS 'MO_MOB',AM.firstname AS 'AM_NAME'
    FROM ak_customer
    JOIN oc_user ON(ak_customer.customer_id=oc_user.customer_id)
		JOIN ak_customer_map AMP ON(ak_customer.customer_id=AMP.CUSTOMER_ID)
		JOIN ak_customer AM ON(AMP.PARENT_USER_ID=AM.customer_id)
    WHERE ak_customer.customer_group_id IN(3,4)

    ) B ON(A.IND_MO=B.MO_ID)

    JOIN(

    SELECT CONCAT(ak_customer.firstname,' ',ak_customer.lastname) AS 'DEL_NAME',ak_customer.telephone AS 'DEL_MOB',oc_user.store_id AS 'DEL_STO',MP.GEO_ID AS 'DEL_DID',
DT.`NAME` AS 'DEL_DIST'
    FROM ak_customer
    JOIN oc_user ON(ak_customer.customer_id=oc_user.customer_id)
		JOIN ak_customer_emp_map MP ON(ak_customer.customer_id=MP.CUSTOMER_ID AND MP.GEO_LEVEL_ID=4)
		JOIN ak_mst_geo_upper DT ON(MP.GEO_ID=DT.SID)
    WHERE ak_customer.customer_group_id IN(10)
    )C ON(A.IND_DEL=C.DEL_STO)";
    $log->write($sql);
    $query=$this->db->query($sql);
    return $query->row;
}

//********************************** AM Order List End **************************//
//******************************* AM Order List Start ***********************//
public function getListRecStoreAmRcv($uid,$start,$limit)
{

$log=new Log("AM_RCV_MOD_".date('Y_m_d').".log");

$mo_list_sql="select ocu.user_id as 'MO_USER_ID'
from oc_user 
left join ak_customer 
on (ak_customer.User_id = oc_user.username)
left join ak_customer_map
on (ak_customer.customer_id = ak_customer_map.parent_user_id)
left join ak_customer akc
on (akc.customer_id = ak_customer_map.customer_id and akc.customer_group_id = 4)
left join oc_user ocu
on (akc.user_id = ocu.username and ocu.user_group_id = 4)
where oc_user.user_id = '".$uid."'";
$log->write($mo_list_sql);

$query = $this->db->query("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id)
LEFT JOIN oc_po_product 
ON(oc_po_order.id = oc_po_product.order_id)
WHERE oc_po_order.user_id IN(select ocu.user_id as 'MO_USER_ID'
from oc_user 
left join ak_customer 
on (ak_customer.User_id = oc_user.username)
left join ak_customer_map
on (ak_customer.customer_id = ak_customer_map.parent_user_id)
left join ak_customer akc
on (akc.customer_id = ak_customer_map.customer_id and akc.customer_group_id = 4)
left join oc_user ocu
on (akc.user_id = ocu.username and ocu.user_group_id = 4)
where oc_user.user_id = '".$uid."') AND oc_po_product.item_status = 2 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$log->write("SELECT oc_po_order.*,'Indent' as 'receivetype'
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id)
LEFT JOIN oc_po_product 
ON(oc_po_order.id = oc_po_product.order_id)
WHERE oc_po_order.user_id IN(select ocu.user_id as 'MO_USER_ID'
from oc_user 
left join ak_customer 
on (ak_customer.User_id = oc_user.username)
left join ak_customer_map
on (ak_customer.customer_id = ak_customer_map.parent_user_id)
left join ak_customer akc
on (akc.customer_id = ak_customer_map.customer_id and akc.customer_group_id = 4)
left join oc_user ocu
on (akc.user_id = ocu.username and ocu.user_group_id = 4)
where oc_user.user_id = '".$uid."') AND oc_po_product.item_status = 2 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
$log->write($query->rows);
return $query->rows;
}

//********************************** AM Order List End **************************//
public function getList($start,$limit)
{
$query = $this->db->query("SELECT
oc_po_order.*
, ".DB_PREFIX."user.firstname
, ".DB_PREFIX."user.lastname
, oc_po_supplier.first_name
, oc_po_supplier.last_name
FROM
oc_po_order
INNER JOIN oc_po_receive_details
ON (oc_po_order.id = oc_po_receive_details.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
INNER JOIN ".DB_PREFIX."user 
ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.delete_bit = 1 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);

return $query->rows;
}
public function getTotalOrders()
{
$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = " . 1);
$results = $query->row;
return $results['total_orders'];
}
//********************************************* Total Order MO Start ********************//
public function getTotalOrdersMo()
{
$query = $this->db->query("SELECT COUNT(oc_po_product.id) as total_orders FROM oc_po_order 
LEFT JOIN oc_po_product 
ON(oc_po_order.id = oc_po_product.order_id)
WHERE oc_po_order.user_id='103' AND oc_po_product.item_status <> 0");
$results = $query->row;
return $results['total_orders'];
}
//********************************************* Total Order AM Start ********************//
public function getTotalOrdersWs()
{
$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = " . 1);
$results = $query->row;
return $results['total_orders'];
}
//********************************************* Total Order AM Start ********************//
public function getTotalOrdersAm()
{
$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = " . 1);
$results = $query->row;
return $results['total_orders'];
}
//********************************************* Total Order AM End********************//
public function getTotalOrdersAmRcv()
{
$log=new Log("AM_RCV_TOTAL_".date('Y_m_d').".log");
$log->write("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = '1' and order_status = " . 2);
$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = '1' and order_status = " . 2);
$results = $query->row;
return $results['total_orders'];
}
//********************************************* Total Order AM End********************//
public function view_order_details_mo($order_id)
{
$log=new Log("IND_LIST_RCV_MO_".date('Y_m_d').".log");
$query = $this->db->query("SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
FROM oc_po_order
LEFT JOIN ".DB_PREFIX."user
ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
WHERE id = " . $order_id . " AND delete_bit = " . 1);
$log->write($query);
$order_info = $query->row;
//ON (oc_po_receive_details.product_id = oc_po_product.id)
$allprod="SELECT
oc_po_product.id,
oc_po_product.product_id,
oc_po_product.store_id,
oc_po_product.`name`,
oc_po_product.quantity,
oc_po_product.order_id,
(oc_po_product.received_products - oc_po_product.disp_qty)as received_products,
oc_po_product.item_status,
oc_po_product.so_qty,
oc_po_product.disp_qty,
oc_po_receive_details.quantity as rd_quantity,
oc_po_receive_details.price,
oc_po_supplier.first_name,
oc_po_supplier.last_name,
oc_po_supplier.id as supplier_id,
oc_po_receive_details.order_id
FROM
oc_po_receive_details
INNER JOIN oc_po_product 
ON (oc_po_receive_details.order_id = oc_po_product.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
WHERE oc_po_product.item_status <> 0 AND (oc_po_receive_details.order_id =".$order_id.")";
$log->write($allprod);
$query = $this->db->query($allprod);

$log->write($query);
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
//******************************** VIEW ORDER DETAILS MO ENDS ***********************//
public function view_order_details($order_id)
{
$log=new Log("OrderDetailsRCV_".date('Y_m_d').".log");
$query = $this->db->query("SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
FROM oc_po_order
LEFT JOIN ".DB_PREFIX."user
ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
WHERE id = " . $order_id . " AND delete_bit = " . 1);
$log->write($query);
$order_info = $query->row;
//ON (oc_po_receive_details.product_id = oc_po_product.id)
$query = $this->db->query("SELECT
oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,oc_po_receive_details.price,oc_po_supplier.first_name,oc_po_supplier.last_name,oc_po_supplier.id as supplier_id,oc_po_receive_details.order_id
FROM
oc_po_receive_details
INNER JOIN oc_po_product 
ON (oc_po_receive_details.order_id = oc_po_product.order_id)
LEFT JOIN oc_po_supplier 
ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
WHERE (oc_po_receive_details.order_id =".$order_id.")");
$log->write($query);
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
LEFT JOIN oc_po_supplier 
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
$log=new Log("FILTER_ORDER_".date('Y_m_d').".log");
$log->write("sql-".$query);
$query = $this->db->query($query);

return $query->rows;
}

/*-----------------------------insert receive order function starts here-------------------*/

public function insert_receive_order($received_order_info,$order_id)
{

$log=new Log("RCV_ORDER_".date('Y_m_d').".log");

$log->write($received_order_info);
if($received_order_info['order_receive_date'] != '')
{
$received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
$received_order_info['order_receive_date'] = date('Y-m-d',$received_order_info['order_receive_date']);
}
$inner_loop_limit = count($received_order_info['received_quantities']);
$quantities = array();
$quantity = 0;
$this->db->query("UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 1 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);
$log->write("UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 1 . ", pending_bit = " . 0 . " WHERE id = " . $order_id); 
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

$log->write($received_order_info['received_quantities']);
for($i =0; $i<count($received_order_info['received_quantities']); $i++)
{
if($received_order_info['received_quantities'][$i] != "next product")
{
$received_quantities[$i] = $received_order_info['received_quantities'][$i];
}
}

$prices = array_values($prices);
$received_quantities = array_values($received_quantities);

$log->write($prices); 
$log->write("after price");
$log->write($received_quantities);


for($i =0; $i<count($received_quantities); $i++)
{
$log->write("in for loop");

$log->write("UPDATE oc_po_receive_details SET price =" .$prices[$i]. ", quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
$this->db->query("UPDATE oc_po_receive_details SET quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
$query = $this->db->query("SELECT quantity FROM oc_po_receive_details WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id =" . $order_id);
$quantities[$i] = $query->row['quantity'];
$log->write("quantity"); 
$log->write($quantities[$i]); 
}
}
else
{
$query = $this->db->query("SELECT * FROM oc_po_receive_details WHERE order_id=".$order_id);
$log->write("update order info done select ".$query);
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
//"INSERT INTO oc_po_receive_details (quantity,price,product_id,supplier_id,order_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['prices'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.")"
$this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.",".$query->rows[$j]["store_id"].")");
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
$query = $this->db->query("SELECT DISTINCT( product_id), store_id FROM oc_po_product WHERE product_id = " . $received_order_info['received_product_ids'][$i]);
$product_ids[$i] = $query->row;
$query1 = $this->db->query("UPDATE oc_po_product SET received_products = " . $quantities[$i] . " WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
}
$totalamount=0;
for($i=0; $i<count($product_ids); $i++)
{

$log->write("SELECT quantity FROM ".DB_PREFIX."product_to_store WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$query = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product_to_store WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$quantity = $quantities[$i];//$query->row['quantity'] ;''+
//$log->write("UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
//$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
$log->write("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$query2 = $this->db->query("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$log->write("no product");
$log->write($query2);
if($query2->num_rows==0)
{ 
$log->write("insert into ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " , store_id=".$product_ids[$i]['store_id']." ,product_id = " . $product_ids[$i]['product_id']);
$this->db->query("insert into ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " , store_id=".$product_ids[$i]['store_id']." ,product_id = " . $product_ids[$i]['product_id']);

}
if($query && $query2)
{
$log->write("before credit change in ");
$log->write("SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id WHERE store_id=".$product_ids[$i]['store_id']." AND p2s.product_id = " . $product_ids[$i]['product_id']);
//upadte current credit
//get product details
$queryprd = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id WHERE store_id=".$product_ids[$i]['store_id']." AND p2s.product_id = " . $product_ids[$i]['product_id']);
$log->write($queryprd);
$tax=round($this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']));
$totalamount=$totalamount+($quantity*$queryprd->row['price'])+($quantity*$tax);
$log->write($totalamount);

}

if($query && $query2)
$bool = true;
}
if($bool)
{
//update credit price
$log->write("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $totalamount . " WHERE store_id=".$this->user->getStoreId());
$this->db->query("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $totalamount . " WHERE store_id=".$this->user->getStoreId()); 
//insert store transaction
$sql="insert into oc_store_trans set `store_id`='".$this->user->getStoreId()."',`amount`='".$totalamount."',`transaction_type`='1',`cr_db`='CR',`user_id`='".$this->session->data['user_id']."' ";
$log->write($sql);
$this->db->query($sql);
}
if($bool)
return true;
}

/*-----------------------------insert receive order function ends here-----------------*/
//*********************************Receive Order MO************************************//
public function insert_receive_order_mo($received_order_info,$order_id)
{ 
$log=new Log("MO_RCV_ORDER_".date('Y_m_d').".log");

$log->write($received_order_info);
if($received_order_info['order_receive_date'] != '')
{
$received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
$received_order_info['order_receive_date'] = date('Y-m-d',$received_order_info['order_receive_date']);
}
$inner_loop_limit = count($received_order_info['received_quantities']);
$quantities = array();
$quantity = 0;
//$this->db->query("UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 1 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);
//$log->write("UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 1 . ", pending_bit = " . 0 . " WHERE id = " . $order_id); 
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

$log->write($received_order_info['received_quantities']);
for($i =0; $i<count($received_order_info['received_quantities']); $i++)
{
if($received_order_info['received_quantities'][$i] != "next product")
{
$received_quantities[$i] = $received_order_info['received_quantities'][$i];
}
}

$prices = array_values($prices);
$received_quantities = array_values($received_quantities);

$log->write($prices); 
$log->write("after price");
$log->write($received_quantities);


for($i =0; $i<count($received_quantities); $i++)
{
$log->write("in for loop");

$log->write("UPDATE oc_po_receive_details SET price =" .$prices[$i]. ", quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
$this->db->query("UPDATE oc_po_receive_details SET quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
$query = $this->db->query("SELECT quantity FROM oc_po_receive_details WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id =" . $order_id);
$quantities[$i] = $query->row['quantity'];
$log->write("quantity"); 
$log->write($quantities[$i]); 
}
}
else
{
$query = $this->db->query("SELECT * FROM oc_po_receive_details WHERE order_id=".$order_id);
$log->write("update order info done select ".$query);
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
//"INSERT INTO oc_po_receive_details (quantity,price,product_id,supplier_id,order_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['prices'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.")"
$this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.",".$query->rows[$j]["store_id"].")");
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

for($i=0; $i<count($received_quantities); $i++)
{
if($received_quantities[$i]!='NA')
{
$query = $this->db->query("SELECT DISTINCT( product_id), store_id FROM oc_po_product WHERE product_id = " . $received_order_info['received_product_ids'][$i]);
$log->write("SELECT DISTINCT( product_id), store_id FROM oc_po_product WHERE product_id = " . $received_order_info['received_product_ids'][$i]);
$product_ids[$i] = $query->row;
$query1 = $this->db->query("UPDATE oc_po_product SET disp_qty=disp_qty+".$quantities[$i].",item_status = 3 WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
$log->write("UPDATE oc_po_product SET disp_qty=disp_qty+".$quantities[$i].",item_status = 3 WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
//$sqlupdatedispatchdetails="update oc_dispatch_details set rcv_qty='',rcv_date='' where product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id";
//$this->db->query($sqlupdatedispatchdetails);

}
}
$totalamount=0;
for($i=0; $i<count($product_ids); $i++)
{

$log->write("SELECT quantity FROM ".DB_PREFIX."product_to_store WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$query = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product_to_store WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$quantity = $quantities[$i];//$query->row['quantity'] ;''+
//$log->write("UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
//$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
$log->write("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$query2 = $this->db->query("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " WHERE store_id=".$product_ids[$i]['store_id']." AND product_id = " . $product_ids[$i]['product_id']);
$log->write("no product");
$log->write($query2);
if($query2->num_rows==0)
{ 
$log->write("insert into ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " , store_id=".$product_ids[$i]['store_id']." ,product_id = " . $product_ids[$i]['product_id']);
$this->db->query("insert into ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " , store_id=".$product_ids[$i]['store_id']." ,product_id = " . $product_ids[$i]['product_id']);

}
if($query && $query2)
{
$log->write("before credit change in ");
$log->write("SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id WHERE store_id=".$product_ids[$i]['store_id']." AND p2s.product_id = " . $product_ids[$i]['product_id']);
//upadte current credit
//get product details
$queryprd = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id WHERE store_id=".$product_ids[$i]['store_id']." AND p2s.product_id = " . $product_ids[$i]['product_id']);
$log->write($queryprd);
$tax=round($this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']));
$totalamount=$totalamount+($quantity*$queryprd->row['price'])+($quantity*$tax);
$log->write($totalamount);

}

if($query && $query2)
$bool = true;
}
/*
* comment by Aasit
* */
if($bool)
{
//update credit price
$log->write("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $totalamount . " WHERE store_id=(select store_id from oc_po_order where id=".$order_id." )");
$this->db->query("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $totalamount . " WHERE store_id=(select store_id from oc_po_order where id=".$order_id." )"); 
//insert store transaction


$sql="insert into oc_store_trans set `store_id`=(select store_id from oc_po_order where id=".$order_id."),`amount`='".$totalamount."',`transaction_type`='1',`cr_db`='CR',`user_id`='".$this->session->data['user_id']."' ";
$log->write($sql);
$this->db->query($sql);
            //**************** CHECK AND UPDATE INDENT STATUS START*******************//
              /*  $chksts="select opo.order_status as 'ORD_STS', opp.quantity as 'IND_QTY', opp.disp_qty as 'RCV_QTY' 
                         from oc_po_order opo
                         left join oc_po_product opp on(opo.id=opp.order_id)
                         where opo.id=='".$order_id."'";  
                $qdata=$this->db->query($chksts);
                $chkdata=$qdata->rows;
                $ord_sts = $chkdata[0]['ORD_STS'];
                $eq=0;
                foreach ($chkdata as $result)
                {
                    if(($result['ORD_STS']== 9) && ($result['IND_QTY']!=$result['RCV_QTY']))
                    {
                        $eq++;
                    }
                }
                if($ord_sts == 9 && $eq>0){
                    $this->db->query("update oc_po_order set order_status = 11 where id='".$order_id."'"); 
                }
                if($ord_sts == 9 && $eq == 0){
                    $this->db->query("update oc_po_order set order_status = 8 where id='".$order_id."'");
                }
                */
            //**************** CHECK AND UPDATE INDENT STATUS END*******************// 
}

if($bool)
return true;
}

//*********************************Receive Order MO Ends*******************************//
//********************** Search Unapproved Indent Based by Store ID ******************//
public function chkApproveIndent($store_id,$indent_dt){
$log=new Log("chkApproveIndent_".date('Y_m_d').".log");
$sql="select id, order_date from oc_po_order where order_status = 1 and store_id = '".$store_id."' and order_date='".$indent_dt."'";
$log->write($sql);
$query = $this->db->query($sql);
return $query->rows;
}
public function chkApproveIndentList($store_id,$indent_id,$indent_dt){
$log=new Log("chkApproveIndentList_".date('Y_m_d').".log");
$sql="select order_id, product_id,store_id,name,quantity from oc_po_product where order_id = '".$indent_id."' and store_id = '".$store_id."' ";
$log->write($sql);
$query = $this->db->query($sql);
return $query->rows;
}
public function updateIndentList($indent_id,$product_ids,$product_qtys)
{
$log=new Log("updateIndentList_".date('Y_m_d').".log");
for($x=0;$x<count($product_ids);$x++)
{
$chk="select id from oc_po_product where order_id = '".$indent_id[$x]."' and product_id = '".$product_ids[$x]."' and quantity = '".$product_qtys[$x]."'";
$check = $this->db->query($chk);
if(!$check->num_rows)
{ 
$sql="update oc_po_product set quantity = '".$product_qtys[$x]."' where order_id = '".$indent_id[$x]."' and product_id = '".$product_ids[$x]."' and item_status=0";
$log->write($sql);
$query = $this->db->query($sql);
}
}
if($query->num_rows)
{
return 1;
}
else {
return 2;
}
}
public function deleteIndentList($indent_id,$product_id)
{
$log=new Log("deleteIndentList_".date('Y_m_d').".log");
$sql="delete from oc_po_product where order_id = '".$indent_id."' and product_id = '".$product_id."'and item_status=0";
$log->write($sql);
$query = $this->db->query($sql);
if($query->num_rows)
{
return 1;
}
else {
return 2;
}
}
    //******************************* Store Key ********************************//
    public function StoKey($StoId){
		$log=new Log("StoreKey_".date('Y_m_d').".log");
        $sql="SELECT store_key FROM oc_store WHERE store_id='".$StoId."'";
		$log->write($sql);
        $query = $this->db->query($sql);
		$log->write('StoreKey Against StoreId:'.$StoId.' = '.$query->row['store_key']);
        return $query->row['store_key'];
    }

    //******************************* Dealer Mobile ****************************//
    public function DlMob($stoid){
       // $sql="SELECT telephone FROM ak_customer WHERE customer_id=(SELECT customer_id FROM oc_user WHERE store_id='".$stoid."') LIMIT 1";
        $sql="SELECT 
        DS.customer_id AS 'DIS_ID', 
        DS.telephone AS 'DIS_MOB',
        CONCAT(DS.firstname,' ',DS.lastname) AS 'DIS_NAME',
        MP.GEO_ID AS 'DIS_DIST_ID',
        DT.`NAME` AS 'DIS_DIST_NAME'
        FROM ak_customer DS
        JOIN ak_customer_emp_map MP ON(DS.customer_id=MP.CUSTOMER_ID AND MP.GEO_LEVEL_ID=4)
        JOIN ak_mst_geo_upper DT ON(MP.GEO_ID=DT.SID)
        WHERE DS.customer_id=(SELECT customer_id FROM oc_user WHERE store_id='".$stoid."') LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }
    //******************************* Am Mobile ****************************//
    public function AmMob($moid){
        $sql="SELECT AK.customer_id AS 'MO_ID',CONCAT(AK.firstname,' ',AK.lastname) AS 'MO_NAME',MP.PARENT_USER_ID,AM.telephone AS 'AM_MOB'
        FROM oc_user OC 
        JOIN ak_customer AK ON(OC.customer_id=AK.customer_id)
        JOIN ak_customer_map MP ON(AK.customer_id=MP.CUSTOMER_ID)
        JOIN ak_customer AM ON(MP.PARENT_USER_ID=AM.customer_id)
        WHERE OC.user_id='".$moid."' LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }
    //******************************* Message Response ****************************//
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