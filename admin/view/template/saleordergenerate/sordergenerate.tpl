<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <h1>GENERATE SALE ORDER</h1>
        </div>
    </div>
    <div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Order Generate</h3>
        <?php if (isset($_SESSION['delete_success_message'])) {		?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['delete_success_message']; unset($_SESSION['delete_success_message']);?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (isset($_SESSION['input_error'])) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['input_error']; unset($_SESSION['input_error']);?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        </div>
        <div class="panel-body">
        <!-- FILTER START -->
        <div class="well">
        <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">SELECT WHOLESALER</label>
                <input type="text" name="filter_name_wh" id="filter_name_wh" value="<?php echo $fi_sto_id_name; ?>" placeholder="Select Wholesaler"  class="form-control" />
                <input type="hidden" name="filter_name_wh_id" id="filter_name_wh_id" value="<?php echo $fi_sto_id; ?>" />
            </div>              
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">MARKETING OFFICER</label>
                <input type="text" name="filter_name_mo" id="filter_name_mo" value="<?php echo $fi_mao_id_name; ?>" placeholder="Marketing Officer" class="form-control" />
                <input type="hidden" name="filter_name_mo_id"  value="<?php echo $fi_mao_id; ?>" id="filter_name_mo_id" />
            </div>              
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">SELECT PRODUCT</label>
                <input type="text" name="filter_name" id="filter_name" value="<?php echo $fi_pro_id_name; ?>" placeholder="Select Product"  class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $fi_pro_id; ?>" id="filter_name_id"/>
            </div>              
        </div>
        <div class="col-sm-3" style="margin-top: 22px!important">
            <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;<?php echo $button_filter; ?></button>
       
            <button type="button" id="button-sale" class="btn btn-warning pull-right"><i class="fa fa-file-text"></i> Generate Sale Order</button>
        </div>
            <!-- Filter End -->
    </div>
    </div>

            <form  name="sale_form" id="sale_form" class="form-horizontal">   
            <div class="table-responsive">
            <table class="table table-bordered table-hover">
            <thead>
                <tr style="background: #515151; color: #ffffff !important;">
                    <td class="text-left">SNO.</td>
                    <td class="text-left" style="width: 9%;">INDENT DATE</td>
                    <td class="text-left" style="width: 7%;">INDENT NO</td>
                    <td class="text-left">MARKETING OFFICER</td>
                    <td class="text-left">WHOLESALER</td>
                    <td class="text-left">PRODUCT NAME</td>
                    <td class="text-left">INDENT QTY.</td>
                    <td class="text-left">PENDING QTY.</td>
                    <td class="text-left">SALE ORDER QTY.</td>
                    <td class="text-left">CHECK</td>
                </tr>
            </thead>
            <tbody>
            <?php
                //print_r($order_list);
                if($order_list){
                    $a='1';
                    foreach($order_list as $order)
                    {
                        if($order['pending_quantity']!=0)
                        {
			?>
			<tr>
                            <td class="text-left"><?php echo $a; ?></td>
                            <td class="text-left"><?php echo date("d-m-Y",strtotime($order['order_date']));?></td>
                            <td class="text-left"><?php echo $order['oid'];?></td>
                            <td class="text-left"><?php echo $order['user_name'];?></td>
                            <td class="text-left"><?php echo $order['store_name'];?></td>
                            <td class="text-left"><?php echo $order['prod_name'];?></td>
                            <td class="text-left"><?php echo $order['quantity']; ?></td>
                            <td class="text-left"><?php echo $order['pending_quantity'];?></td>
                            <td class="text-left">
                                <input type="hidden" class="form-control" name="stoid[]" id="sto<?php echo $order['pid'];?>" value="<?php echo $order['sto_id'];?>">
                                <input type="hidden" class="form-control" name="pqty[]" id="pqty<?php echo $order['pid'];?>" value="<?php echo $order['pending_quantity'];?>">
                                <input type="text" class="form-control" name="sqty[]" id="sqty<?php echo $order['pid'];?>" value="0"  onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.keyCode==8 || event.keyCode==46'>
                                <input type="hidden" class="form-control" name="oid[]" id="oid<?php echo $order['pid'];?>" value="<?php echo $order['oid'];?>">
                                <input type="hidden" class="form-control" name="pid[]" id="pid<?php echo $order['pid'];?>" value="<?php echo $order['pid'];?>">
                            </td>
                            <td style="width:1px"class="text-center">
                                <input type="checkbox" name="chk[]" value="<?php echo $order['pid'];?>" onclick="$('input[name*=\'selected\']').prop('checked',this.checked);" />
                            </td>
			</tr>
			<?php
                            }
                            $a++;
                        }
                    }?>
            </tbody>
            </table>
        </div>
        </form>
      </div>
    </div>
  </div>

<script type="text/javascript">
$('#button-filter').on('click', function() {
	//url = 'index.php?route=report/inventory_report/product_wise&token=<?php echo $token; ?>';
        url = 'index.php?route=saleordergenerate/sordergenerate&token=<?php echo $token; ?>';
       
	//alert(url);
        
        var filter_name_wh_id = $('input[name=\'filter_name_wh_id\']').val();
        var filter_name_wh = $('input[name=\'filter_name_wh\']').val();
        //alert(filter_name_wh_id+'::'+filter_name_wh);
        //if(filter_name_wh_id == '' || filter_name_wh == '')
        //{
          //  alert('Select wholesaler first');
          //  return false;
       // }
        
        var filter_name_mo_id = $('input[name=\'filter_name_mo_id\']').val();
        var filter_name_mo = $('input[name=\'filter_name_mo\']').val();
        //alert(filter_name_mo_id+'::'+filter_name_mo);
        
        var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();
        //alert(filter_name_id+'::'+filter_name);
        
        if (filter_name_wh) {
            url += '&sto_id=' + encodeURIComponent(filter_name_wh_id);
            url += '&sto_id_name=' + encodeURIComponent(filter_name_wh);
        }
        if (filter_name_mo) {
            url += '&mao_id=' + encodeURIComponent(filter_name_mo_id);
            url += '&mao_id_name=' + encodeURIComponent(filter_name_mo);
        }
         if (filter_name) {
            url += '&pro_id=' + encodeURIComponent(filter_name_id);
            url += '&pro_id_name=' + encodeURIComponent(filter_name);
        }
        //alert(url);
	location = url;
        //return false;
});
</script> 

<script type="text/javascript"><!--
$('#button-download').on('click', function() { 
	url = 'index.php?route=report/inventory_report/download_excel_product_wise&token=<?php echo $token; ?>';
	
        var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name_id) {
                if(filter_name!=="")
                {
        url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
    }
       

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }
       // alert(url);
	//location = url;
	window.open(url, '_blank');
});
//--></script>

 <script type="text/javascript">
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name\']').val(item['label']);
                $('input[name=\'filter_name_id\']').val(item['value']);
    }
});
</script>
 <script type="text/javascript">
$('input[name=\'filter_name_mo\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=saleordergenerate/sordergenerate/autocomplete&token=<?php echo $token; ?>&filter_name_mo=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) { //alert(json)
                response($.map(json, function(item) {
                    return {
                        label: item['firstname'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name_mo\']').val(item['label']);
                $('input[name=\'filter_name_mo_id\']').val(item['value']);
    }
});
</script>
<script type="text/javascript">
$('input[name=\'filter_name_wh\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=saleordergenerate/sordergenerate/autocompletewh&token=<?php echo $token; ?>&filter_name_wh=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['firstname'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name_wh\']').val(item['label']);
                $('input[name=\'filter_name_wh_id\']').val(item['value']);
    }
});
</script>
<script type="text/javascript">
$('#button-sale2').on('click', function() { 
        var serlizedata=$("#sale_form").serialize();
	var url= 'index.php?route= saleordergenerate/sordergenerate/insert_sale_order&token=<?php echo $token; ?>';
        alert(serlizedata);
        $.post(url, serlizedata, function (data) {
                alert(data);
        });
});
</script>

<script> 
 $('#button-sale').click(function() { 
 //alert("hasjdja");
         var cust_sto_id = $('input[name=\'filter_name_wh_id\']').val(); 
         var cbc = document.getElementsByName('chk[]'); 
     
          var result = ''; 
        
          for(var i=0; i<cbc.length; i++)  
          { 
             if(cbc[i].checked ) result += (result.length > 0 ? "_" : "") + cbc[i].value;
            
          } 
          //alert(result);
            var res = result.split("_");
            var lstqty='';
            var lstoid='';
            var lstpid='';
            var lstwid='';
            for(var j=0;j< res.length; j++){
            var pqty= $('#pqty'+res[j]).val();
            var sqty= $('#sqty'+res[j]).val();
            var oid= $('#oid'+res[j]).val();
            var pid= $('#pid'+res[j]).val();
            var wid= $('#sto'+res[j]).val();
            var pq=parseInt(pqty);
            var sq=parseInt(sqty);
            if(!sq)
            {
                sq=0;
            }
           // alert(pq+":"+sq);
            if(pq < sq)
            {
                alert('Order quantity must be less than or equal to pending quantity.');
                return false;
            }
            lstqty+=sqty+"_";
            lstoid+=oid+"_";
            lstpid+=pid+"_";
            lstwid+=wid+"_";
         }
        //alert(lstwid);
        
        if(lstwid.charAt(lstwid.length - 1) == '_') {
            lstwid = lstwid.substr(0, lstwid.length - 1);
        }
        var res = lstwid.split("_");
        //alert(res);
        Array.prototype.allValuesSame = function() {
            for(var i = 1; i < this.length; i++)
            {
                if(this[i] !== this[0])
                return false;
            }
        return true;
        }
        var b = res.allValuesSame(); //true
        if(b==false)
        {
            alert('Sale Order can be generated only for same wholesaler');
            return false;
        }
        
        
        
        if(result){ 
        var con=confirm("Do you want to Generate Sale Order?"); 
             
            if(con==true){ 
           
                url = 'index.php?route=saleordergenerate/sordergenerate/insert_sale_order&token=<?php echo $token; ?>';
                url += '&prd_ref_id=' + result;
                url += '&gsqty=' + lstqty;
                url += '&goid=' + lstoid;
                url += '&gpid=' + lstpid;
                url += '&cust_sto_id=' + cust_sto_id;
                
                location = url;
                
            }else{ 
                 
                return false; 
                 
            } 
             
        }else{ 
             
            alert("Please select atleast one dispatch!"); 
            return false; 
        } 
           
    });
  

</script>
                                                                                            
 </div>
<?php echo $footer; ?>


