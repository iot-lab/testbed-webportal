<?php 
session_start();

if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}

include("header.php") ?>

    <div class="container">
    
   
<div class="row">

    <h2>Admin Experiment Details</h2>

    <div id="detailsExp">
        <div class="alert alert-error" id="div_msg" style="display:none"></div>
        <p id="detailsExpSummary"></p>
        
        <p><button class="btn btn-danger" id="btnCancel" onclick="cancelExperiment()">Cancel</button></p>
        
        <table class="table table-striped table-bordered table-condensed" style="width:500px">
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

        
        <?php include('footer.php') ?>


    <script type="text/javascript">
        
        var json_exp = [];
        var withAssoc = false;
        var id = <?php echo $_GET['id']?>;
        var state = "";
        
        $(document).ready(function(){

            /* Retrieve experiment details */
            $.ajax({
                url: "/rest/admin/experiment/"+id,
                type: "GET",
                data: {},
                dataType: "json",
                success:function(data){
                    //console.log(data);

                    $("#detailsExpSummary").html("<b>Experiment:</b> " + id + "<br/>");
                    $("#detailsExpSummary").append("<b>Owner:</b> " + data.owner + "<br/>");
                    $("#detailsExpSummary").append("<b>Name:</b> " + data.name + "<br/>");
                    $("#detailsExpSummary").append("<b>Duration:</b> " + data.duration + "<br/>");
                    $("#detailsExpSummary").append("<b>Number of Nodes:</b> " + data.nodes.length + "<br/>");
        
                    state = data.state;
                    if(state == "Running" || state == "Waiting") {
                        $("#btnCancel").attr("disabled",false);
                    }
                    else {
                        $("#btnCancel").attr("disabled",true);
                    }
        
                    json_exp = [];
                    withAssoc = false;
                    //build a more simple json for parsing
                    if(data.profileassociations != null)
                    {
                        withAssoc = true;
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
                    
                    if(!withAssoc)
                    {
                        for(k = 0; k < data.nodes.length; k++) {
                            $("#detailsExpRow").append("<tr><td>"+data.nodes[k]+"</td><td></td><td></td></tr>");
                        }
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#div_msg").html("An error occurred while retrieving experiment #" + id + " details");
                    $('#div_msg').show();
                }
            });
    });

    function cancelExperiment(){
        if(confirm("Cancel Experiment?")) {
            
            $.ajax({
                url: "/rest/experiment/" + id,
                type: "DELETE",
                contentType: "application/json",
                dataType: "text",
            
                success:function(data){
                    $("#btnCancel").attr("disabled",true);
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error: " + errorThrows)
                }
            });
        }
    }

    </script>

    </div>
  </body>
</html>
