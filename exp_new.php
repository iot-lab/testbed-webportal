<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}
?>

<?php include("header.php") ?>

<link rel="stylesheet" href="css/datepicker.css" type="text/css"/>
<link rel="stylesheet" href="css/timepicker.css" type="text/css"/>

    <div class="container">
              
        <h2>New experiment</h2>
                
        <div class="row">
            <div class="span9">
                
            <div class="alert" id="txt_notif">
                <button class="close" data-dismiss="alert">×</button>
                <p id="txt_notif_msg"></p>
            </div>

        <div class="modal hide" id="expState">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onClick="redirectDashboard();">×</button>
            <h3>Experiment state</h3>
          </div>
          <div class="modal-body">
            <p id="expStateMsg"></p>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal" onClick="redirectDashboard();">Close</a>
          </div>
        </div>

            <form class="well form-horizontal" id="form_part1">

                <h3>Configure your experiment</h3>
                <div class="control-group">
                    <label class="control-label" for="txt_name">Name:</label>
                    <div class="controls">
                        <input id="txt_name" name="name" type="text" class="input-large">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="txt_duration">Duration (minutes):</label>
                    <div class="controls">
                        <input id="txt_duration" name="duration" type="number" class="input-small" value="20" required="required" min="0">
                    </div>
                </div>
                
                <div class="control-group">
					<label class="control-label" for="txt_start">Start:</label>
					<div class="controls">
						<div class="row-fluid">
							<div class="span1" style="text-align:center"><input type="radio" id="radioStart" name="radioStart" value="asap" checked/></div>
                            <div class="span8" style="margin:0px;padding-top:3px">As soon as possible</div>
                        </div>
                        <div class="row-fluid">
                            <div class="span1" style="text-align:center"><input type="radio" id="radioStart" name="radioStart" value="scheduled"/></div>
                            <div class="span2" style="margin:0px;padding-top:3px">Scheduled</div>
                            <div class="span2" style="margin:0px"><input type="text" class="input-small" value="" id="dp1" name="dp1" disabled="disabled" style="display:none"></div>
                            <div class="span2"><input class="dropdown-timepicker input-mini" data-provide="timepicker" type="text" id="tp1" name="tp1" disabled="disabled" style="display:none"></div>
                        </div>
					</div>
				</div>
                
                <h3>Choose your nodes</h3>
                <div class="control-group">
                    <label class="control-label">Resources:</label>
                    <div class="controls">
                        <div class="row-fluid">
                                <div class="span1" style="text-align:center"><input type="radio" name="resources_type" id="optionsRadiosMaps" value="physical" checked></div>
                                <div class="span2" style="margin:0px;padding-top:3px">from maps</div>
                                <div class="span9" style="margin:0px"><div class="" id="div_resources_map"><table id="div_resources_map_tbl"></table></div></div>

                        </div>
                       <div class="row-fluid">
                                <div class="span1" style="text-align:center"><input type="radio" name="resources_type" id="optionsRadiosType" value="alias"></div>
                                <div class="span2" style="margin:0px;padding-top:3px">by type</div>
                        </div> 
                        <div class="row-fluid">
                                <div class="span12" style="margin:0px;padding-top:3px">
                        
                            
			                        <!-- by alias -->
			                        <div class="" id="div_resources_type">
			                            
			                            <table style="text-align:center">
			                                <thead>
			                                    <tr>
			                                        <th><button id="btn_add" class="btn" style="width:50px">+</button></th>
			                                        <th>Archi</th>
			                                        <th>Site</th>
			                                        <th>Number</th>
			                                        <th>Mobile</th>
			                                    </tr>
			                                </thead>
			                                <tbody id="resources_table">
			                                    <tr id="resources_row">
			                                        <td>
			                                            <button class="btn" style="width:50px" onclick="removeRow(this);return false;">-</button>
			                                        </td>
			                                        <td>
			                                            <select id="lst_archi" class="input-medium archi">
			                                            </select>
			                                        </td>
			                                        <td>
			                                            <select id="lst_site" class="input-medium site">
			                                            </select> 
			                                        </td>
			                                        <td>
			                                            <input id="txt_fixe" type="number" class="input-mini number" value="1" min="0">
			                                        </td>
			                                        <td>
			                                            <input id="txt_mobile" type="checkbox" class="input-small mobile">
			                                        </td>
			                                    </tr>
			                                </tbody>
			                            </table>
			
			                        </div>
			                   </div>
			              </div>
                        
                    </div>
                </div>
                <button id="btn_submit" class="btn btn-primary" type="submit">Next</button>
            </form>
            
            <form class="well form-horizontal" id="form_part2">
                
                
                <button id="btn_previous" class="btn">Previous</button>
                
                <h3>Manage associations</h3>
              
                    <table>
                        <thead>
                            <tr>
                                <th>Node</th>
                                <th>Profile</th>
                                <th>Firmware</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><select id="my_nodes" size="15" multiple></select></td>
                                <td><select id="my_profiles" size="15"></select></td>
                                <td><select id="my_firmwares" size="15"></select></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><button id="btn_refresh" class="btn">Refresh</button></td>
                                <td><input type="file" id="files" name="files[]" multiple /></td>
                            </tr>
                            <tr>
                                <td><button id="btn_assoc" class="btn">Add Association</button></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    
                    <p>
                        <table style="width:700px" class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Node</th>
                                    <th>Profile</th>
                                    <th>Firmware</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="my_assoc"></tbody>
                        </table>
                    </p>
                    
                <hr/>
                
                <button id="btn_submit" class="btn btn-primary" type="submit">Submit</button>
                
            </form>
            
</div> <!-- span -->

    <div class="span3">
        <div id="help1" class="alert alert-info">
            <img src="img/help.png"> To create a new experiment you can: set a <b>name</b>, 
            set a <b>duration</b>, run it <b>as soon as possible</b> or <b>scheduled</b>.
        </div>
        
        <div id="help2" class="alert alert-info">
            <img src="img/help.png"> You have two ways to choose the resources : 
<br/><br/>From the maps, or directly entering their numbers. 
<br/><br/>By type. Take care of building a consistent request (correct number of nodes...), otherwise it will be rejected. You can go to <i>Testbed activity -> View nodes status</i> to check the properties of the nodes.
If you select mobile nodes on train, every nodes of the train will be reserved.
            <br/><br/>Then click <b>Next</b> to go to the firmwares/profiles associations page.
        </div>
        
        <div id="help3" class="alert alert-info">
            <img src="img/help.png"> You can associate nodes with profiles and/or firmwares by selecting items and click <b>Add Association</b>.
            
            <br/><br/>Click <b>Browse</b> button to add a firmware to the list.
            
            <br/><br/>When it's done, click <b>Submit</b> to submit your experiment.
        </div>
        
    </div>

</div> <!-- row -->   
    
            
        <?php include('footer.php') ?>
        
        </div>
        
        <script type="text/javascript">

            /* ************ */
            /*  global var  */
            /* ************ */

            //json
            var exp_json = {
                "type": "physical",
                "duration": 20
            };
            
            //profiles
            var my_profiles = [];

            //firmwares
            var binary = [];

            //scheduled exp
            var scheduled = false;

            //sites resources json
            var site_resources = [];

            var alias_nodes = [];

            /* ************ */
            /*   on ready   */
            /* ************ */
            $(document).ready(function () {

                $("#txt_notif").hide();
                $("#expState").modal('hide');
                $("#form_part2").hide();

                $("#help1").show();
                $("#help2").show();
                $("#help3").hide();

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
                
                $("#expState").bind("hidden",redirectDashboard);

                //file upload event
                document.getElementById('files').addEventListener('change', handleFileSelect, false);

                getProfiles();

                //get sites resources
                $.ajax({
                    type: "GET",
                    dataType: "text",
                    contentType: "application/json; charset=utf-8",
                    url: "/rest/experiments?sites",
                    success: function (data_server) {
                        site_resources = JSON.parse(data_server);
                        //console.log(site_resources);
                        
                        for(var j = 0; j < site_resources.items.length; j++) {
                            $("#div_resources_map_tbl").append('<tr><td><a href="#" onclick="openMapPopup(\''+site_resources.items[j].site+'\')" id="'+site_resources.items[j].site+'_maps">'+site_resources.items[j].site.charAt(0).toUpperCase() + site_resources.items[j].site.slice(1)+' map</a></td><td><input id="'+site_resources.items[j].site+'_list" value="" class="input-large" /></td></tr>');
                            
                        }


                        for(var i = 0; i < site_resources.items.length; i++) {
                            $("#lst_site").append(new Option(site_resources.items[i].site, site_resources.items[i].site));
                            
                            for(j = 0; j < site_resources.items[i].resources.length; j++) {
                                var find = false;
                                for(var z = 0; z < $("#lst_archi option").length && !find; z++) {
                                    if($("#lst_archi option")[z].value == site_resources.items[i].resources[j].archi) {
                                        find = true;
                                    }
                                }
                                if(!find) {
                                    $("#lst_archi").append(new Option(site_resources.items[i].resources[j].archi, site_resources.items[i].resources[j].archi));
                                }
                            }
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrows) {
                        $("#txt_notif_msg").html(errorThrows);
                        $("#txt_notif").show();
                        $("#txt_notif").removeClass("alert-success");
                        $("#txt_notif").addClass("alert-error");
                    }
                });
            });


            /* ************* */
            /* submit part 1 */
            /* ************ */
            $("#form_part1").bind("submit", function (e) {

                e.preventDefault();

                var regExp = /^[0-9A-z]*$/; ;
                if(regExp.test($("#txt_name").val()) == false){
                   alert("You must set an experiment name with only alphanumeric characters [0-9A-Za-z_]");
                   return false;
                }

                //var url = $(this).attr('action');
                //$("#form_part1").serializeArray();
        
                $("#my_nodes").empty();
                
                //reset
                exp_json = {};
                exp_json.type = $("input[name=resources_type]:checked").val();


                //type alias
                if($("input[name=resources_type]:checked").val() == "alias"){
                    
                    alias_nodes = [];
                    var alias_index = 0;
                    
                    for(var i = 0; i<$("#resources_table tr").length; i++){
                        var row_rs = {};
                        
                        row_rs.alias = alias_index;
                        row_rs.properties = {};
                        
                        var row = $("#resources_table tr")[i];

                        var site = $(row).find(".site").val();
                        var archi = $(row).find(".archi").val();
                        var number = $(row).find(".number").val();
                        var mobile = $(row).find(".mobile").is(':checked');
                        
                        var ntype = "fixe";
                        if(mobile){
                            ntype = "mobile";
                        }
                        
                        if(number != 0) {
                            row_rs.nbnodes = number;
                            row_rs.properties.archi = archi;
                            
                            row_rs.properties.site = site;
                            
                            if(mobile)
                                row_rs.properties.mobile = 1;
                            else
                                row_rs.properties.mobile = 0;

                            alias_nodes.push(row_rs);

                            $("#my_nodes").append(new Option(archi+"/"+site+"/"+number+"/"+ntype,alias_index));

                            alias_index++;
                        }
                    }

                    exp_json.nodes = alias_nodes;
                }
                else {

                    exp_json.nodes = [];
                    $("#div_resources_map input").each(function(){
                        var site = $(this).attr("id").split('_')[0];
                        var val = $(this).attr("value");
                        if(val != "") {
                            var snodes = parseNodebox(val);
                                        for (i = 0; i < snodes.length; i++) {
                                            if(!isNaN(snodes[i]))
                                                exp_json.nodes.push("node"+snodes[i]+"."+site+".senslab.info");
                                        }

                        }
                    });

              
                    for (i = 0; i < exp_json.nodes.length; i++) {
                        $("#my_nodes").append(new Option(exp_json.nodes[i], exp_json.nodes[i], false, false));
                    }

                }

                //console.log(JSON.stringify(exp_json));
                if(exp_json.nodes.length!=0) {
                        $("#form_part1").hide();
                        $("#form_part2").show();

                        $("#help1").hide();
                        $("#help2").hide();
                        $("#help3").show();
                        $("#txt_notif").hide();

                        displayAssociation();
                } else {
                    $("#txt_notif_msg").html("You have to choose at least one node.");
                    $("#txt_notif").show();
                    $("#txt_notif").removeClass("alert-success");
                    $("#txt_notif").addClass("alert-error");
                }
                return false;
            });


            /* ****************** */
            /* set an association */
            /* ****************** */
            $("#btn_assoc").click(function () {

                //get selected item and remove
                var nodes_set = $("#my_nodes").val();
                var profil_set = $("#my_profiles").val();
                var firmware_set = $("#my_firmwares").val();


                $("#my_profiles option:selected").removeAttr("selected");
                $("#my_firmwares option:selected").removeAttr("selected");
 
                var error = false;
                
                if( nodes_set == null) {    //no node selected
                    error = true;
                }
                else {                      //no profile or firmware selected
                    if(profil_set == null && firmware_set == null) {
                        error = true;
                    }
                }
                
                if (error) {
                    alert("Please select nodes and a profile and/or a firmware");
                    return false;
                }
                $("#my_nodes option:selected").remove();


                if(profil_set != null) {
                    
                    //init some vars
                    if (exp_json.profileassociations == null) {
                        exp_json.profileassociations = [];
                    }
                    
                    //retrieve profile index
                    var find = false;
                    var index = -1;
                    for (var i = 0; i < my_profiles.length && index == -1; i++) {
                        if (my_profiles[i].profilename == profil_set) {
                            find = true;
                            index = i;
                            break;
                        }
                    }

                    var find = false;
                    //if profile already exist in the table
                    for (i = 0; i < exp_json.profileassociations.length; i++) {
                        if (exp_json.profileassociations[i].profilename == profil_set) {
                            exp_json.profileassociations[i].nodes = exp_json.profileassociations[i].nodes.concat(nodes_set);
                            find = true;
                        }
                    }

                    if (!find) {
                        exp_json.profileassociations.push({
                            "profilename": profil_set,
                            "nodes": nodes_set
                        });
                    }
                }

                if(firmware_set != null) {
                    
                    //init some vars
                    if(exp_json.firmwareassociations == null) {
                        exp_json.firmwareassociations = [];
                    }
                
                    
                    find = false;
                    //if firmware already exist in the table
                    for (i = 0; i < exp_json.firmwareassociations.length; i++) {
                        if (exp_json.firmwareassociations[i].firmwarename == firmware_set) {
                            exp_json.firmwareassociations[i].nodes = exp_json.firmwareassociations[i].nodes.concat(nodes_set);
                            find = true;
                        }
                    }

                    if (!find) {
                        exp_json.firmwareassociations.push({
                            "firmwarename": firmware_set,
                            "nodes": nodes_set
                        });
                    }
                }

                //console.log(JSON.stringify(exp_json));
                displayAssociation();
                
                return false;
            });


            /* ************* */
            /* submit part 2 */
            /* ************ */
            $("#form_part2").bind('submit', function (e) {

                e.preventDefault();

                //set main properties
                exp_json.type = $("input[name=resources_type]:checked").val();

                if($("#txt_name").val() != "") {
                    exp_json.name = $("#txt_name").val();
                }    

                exp_json.duration = parseInt($("#txt_duration").val());


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
                
                    exp_json.reservation = scheduled_timestamp;
                }

                //console.log(JSON.stringify(exp_json));

                var mydata = JSON.stringify(exp_json);
                var datab = "";
                
                var boundary = "AaB03x";

                //JSON
                datab += "--" + boundary + '\r\n';
                datab += 'Content-Disposition: form-data; name="'+exp_json.name+'.json"; filename="'+exp_json.name+'.json"\r\n';
                datab += 'Content-Type: application/json\r\n\r\n';
                datab += mydata + '\r\n\r\n';
                //datab += "--" + boundary + '\r\n';


                for (var i = 0; i < binary.length; i++) {
                    datab += "--" + boundary + '\r\n';
                    datab += 'Content-Disposition: form-data; name="' + binary[i].name + '"; filename="' + binary[i].name + '"\r\n';
                    datab += 'Content-Type: application/octet-stream\r\n\r\n';
                    datab += binary[i].bin + '\r\n';
                }

                //add json
                datab += "--" + boundary + '--';

                $.ajax({
                    type: "POST",
                    dataType: "text",
                    data: datab,
                    url: "/rest/experiment",
                    contentType: "multipart/form-data; boundary="+boundary,
                    
                    success: function (data_server) {
                        
                        $("#expState").modal('show');
                        var info = JSON.parse(data_server);
                        
                        if(info.id) {
                           
                            $("#expStateMsg").html("<h3>Your experiment has successfully been submitted</h3>");
                            $("#expStateMsg").append("Experiment Id : " + info.id);
                        }
                        else {
                            $("#expStateMsg").html("<h3 style='color:red'>Error</h3>");
                            $("#expStateMsg").append(info.error);
                        }
                        
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrows) {
                        $("#expState").modal('show');
                        $("#expStateMsg").html("<h3 style='color:red'>Error</h3>");
                        $("#expStateMsg").append(textStatus + ": " + errorThrows + "<br/>" + XMLHttpRequest.responseText);
                    }
                });

                return false;
            });



            /* ************ */
            /*   function   */
            /* ************ */

            // expand a list of nodes containing dash intervals
            // 1-3,5,9 -> 1,2,3,5,9
            function expand(factExp) {
                exp = [];
                for (var i = 0; i < factExp.length; i++) {
                    dashExpression = factExp[i].split("-");
                    if (dashExpression.length == 2) {
                        for (var j = parseInt(dashExpression[0]); j < (parseInt(dashExpression[1]) + 1); j++)
                        exp.push(j);
                    } else exp.push(parseInt(factExp[i]));
                }
                exp.sort(sortfunction);
                for (var i = 1; i < exp.length; i++) {
                    if (exp[i] == exp[i - 1]) {
                        exp.splice(i--, 1);
                    }
                }
                return exp;
            }

            function parseNodebox(input) {
                return expand(input.split(","));
            }

            function sortfunction(a, b) {
                return (a - b); //causes an array to be sorted numerically and ascending
            }
            
            function handleFileSelect(evt) {
                var files = evt.target.files; // fileList object

                // loop through the FileList and render image files as thumbnails.
                for (var i = 0, f; f = files[i]; i++) {

                    var reader = new FileReader();

                    // closure to capture the file information.
                    reader.onload = (function (theFile) {
                        return function (e) {

                            binary.push({
                                "name": theFile.name,
                                "bin": e.target.result
                            });

                            $("#my_firmwares").append(new Option(theFile.name, theFile.name, false, false));
                        };
                    })(f);
					if(f.name.indexOf(".elf",f.name.length -4)!=-1) reader.readAsDataURL(f);
					else reader.readAsText(f);
                }
            }
            
            
            //display nodes associations
            function displayAssociation() {
                $("#my_assoc").html("");
                
                json_tmp = rebuildJson(exp_json);

                
                //display
                for(var k = 0; k < json_tmp.length; k++) {
                    if(exp_json.type == "physical") {
                        $("#my_assoc").append("<tr><td>"+json_tmp[k].node+"</td><td>"+displayVar(json_tmp[k].profilename)+"</td><td>"+displayVar(json_tmp[k].firmwarename)+"</td><td><a href='#' onClick='removeAssociation("+k+")'><img src='img/del.png'></img></a></td></tr>");
                    }
                    
                    else {
                        //$("#my_assoc").append("<tr><td>"+json_tmp[k].node+"</td><td>"+json_tmp[k].profilename+"</td><td>"+json_tmp[k].firmwarename+"</td></tr>");

                        for(var z = 0; z < exp_json.nodes.length; z++) {
                            
                            if(json_tmp[k].node == exp_json.nodes[z].alias) {
                                var archi = exp_json.nodes[z].properties.archi;
                                
                                var site = "any";
                                if(exp_json.nodes[z].properties.site != null)
                                    site = exp_json.nodes[z].properties.site;
                                    
                                var nbnodes = exp_json.nodes[z].nbnodes;
                                
                                var mobile = false;
                                if(exp_json.nodes[z].properties.mobile != null) {
                                    mobile = exp_json.nodes[z].properties.mobile;
                                }
                                
                                var ntype = "fixe";
                                if(mobile){
                                    ntype = "mobile";
                                }
                                
                                $("#my_assoc").append("<tr><td>"+archi+"/"+site+"/"+nbnodes+"/"+ntype+"</td><td>"+json_tmp[k].profilename+"</td><td>"+json_tmp[k].firmwarename+"</td><td><a href='#' onClick='removeAssociation("+k+")'><img src='img/del.png'></img></a></td></tr>");
                            }
                        }
                    }
                }
            }
            
            //click on add row alias resources
            $("#btn_add").click(function(){
                var t = $("#resources_row").clone();
                $(t).find(".number").val(1);
                var t = $("#resources_table").append(t);
                
                
                return false;
            });
            
            
            //click on remove row alias resources
            function removeRow(btnDelete) {
                if($("#resources_table tr").length != 1) {
                    $(btnDelete).parent().parent().remove();
                }
            }
            
            //refresh profile list
            $("#btn_refresh").click(function(){
                $("#my_profiles option").remove();
                getProfiles();
                return false;
            });
            
            
            function getProfiles() {
                
                //get all profiles
                $.ajax({
                    type: "GET",
                    dataType: "text",
                    contentType: "application/json; charset=utf-8",
                    url: "/rest/profiles",
                    success: function (data_server) {
                        
                        if(data_server == "") {
                            //no profile
                            return false;
                        }
                        
                        my_profiles = JSON.parse(data_server);
                        
                        //fill profiles list
                        for(var i = 0; i < my_profiles.length; i++) {
                            $("#my_profiles").append(new Option(my_profiles[i].profilename,my_profiles[i].profilename));
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrows) {
                        $("#txt_notif_msg").html(errorThrows);
                        $("#txt_notif").show();
                        $("#txt_notif").removeClass("alert-success");
                        $("#txt_notif").addClass("alert-error");
                    }
                });
            }
            
            //click on previous button
            $("#btn_previous").click(function(){
                $("#form_part2").hide();
                $("#form_part1").show();
                
                $("#help1").show();
                $("#help2").show();
                $("#help3").hide();
                
                return false;
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
            
            //click on ressources type radio button
            $("#div_resources_type").hide();
            $("input[name=resources_type]").change(function () {
                if ($(this).val() == "physical") {
                    $("#div_resources_type").hide();
                    $("#div_resources_map").show();
                } else {
                    $("#div_resources_type").show();
                    $("#div_resources_map").hide();
                }
            });

            function removeAssociation(index) {
                json_tmp = rebuildJson(exp_json);

                // looking for profile in json_tmp[index] 
                if(JSON.stringify(json_tmp[index].profilename)!=undefined) {
                    for (var i=0;i<exp_json.profileassociations.length;i++) {
                        if(exp_json.profileassociations[i].profilename==json_tmp[index].profilename) {
                            for (var j=0; j<exp_json.profileassociations[i].nodes.length;j++)
                                if(exp_json.profileassociations[i].nodes[j]==json_tmp[index].node)
                                    exp_json.profileassociations[i].nodes.splice(j,1);
                            if(exp_json.profileassociations[i].nodes.length==0)
                                exp_json.profileassociations.splice(i,1);
                        }
                    }
                    if(exp_json.profileassociations.length==0)
                        delete exp_json.profileassociations;
                
                }
                // looking for firmware in json_tmp[index] 
                if(JSON.stringify(json_tmp[index].firmwarename)!=undefined) {
                        for (var i=0;i<exp_json.firmwareassociations.length;i++) {
                           if(exp_json.firmwareassociations[i].firmwarename==json_tmp[index].firmwarename) {
                                for (var j=0; j<exp_json.firmwareassociations[i].nodes.length;j++)
                                    if(exp_json.firmwareassociations[i].nodes[j]==json_tmp[index].node)
                                        exp_json.firmwareassociations[i].nodes.splice(j,1);
                                if(exp_json.firmwareassociations[i].nodes.length==0)
                                    exp_json.firmwareassociations.splice(i,1);
                           }
                         }
                         if(exp_json.firmwareassociations.length==0)
                             delete exp_json.firmwareassociations;
                }
        
                if(exp_json.type=="alias") {
                    
                    var node = exp_json.nodes[json_tmp[index].node];
                    var ntype = "fixe";
                    if(node.properties.mobile==1)
                          ntype = "mobile";
        
                    nsite = node.properties.site;
        
                    $("#my_nodes").append(new Option(node.properties.archi+"/"+nsite+"/"+node.nbnodes+"/"+ntype,json_tmp[index].node));
        
                } else {
                    $("#my_nodes").append(new Option(json_tmp[index].node, json_tmp[index].node , false, false));
                }
        
                displayAssociation();
            }
   

function openMapPopup(site) {
	window.open('maps.php?site='+site, '', 'resizable=yes, location=no, width=800, height=600, menubar=no, status=no, scrollbars=no, menubar=no');
}
            
        </script>
        
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/bootstrap-timepicker.js"></script>
       
        </body>
</html>
