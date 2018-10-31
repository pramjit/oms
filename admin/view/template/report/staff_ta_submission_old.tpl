<?php echo $header; ?><?php echo $column_left;//print_r($orders); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Staff TA Submission Detail</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Staff TA Submission Detail</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Staff TA Submission Detail</h3>
        <button  type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-name">SELECT MONTH</label>
             <select name="month" id="month" class="form-control ">
                   <option value=''>Select Month</option> 
                   <option value='January'>January</option>
                   <option value='February'>February</option>
                   <option value='March'>March</option>
                   <option value='April'>April</option>
                   <option value='May'>May</option>
                   <option value='June'>June</option>
                   <option value='July'>July</option>
                   <option value='August'>August</option>
                   <option value='September'>September</option>
                   <option value='October'>October</option>
                   <option value='November'>November</option>
                   <option value='December'>December</option>
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
                <td class="text-center">S No</td>
                <td class="text-center">Name </td>
                <td class="text-center">Mobile No</td>
                <td class="text-center" width="5px">01</td>
                <td class="text-center" width="5px">02</td>
                <td class="text-center" width="5px">03</td> 
                <td class="text-center" width="5px">04</td> 
                <td class="text-center" width="5px">05</td> 
                <td class="text-center" width="5px">06</td>
                <td class="text-center" width="5px">07</td> 
                <td class="text-center" width="5px">08</td> 
                <td class="text-center" width="5px">09</td> 
                <td class="text-center" width="5px">10</td> 
                <td class="text-center" width="5px">11</td>
                <td class="text-center" width="5px">12</td>
                <td class="text-center" width="5px">13</td> 
                <td class="text-center" width="5px">14</td> 
                <td class="text-center" width="5px">15</td> 
                <td class="text-center" width="5px">16</td>
                <td class="text-center" width="5px">17</td> 
                <td class="text-center" width="5px">18</td> 
                <td class="text-center" width="5px">19</td> 
                <td class="text-center" width="5px">20</td>
                <td class="text-center" width="5px">21</td>
                <td class="text-center" width="5px">22</td>
                <td class="text-center" width="5px">23</td> 
                <td class="text-center" width="5px">24</td> 
                <td class="text-center" width="5px">25</td> 
                <td class="text-center" width="5px">26</td>
                <td class="text-center" width="5px">27</td> 
                <td class="text-center" width="5px">28</td> 
                <td class="text-center" width="5px">29</td> 
                <td class="text-center" width="5px">30</td>
                <td class="text-center" width="5px">31</td>
		                              
              </tr>
            </thead>
            <tbody>
                
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-center"><?php echo $aa; ?></td>
                <td class="text-center"><?php echo $order['Emp_Name']; ?></td>
                <td class="text-center" ><?php echo $order['Mobile']; ?></td>
                
                <td class="text-center" width="5px"><?php echo $order['01']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['02']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['03']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['04']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['05']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['06']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['07']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['08']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['09']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['10']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['11']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['12']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['13']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['14']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['15']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['16']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['17']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['18']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['19']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['20']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['21']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['22']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['23']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['24']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['25']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['26']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['27']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['28']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['29']; ?></td> 
                <td class="text-center" width="5px"><?php echo $order['30']; ?></td>
                <td class="text-center" width="5px"><?php echo $order['31']; ?></td>
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
    
        url = 'index.php?route=report/staff_ta_submission&token=<?php echo $token; ?>';
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
/*$('#button-download').on('click', function() { 
    //alert("bvhjsdb");
	url = 'index.php?route=report/staff_ta_submission/download_excel&token=<?php echo $token; ?>';
        
        var gmonth = $('#month').val();
        var gmonth_nm = $("#teh_id option[value='"+gmonth_id+"']").text();
        if (gmonth) 
        {
            url += '&month=' + encodeURIComponent(gmonth);
           url += '&month_nm=' + encodeURIComponent(gmonth_nm);
        }
        location = url;     
});

*/
   $('#button-download').on('click', function() { 
	url = 'index.php?route=report/staff_ta_submission/download_excel&token=<?php echo $token; ?>';
   
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