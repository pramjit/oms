<?php echo $header; ?><?php echo $column_left;//print_r($orders); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Payment Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">PaymentReport</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Payment Report</h3>
      <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
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
               <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-group">Select Party</label>
                <select name="ws_id" id="ws_id" class="form-control select2-selection select2-selection--multiple"  multiple="multiple">
                    
                    <option value='0'>Select ALL</option>
                  <?php foreach ($listWSs as $listWS) { ?>
                   
                  <option value="<?php echo $listWS['store_id']; ?>" <?php if($listWS['store_id']==$filter_ws) { echo 'selected'; } ?>><?php echo $listWS['name']; ?></option>
                    
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
              <tr style="background: #515151;color: #ffffff;">
                <th class="text-center">#SNO</th>
                <th class="text-center">PARTY NAME</th>
                <th class="text-center">ADDRESS </th>
                <th class="text-center" style="width:8%;">PAYMENT DATE</th>
                <th class="text-center">BANK</th>                     
				<th class="text-center">PAYMENT TYPE</th>
                <th class="text-center">AMOUNT REF</th>
                <th class="text-center">AMOUNT</th>	
				
                <th class="text-center">STATUS</th>
				<th class="text-center" style="width:8%;">RECEIVED DATE</th>
				<th class="text-center">ACTION</th>					
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
         
                <td class="text-left"><?php echo $order['party_name']; ?></td>
                <td class="text-left"><?php echo $order['Par_Addrs']; ?></td>
                <td class="text-left"><?php echo $order['Amnt_Date']; ?></td>
                <td class="text-left"><?php echo $order['Amnt_Bank']; ?></td>                 
                <td class="text-left">
                                       <?php if($order['Amnt_Type']=='1')
                                            {
                                                echo "Cheque Number";
                                            }
                                            if($order['Amnt_Type']=='2')
                                                {
                                                echo "DD Number";
                                            }
                                              if($order['Amnt_Type']=='3')
                                                {
                                                echo "UTR Number";
                                            }
                                             if($order['Amnt_Type']=='4')
                                                {
                                                echo "Cash";
                                            }
                                       ?>
                </td>
                <td class="text-left"><?php echo $order['Amnt_Ref']; ?></td>
				<td class="text-left"><?php echo $order['Amnt_Rs']; ?></td>
				<td class="text-left"><?php echo $order['PAYMENT_STATUS']; ?></td>
				<td class="text-left"><?php echo $order['PAYMENT_DATE']; ?></td>
				<td class="text-left">
				<?php if($order['PAYMENT_STATUS']=='RECEVIED'){?>
				<button type="button" id="mobile" class="btn btn-warning btn-xs" value="<?php echo $order['Sid']; ?>" data-toggle="modal" data-target="#Modal<?php echo $order['Sid']; ?>" disabled>UPDATE &nbsp;<i class="fa fa-edit" aria-hidden="true" style="font-size:18px;"></i>
                </button>
				<?php }else{?>
				<button type="button" id="mobile" class="btn btn-primary btn-xs" value="<?php echo $order['Sid']; ?>" data-toggle="modal" data-target="#Modal<?php echo $order['Sid']; ?>">UPDATE &nbsp;<i class="fa fa-edit" aria-hidden="true" style="font-size:18px;"></i>
                </button>
				<?php }?>
				</td>
           <!-- Modal -->
<div id="Modal<?php echo $order['Sid']; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title" ><b>STATUS</b></h3>
      </div>
      <div class="modal-body">
        <form>
               <!--start-->
			  <div class="container">
			  <div class="row">
               <div class="col-sm-3">
                <input type="hidden" class="form-control" name="sid" id="sid<?php echo $order['Sid']; ?>" value="<?php echo $order['Sid']; ?>" > 
                <select  id="status_name<?php echo $order['Sid']; ?>" onclick="clear_status(<?php echo $order['Sid']; ?>);" class="form-control">
                 <option  value="">SELECT STATUS</option>
                 <option  value="1">PENDING</option>
                 <option  value="2">RECEVIED</option>
				 <option  value="3">CHEQUE BOUNCED</option>
               </select>
			   <p id="status_name_p<?php echo $order['Sid']; ?>" style="display:none;color:red;">Required Status</p>
			   <hr />
			   <div class="input-group date">
                  <input type="text" id="status_date<?php echo $order['Sid']; ?>" value="" placeholder="Select Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" onclick="clear_date(<?php echo $order['Sid']; ?>);" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div> 
                 
				 <p id="date_name_p<?php echo $order['Sid']; ?>" style="display:none;color:red;">Required Date</p>
              </div>
             </div>
			</div>
        </form>
      </div>
      <div class="modal-footer">
	   <button type="button" class="btn btn-primary" onclick="SendRecStatus(<?php echo  $order['Sid']; ?>);"><i class="fa fa-save"></i>&nbsp;Save</button>
          <button type="button" class="btn btn-danger"  data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>
&nbsp;Close</button>
      </div>
    </div>

  </div>
</div>
              <?php $total=$total+$order['total']; $aa++; 
			 
			  } ?>
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
          <div class="col-sm-6 text-left">
           <!--<span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>-->

         <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
 </div>
<!--Model status Start-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h2 class="modal-title">SEND STATUS</h2>
      </div>
      <div class="modal-body">
		<form>
		<div class="row">
		 <div class="col-md-2">
		<label for="inputlg">STATUS :</label>
	    </div>
		<div class="col-md-10">
	   <div class="form-group">
	    <input class="form-control input-lg" id="inputlg" type="text">
	  </div>
	  </div>
	  	</div>
	</form> 
      </div>
      <div class="modal-footer">
	    <button type="button" class="btn btn-primary" name="saveStatus" id="saveStatus"><i class="fa fa-save" style="font-size:20px;"></i>&nbsp;Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"style="font-size:20px;"></i>&nbsp;Close</button>
      </div>
    </div>

  </div>
</div>-->
<!--Model Status End-->
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
    $('#am_id').select2();
});
</script>

<script type="text/javascript">
  $('.date').datetimepicker({
      pickTime: false 
   });
function clear_status(id){
    $('#status_name_p'+id).hide();
} 
function clear_date(id){
    $('#date_name_p'+id).hide();
}    
function SendRecStatus(sid){
var status_name=$('#status_name'+sid).val();
var status_date=$('#status_date'+sid).val();

if(status_name.length==0){
$('#status_name_p'+sid).show();
 return false;
}else if(status_date==''){
$('#date_name_p'+sid).show();
 return false;	
}else{
	
     var urll='index.php?route=report/paymentreport/sendStatus&token=<?php echo $token;?>';
	 //alert(urll);
	 $.ajax({ 
	 type: 'post',
	 url: urll,
	 data: {sid:sid,status_name:status_name,status_date:status_date},
	 //dataType: 'text',
	 cache: false,
	 success: function(data) {
	if(data>0){
		alert("Successfully Record Updated!");
		location.reload();
	}else{
		alert("Sorry !Some Error has been occure!please try again");
	}
		   
	 }
	 });
}
}	
    
$('#button-filter').on('click', function() {
    
        url = 'index.php?route=report/paymentreport&token=<?php echo $token; ?>';
		
        var gws_id = $('#ws_id').val();
		/*if(!gws_id){
			alert('Please Select Party');
			return false;
		}*/
		
        var gws_nm = $("#ws_id option[value='"+gws_id+"']").text();
		if(gws_id)
		{
           url += '&ws_id=' + encodeURIComponent(gws_id);
		}
		
		var filter_date_start =$('#input-date-start').val();
		if(filter_date_start)
        {
            url += '&filter_date_start=' + encodeURIComponent(filter_date_start); 
        }
		
		var filter_date_end =$('#input-date-end').val();
        if(filter_date_end)
        {
            url += '&filter_date_end=' + encodeURIComponent(filter_date_end); 
        }
        
	location = url;     
   
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() { 
	url = 'index.php?route=report/paymentreport/download_excel&token=<?php echo $token; ?>';
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


<?php echo $footer; ?>