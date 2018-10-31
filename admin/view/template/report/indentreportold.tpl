 <?php echo $header; ?><?php echo $column_left;//print_r($orders); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Indent Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Indent Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Indent Report</h3>
        <button  type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Marketing Officer</label>
                <select name="mo_id" id="mo_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple">
                    <option value=''>Select Employee</option> 
                    <option value=''>Select ALL</option>
                  <?php foreach ($listMOs as $listMO) { ?>
                   
                  <option value="<?php echo $listMO['customer_id']; ?>" ><?php echo $listMO['firstname']; ?></option>
                    
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Wholesaler</label>
                <select name="ws_id" id="ws_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple">
                    <option value=''>Select Employee</option> 
                    <option value=''>Select ALL</option>
                  <?php foreach ($listWSs as $listWS) { ?>
                   
                  <option value="<?php echo $listWS['store_id']; ?>" <?php if($listWS['store_id']==$filter_ws) { echo 'selected'; } ?>><?php echo $listWS['name']; ?></option>
                    
                  <?php } ?>
                </select>
              </div>
            </div>
             <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Area Manager</label>
                <select name="am_id" id="am_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple">
                    <option value=''>Select Area Manager</option> 
                    <option value=''>Select ALL</option>
                  <?php foreach ($listAMs as $listAM) { ?>
                   
                   <option value="<?php echo $listAM['customer_id']; ?>" <?php if($listAM['customer_id']==$filter_am) { echo 'selected'; } ?>><?php echo $listAM['firstname']; ?></option>
                    
                  <?php } ?>
                </select>
              </div>
            </div>
          <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            
            <!----------<div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">District</label>
                <select name="dist_id" id="dist_id" class="form-control select2">
                    <option value=''>Select District</option>
                  <?php foreach ($listDISTs as $listDIST) { ?>
                    <option value="<?php echo $listDIST['SID']; ?>"<?php if($listDIST['SID']==$filter_dist) { echo 'selected'; } ?>><?php echo $listDIST['NAME']; ?></option>
               
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Tehsil</label>
                <select name="teh_id" id="teh_id" class="form-control select2">
                    <option value=''>Select Tehsil</option>
                  <?php foreach ($listTEHs as $listTEH) { ?>
                    <option value="<?php echo $listTEH['SID']; ?>"<?php if($listTEH['SID']==$filter_teh) { echo 'selected'; } ?>><?php echo $listTEH['NAME']; ?></option>
               
                  <?php } ?>
                </select>
              </div>
            </div>------------->
            <div class="col-sm-2"> 
                <label class="control-label" for="input-group">&nbsp;</label>
                <button type="button" id="button-filter" class="btn btn-warning pull-right" style="margin-top: 22px;"><i class="fa fa-search"></i> &nbsp; Search</button>
            </div>
          </div>
        </div>


        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S No</td>
                <td class="text-left">MO Name </td>
                <td class="text-right">AM Name</td>
                <td class="text-left">Wholeseller Name</td>                                   
		<td class="text-right">Indent Date</td>		
                <td class="text-right">Indent No</td>              
                <td class="text-right">Order Qty</td>
                <td class="text-right">Status</td>
                              
              </tr>
            </thead>
            <tbody>
                
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-right"><?php echo $order['Mo']; ?></td>
                <td class="text-right"><?php echo $order['am']; ?></td>
                <td class="text-right"><?php echo $order['wholesaler_name']; ?></td>
                <td class="text-right"><?php echo $order['indateorder']; ?></td>
                <td class="text-right"><?php echo $order['indnumber']; ?></td>
                <td class="text-right" onclick="orderdtl(<?php echo $order['indnumber']; ?>);"><?php echo $order['sum_of_qnty']; ?></td>
                <td class="text-right"><?php if($order['order_status']=='1'){ echo "Not Approved";  } else { echo "Approved"; } ?></td>
              </td>
               
             </tr>
              <?php $total=$total+$order['total']; $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
           <!--<span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>-->

         <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
 </div>

<!-- model for image1--->


<!-- Modal -->
<div id="docdata">

  </div>
<!-- end model--->


<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#mo_id').select2();
    $('#ws_id').select2();
    $('#dist_id').select2();
    $('#teh_id').select2();
    $('#am_id').select2();
});
</script>

<script type="text/javascript">
  $('.date').datetimepicker({
      pickTime: false 
   });
    
    
    
$('#button-filter').on('click', function() {
    
        url = 'index.php?route=report/indentreport&token=<?php echo $token; ?>';
        var gmo_id = $('#mo_id').val();
        var gmo_nm = $("#mo_id option[value='"+gmo_id+"']").text();
       if(gmo_id!=null)
       {
           url += '&mo_id=' + encodeURIComponent(gmo_id);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       }
       var gam_id = $('#am_id').val();
        var gam_nm = $("#am_id option[value='"+gam_id+"']").text();
       if(gam_id!=null)
       {
           url += '&am_id=' + encodeURIComponent(gam_id);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       }
       
          var gws_id = $('#ws_id').val();
        var gws_nm = $("#ws_id option[value='"+gws_id+"']").text();
       if(gws_id!=null)
       {
           url += '&ws_id=' + encodeURIComponent(gws_id);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       }
         var filter_date_start =$('#input-date-start').val();
         var filter_date_end =$('#input-date-end').val();
         
        if(filter_date_start)
        {
            url += '&filter_date_start=' + encodeURIComponent(filter_date_start); 
        }
        if(filter_date_end)
        {
            url += '&filter_date_end=' + encodeURIComponent(filter_date_end); 
        }
        
	location = url;     
   
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=report/indentreport/download_excel&token=<?php echo $token; ?>';
        var gmo_id = $('#mo_id').val();
        var gmo_nm = $("#mo_id option[value='"+gmo_id+"']").text();
       
        url += '&mo_id=' + encodeURIComponent(gmo_id);
        url += '&mo_nm=' + encodeURIComponent(gmo_nm);
        var gdist_id = $('#dist_id').val();
        var gdist_nm = $("#dist_id option[value='"+gdist_id+"']").text();
        if (gdist_id) 
        {
            url += '&dist_id=' + encodeURIComponent(gdist_id);
            url += '&dist_nm=' + encodeURIComponent(gdist_nm);
        }
        var gteh_id = $('#teh_id').val();
        var gteh_nm = $("#teh_id option[value='"+gteh_id+"']").text();
        if (gteh_id) 
        {
            url += '&teh_id=' + encodeURIComponent(gteh_id);
            url += '&teh_nm=' + encodeURIComponent(gteh_nm);
        }
        location = url;     
});



function orderdtl(datno)
{    
     //alert(dat);
  // alert(datno);
          $.ajax({ 
        type: 'post',
        url: 'index.php?route=report/indentreport/getorderdata&token=<?php echo $token; ?>',
        data: 'orddata='+datno,
        
        
       //dataType: 'json',
        cache: false,

        success: function(data) {
      
        
        $("#docdata").html(data);
        $('#myModal').modal(); 
       
        }


   });
   
   
}
</script>
<script>
 $("#mo_id").select2({ tags: true,
      placeholder: function(){
        $(this).data('placeholder');
    }
  
});
</script>
<script>
 $("#ws_id").select2({ tags: true,
      placeholder: function(){
        $(this).data('placeholder');
    }
  
});
</script>

<?php echo $footer; ?>