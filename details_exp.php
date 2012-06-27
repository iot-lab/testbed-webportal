<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php") ?>

    <div class="container" text-align="top">
    
   
<div class="row">

    <h2>Experiment Details</h2>

    <div id="detailsExp">
        <p id="detailsExpSummary"></p>
        
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
        
        $(document).ready(function(){


        var json_exp = [];
        var withAssoc = false;
        var id = <?php echo $_GET['id']?>

            /* Retrieve experiment details */
            $.ajax({
                url: "/rest/experiment/"+id,
                type: "GET",
                data: {},
                dataType: "json",
                success:function(data){
                    //console.log(data);

                    var exp_name = "";
                    if(data.name != null)
                        exp_name = data.name;

                    $("#detailsExpSummary").html("<b>Experiment:</b> " + id + "<br/>");
                    $("#detailsExpSummary").append("<b>Name:</b> " + exp_name + "<br/>");
                    $("#detailsExpSummary").append("<b>Duration (min):</b> " + data.duration + "<br/>");
                    $("#detailsExpSummary").append("<b>Number of Nodes:</b> " + data.nodes.length + "<br/>");
        
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
                        
                        if(data.type == "physical") {
                            $("#detailsExpRow").append("<tr><td>"+json_exp[k].node+"</td><td>"+json_exp[k].profilename+"</td><td>"+json_exp[k].firmwarename+"</td></tr>");
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
                    
                    if(!withAssoc)
                    {
                        if(data.type == "physical") {
                            for(k = 0; k < data.nodes.length; k++) {
                                $("#detailsExpRow").append("<tr><td>"+data.nodes[k]+"</td><td></td><td></td></tr>");
                            }
                        }
                        else {
                            for(k = 0; k < data.nodes.length; k++) {
                                var archi = data.nodes[k].properties.archi;
                                
                                var site = "any";
                                if(data.nodes[k].properties.site != null)
                                    site = data.nodes[k].properties.site;
                                    
                                var nbnodes = data.nodes[k].properties.nbnodes;
                                
                                var mobile = false;
                                if(data.nodes[k].properties.mobile != null)
                                    mobile = data.nodes[k].properties.mobile;
                                
                                $("#detailsExpRow").append("<tr><td>"+archi+"/"+site+"/"+nbnodes+"/"+mobile+"</td><td></td><td></td></tr>");
                            }
                        }
                    }

                    $('#details_modal').modal('show');
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#detailsExpSummary").html("An error occurred while retrieving experiment #" + id + " details");
                    $('#details_modal').modal('show');
                }
            });
    });

    </script>

  </body>
</html>
