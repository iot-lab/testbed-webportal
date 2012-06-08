<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php") ?>

    <div class="container" text-align="top">
    
   
    <div class="row">
        <div class="span9">
          <h2>Experiment List</h2>
        </div>
        <div class="span5" style="text-align:left;padding-bottom:5px;padding-left:5px;">
          <a href="new_experiment.php" class="btn btn-new">New Experiment</a>
        </div>
        <div class="span9">
    
                <div class="alert alert-error" id="div_msg" style="display:none"></div>
                <table id="tbl_exps" class="table table-bordered table-striped table-condensed" style="display:none">
                    <thead>
                        <tr>
                            <th>ID</th>
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
          <h2>Personal dashboard</h2>
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
        
        
        <?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>
    <script type="text/javascript">

        var oTable;
        
        $(document).ready(function(){
           
            // Retrieve experiments total 
            $.ajax({
                url: "/rest/experiments?total",
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
                    $("#div_msg").html("An error occurred while retrieving your experiment list");
                }
            });

            // Manage experiment list 
            oTable = $('#tbl_exps').dataTable({
                "sDom": "<'row'<'span7'l><'span7'f>r>t<'row'<'span7'i><'span7'p>>",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "list_experiments.php",
                "bPaginate": true,
                "sPaginationType": "bootstrap",
                "bLengthChange": true,
                "bFilter": false,
                "bSort": false,
                "bInfo": true,
                "bAutoWidth": false
            });
			$('#tbl_exps tbody tr').live('click',function () {
				var aData = oTable.fnGetData( this );
				window.location.href="details_exp.php?id="+aData[0];
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
