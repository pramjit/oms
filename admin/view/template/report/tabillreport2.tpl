<?php 
echo $header;
echo $column_left; 
?>

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
                <select name="month_id" id="month_id" class="form-control">
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
?>
<div style="text-align:center; color:#5c843d;"> 
    <h2>KHANDELWAL AGRO INDUSTRIES</h2>
    <h5>MORE KOTHI GANGAPUR BAREILLY</h5>
</div>
<div class="div3" align="center" width="100%" >
<form>      
    <label for="name">Name Of Employee:</label>
    <input type="text" id="name"  value="<?php echo $PDF['EMP_NAME'];?>"/>
    <label for="email">Designation:</label>
    <input type="email" id="email"  value="<?php echo $PDF['EMP_GRP']; ?>"/>
    <label for="message">H.Q.:</label>
    <input type="email" id="email" value="<?php echo $PDF['GEO_NAME'];?>"/><br><br>
   <!-------- <label for="name">From:</label>
    <input type="text" id="name" />
    <label for="email">To:</label>
    <input type="email" id="email"   />------------->
    <label for="message">Month:</label>
    <?php foreach($allmonth as $key=>$value){ if($key == $month_id) { $my=$value.', '.date('Y'); }} ?>
    <input type="email" id="email" value="<?php echo $my; ?>"/>
    <label for="message">Bill No:</label>
    <input type="email" id="email"   />
   
</form>
<br>
<?php 
   $TOT_KM=$PDF["CLOSE_MTR"]-$PDF["OPEN_MTR"];
   $TOT_AMT=$TOT_KM*$PDF['EMP_VEH_ALW'];
   
   $TOT_VEH_EXP=$TOT_AMT+$PDF['TAX_RS'];
   $TOT_TRA_EXP=$PDF['FARE_RS']+$PDF['LOCAL_CONVEYANCE']+$PDF['HOTEL_RS']+$PDF['DAILY_DA'];
   
   
?>
<table width="900" style=" border: 1px solid black;
    border-collapse: collapse;   padding: 5px;
    text-align: left;">
<tr>
<th>S.No.</th>
    <th>Particulars</th>
    <th>Claimed Amount</th>
    <th>Deduction Amount</th>
    <th>Checked Amount</th>
    <th>Passed Amount</th>
  </tr>  
  <tr>
    <th>1.</th>
    <th colspan="5">Vehicle Running Expenses:-</th>
   
    
  </tr>
  <tr>
    <td rowspan="5">&nbsp;</td>
    <td>Vehicle Running Km</td>
    <td><?php echo $TOT_KM ?></td>
    <td></td>
    <td></td>
    <td></td>
   
  </tr>
  <tr>
    <td>Rate Per Km</td>
    <td><?php echo $PDF['EMP_VEH_ALW']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   
   
  </tr>
  <tr>
    <td>Vehicle Running Amount</td>
    <td><?php echo $TOT_AMT; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
    
  </tr>
  <tr>
    <td>Toll Tax</td>
    <td><?php echo $PDF['TAX_RS']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   
    
  </tr>
  <tr>
    <th>Total</th>
    <td><?php echo $TOT_VEH_EXP; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
    
  </tr>
  <tr>
    <td>2.</td>
    <th colspan="5">Travelling Expenses:-</th>
   
    
  </tr>
  <tr>
    <td rowspan="5">&nbsp;</td>
    <td>Fare</td>
    <td><?php echo $PDF['FARE_RS']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
   
  </tr>
  <tr>
    <td>Local Conveyance</td>
    <td><?php echo $PDF['LOCAL_CONVEYANCE']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   
   
  </tr>
  <tr>
    <td>Hotel/Lodging</td>
    <td><?php echo $PDF['HOTEL_RS']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
    
  </tr>
  <tr>
    <td>D.A.</td>
    <td><?php echo $PDF['DAILY_DA']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   
    
  </tr>
  <tr>
    <th>Total</th>
    <td><?php echo $TOT_TRA_EXP; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
    
  </tr>
 <tr>
 <td>3.</td>
    <td>Mobile & Telephone Exps.</td>
    <td>0.00</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
 </tr>
 <tr>
 <td>4.</td>
    <td>Postage & Couriers Exps.</td>
    <td><?php echo $PDF['POSTAGE_RS']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
 </tr>
 <tr>
 <td>5.</td>
    <td>Printing & Stationary Exps.</td>
    <td><?php echo $PDF['PRINTING_RS']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
 </tr>
 <tr>
 <td>6.</td>
    <td>Misc. Exps.</td>
    <td><?php echo $PDF['MISC_RS']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
 </tr>
 <tr>
 <td>&nbsp;</td>
    <th>Total Bill Amount</th>
    <td><?php echo $ALL_TOT=$PDF['POSTAGE_RS']+$PDF['PRINTING_RS']+$PDF['MISC_RS']+$TOT_VEH_EXP+$TOT_TRA_EXP; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
 </tr>
<tr>
<td>&nbsp;</td>     
<th style="padding:10px;">Amounts In words Rs.</th>
<td colspan="4"><?php echo ucfirst($ALL_TOT_WORD);?></td>
</tr>
</table>
 <label for="name">SIGNATURE OF CLAIMANT:</label>
    <input type="text" id="name"  />
    <label for="email">CHECKED BY:</label>
    <input type="email" id="email"  />
    <label for="message">PASSED BY:</label>
    <input type="email" id="email"/><br><br>
</div>
</form>
<?php }
elseif(count($PDF)==0){
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
    url = 'index.php?route=report/tabillreport2&token=<?php echo $token; ?>';
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

<style>

body {
	
	margin: 0;
	padding: 0;
	
}
h1 {
	color : #000000;
	text-align : center;
	font-family: "SIMPSON";
}
form {
	width:900px;
	margin: 0 auto;
	
}
.div1{text-align:center;}
.div2{text-align:center;}
.div2 span{border:1px solid #000;}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
input{border:none;}
</style>
<?php echo $footer; ?>