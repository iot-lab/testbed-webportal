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
        
        <p>
            <button class="btn btn-danger" id="btnCancel" onclick="cancelExperiment()">Cancel</button>
            <a href="scripts/exp_download_data.php?id=<?php echo $_GET['id']?>" class="btn" id="btnDownload">Download</a>
        </p>
        
        <table class="table table-striped table-bordered table-condensed" style="width:500px" id="tbl_nodes">
        <thead>
            <tr>
                <th></th>
                <th>node</th>
                <th>profile</th>
                <th>firmware</th>
            </tr>
        </thead>
        <tbody id="detailsExpRow">
        </tbody>
        </table>
        
        <p><a href="javascript:selectAll();">Select All</a> - <a href="javascript:unSelectAll();">Unselect All</a></p>
        
        
        <form id="frm_actions">

            <b>Actions on selected nodes: </b>
            
            <select id="action" class="input-small">
                <option value="start">Start</option>
                <option value="start" data-battery="battery">Start (battery)</option>
                <option value="stop">Stop</option>
                <option value="reset">Reset</option>
            </select>
            
            <button id="btn_send" class="btn" type="submit">Send</button>
            
            <div id="state" class="alert" style="display:none"></div>
            
        </form>
        
    </div>
</div>


    <?php include('footer.php') ?>

    <script type="text/javascript">
        
        var json_exp = [];
        var id = <?php echo $_GET['id']?>;
        var state = "";
        
        $(document).ready(function(){

            /* Retrieve experiment details */
            $.ajax({
                url: "/rest/experiment/"+id,
                type: "GET",
                data: {},
                dataType: "json",
                success:function(data){

                    var exp_name = "";
                    if(data.name != null)
                        exp_name = data.name;

                    state = data.state;
                    if(state == "Running" || state == "Waiting") {
                        $("#btnCancel").attr("disabled",false);
                    }
                    else {
                        $("#btnCancel").attr("disabled",true);
                    }


                    $("#detailsExpSummary").html(
                    "<b>Experiment:</b> <a href=\"monika?job=" + id + "\">" + id + "</a><br/>");
                    $("#detailsExpSummary").append(
                    "<b>State:</b> " + data.state + "<br/>");
                    $("#detailsExpSummary").append(
                    "<b>Name:</b> " + exp_name + "<br/>");
                    $("#detailsExpSummary").append(
                    "<b>Duration (min):</b> " + data.duration + "<br/>");
                    $("#detailsExpSummary").append(
                    "<b>Number of Nodes:</b> " + data.nodes.length + "<br/>");
        
        
                    if(data.state != "Running") {
                        $("#frm_actions").hide();
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
                            $("#detailsExpRow").append(
                            "<tr><td><input type=\"checkbox\" name=\"option1\" value=\""+json_exp[k].node+"\"></td>"+
                            "<td>"+json_exp[k].node+"</td><td>"+displayVar(json_exp[k].profilename)+"</td>"+
                            "<td>"+displayVar(json_exp[k].firmwarename)+"</td></tr>");
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
                    $("#detailsExpSummary").html("An error occurred while retrieving experiment #" + id + " details");
                    $('#details_modal').modal('show');
                }
            });
            
            
             //actions on nodes
            $("#frm_actions").bind("submit",function(e){
                e.preventDefault();
               
                var command = $("#action option:selected").val();
                
                var battery = "";
                if($("#action option:selected").attr("data-battery") == "battery") {
                    battery = "&battery=true";
                }
                
                
                var lnodes = [];
                $("#tbl_nodes :checked").each(function(){
                    lnodes.push($(this).val());
                });
                
                
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: JSON.stringify(lnodes),
                    contentType: "application/json; charset=utf-8",
                    url: "/rest/experiment/"+id+"/nodes?"+command+""+battery,
                    success: function (data) {
                        $("#state").html("OK: " + JSON.stringify(data.success));
                        $("#state").removeClass("alert-error");
                        $("#state").addClass("alert-success");
                        $("#state").show();
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrows) {
                        $("#state").html(textStatus);
                        $("#state").removeClass("alert-success");
                        $("#state").addClass("alert-error");
                        $("#state").show();
                    }
                });
                
               // return false;
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
    
    function selectAll() {
        $("#tbl_nodes :checkbox").each(function(){
            $(this).attr('checked', true);
        });
    }
    
    function unSelectAll() {
        $("#tbl_nodes :checkbox").each(function(){
            $(this).attr('checked', false);
        });
    }

    </script>

  </body>
</html>
