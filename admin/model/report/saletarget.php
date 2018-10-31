<?php
class ModelReportSaletarget extends Model {
	
    
    function saletargetsubmitdata($order_info)
    {
        $tdesp=$this->request->post["tdesp"];
        $filter_start=$this->request->post["filter_date_start"];
        $filter_end=$this->request->post["filter_date_end"];
        $type=$this->request->post["tar_id"];
        //print_r($this->request->post);
        $this->db->query("INSERT INTO target_master SET TARGET_DESC = '".$tdesp. "', FROM_DATE = '".$filter_start. "', TO_DATE = '".$filter_end."', TYPE = '".$type."'");
        $lid=$this->db->getLastId();
        $count=count($this->request->post["prod_name"]);
        for($a=0;$a<$count;$a++)
        {
            $prod_name=$this->request->post["prod_name"][$a];
            $prod_qty=$this->request->post["qty"][$a];
            $prod_amt=$this->request->post["amt"][$a];
            $this->db->query("INSERT INTO target_products SET TARGET_ID = '".$lid. "', PRODUCT_ID = '".$prod_name. "', PRODUCT_QTY = '".$prod_qty."', PRODUCT_AMOUNT = '".$prod_amt."'");	
        }
	
    }
    
    function prodname()
    {
        $query = $this->db->query("select opd.product_id,opd.name,op.sku from oc_product_description as opd
                                   left join oc_product as op on opd.product_id=op.product_id");

        return $query->rows;   
    }
    

}