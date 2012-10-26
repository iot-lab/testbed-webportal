<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php") ?>

<link rel="stylesheet" href="css/datepicker.css" type="text/css"/>
<link rel="stylesheet" href="css/timepicker.css" type="text/css"/>

    <div class="container" text-align="top">
    
   
<div class="row">

    <h2>Experiment Details</h2>

    <div id="detailsExp">
        <p id="detailsExpSummary"></p>
        
        <p>
            <button class="btn btn-danger" id="btnCancel" onclick="cancelExperiment()">Cancel</button>
            <a href="scripts/exp_download_data.php?id=<?php echo $_GET['id']?>" class="btn" id="btnDownload">Download</a>
            
        </p>
        
        <form class="well form-horizontal" style="width:500px" id="frm_reload">
            <button class="btn" id="btn_reload">Reload</button>
            <div class="control-group">
					<label class="control-label" for="txt_start">Start:</label>
					<div class="controls">
						<div class="row-fluid">
							<div class="span1" style="text-align:center"><input type="radio" id="radioStart" name="radioStart" value="asap" checked/></div>
                            <div class="span8" style="margin:0px;padding-top:3px">As soon as possible</div>
                        </div>
                        <div class="row-fluid">
                            <div class="span1" style="text-align:center"><input type="radio" id="radioStart" name="radioStart" value="scheduled"/></div>
                            <div class="span3" style="margin:0px;padding-top:3px">Scheduled</div>
                            <div class="span4" style="margin:0px;text-align:right;"><input type="text" class="input-small" value="" id="dp1" name="dp1" disabled="disabled" style="display:none"></div>
                            <div class="span2"><input class="dropdown-timepicker input-mini" data-provide="timepicker" type="text" id="tp1" name="tp1" disabled="disabled" style="display:none"></div>
                        </div>
					</div>
            </div>
        </form>
        

        
        <table class="table table-striped table-bordered table-condensed" style="width:500px" id="tbl_nodes">
        <thead>
            <tr>
                <th></th>
                <th>Node</th>
                <th>Profile</th>
                <th>Firmware</th>
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
                <option value="update">Update</option>
            </select>
            
            <button id="btn_send" class="btn" type="submit">Send</button>
            
            <div id="firmware" style="display:none">Firmware: <input type="file" id="files" name="files[]" multiple /></div>
            
            <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
            <div id="state" class="alert" style="display:none"></div>
            
        </form>
        
    </div>
</div>


    <?php include('footer.php') ?>

    <script type="text/javascript">
        
        var json_exp = [];
        var id = <?php echo $_GET['id']?>;
        var state = "";
        var binary = "";
        var boundary = "AaB03x";
        var scheduled = false;
        
        $(document).ready(function(){

            $("#loader").ajaxStart(function(){
                $(this).show();
            });
            $("#loader").ajaxStop(function(){
                $(this).hide();
            });

            //date picker
            var now = new Date();
            $("#dp1").val(now.getMonth()+1 + "-" + now.getDate() + "-" + now.getFullYear());
            
            $('#dp1').datepicker({
                format: 'mm-dd-yyyy'
            });

            $('.dropdown-timepicker').timepicker({
                defaultTime: 'current',
                minuteStep: 1,
                disableFocus: true,
                showMeridian: false,
                template: 'dropdown'
            });

            //click on scheduled button 
            $("input[name=radioStart]").change(function () {
                if($(this).val()== "asap") {
                    $("#dp1").attr("disabled","disabled");
                    $("#dp1").hide();
                    $("#tp1").attr("disabled","disabled");
                    $("#tp1").hide();
                    scheduled = false;
                } else {                    
                    $("#dp1").removeAttr("disabled");
                    $("#dp1").show();
                    $("#tp1").removeAttr("disabled");
                    $("#tp1").show();
                    scheduled = true;
                }
            });

            /* retrieve experiment details */
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
        
        
                    if(data.state != "Running") {
                        $("#frm_actions").hide();
                    }
        
        
                    json_exp = rebuildJson(data);

                    
    
                    //then nodes without association
                    for(var l=0; l<data.nodes.length; l++) {
                        find = false;
  
                        if(data.type == "physical") {
  
                            for(var z=0; z<json_exp.length && !find; z++){
                                if(data.nodes[l] == json_exp[z].node) {
                                    find = true;
                                }
                            }
                            
                            if(!find) {
                                json_exp.push({"node": data.nodes[l],"profilename":"","firmwarename":""});
                            }
                        }
                        else {
                            for(var z=0; z<json_exp.length && !find; z++){
                                if(data.nodes[l].alias == json_exp[z].node) {
                                    find = true;
                                }
                            }
                            
                            if(!find) {
                                json_exp.push({"node": data.nodes[l].alias,"profilename":"","firmwarename":""});
                            }
                        }    
                    }


                    $("#detailsExpSummary").html("<b>Experiment:</b> <a href=\"monika?job=" + id + "\">" + id + "</a><br/>");
                    $("#detailsExpSummary").append("<b>State:</b> " + data.state + "<br/>");
                    $("#detailsExpSummary").append("<b>Name:</b> " + exp_name + "<br/>");
                    $("#detailsExpSummary").append("<b>Duration (min):</b> " + data.duration + "<br/>");
                
                    
                    //display
                    $("#detailsExpRow").html("");
                    
                    var nbTotalNodes=0;
                    if(data.type == "physical") nbTotalNodes=data.nodes.length;                    
                    
                    for(var k = 0; k < json_exp.length; k++) {
                        
                        if(data.type == "physical") {
                            $("#detailsExpRow").append(
                            "<tr><td><input type=\"checkbox\" name=\"option1\" value=\""+json_exp[k].node+"\"></td>"+
                            "<td>"+json_exp[k].node+"</td><td>"+displayVar(json_exp[k].profilename)+"</td>"+
                            "<td>"+displayVar(json_exp[k].firmwarename)+"</td></tr>");
                        }
                        else {
                            for(var k = 0; k < data.nodes.length; k++) {
                                var archi = data.nodes[k].properties.archi;
                                
                                var site = "any";
                                if(data.nodes[k].properties.site != null) {
                                    site = data.nodes[k].properties.site;
                                }
                                    
                                var nbnodes = data.nodes[k].nbnodes;
                                nbTotalNodes += nbnodes;                                
                                
                                var mobile = false;
                                
                                if(data.nodes[k].properties.mobile != null) {
                                    mobile = data.nodes[k].properties.mobile;
                                }    
                                
                                var ntype = "fixe";
                                if(mobile == "1"){
                                    ntype = "mobile";
                                }

                                $("#detailsExpRow").append("<tr><td></td><td>"+archi+"/"+site+"/"+nbnodes+"/"+ntype+"</td><td>"+json_exp[k].profilename+"</td><td>"+json_exp[k].firmwarename+"</td></tr>");
                            }
                        }
                    }
                    $("#detailsExpSummary").append("<b>Number of nodes:</b> " + nbTotalNodes + "<br/>");
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#detailsExpSummary").html("An error occurred while retrieving experiment #" + id + " details");
                    $('#details_modal').modal('show');
                }
            });
            
            
            /* actions list change */
            $("#action").change(function(){
               if($("#action").val() == "update") {
                   $("#firmware").show();
               } 
               else {
                   $("#firmware").hide();
               }
            });
            
            
            /* actions on nodes */
            $("#frm_actions").bind("submit",function(e){
                e.preventDefault();
               
                $("#state").hide();
               
                var command = $("#action option:selected").val();
                
                var battery = "";
                if($("#action option:selected").attr("data-battery") == "battery") {
                    battery = "&battery=true";
                }
                
                
                var lnodes = [];
                $("#tbl_nodes :checked").each(function(){
                    lnodes.push($(this).val());
                });
                
                if($("#action").val() == "update") {
                    //JSON
                    var datab = "";
                    datab += "--" + boundary + '\r\n';
                    datab += 'Content-Disposition: form-data; name="test.json"; filename="test.json"\r\n';
                    datab += 'Content-Type: application/json\r\n\r\n';
                    datab += JSON.stringify(lnodes) + '\r\n\r\n';
                    //datab += "--" + boundary + '\r\n';

                    //firmware
                    datab += "--" + boundary + '\r\n';
                    datab += 'Content-Disposition: form-data; name="test.hex"; filename="test.hex"\r\n';
                    datab += 'Content-Type: text/plain\r\n\r\n';
                    datab += binary + '\r\n';


                    //add json
                    datab += "--" + boundary + '--';

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: datab,
                        url: "/rest/experiment/"+id+"/nodes?update",
                        contentType: "multipart/form-data; boundary="+boundary,
                        
                        success: function (data) {
                            $("#state").html("<b>Update</b> successful for node(s): " + JSON.stringify(data.success));
                            $("#state").removeClass("alert-error");
                            $("#state").addClass("alert-success");
                            $("#state").show();
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#state").html(textStatus + " : " + errorThrows +  " : " +  XMLHttpRequest.responseText);
                            $("#state").removeClass("alert-success");
                            $("#state").addClass("alert-error");
                            $("#state").show();
                        }
                    });
                }
                else {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: JSON.stringify(lnodes),
                        contentType: "application/json; charset=utf-8",
                        url: "/rest/experiment/"+id+"/nodes?"+command+""+battery,
                        success: function (data) {
                            $("#state").html("<b>"+command + "</b> successful for node(s): " + JSON.stringify(data.success));
                            $("#state").removeClass("alert-error");
                            $("#state").addClass("alert-success");
                            $("#state").show();
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#state").html(textStatus + " : " + errorThrows +  " : " +  XMLHttpRequest.responseText);
                            $("#state").removeClass("alert-success");
                            $("#state").addClass("alert-error");
                            $("#state").show();
                        }
                    });
                }
               // return false;
            });
            
            //file upload event
            document.getElementById('files').addEventListener('change', handleFileSelect, false);
            
            
            
            $("#frm_reload").bind("submit",function(e){
                e.preventDefault();
                
                var exp_json = {};
                if(scheduled) {
                    
                    //parse date
                    var tab_date = $("#dp1").val().split("-");
                    var month = (tab_date[0] - 1);
                    var day = tab_date[1];
                    var year = tab_date[2];
                    
                    //parse hour
                    var tab_hour = $("#tp1").val().split(":");
                    var hour = tab_hour[0];
                    var minute = tab_hour[1];
                    
                    //create date
                    var schedule_date = new Date(year, month, day, hour, minute);
                    var scheduled_timestamp = schedule_date.getTime()/1000;
                    
                    var d = new Date();
                    var offset = d.getTimezoneOffset();
                    exp_json.reservation = scheduled_timestamp - (offset*60);
                }
                
                console.log(exp_json);
                
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: JSON.stringify(exp_json),
                    contentType: "application/json; charset=utf-8",
                    url: "/rest/experiment/"+id+"?reload",
                    success: function (data) {
                        alert(data);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrows) {
                        alert(textStatus);
                    }
                });
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
                    alert("error: " + errorThrows);
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

    function handleFileSelect(evt) {
        var files = evt.target.files; // fileList object

        // loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

            var reader = new FileReader();

            // closure to capture the file information.
            reader.onload = (function (theFile) {
                return function (e) {
                    binary = e.target.result;
                };
            })(f);

            reader.readAsText(f);
        }
    }

    </script>

        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/bootstrap-timepicker.js"></script>

  </body>
</html>
