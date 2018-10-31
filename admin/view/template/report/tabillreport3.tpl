<?php echo $header; ?><?php echo $column_left;//print_r($orders); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>TA Bill Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">TA Bill Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
     
      <div class="panel-body">
        <div class="well">
        <div class="row">
          <div class="col-sm-2">
              <div class="form-group required">
                <label class="control-label" for="input-group"> Month</label>
                <select name="month_id" id="month_id" class="form-control" onchange="starget(this.value);" >
                    <option value=''>Select Month</option>
                    <?php
                    foreach($allmonth as $key=>$value){
                    ?>
                    <option value="<?php echo $key;?>" <?php echo ($key == $month_id) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                    <?php
                    }
                    ?>
                </select>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group required">
                <label class="control-label" for="input-group">Employee</label>
                <select name="mo_id" id="mo_id" class="form-control select2" >
                    <option value=''>Select Employee</option> 
                  <?php foreach ($listMOs as $listMO) { ?>
                   
                  <option value="<?php echo $listMO['customer_id']; ?>" <?php if($listMO['customer_id']==$filter_mo) { echo 'selected'; } ?>><?php echo $listMO['firstname']; ?></option>
                    
                  <?php } ?>
                </select>
              </div>
            </div>
             <div class="col-sm-6"> 
                <label class="control-label" for="input-group">&nbsp;</label>
                
                <button type="button" id="button-filter" class="btn btn-warning pull-left" style="margin-top: 22px;"><i class="fa fa-search"></i> &nbsp; Search</button>
                <?php
                if($pdf){
                echo "<a href='".$pdf."' download><button type='button' class='btn btn-success' style='margin-top: 22px;'><i class='fa fa-download'></i> &nbsp; Download</button></a>";
                }
                ?>
            </div>
           
</div>
</div>    
<?php
if(count($pdfdatas)>0)
{
?>
<div style="text-align:center; color:#5c843d;"> 
    <h2>KHANDELWAL AGRO INDUSTRIES</h2>
    <h5>MORE KOTHI GANGAPUR BAREILLY</h5>
</div>
<table  border="0" align="center" width="100%" class="form-group">
<tbody >
<tr>
    <td ><b>NAME :&nbsp;</b><?php echo $empDtls['EMP_NAME']; ?></td>
</tr> 
<tr>
    <td ><b>DESIGNATION :&nbsp;</b><?php echo $empDtls['EMP_GRP']; ?></label></td>
</tr> 
<tr>
    <td ><b>H.Q. :&nbsp;</b><?php echo $empDtls['GEO_NAME']; ?></label></td>
</tr> 
<tr>
    <td ><b>REPORT MONTH :&nbsp;</b><?php echo $my; ?></label></td>
</tr>
</table>
<table class="table table-bordered">
<tr style="background: #515151;color: #ffffff;">
    <th class="text-center" style="width: 9%;">Post Date</th>
    <th class="text-center" style="width: 8%;">Place Form</th>
    <th class="text-center" style="width: 9% !Important;">Place Visited</th>
    <th class="text-center">Opening (MR)</th>
    <th class="text-center">Closing (MR)</th>
    <th class="text-center" style="width: 9%;">Official Used(Km)</th>
    <th class="text-center">Toll Tax</th>
    <th class="text-center">Petrol (Rs.)</th>
    <th class="text-center">Fare (Taxi)</td>
    <th class="text-center">Local Con.</th>
    <th class="text-center">Hotel</th>
    <th class="text-center">DA</th>
    <th class="text-center">Postage</th>
    <th class="text-center">Printing</th>
    <th class="text-center">Misc Exp.</th>
</tr>                    

<?php 
$TOT_OPEN_MTR=$TOT_CLOSE_MTR=$TOT_MTR=$TOT_TAX_RS=$TOT_PETROL_LTR=$TOT_FARE_RS=$TOT_LOCAL_CONVEYANCE=$TOT_HOTEL_RS=$TOT_DAILY_DA=$TOT_PRINTING_RS=$TOT_POSTAGE_RS=$TOT_MISC_RS=0;
foreach ($pdfdatas as $pdf)
{
    $TOT_OPEN_MTR=$TOT_OPEN_MTR+$pdf["OPEN_MTR"];
    $TOT_CLOSE_MTR=$TOT_CLOSE_MTR+$pdf["CLOSE_MTR"];
    $BAL=$pdf["CLOSE_MTR"]-$pdf["OPEN_MTR"];
    $TOT_MTR=$TOT_MTR+$BAL;
    $TOT_TAX_RS=$TOT_TAX_RS+$pdf["TAX_RS"];
    $TOT_PETROL_LTR=$TOT_PETROL_LTR+$pdf["PETROL_LTR"];
    $TOT_FARE_RS=$TOT_FARE_RS+$pdf["FARE_RS"];
    $TOT_LOCAL_CONVEYANCE=$TOT_LOCAL_CONVEYANCE+$pdf["LOCAL_CONVEYANCE"];
    $TOT_HOTEL_RS=$TOT_HOTEL_RS+$pdf["HOTEL_RS"];
    $TOT_DAILY_DA=$TOT_DAILY_DA+$pdf["DAILY_DA"];
    $TOT_PRINTING_RS=$TOT_PRINTING_RS+$pdf["PRINTING_RS"];
    $TOT_POSTAGE_RS=$TOT_POSTAGE_RS+$pdf["POSTAGE_RS"];
    $TOT_MISC_RS=$TOT_MISC_RS+$pdf["MISC_RS"];
?> <tr>
        <td><?php echo date("d-m-Y", strtotime($pdf["tadate"])); ?></td>
        <td><?php echo $pdf["PLACE_FROM"]; ?></td>
        <td><?php echo $pdf["PLACE_TO"].', '.$pdf["PLACE_TO1"].', '.$pdf["PLACE_TO2"].', '.$pdf["PLACE_TO3"].', '.$pdf["PLACE_TO4"]; ?></td>
        <td><?php echo $pdf["OPEN_MTR"]; ?></td>
        <td><?php echo $pdf["CLOSE_MTR"]; ?></td>
        <td><?php echo $BAL; ?></td>
        <td><?php echo $pdf["TAX_RS"]; ?></td>
        <td><?php echo $pdf["PETROL_LTR"]; ?></td>
        <td><?php echo $pdf["FARE_RS"]; ?></td>
        <td><?php echo $pdf["LOCAL_CONVEYANCE"]; ?></td>
        <td><?php echo $pdf["HOTEL_RS"]; ?></td>
        <td><?php echo $pdf["DAILY_DA"]; ?></td>
        <td><?php echo $pdf["PRINTING_RS"]; ?></td>
        <td><?php echo $pdf["POSTAGE_RS"]; ?></td>
        <td><?php echo $pdf["MISC_RS"]; ?></td>
    </tr>
    
<?php } ?>
<tr>
    
    <td colspan=3">Total&nbsp;:</td>
    <td><?php echo $TOT_OPEN_MTR; ?></td>
    <td><?php echo $TOT_CLOSE_MTR; ?></td>
    <td><?php echo $TOT_MTR; ?></td>
    <td><?php echo $TOT_TAX_RS; ?></td>
    <td><?php echo $TOT_PETROL_LTR; ?></td>
    <td><?php echo $TOT_FARE_RS; ?></td>
    <td><?php echo $TOT_LOCAL_CONVEYANCE; ?></td>
    <td><?php echo $TOT_HOTEL_RS; ?></td>
    <td><?php echo $TOT_DAILY_DA; ?></td>
    <td><?php echo $TOT_PRINTING_RS; ?></td>
    <td><?php echo $TOT_POSTAGE_RS; ?></td>
    <td><?php echo $TOT_MISC_RS; ?></td>
</tr>
        </tbody>
        </table>
<?php }
elseif(count($pdfdatas)==0){
echo '<div style="width:100%; text-align:center; color:#f00f00;">'.$Noop.'</div>';
}?>      


</div>
</div>
</div>
</div>
<!-- end model--->


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
    url = 'index.php?route=report/tabillreport3&token=<?php echo $token; ?>';
   // var str=$('#mo_id').val();
   // var rk= str.length
    
      var gmo_id = $('#mo_id').val();
      var gmo_nm = $("#mo_id option[value='"+gmo_id+"']").text();         
      var gmon_id = $('#month_id').val();
      
    if(gmon_id && gmo_id )
    {
           url += '&mo_id=' + encodeURIComponent(gmo_id);
           url += '&mo_nm=' + encodeURIComponent(gmo_nm);
           url += '&month_id=' + encodeURIComponent(gmon_id);
           //url += '&mo_nm=' + encodeURIComponent(gmo_nm);
    }
       else
       {
           alert('Please select month and employee');
           return false;
       }
      // alert(url);
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


<?php echo $footer; ?>