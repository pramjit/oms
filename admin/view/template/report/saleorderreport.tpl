<?php echo $header; ?><?php echo $column_left;//print_r($orders); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Sale Order Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Sale Order Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Sale Order Report</h3>
        <button disabled="disabled" type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
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
                   
                  <option value="<?php echo $listMO['customer_id']; ?>" <?php if($listMO['customer_id']==$filter_mo) { echo 'selected'; } ?>><?php echo $listMO['firstname']; ?></option>
                    
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
                   
                  <option value="<?php echo $listWS['customer_id']; ?>" <?php if($listWS['customer_id']==$filter_mo) { echo 'selected'; } ?>><?php echo $listWS['firstname']; ?></option>
                    
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
                <td class="text-left">Wholesale Person</td>
                <td class="text-left">Product Name</td>
                <td class="text-left">Sap Code</td>
                <td class="text-right">SO Qty</td>                     
		<td class="text-right">Dispatch Qty</td>		
                <td class="text-right">Indent Nos</td>
                           
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-right"><?php echo $order['TA_DATE']; ?></td>
                <td class="text-right"><?php echo $order['firstname']; ?></td>
                <td class="text-right"><?php echo $order['telephone']; ?></td>
                <td class="text-right"><?php echo $order['PLACE_FROM']; ?></td>                
		<td class="text-right"><?php echo $order['PLACE_TO1']; ?></td>
                <td class="text-right"><?php echo $order['PLACE_TO2']; ?></td>
           
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

<div class="modal fade" id="myModal1" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div style="width:70%;margin-top: 30%;margin-left: 30%;" id="successmessage" class="modal-content">
<div class="modal-header" style="background-color: #1a3d5c;">
<label style="text-align: center;margin:-3%;width: 100%;color:white" class="col-md-6">Uploaded Document</label>
<button style="margin: -3%;color: white" type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body" style="z-index:9999">

<div class="row">
  
    <div align="center" id="docdata">
        <table width="365" height="100" border="1">
        <tr>
        <td >Upload 1</td>
        <a href="DIR_UPLOAD." download="download"><td id="up1">&nbsp;<img src="DIR_UPLOAD" ></td></a>
        </tr>
        <tr>
        <td>Upload 2</td>
        <a href=""><td id="up2">&nbsp;</td></a>
        </tr>
        <tr>
        <td>Upload 3</td>
        <a href=""><td id="up3">&nbsp;</td></a>
        </tr>
        <tr>
        <td>Upload 4</td>
        <a href=""><td id="up4">&nbsp;</td></a>
        </tr>
   
        </table>
    </div>
</div>
</div>

<div class="modal-footer" style=" background-color: #1a3d5c;">
</div>

</div>

</div>
</div>
<!-- end model--->


<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#mo_id').select2();
    $('#ws_id').select2();
    $('#dist_id').select2();
    $('#teh_id').select2();
});
</script>

<script type="text/javascript">
  $('.date').datetimepicker({
      pickTime: false 
   });
    
    
    
$('#button-filter').on('click', function() {
    
        url = 'index.php?route=report/tasummaryreport&token=<?php echo $token; ?>';
        var gmo_id = $('#mo_id').val();
        var gmo_nm = $("#mo_id option[value='"+gmo_id+"']").text();
       if(gmo_id!=null)
       {
           url += '&mo_id=' + encodeURIComponent(gmo_id);
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
	url = 'index.php?route=report/tasummaryreport/download_excel&token=<?php echo $token; ?>';
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



function upload(dat)
{
     
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=report/tasummaryreport/gettaupload&token=<?php echo $token; ?>',
        data: 'taupl='+dat,
       //dataType: 'json',
        cache: false,

        success: function(data) {
      
        
        $("#docdata").html(data);
        $('#myModal1').modal(); 
       
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