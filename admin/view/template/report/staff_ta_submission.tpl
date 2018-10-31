<?php 
echo $header;
echo $column_left;
?>
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
        </div>
        <div class="panel-body">
        <div class="well">
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                <label class="control-label" for="input-name">SELECT MONTH </label>
                <select name="month" id="month" class="form-control ">
                    <option value=''>Select Month</option>
                    <?php
                    foreach($allmonth as $key=>$value){
                    ?>
                    <option value="<?php echo $key;?>" <?php echo ($key == $month_id) ? 'selected' : ''; ?>><?php echo $value.', '. date('Y'); ?></option>
                    <?php
                    }
                    ?>
                </select>
              </div>
            </div>
           
            <div class="col-sm-2"> 
                <label class="control-label" for="input-group">&nbsp;</label>
                <button type="button" id="button-filter" class="btn btn-warning pull-right" style="margin-top: 22px;"><i class="fa fa-search"></i> &nbsp;Search</button>
            </div>
            <?php if(count($ChkTaAvl)>0){ ?>
            <div class="col-sm-2">
                <label class="control-label" for="input-group">&nbsp;</label>
                <button  type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: 22px;"><i class="fa fa-download"></i> &nbsp;Download </button>
            </div>
            <?php } ?>
        </div>
        </div>
        <?php
        if(count($ChkTaAvl)>0)
        {
        ?>
        <div class="table-responsive">
          <table class="table table-bordered" styele="font-size:10px!important;">
            <thead>
            <tr style="background: #373737;color: #ffffff;">
                <td class="text-center">#No</td>
                <td class="text-center">Name</td>
                <td class="text-center">Mobile</td>
            <?php
            foreach($dtlist as $cdt){
            ?>
                <td class="text-center" width="5px"><?php echo $cdt['XD']; ?></td>
            <?php
            }
            ?>
            </tr>
            </thead>
            <tbody>
                <?php
                $x=0;
                foreach($masData as $ma){
                ?>
                    <tr>
                    <td><?php echo $x+1;?></td>
                <?php
                    foreach($ma as $xd){
                ?>
                    <td><?php echo $xd;?></td>
                <?php
                    }
                    ?>
                    </tr>
                <?php
                $x++;
                }
                ?>
            </tbody>
          </table>
        </div>
       <?php }
        elseif(count($pdfdatas)==0){
        echo '<div style="width:100%; text-align:center; color:#f00f00;">'.$Noop.'</div>';
        }?>  
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
   
    $('#button-filter').on('click', function() {
        url = 'index.php?route=report/staff_ta_submission&token=<?php echo $token; ?>';
        var gmonth_id = $('#month').val();
        var gmonth_nm = $("#month option[value='"+gmonth_id+"']").text();
        if(gmonth_id!=null)
        {
           url += '&gmonth_id=' + encodeURIComponent(gmonth_id);
           url += '&month_nm=' + encodeURIComponent(gmonth_nm);
        }
        location = url;     
   
});

   $('#button-download').on('click', function() { 
	url = 'index.php?route=report/staff_ta_submission/download_excel&token=<?php echo $token; ?>';
        var gmonth_id = $('#month').val();
        var gmonth_nm = $("#month option[value='"+gmonth_id+"']").text();
        if(gmonth_id!=null)
        {
           url += '&gmonth_id=' + encodeURIComponent(gmonth_id);
           url += '&month_nm=' + encodeURIComponent(gmonth_nm);
        }
        location = url;     
});



</script>
<?php echo $footer; ?>