<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Manage Indents</h1>

    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Approved Indent List</h3>
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
  <!--  <div class="well">
        <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">SAP REFERENCE ID</label>
                 <input type="text" name="filter_name_sap" id="filter_name_sap" value="<?php echo $fi_sap_id_name; ?>"placeholder="SAP Refeference Id" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_sap_id" id="filter_name_sap_id"  value="<?php echo $fi_sap_id; ?>"/>
            </div>              
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-name">SELECT WHOLESALER</label>
                <input type="text" name="filter_name_wh" name="filter_name_wh" value="<?php echo $fi_sto_id_name; ?>" placeholder="Select Wholesaler" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_wh_id" id="filter_name_wh_id" value="<?php echo $fi_sto_id; ?>"/>
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
            <button type="button" id="disp_order" class="btn btn-warning pull-right"><i class="fa fa-file-text"></i> &nbsp;Dispatch Order</button>
        </div>-->
            <!-- Filter End -->
        </div>
        </div>
        <div class="table-responsive">
             
          <table class="table table-bordered table-hover">
              <thead>
                <tr style="background: #515151; color: #ffffff !important;">
                    <td class="text-left">SNO</td>
                    <td class="text-left">INDENT NO</td>
                    <td class="text-left">INDENT DATE</td>
                    <td class="text-left">MARKETING OFFICER</td>
                    <td class="text-left">WHOLESALER</td>
                    <td class="text-left">INDENT TOTAL QUANTITY</td>
                    <td class="text-left">ACTION</td>
		</tr>
              </thead>
              <tbody>
                <?php if($order_list){
                $a='1';
		foreach($order_list as $order){
                ?>
                    <tr>
                        <td class="text-left"><?php echo $a; ?></td>
                        <td class="text-left"><?php echo $order['IND_ID'];?></td>
                        <td class="text-left"><?php echo $order['IND_DATE'];?></td>
                        <td class="text-left"><?php echo $order['MO_NAME'];?></td>
                        <td class="text-left"><?php echo $order['WS_NAME'];?></td>
                        <td class="text-left"><?php echo round($order['TOT_IND_QTY']);?></td>
                        <td class="text-left btn-primary" onclick="fuckup(<?php echo $order['IND_ID'];?>);">EDIT/UPDATE</td>
                    </tr>			
		<?php
                    $a++;
                    }
		}
                ?>                      
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
    <div class="modal-dialog modal-lg">
        <div id="supModalLoad" style="margin: 0px auto; width: 200px; height: 100px;"><img src="view/image/loader.jpg"></div>
        <!-- Modal content-->
        <div class="modal-content">
        <form id="proform" name="proform">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center font-bold">PRODUCT LIST OF INDENT NO :&nbsp;&nbsp;<span id="indno"></span></h4>
        </div>
        <div class="modal-body"><!-- Load Content --></div>
        <div class="modal-footer">
            <div class="form-group">
                <div class="col-md-6"></div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary form-control" id="sub_sup_data">Submit</button>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-default form-control" data-dismiss="modal">Cancel</button>
            </div>
            </div>
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
$( document ).ready(function() {
    $("#supModalLoad").hide();
});

    
 $('#button-filter').on('click', function() {
	//url = 'index.php?route=report/inventory_report/product_wise&token=<?php echo $token; ?>';
        url = 'index.php?route=supplychain/supplychainorder&token=<?php echo $token; ?>';
       
        var filter_name_sap_id = $('input[name=\'filter_name_sap_id\']').val();
        var filter_name_sap = $('input[name=\'filter_name_sap\']').val();
        //alert(filter_name_sap_id+'::'+filter_name_sap);
        /*if(filter_name_sap_id == '' || filter_name_sap == '')
        {
            alert('Select SAP-Reference first');
            return false;
        }*/
        
        var filter_name_wh_id = $('input[name=\'filter_name_wh_id\']').val();
        var filter_name_wh = $('input[name=\'filter_name_wh\']').val();
        //alert(filter_name_wh_id+'::'+filter_name_wh);
        //if(filter_name_wh_id == '' || filter_name_wh == '')
        //{
          //  alert('Select wholesaler first');
           // return false;
        //}
        
        
        var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();
        //alert(filter_name_id+'::'+filter_name);
     
        if (filter_name_wh) {
            url += '&sto_id=' + encodeURIComponent(filter_name_wh_id);
            url += '&sto_id_name=' + encodeURIComponent(filter_name_wh);
        }
        if (filter_name_sap) {
            url += '&sap_id=' + encodeURIComponent(filter_name_sap_id);
            url += '&sap_id_name=' + encodeURIComponent(filter_name_sap);
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
<script type="text/javascript">
    function fuckup(indid){
        //alert(indid);
        $.ajax({
        type: "POST",
        url: "index.php?route=manageorder/orderlist/prodlist&token=<?php echo $token; ?>",
        data: "indid="+indid,
        dataType: "text",
        success: function( data ) {
                //alert(data);
                $('#indno').html(indid);
                $('#supModal').modal();
                $(".modal-body").html(data);
                $(".modal-content").show();
            }
        }); 
       // $('#supModal').modal();
       // $(".modal-content").hide();
       // $("#supModalLoad").show();
    }
</script>
</div>
<?php echo $footer; ?>