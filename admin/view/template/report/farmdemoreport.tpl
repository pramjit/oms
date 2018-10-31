<?php echo $header; ?><?php echo $column_left;//print_r($orders); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Farmer Demo Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Farmer Demo Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Farmer Demo Report</h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
             <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
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
                <label class="control-label" for="input-group">Select Farmer</label>
                <select name="fm_id" id="fm_id" class="form-control select2">
                    <option value=''>Select Farmer</option> 
                  <?php foreach ($listFARMs as $listFM) { ?>
                   
                  <option value="<?php echo $listFM['SID']; ?>" <?php if($listFM['SID']==$filter_fm) { echo 'selected'; } ?>><?php echo $listFM['FARMER_NAME']; ?></option>
                    
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Select Village</label>
                <select name="vil_id" id="vil_id" class="form-control select2">
                    <option value=''>Select Village</option>
                  <?php foreach ($listVILs as $listVIL) { ?>
                    <option value="<?php echo $listVIL['SID']; ?>"<?php if($listVIL['SID']==$filter_dist) { echo 'selected'; } ?>><?php echo $listVIL['NAME']; ?></option>
               
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Select Status</label>
                <select name="stat_id" id="stat_id" class="form-control select2">
                    <option value="">Select Status</option>
                   <option value="null">All</option>
                   <option value="2">30 Days</option>
                   <option value="3">60 Days</option>
                   <option value="4">Harvest</option>
                </select>
              </div>
            </div>
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
                <td class="text-left">Sno</td>
                <td class="text-left">Village Name</td>
                <td class="text-right">Farmer Name</td>
                <td class="text-right">Farmer Mobile</td>
                <td class="text-right">Date</td>
                <td class="text-right">Demo Acres</td>
		<td class="text-right">Crop</td>
		<td class="text-right">Fertilizer Used</td>
                <td class="text-right">30 Day's</td>
                <td class="text-right">60 Day's</td>
                <td class="text-right">On Harvest</td>
              
                
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-right"><?php echo $order['Village_Name']; ?></td>
                <td class="text-right"><?php echo $order['FARMER_NAME']; ?></td>
                <td class="text-right"><?php echo $order['FARMER_MOBILE']; ?></td>
                <td class="text-right"><?php echo $order['CR_DATE']; ?></td>
                
		<td class="text-right"><?php echo $order['DEMO_ACRES']; ?></td>
                <td class="text-right"><?php echo $order['CROP_NAME']; ?></td>
                <td class="text-right"><?php echo $order['FERTILIZER']; ?></td>
                <td class="text-right"><?php if($order['STATUS']=='2') { echo $order['REMARKS']; } ?></td>
                <td class="text-right"><?php if($order['STATUS']=='3') { echo $order['REMARKS']; } ?></td>
                <td class="text-right"><?php if($order['STATUS']=='4') { echo $order['REMARKS']; } ?></td>
               
              </tr>
            <?php $total=$total+$order['total']; $aa++; } ?>
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
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#fm_id').select2();
    $('#vil_id').select2();
    $('#stat_id').select2();
});
 $('.date').datetimepicker({
      pickTime: false 
   });
</script>

<script type="text/javascript">
$('#button-filter').on('click', function() {
	 
        url = 'index.php?route=report/farmdemoreport&token=<?php echo $token; ?>';
        var gfm_id = $('#fm_id').val();
        var gfm_nm = $("#fm_id option[value='"+gfm_id+"']").text();
       
        url += '&fm_id=' + encodeURIComponent(gfm_id);
        url += '&fm_nm=' + encodeURIComponent(gfm_nm);
        
        var gvil_id = $('#vil_id').val();
        var gvil_nm = $("#vil_id option[value='"+gvil_id+"']").text();
        if (gvil_id) 
        {
            url += '&vil_id=' + encodeURIComponent(gvil_id);
            url += '&vil_nm=' + encodeURIComponent(gvil_nm);
        }
        
        var gstat_id = $('#stat_id').val();
        var gstat_nm = $("#stat_id option[value='"+gstat_id+"']").text();
        
       var filter_date_start =$('#input-date-start').val();
        if(filter_date_start)
        {
            url += '&filter_date_start=' + encodeURIComponent(filter_date_start); 
        }
        
        //alert('2');
        
	location = url;     
   
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=report/farmdemoreport/download_excel&token=<?php echo $token; ?>';
        var gfm_id = $('#fm_id').val();
        var gfm_nm = $("#fm_id option[value='"+gfm_id+"']").text();
       
        url += '&fm_id=' + encodeURIComponent(gfm_id);
        url += '&fm_nm=' + encodeURIComponent(gfm_nm);
        
        var gvil_id = $('#vil_id').val();
        var gvil_nm = $("#vil_id option[value='"+gvil_id+"']").text();
        if (gvil_id) 
        {
            url += '&vil_id=' + encodeURIComponent(gvil_id);
            url += '&vil_nm=' + encodeURIComponent(gvil_nm);
        }
        
        var gstat_id = $('#stat_id').val();
        var gstat_nm = $("#stat_id option[value='"+gstat_id+"']").text();
        
        if (gstat_id) 
        {
            url += '&stat_id=' + encodeURIComponent(gstat_id);
            url += '&stat_nm=' + encodeURIComponent(gstat_nm);
        }
        location = url;     
});
</script>

<?php echo $footer; ?>