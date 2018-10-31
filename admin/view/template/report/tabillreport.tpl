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
if(count($PDF)>0)
{
//print_r($PDF);
?>
<!------- RESULT DATA------------------>          
<div style="text-align:center; color:#5c843d;"> 
    <h2>KHANDELWAL AGRO INDUSTRIES</h2>
    <h5>MORE KOTHI GANGAPUR BAREILLY</h5>
</div>
<table width="100%">
<tr>
<td><b>NAME :</b> &nbsp; <?php echo $PDF['EMP_NAME']; ?></td>
<td></td>
</tr>
<tr>
<td><b>ROLE :</b>  &nbsp;<?php echo $PDF['EMP_GRP'] ; ?> </td>
<td></td>
</tr>
<tr>	
<tr>
<td colspan="=2"><b>AREA:</b> &nbsp; <?php echo $PDF['GEO_NAME']; ?></td>
</tr>
<td><b>BUDGET MONTH :</b>  &nbsp;<?php echo $PDF['EMP_MONTH']; ?></td>
<td></td>
</tr>
<tr>
<td><b>REPORT DATE :</b>  &nbsp;<?php echo DATE('d-m-Y');?></td>
<td>&nbsp;</td>
</tr>
</table>
<div class="main3" id="main3" style="height:230px;" >
<table width="100%" border="1">
<tr>
<td width="625">PARTICULARS</td>
<td width="122">KM</td>
<td width="148">RATE</td>
<td width="228">APPROVED BUDGET</td>
</tr>
<tr>
<td>DA</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Vehicle Running Exp</td>
<td><?php echo $PDF['EMP_ALW_KM'] ; ?></td>
<td><?php echo $PDF['EMP_VEH_ALW'] ; ?></td>
<td><?php echo $PDF['EMP_ALW_KM']*$PDF['EMP_VEH_ALW']; ?></td>
</tr>
<tr>
<td>Telephone & Mobile</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Postage & Telegram</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Printing & Stationary</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Loading Per Day</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Travelling By Bus/Train For Meeting Only</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Total</td>
<td><?php echo $PDF['EMP_ALW_KM'] ; ?></td>
<td><?php echo $PDF['EMP_VEH_ALW'] ; ?></td>
<td><?php echo $PDF['EMP_ALW_KM']*$PDF['EMP_VEH_ALW']; ?></td>
</tr>
</table>
</div>
<div class="main4" id="main4" style="height:200px;" >
<tr>
<td >NOTE:-</td>
</tr>
<tr>
<td> Please send following reports:-<br/>
1. ATP 1-15 upto 3rd of Month.<br/>
2. ATP 16-31 upto 16th of Month.<br/>
3. TA Bill 3rd of month will suppoting documents.<br/>
4. Budget Attached with TA Bill.<br/>
5. Date of month 30 & 31 complete TA bill, ATP and Courier to Head Office Bill.<br/>
6. No claim of any tour on date of month end (30 & 31).<br/>
7. Millage will be Rs <?php echo $PDF['EMP_VEH_ALW'] ; ?> per/km

</td>
</tr><br/><br/>
<tr>
	<td>APPROVED BY.</td>
</tr>
</table>
<?php }
elseif(count($PDF)==0){
echo '<div style="width:100%; text-align:center; color:#f00f00;">'.$Noop.'</div>';
}?>
<!--- Result Data End-->
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
    url = 'index.php?route=report/tabillreport&token=<?php echo $token; ?>';
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