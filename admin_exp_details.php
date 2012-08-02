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
        
                    json_exp = rebuildJson(data);
                    
//then nodes without association
                    for(l=0; l<data.nodes.length; l++) {
                        find = false;
  
                        if(data.type == "physical") {
  
                            for(z=0; z<json_exp.length && !find; z++){
                                if(data.nodes[l] == json_exp[z].node) {
                                    find = true;
                                }
                            }
                            
                            if(!find) {
                                json_exp.push({"node": data.nodes[l],"profilename":"","firmwarename":""});
                            }
                        }
                        else {
                            for(z=0; z<json_exp.length && !find; z++){
                                if(data.nodes[l].alias == json_exp[z].node) {
                                    find = true;
                                }
                            }
                            
                            if(!find) {
                                json_exp.push({"node": data.nodes[l].alias,"profilename":"","firmwarename":""});
                            }
                        }    
                    }
                
                    
                    //display
                    $("#detailsExpRow").html("");
                    
                    for(k = 0; k < json_exp.length; k++) {
                        
                        if(data.type == "physical") {
                            $("#detailsExpRow").append("<tr><td>"+json_exp[k].node+"</td><td>"+displayVar(json_exp[k].profilename)+"</td><td>"+displayVar(json_exp[k].firmwarename)+"</td></tr>");
                        }
                        else {
                            for(k = 0; k < data.nodes.length; k++) {
                                var archi = data.nodes[k].properties.archi;
                                
                                var site = "any";
                                if(data.nodes[k].properties.site != null) {
                                    site = data.nodes[k].properties.site;
                                }
                                    
                                var nbnodes = data.nodes[k].properties.nbnodes;
                                
                                var mobile = false;
                                
                                if(data.nodes[k].properties.mobile != null) {
                                    mobile = data.nodes[k].properties.mobile;
                                }    
                                
                                var ntype = "fixe";
                                if(mobile){
                                    ntype = "mobile";
                                }
                                
                                $("#detailsExpRow").append("<tr><td>"+archi+"/"+site+"/"+nbnodes+"/"+ntype+"</td><td>"+json_exp[k].profilename+"</td><td>"+json_exp[k].firmwarename+"</td></tr>")
                            }
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
