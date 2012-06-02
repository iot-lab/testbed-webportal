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
    
                <div class="loading" id="div_msg" style="display:none"><b>Loading ...</b></div>
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
        
        <div id="details_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">×</a>
              <h3>Experiment Details</h3>
            </div>
           <div class="modal-body">

            <div id="detailsExp">
                <p id="detailsExpSummary"></p>
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>node</th>
                        <th>profile</th>
                        <th>firmware</th>
                    </tr>
                    </thead>
                    <tbody id="detailsExpRow">
                    </tbody>
                </table>
            
            
            </div>

            </div>
        </div>

        
        <?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>
    <script type="text/javascript">

        var oTable;
        
        $(document).ready(function(){

            /* Hide modal windows */
            $('#exp_details').modal('hide');        
           
            /* Retrieve experiments total */
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
                    $("#div_msg").removeClass("loading");
                    $("#div_msg").addClass("alert");
                    $("#div_msg").addClass("alert-error");
                    $("#div_msg").show();
                    $("#div_msg").html("An error occurred while retrieving your experiment list");
                }
            });

            /* Manage experiment list */
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
            $('#div_msg').hide();
            $('#tbl_exps').show();
            
        });


        var json_exp = [];
        function detailsExp(id) {

            /* Retrieve experiment details */
            $.ajax({
                url: "/rest/experiment/"+id,
                type: "GET",
                data: {},
                dataType: "json",
                success:function(data){
                    //console.log(data);

                    $("#detailsExpSummary").html("<b>Experiment:</b> " + id + "<br/>");
                    $("#detailsExpSummary").append("<b>Name:</b> " + data.name + "<br/>");
                    $("#detailsExpSummary").append("<b>Duration:</b> " + data.duration + "<br/>");
                    $("#detailsExpSummary").append("<b>Number of Nodes:</b> " + data.nodes.length + "<br/>");
        
                    json_exp = [];
                    //build a more simple json for parsing
                    if(data.profileassociations != null)
                    {
                        for(i = 0; i < data.profileassociations.length; i++) {
                            for(j = 0; j < data.profileassociations[i].nodes.length;j++){
                                json_exp.push({"node": data.profileassociations[i].nodes[j],"profilename":data.profileassociations[i].profilename});
                            }
                        }
                    }
                    
                    if(data.firmwareassociations != null) {
                        for(i = 0; i < data.firmwareassociations.length; i++) {
                            for(j = 0; j < data.firmwareassociations[i].nodes.length;j++){
                                
                                for(k = 0; k < json_exp.length; k++) {
                                    if(json_exp[k].node == data.firmwareassociations[i].nodes[j])
                                        json_exp[k].firmwarename = data.firmwareassociations[i].firmwarename;
                                }
                            }
                        }
                    }
                    
                    
                    $("#detailsExpRow").html("");
                    for(k = 0; k < json_exp.length; k++) {
                        $("#detailsExpRow").append("<tr><td>"+json_exp[k].node+"</td><td>"+json_exp[k].profilename+"</td><td>"+json_exp[k].firmwarename+"</td></tr>");

                    }

                    $('#details_modal').modal('show');
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#detailsExpSummary").html("An error occurred while retrieving experiment #" + id + " details");
                    $('#details_modal').modal('show');
                }
            });
        }
  
        
    </script>

  </body>
</html>
