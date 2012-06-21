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
$request_profiles="/rest/admin/profiles";
$request_exps="";
if (isset($_GET['user'])) {
	$title = " of user ".$_GET['user'];
	$dashboard=$_GET['user'];
	$request_total.="&user=".$_GET['user'];
	$request_profiles.="&user=".$_GET['user'];
	$request_exps = '"fnServerParams": function ( aoData ) { aoData.push( { "name": "user", "value": "'.$_GET['user'].'" } ); },';
}
?>

    <div class="container">
      
    <div class="row">
        <div class="span9">
        	<h2>Experiment List <?php echo $title; ?></h2>
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
          <p><i class="icon-th"></i> Profiles: <span id="nb_profiles">&nbsp;</span></p>
          <p><i class="icon-home"></i> Home's quota: 60% (600/1000Mo)
            <div class="progress" style="width:200px">
              <div class="bar" style="width: 60%;"></div>
            </div>
          </p>
           
		</div>
	</div>
	

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

        // Retrieve profiles total 
        $.ajax({
            url: "/rest/admin/profiles?user=<?php echo $_GET['user']; ?>",
            type: "GET",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                my_profiles = JSON.parse(data);
                $("#nb_profiles").text(my_profiles.total);
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                $("#div_msg").removeClass("alert-success");
                $("#div_msg").addClass("alert-error");
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving the profile list");
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
            "aoColumns": [
                          {"mDataProp": "id" },
                          {"mDataProp": "owner" },
                          {"mDataProp": "name" },
                          {"mDataProp": "date",
                               "fnRender": function(obj) {
                                      var date = obj.aData['date'];
                                      myDate = new Date(date*1000);
                                      if(myDate == "Invalid Date") 
                                          myDate = "As soon as possible";
                                      else myDate = myDate.toGMTString();
                                      return myDate;
                               }
                          },
                          {"mDataProp": "duration" },
                          {"mDataProp": "nb_resources" },
                          {"mDataProp": "state",
                           "fnRender": function(obj) {
                                  var state = obj.aData['state'];
                                  if ( state == "Error" ) { // terminated error 
                                      state = "<span class='label label-important'>"+state+"</span>";
                                  } else if( state == "Terminated" ) { // terminated OK 
                                      state = "<span class='label'>"+state+"</span>";
                                  } else if( state == "Running" || state == "Finishing" || state == "Resuming" || state == "toError" ) { // running 
                                      state = "<span class='label label-success'>"+state+"</span>";
                                  } else if( state == "Waiting" || state=="Launching" || state=="Suspended"
                                      || state == "Hold" || state=="toAckReservation" || state=="toLaunch" ) { // upcomming 
                                      state = "<span class='label label-info'>"+state+"</span>";
                                  }
                                  return state;
                           }
                          }
			],
			"sAjaxDataProp": "items",
            "sPaginationType": "bootstrap",
            "bLengthChange": true,
            "bFilter": false,
            "bSort": false,
            "bInfo": true,
            "bAutoWidth": false,
            "fnInitComplete": function(oSettings, json) {
                $('#tbl_exps tbody tr').each(function(){
                    this.setAttribute('title','Click to see details');
                });
                $('#tbl_exps tbody tr[title]').tooltip( {
                    "delay": 0,
                    "track": true,
                    "fade": 250,
                    "placement": 'right'
                });
            }
        });

        $('#tbl_exps tbody tr').live('click',function () {
            var aData = oTable.fnGetData( this );
            window.location.href="admin_details_exp.php?id="+aData['id'];
        });
        
        $('#div_msg').hide();
        $('#tbl_exps').show();
        
    });
    
    </script>

  </body>
</html>
