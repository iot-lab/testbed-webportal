<?php
session_start();

if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}

include("header.php");

$title="";
$dashboard="Admin";
$request_total="/rest/admin/experiments?total";
$request_exps="";
if (isset($_GET['user'])) {
	$title = " of user ".$_GET['user'];
	$dashboard=$_GET['user'];
	$request_total.="&user=".$_GET['user'];
	$request_exps = '"fnServerParams": function ( aoData ) { aoData.push( { "name": "user", "value": "'.$_GET['user'].'" } ); },';
}
?>

    <div class="container">
      
    <div class="row">
        <div class="span9">
          <h2>Experiments List <?php echo $title; ?></h2>
        </div>
        
        <div class="span9">
    
                <div class="alert alert-error" id="div_msg" style="display:none"></div>
                <table id="tbl_exps" class="table table-bordered table-striped table-condensed" style="display:none">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Owner</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Node(s)</th>
                            <th>State</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
        </div>
          
        <div class="span4">
          <h2><?php echo $dashboard; ?> dashboard</h2>
          <p><i class="icon-cog"></i> Experiments: <span id="expTotal">&nbsp;</span></p>
            <ul>
                <li><span id="expRunning" class="badge badge-success">&nbsp;</span> running</li>
                <li><span id="expUpcoming" class="badge badge-info">&nbsp;</span> upcoming</li>
                <li><span id="expPast" class="badge">&nbsp;</span> past</li>
            </ul>
          <p><i class="icon-th"></i> Profiles: 2 <p>
          <p><i class="icon-home"></i> Home's quota: 60% (600/1000Mo)
            <div class="progress" style="width:200px">
              <div class="bar"
                   style="width: 60%;"></div>
            </div>
            </p>
           
          </div>
      </div> 


      <hr>

<?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>
 
    <script type="text/javascript">

    var oTable;
    
    $(document).ready(function(){
        
        // Retrieve experiments total
        $.ajax({
            url: "<?php echo $request_total; ?>",
            type: "GET",
            dataType: "json",
            success:function(data){
                var total = data.running+data.upcoming+data.terminated;
                $("#expRunning").text(data.running);
                $("#expUpcoming").text(data.upcoming);
                $("#expPast").text(data.terminated);
                $("#expTotal").text(total);
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_msg").removeClass("alert-success");
                $("#div_msg").addClass("alert-error");
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving the experiment total");
            }
        });

        // Manage experiment list
        oTable = $('#tbl_exps').dataTable({
            "sDom": "<'row'<'span7'l><'span7'f>r>t<'row'<'span7'i><'span7'p>>",
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "admin_list_experiments.php",
			<?php echo $request_exps; ?>
			"bPaginate": true,
            "sPaginationType": "bootstrap",
            "bLengthChange": true,
            "bFilter": false,
            "bSort": false,
            "bInfo": true,
            "bAutoWidth": false
        });
        $('#div_msg').hide();
        $('#tbl_exps').show();
        
    });

    function reloadExp(id) {
        $("#div_msg").html("NYI");
        $("#div_msg").show();
        setTimeout( "$('#div_msg').hide()", 2000); 
    }

    function cancelExp(id) {
        $("#div_msg").html("NYI");
        $("#div_msg").show();
        setTimeout( "$('#div_msg').hide()", 2000); 
    }

    function stopExp(id) {
        $("#div_msg").html("NYI");
        $("#div_msg").show();
        setTimeout( "$('#div_msg').hide()", 2000); 
    }
    
    </script>

  </body>
</html>
