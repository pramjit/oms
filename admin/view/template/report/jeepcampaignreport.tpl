<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Jeep Campaign Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Jeep Campaign Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Jeep Campaign Report</h3>
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
                <label class="control-label" for="input-group">Marketing Officer</label>
                <select name="mo_id" id="mo_id" class="form-control select2">
                    <option value=''>Select MO</option> 
                  <?php foreach ($listMOs as $listMO) { ?>
                   
                  <option value="<?php echo $listMO['customer_id']; ?>" ><?php echo $listMO['firstname']; ?></option>
                    
                  <?php } ?>
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
                <td class="text-left">S. No.</td>
                <td class="text-right">Marketing Officer</td>
                <td class="text-right">Retailer Name</td>
                <td class="text-right">Wholeseller Name</td>
                <td class="text-right">Date</td>
		<td class="text-right">Start Village</td>
		<td class="text-right">Open Km</td>
                <td class="text-right">Village Covered</td>
                <td class="text-right"></td>
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; }  //print_r($orders);?>
                
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-right"><?php echo $order['MO_Name']; ?></td>
                <td class="text-right"><?php echo $order['RETAILER_NAME']; ?></td>
                 <td class="text-right" >
                    <?php 
                    foreach ($WHOLE_SELLER as $WHOLE_SELLE) 
                    {
                       echo $WHOLE_SELLE["name"]."<br/>";
                     
                    }
                    ?>
                </td> 
               <!-- <td class="text-right"><?php //echo $order['WHOLE_SELLER']; ?></td>------->
                <td class="text-right"><?php echo $order['cr_date']; ?></td>
		<td class="text-right"><?php echo $order['START_VILLAGE']; ?></td>
                <td class="text-right"><?php echo $order['OPEN_KM']; ?></td> 
                <td class="text-right"><?php echo $order['village_covered']; ?></td>
                <input type="hidden" id="jeep_id" name="jeep_id" value="<?php echo $order['JEEP_ID']; ?>">
                <td class="text-right">
                    <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="ModelOpenfarmer(<?php echo $order['JEEP_ID']; ?>);" >Farmers</button>
                    <br/><br/><br/>
                    <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="ModelOpenOther(<?php echo $order['JEEP_ID']; ?>);" >Others Dtl</button>
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



<!--Others  Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Other details</h4>
      </div>
      <div class="modal-body">
       <div align="center" id="tbldata2">


      </div>
   
    </div>

  </div>
</div>
</div>
<!--Others  Modal End -->

<!--farmer  Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Farmer details</h4>
      </div>
      <div class="modal-body">
          <div align="center" id="tbldata">
                
        </div>


      </div>
   
    </div>

  </div>
</div>
<!--farmer  Modal End -->

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
	
        url = 'index.php?route=report/jeepcampaignreport&token=<?php echo $token; ?>';
        var gmo_id = $('#mo_id').val();
        var gmo_nm = $("#mo_id option[value='"+gmo_id+"']").text();
       
        url += '&mo_id=' + encodeURIComponent(gmo_id);
        url += '&mo_nm=' + encodeURIComponent(gmo_nm);
        
        var filter_date_start =$('#input-date-start').val();
        if(filter_date_start)
        {
            url += '&filter_date_start=' + encodeURIComponent(filter_date_start); 
        }
	location = url;     
   
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=report/jeepcampaignreport/download_excel&token=<?php echo $token; ?>';
        var gmo_id = $('#mo_id').val();
        var gmo_nm = $("#mo_id option[value='"+gmo_id+"']").text();
       
        url += '&mo_id=' + encodeURIComponent(gmo_id);
        url += '&mo_nm=' + encodeURIComponent(gmo_nm);
       var filter_date_start =$('#input-date-start').val();
        if(filter_date_start)
        {
            url += '&filter_date_start=' + encodeURIComponent(filter_date_start); 
        }
        location = url;    
});

function ModelOpen(){
    //alert();
   $("#myModal").modal();
}
function ModelOpenfarmer(jeep){
    //alert(jeep);
     url = 'index.php?route=report/jeepcampaignreport/getfarmerdtl&token=<?php echo $token; ?>';
     url += '&jeepid=' + encodeURIComponent(jeep);
     //alert(url);
    $.ajax({ 
        type: 'post',
        url: url,
        //dataType: 'json',
        cache: false,
        success: function(data) {
                    //alert(data);
                    $("#tbldata").html(data);
}
   });
    
   $("#myModal").modal();
}

function ModelOpenOther(jeep){
    //alert(jeep);
     url = 'index.php?route=report/jeepcampaignreport/getOtherdtl&token=<?php echo $token; ?>';
     url += '&jeepid=' + encodeURIComponent(jeep);
     //alert(url);
    $.ajax({ 
        type: 'post',
        url: url,
        //dataType: 'json',
        cache: false,
        success: function(data) {
                    //alert(data);
                    $("#tbldata2").html(data);
}
   });
    
   $("#myModal2").modal();
}
</script>

<?php echo $footer; ?>