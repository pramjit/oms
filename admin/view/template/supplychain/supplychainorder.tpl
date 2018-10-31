<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Supply Chain Order Dispatch</h1>

    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Order Dispatch</h3>
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
              <select name="SAP_ID" id="SAP_ID" class="col-sm-4 form-control select2">
                        <option value='0'> Select SAP ID</option>   
                        <?php foreach($SPLIST as $SL){
                        echo '<option value='.$SL['SAP_ID'].'>'.$SL['SAP_ID'].'</option>';
                        }
                        ?>
                    </select>  
            </div>              
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                 <select name="WS_ID" id="WS_ID" class="col-sm-4 form-control select2">
                        <option value='0'> Select Wholesaler</option> 
                        <?php foreach($WSLIST as $WL){
                        echo '<option value='.$WL['WS_ID'].'>'.$WL['WS_NAME'].'</option>';
                        }
                        ?>
                    </select>
            </div>              
        </div>
              
         <div class="col-sm-3">
            <div class="form-group">
                 <select name="PRO_ID" id="PRO_ID" class="col-sm-4 form-control select2">
                        <option value='0'> Select Product</option>
                        <?php foreach($PROLIST as $PL){
                        echo '<option value='.$PL['PRO_ID'].'>'.$PL['PRO_NAME'].'</option>';
                        }
                        ?>
                    </select>
            </div>              
        </div>
        <div class="col-sm-3">
            <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;<?php echo $button_filter; ?></button>
            
        </div>
        <div class="col-sm-3">
            
            <button type="button" id="disp_order" class="btn btn-warning pull-right"><i class="fa fa-file-text"></i> &nbsp;Dispatch Order</button>
        </div>
            <!-- Filter End -->
        </div>
        
        
        </div>
        <div class="table-responsive">
             
          <table class="table table-bordered table-hover">
              <thead>
                <tr style="background: #515151; color: #ffffff !important;">
                  
                                  <td class="text-left">SNO</td>
				  <td class="text-left">SALE ORDER DATE</td>
				  <td class="text-left">SAP REF NUMBER</td>
                                  <td class="text-left">MARKETING OFFICER</td>
                                  <td class="text-left">WHOLESALER</td>
				  <td class="text-left">PRODUCT NAME</td>
                                  <td class="text-left">SALE ORDER QUANTITY</td>
				  <td class="text-left">DISPATCH QUANTITY</td>
                                  <td class="text-left">CHECK</td>
				
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
                    $a='1';
					foreach($order_list as $order)
					{
                                            if($order['SAP_QTY'] != 0)
                                            {
				?>
						<tr>
							
							<td class="text-left"><?php echo $a; ?></td>
							<td class="text-left"><?php echo date("d-m-Y",strtotime($order['SAP_DATE']));?></td>
							<td class="text-left"><?php echo $order['SAP_REF'];?></td>
                                                        <td class="text-left"><?php echo $order['MO_NAME'];?></td>
                                                        <td class="text-left"><?php echo $order['STO_NAME'];?></td>
                                                        <td class="text-left"><?php echo $order['PROD_NAME'];?></td>
							<td class="text-left"><?php echo round($order['SAP_QTY']);?></td>
							<td class="text-left">
                                                            <input type="hidden" class="form-control" value="<?php echo $order['STO_ID'];?>" name="sto_id<?php echo $order['SAP_REF'].$order['PROD_ID'];?>" id="sto_id<?php echo $order['SAP_REF'].$order['PROD_ID'];?>">
                                                            <input type="hidden" class="form-control" value="<?php echo $order['PROD_ID'];?>" name="prod_id<?php echo $order['SAP_REF'].$order['PROD_ID'];?>" id="prod_id<?php echo $order['SAP_REF'].$order['PROD_ID'];?>">
                                                            <input type="hidden" class="form-control" value="<?php echo $order['SAP_REF'];?>" name="sap_id<?php echo $order['SAP_REF'].$order['PROD_ID'];?>" id="sap_id<?php echo $order['SAP_REF'].$order['PROD_ID'];?>">
                                                            <input type="hidden" class="form-control" value="<?php echo round($order['SAP_QTY']);?>" name="sap_qty<?php echo $order['SAP_REF'].$order['PROD_ID'];?>" id="sap_qty<?php echo $order['SAP_REF'].$order['PROD_ID'];?>">
                                                            <input type="text" class="form-control" name="disp_qty<?php echo $order['SAP_REF'].$order['PROD_ID'];?>" id="disp_qty<?php echo $order['SAP_REF'].$order['PROD_ID'];?>" value=""></td>
                                                        <td style="width: 1px;" class="text-center"><input type="checkbox" name="arr_user_ids[]" value="<?php echo $order['SAP_REF'].$order['PROD_ID'];?>" /></td>
                                                        
						</tr>
				<?php
                                            }   
					$a++;}
				}?>
              </tbody>
            </table>
        </div>
        <!-- #####**********#####Supply Chain Modal Start#####*******#####-->
    <div class="container">
  <!--  <h2>Modal Example</h2>
 Trigger the modal with a button 
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
-->
  <!-- Modal -->
  
  <div class="modal fade" id="supModal" role="dialog">
    <div class="modal-dialog">
        <div id="supModalLoad" style="margin: 0px auto; width: 200px; height: 100px;"><img src="view/image/loader.jpg"></div>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Transport Details</h4>
        </div>
        <div class="modal-body">
            <form id="supform" name="supform" enctype="multipart/form-data">
            <div class="form-group">
            <label for="input-username">Transport Name:</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                <input type="text" name="trans_name" value="" id="trans_name" class="form-control" placeholder="Transport Name" required="required">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Vehicle Number:</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input maxlength="10" type="text" name="vehi_num" value="" id="vehi_num" placeholder="Vehicle Number"  class="form-control">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Grr. Details:</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input type="text" name="grr_dtls" id="grr_dtls" value="" placeholder="Grr. Details"  class="form-control">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Driver Mobile:</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input maxlength="10" type="text" name="dri_mob" id="dri_mob" value="" placeholder="Driver Mobile Number" class="form-control">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Invoice Number:</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input type="text" name="inv_num" id="inv_num" value="" placeholder="Invoice Number"  class="form-control">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Upload Doc.:</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input type="file" name="sup_doc" id="sup_doc" value="" placeholder="Supply Doc" class="form-control">
            </div>
            </div>
            <div class="form-group">
               <input type="hidden" name="get_disp_order" id="get_disp_order" value="" class="form-control">
               <input type="hidden" name="get_dis_prod_id" id="get_dis_prod_id" value="" class="form-control">
               <input type="hidden" name="get_dis_sap_id" id="get_dis_sap_id" value="" class="form-control">
            </div>
            </div>
            <div class="text-right form-group">
                
                <button type="button" class="btn btn-primary form-control" id="sub_sup_data">Submit</button>
                <button type="button" class="btn btn-default form-control" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>
  
</div>

        <!--#####***********#####Supply Chain Modal End#####*********#### -->
       <!-- <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results; ?>  </div>
        </div>-->
      </div>
    </div>
  </div>
<script type="text/javascript">

 $(document).ready(function(){
        $("#supModalLoad").hide();
        $("#SAP_ID").select2({ 
            tags: true,
            placeholder: '   Select SAP ID',
            width: '100%'
        });
        
        $("#WS_ID").select2({ 
            tags: true,
            placeholder: '   Select WHOLESALER',
            width: '100%'
        });
        
        $("#PRO_ID").select2({ 
            tags: true,
            placeholder: '   Select PRODUCT',
            width: '100%'
        });
    });
    
 $('#button-filter').on('click', function() {
	
        url = 'index.php?route=supplychain/supplychainorder&token=<?php echo $token; ?>';
        var sap_id = $("#SAP_ID").val();
        var sto_id = $("#WS_ID").val();
        var pro_id = $("#PRO_ID").val();
        if(sap_id){ url += '&sap_id='+sap_id ; }
        if(sto_id){ url += '&sto_id='+sto_id ; }
        if(pro_id){ url += '&pro_id='+pro_id ; }
        
        location = url;
       
});
</script>
<script type="text/javascript">
 $('#disp_order').click(function() { 
 //alert("hasjdja");
          
        var cbc = document.getElementsByName('arr_user_ids[]'); 
        var result = ''; 
        for(var i=0; i<cbc.length; i++)  
        { 
            if(cbc[i].checked ) result += (result.length > 0 ? "_" : "") + cbc[i].value;
        } 
            var res = result.split("_");
            var lstreq='';
            var lstval='';
            var lstprd='';
            var lstsap='';
            var lststo ='';
            for(var j=0;j<res.length;j++){
                var req= $('#sap_qty'+res[j]).val();
                var dis= $('#disp_qty'+res[j]).val();
                var prd= $('#prod_id'+res[j]).val();
                var sap= $('#sap_id'+res[j]).val();
                var sto= $('#sto_id'+res[j]).val();
                var rq=parseInt(req);
                var sq=parseInt(dis);
                
                if(!sq)
                {
                    sq=0;
                }
                if(rq < sq)
                {
                    alert('Dispatch quantity must be less than or equal to pending quantity.');
                    return false;
                }
                       
            lstreq+=req+"_";
            lstval+=dis+"_";
            lstprd+=prd+"_";
            lstsap+=sap+"_";
            lststo+=sto+"_";
            
        }
        // alert(lststo);
        // alert(result);
        // alert(lstval);
        // alert(lstprd);
        // alert(lstsap);
        if(lststo.charAt(lststo.length - 1) == '_') {
            lststo = lststo.substr(0, lststo.length - 1);
        }
        var res = lststo.split("_");
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
            alert('Dispatch Order can be generated only for same wholesaler');
            return false;
        }
        if(result){ 
        //alert(result);
        //$('#get_sap_ref_id').val(result);
        $('#get_disp_order').val(lstval);
        $('#get_dis_prod_id').val(lstprd);
        $('#get_dis_sap_id').val(lstsap);
        $('#supModal').modal();
        
        //var con=confirm("Do you want to dispatch?"); 
             
           /* if(con==true){ 
           
                url = 'index.php?route=supplychain/supplychainorder/orderdispatch&token=<?php echo $token; ?>';
                url += '&sap_ref_id=' + result;
                url += '&disp_order=' + lstval;
                url += '&dis_prod_id=' + lstprd;
                url += '&dis_sap_id=' + lstsap;
                location = url;
            }else{ 
                 
                return false; 
                 
            } */
             
        }else{ 
             
            alert("Please select atleast one dispatch!"); 
            return false; 
        } 
           
    });
</script>
<script type="text/javascript">
 $('#sub_sup_data').click(function() { 
     var data = new FormData();
     data.append('UPLOAD', $('#sup_doc')[0].files[0]);
     //Other form Data
     var tname = $('#trans_name').val();
     if(tname=='')
     {
         alert('Please Enter Transport Name');
         return false;
     }
     var vnum =  $('#vehi_num').val();
     if(vnum=='')
     {
         alert('Please Enter Vehicle Number');
         return false;
     }
     var grdtl = $('#grr_dtls').val();
     if(grdtl=='')
     {
         alert('Please Enter Grr Details');
         return false;
     }
     var dnum =  $('#dri_mob').val();
     if(dnum=='')
     {
         alert('Please Enter Driver Mobile Number');
         return false;
     }
     var inum =  $('#inv_num').val();
     if(inum=='')
     {
         alert('Please Enter Invoice Number');
         return false;
     }
     var lstval = $('#get_disp_order').val();
     var lstprd = $('#get_dis_prod_id').val();
     var lstsap = $('#get_dis_sap_id').val();
     url = 'index.php?route=supplychain/supplychainorder/orderdispatch&token=<?php echo $token; ?>';
                url += '&sap_ref_id=' + lstsap;
                url += '&disp_order=' + lstval;
                url += '&dis_prod_id=' + lstprd;
                url += '&dis_sap_id=' + lstsap;
                url += '&tname=' + tname;
                url += '&vnum=' + vnum;
                url += '&grdtl=' + grdtl;
                url += '&dnum=' + dnum;
                url += '&inum=' + inum;
    $.ajax({
    url: url,
    type: "POST",
    data : data,
    contentType: false,
    cache : false,
    processData :false,
    beforeSend: function(){ 
            $(".modal-content").hide();
            $("#supModalLoad").show();
        },
    success: function (retdata) {
   $('#supModal').modal('toggle');
   $('#supform').trigger("reset");
   location.reload();
    }
});
     /*
     var data = new FormData('#supform');
     data.append('upload_doc', $('#sup_doc').files);
     var tname = $('#trans_name').val();
     var vnum =  $('#vehi_num').val();
     var grdtl = $('#grr_dtls').val();
     var dnum =  $('#dri_mob').val();
     var inum =  $('#inv_num').val();
     var lstval = $('#get_disp_order').val();
     var lstprd = $('#get_dis_prod_id').val();
     var lstsap = $('#get_dis_sap_id').val();
     url = 'index.php?route=supplychain/supplychainorder/orderdispatch&token=<?php echo $token; ?>';
                url += '&sap_ref_id=' + lstsap;
                url += '&disp_order=' + lstval;
                url += '&dis_prod_id=' + lstprd;
                url += '&dis_sap_id=' + lstsap;
                url += '&tname=' + tname;
                url += '&vnum=' + vnum;
                url += '&grdtl=' + grdtl;
                url += '&dnum=' + dnum;
                url += '&inum=' + inum;
                url += '&data=' + data;
                location = url;
    
        $.ajax({
        url : "index.php?route=supplychain/supplychainorder/orderdispatch&token=<?php echo $token; ?>",
        type: "POST",
        data : data,
       contentType: false,
       cache : false,
        processData :false,
        success: function (html) {
        alert(html);
            }
        });
   
   
   */
     
 });
 </script>

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
$('input[name=\'filter_name_wh\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=supplychain/supplychainorder/autocompletewh&token=<?php echo $token; ?>&filter_name_wh=' +  encodeURIComponent(request),
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
$('input[name=\'filter_name_sap\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=supplychain/supplychainorder/autocomplete&token=<?php echo $token; ?>&filter_name_sap=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) { //alert(json)
                response($.map(json, function(item) {
                    return {
                        label: item['sap_ref'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name_sap\']').val(item['label']);
        $('input[name=\'filter_name_sap_id\']').val(item['value']);
    }
});





</script>

 </div>
<?php echo $footer; ?>