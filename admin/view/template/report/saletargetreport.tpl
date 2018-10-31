<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Sale Target Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Sale Target Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Sale Target Report</h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
            <div class="col-sm-12">   
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label " for="input-group">Target Description</label>
                <select name="mo_id" id="mo_id" class="form-control select2" onchange="datrange(this.value);">
                    <option value=''>Select Target Description</option> 
                  <?php foreach ($listTargets as $listTA) { ?>
                   
                  <option value="<?php echo $listTA['SID']; ?>" ><?php echo $listTA['TARGET_DESC']; ?></option>
                    
                  <?php } ?>
                </select>
              </div>
            </div>
                 <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label " for="input-group"></label>
                <input class="form-control" type="text" name="fdate" id="fdate" placeholder="first date" disabled="disabled">
              </div>
            </div>
             <div class="col-sm-2" style="margin-top:12px;">
             <div class="form-group ">
              <label class="col-sm-4 control-label" ></label> 
                  <input class="form-control" type="text" name="ldate" id="ldate"  placeholder="last date" disabled="disabled">
              </div>
            </div> 
                
            <div class="col-sm-2"> 
                <div class="form-group ">
                <label class="col-sm-4 control-label"></label>
                <button type="button" id="button-filter" class="btn btn-warning pull-right" style="margin-top: 22px;"><i class="fa fa-search"></i> &nbsp; Search</button>
                </div>
                </div>
            </div>
          </div>
        </div>


        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Product</td>
                <td class="text-right">Party 1</td>
                <td class="text-right">Party 2</td>
                <td class="text-right">Party 3</td>
               
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-right"><?php echo $order['firstname']; ?></td>
                <td class="text-right"><?php echo $order['mst_farmer_cnt']; ?></td>
                <td class="text-right"><?php echo $order['retailer_cnt']; ?></td>
                <td class="text-right"><?php echo $order['vill_mtng_cnt']; ?></td>
		<td class="text-right"><?php echo $order['pos_vist']; ?></td>
                <td class="text-right"><?php echo $order['farmer_vist']; ?></td> 
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
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#mo_id').select2();
    $('#dist_id').select2();
    $('#teh_id').select2();
});
</script>

<script type="text/javascript">
    $('.date').datetimepicker({
      pickTime: false 
   });
$('#button-filter').on('click', function() {
	
        url = 'index.php?route=report/dailysummaryreport&token=<?php echo $token; ?>';
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
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=report/dailysummaryreport/download_excel&token=<?php echo $token; ?>';
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
</script>
<script>
   
    function datrange(data) {
  
       //var pdesc=data;
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=report/saletargetreport/getdatrange&token='+getURLVar('token'),
        data: 'pdsec='+data,
        // dataType: 'json',
        cache: false,

        success: function(data) {
        //alert(data);
        var data=data.split(',');
        //alert(data);
        $("#fdate").val(data[0]);
        $("#ldate").val(data[1]);
        
        }


   });

      
 }
</script>

<?php echo $footer; ?>