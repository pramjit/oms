<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1>Add Tour Budget</h1>
        </div>
    </div>
    <div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Add Budget</h3>
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
        
        
        
        
        
    <form action = "index.php?route=budget/budgetlist/budgetsubmit&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data" id= "filter_form">
        <div class="panel-body">
        <!-- FILTER START -->
    <div class="well">
        <?php 
        $dt = strtotime(date('Y-m-01'));
        for ($j = 0; $j <= 5; $j++){
            $optval[]=array(
                  'opt'=>(date("FY", strtotime(" -$j month", $dt))), 
                  'val'=>(date("F, Y", strtotime(" -$j month", $dt)))
            );
        }
       /* $month1=date('FY', strtotime('-0 month', time()));
        $month2=date('FY', strtotime('-1 month', time()));
        $month3=date('FY', strtotime('-2 month', time()));
        $month4=date('FY', strtotime('-3 month', time()));
        $month5=date('FY', strtotime('-4 month', time()));
        $month6=date('FY', strtotime('-5 month', time()));

        $month1V=date('F, Y', strtotime('-0 month', time()));
        $month2V=date('F, Y', strtotime('-1 month', time()));
        $month3V=date('F, Y', strtotime('-2 month', time()));
        $month4V=date('F, Y', strtotime('-3 month', time()));
        $month5V=date('F, Y', strtotime('-4 month', time()));
        $month6V=date('F, Y', strtotime('-5 month', time()));
        */
        ?>
        <div class="row">
        <div class="col-sm-2">
        <div class="form-group">
        <label class="control-label" for="input-name">SELECT MONTH</label>
        <select name="month" id="month" class="form-control ">
            <option value=''>Select Month</option> 
            <?php
            foreach($optval as $OV){
            ?>
            <option value="<?php echo $OV['opt']; ?>" <?php echo ($OV['opt']== $gmonth) ? 'selected' : ''; ?>> <?php echo strtoupper($OV['val']); ?></option>';
            <?php
            }
            ?>
            <!--
             <?php echo ($access == 1) ? 'selected' : ''; ?>
            <option value='<?php echo $month1; ?>' <?php if($month1==$gmonth) { echo 'selected'; } ?>><?php echo strtoupper($month1V); ?></option>
            <option value='<?php echo $month2; ?>' <?php if($month2==$gmonth) { echo 'selected'; } ?>><?php echo strtoupper($month2V); ?></option>
            <option value='<?php echo $month3; ?>' <?php if($month3==$gmonth) { echo 'selected'; } ?>><?php echo strtoupper($month3V); ?></option>
            <option value='<?php echo $month4; ?>' <?php if($month4==$gmonth) { echo 'selected'; } ?>><?php echo strtoupper($month4V); ?></option>
            <option value='<?php echo $month5; ?>' <?php if($month5==$gmonth) { echo 'selected'; } ?>><?php echo strtoupper($month5V); ?></option>
            <option value='<?php echo $month6; ?>' <?php if($month6==$gmonth) { echo 'selected'; } ?>><?php echo strtoupper($month6V); ?></option>
            -->
        </select>
        </div> 
        </div> 

        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label" for="input-name">SELECT DISTRICT</label>
                <select name="dist_id" id="dist_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple">
                    <option value=''>Select District</option> 
                    <option value=''>Select ALL</option>
                    <?php foreach ($listDISTs as $listDIST) { ?>
                    <option value="<?php echo $listDIST['SID']; ?>"<?php if($listDIST['SID']==$filter_dist) { echo 'selected'; } ?>><?php echo $listDIST['NAME']; ?></option>
                    <?php } ?>
                </select>
            </div>              
        </div>
              
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label" for="input-name">SELECT</label>
                <select name="am_or_mo" id="am_or_mo" class="form-control ">
                    <option value=''>Select</option> 
                    <option value='3'>All Area Manager</option>
                    <option value='4'>All MO</option>
                </select>
            </div>              
        </div>
            
        <div class="col-sm-3" style="margin-top: 22px!important">
            <button type="button" id="button-filter" onclick="buttonfilter();" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;<?php echo $button_filter; ?></button>
            <button type="button" id="budget_submit" class="btn btn-warning pull-right"><i class="fa fa-file-text"></i> &nbsp;Add Budget</button>
        </div>
            <!-- Filter End -->
        </div>
    </div>
       
        
        
        
        
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="background: #515151; color: #ffffff !important;">
                    <td class="text-left">SNO.</td>
                    <td class="text-left">EMPLOYEE NAME</td>
                    <td class="text-left">ROLE</td>
                    <td class="text-left">DISTRICT</td>				 
                    <td class="text-left">BUDGET(KM)</td>                                  
                    <td class="text-left">ADD BUDGET(KM)</td>  
                    <td class="text-left">TOTAL BUDGET(KM)</td>  
                    <td class="text-left">AVL BUDGET(KM)</td>  
		</tr>
            </thead>
            <?php 
                if($order_list){
                    $total=0; if ($results) { 
                    if($_GET["page"]=="") {$aa=1;} 
                    else if($_GET["page"]=="1") {$aa=1;}
                    else{ $aa=(($_GET["page"]-1)*20)+1; } 
                    foreach($order_list as $order){
            ?>
		<tr>
		<td class="text-left"><?php echo $aa; ?></td>
		<td class="text-left"><?php echo $order['customer_name'];?></td>
                <td class="text-left"><?php echo $order['customer_group_id'];?></td>
                <td  class="text-left" >
                <?php 
                    $rk= $order['geoname'];
                    $rks=explode(",",$rk);
                    foreach($rks as $sk){ echo $sk.'<br/>'; }
                ?>
                </td>                                                                                                          
                <td class="text-left">
                    <input type="text" class="form-control" value="" placeholder="<?php echo $order['budget_km'];?>" name="budget[]" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" id="budget'<?php echo $a; ?>'">
                    <input type="hidden" class="form-control" value="<?php echo $order['CUSTOMER_ID'];?>" name="customer_id[]" id="customer_id">
                    <input type="hidden" class="form-control" value="<?php echo $order['geoid'];?>" name="geo_id[]" id="geo_id">
                </td>
		<td class="text-left">
                    <input type="text" class="form-control" value="" placeholder="<?php echo $order['add_budget_km'];?>" name="add_budget[]" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" id="add_budget'<?php echo $a; ?>'">
                </td>
                <td class="text-left">
                    <input type="text" class="form-control" value="<?php echo $order['budget_km']+$order['add_budget_km'];?>" name="tot_budget[]" id="tot_budget'<?php echo $a; ?>'" readonly="readonly">
                </td>
                <td class="text-left">
                    <input type="text" class="form-control" value="<?php echo $order['avl_km'];?>" name="avl_budget[]" id="avl_budget'<?php echo $a; ?>'" readonly="readonly">
                </td>
		</tr>
                <?php $total=$total+$order['total']; $aa++; }
                } else { ?>
                <tr>
                    <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
                <?php   
                    } 
                }?>
              </tbody>
            </table>
        </div>
        <!-- #####**********#####Supply Chain Modal Start#####*******#####-->
    </div>

        </form>
        
             <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
              </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
 function buttonfilter(){
	//url = 'index.php?route=report/inventory_report/product_wise&token=<?php echo $token; ?>';
        url = 'index.php?route=budget/budgetlist/&token=<?php echo $token; ?>';
       //alert(url);
        var gdist_id = $('#dist_id').val();
        var gdist_nm = $("#dist_id option[value='"+gdist_id+"']").text();
        //alert(gdist_id);
        if(gdist_id!=null)
        {
           url += '&dist_id=' + encodeURIComponent(gdist_id);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
        }
        //alert(gdist_id);
        var gam_or_mo = $('#am_or_mo').val();
        var gam_or_nmo = $("#am_or_mo option[value='"+gam_or_nmo+"']").text();
       if(gam_or_mo!=null)
       {
           url += '&gam_or_mo=' + encodeURIComponent(gam_or_mo);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       }
        var gmonth = $('#month').val();
        var gnmonth = $("#month option[value='"+gnmonth+"']").text();
       if(gnmonth!=null)
       {
           url += '&gmonth=' + encodeURIComponent(gmonth);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       }
     
	location = url;
     return false;
}
</script>

<script>
$('#budget_submit').on('click', function() {
	
        //url = 'index.php?route=report/saletarget/sale
        //\targetsubmit&token=<?php echo $token; ?>';
      var monthname = document.getElementById('month').value;
      //alert(monthname);
       if(monthname=='')
       {
            alert('Select Month Name First');
            return false;
       }
       else
       {
           $('#filter_form').submit();    
       }
	
   
});
</script>
<script> 
    
    
    
    
    
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
        
        // var con=confirm("Do you want to dispatch?"); 
             
            if(con==true){ 
           
                url = 'index.php?route=supplychain/supplychainorder/orderdispatch&token=<?php echo $token; ?>';
                url += '&sap_ref_id=' + result;
                url += '&disp_order=' + lstval;
                url += '&dis_prod_id=' + lstprd;
                url += '&dis_sap_id=' + lstsap;
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
<script> 
 $('#sub_sup_data').click(function() { 
 var data = new FormData($('#supform'));
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
    success: function (retdata) {
   $('#supModal').modal('toggle');
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
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#mo_id').select2();
    $('#dist_id').select2();
    $('#teh_id').select2();
});
</script>

 </div>
<?php echo $footer; ?>