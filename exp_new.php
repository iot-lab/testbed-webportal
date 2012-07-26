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
            <button type="button" class="close" data-dismiss="modal">×</button>
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
                        <input id="txt_name" type="text" class="input-small">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="txt_duration">Duration (minutes):</label>
                    <div class="controls">
                        <input id="txt_duration" type="number" class="input-small" value="20" required="required">
                    </div>
                </div>
                
                <div class="control-group">
                    
                    <label class="control-label" for="txt_duration">Scheduled</label>
                    <div class="controls">
                        <input type="checkbox" id="cbScheduled" value="cbScheduled"/>
                        <div id="div_scheduled" style="display:none">
                            <input type="text" class="input-small" value="" id="dp1" disabled="disabled">
                            <input class="dropdown-timepicker input-mini" data-provide="timepicker" type="text" id="tp1" disabled="disabled">
                        </div>
                        
                    </div>
                </div>
                
                
                <h3>Choose your nodes</h3>
                <div class="control-group">
                    <label class="control-label">Resources</label>
                    <div class="controls">
                        
                        <input type="radio" name="resources_type" id="optionsRadiosType" value="alias" > by alias

                        <input type="radio" name="resources_type" id="optionsRadiosMaps" value="physical" checked=""> physical   
                            
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
                                                <option value="any">Any</option>
                                            </select> 
                                        </td>
                                        <td>
                                            <input id="txt_fixe" type="number" class="input-mini number" value="1">
                                        </td>
                                        <td>
                                            <input id="txt_mobile" type="checkbox" class="input-small mobile">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        
                        <!-- physical -->
                        <div class="" id="div_resources_map">
                            
                                <a href="#" id="devlille_maps">Devlille Maps</a>
                                <input id="devlille_list" value="" />
                        </div>
                        
                    </div>
                </div>
                <button id="btn_submit" class="btn btn-primary" type="submit">Next</button>
            </form>
            
            <form class="well form-horizontal" id="form_part2">
                
                
                <button id="btn_previous" class="btn">Previous</button> <button id="btn_submit" class="btn btn-primary" type="submit">Submit</button>
                
                <h3>Manage association</h3>
              
                    <table>
                        <thead>
                            <tr>
                                <th>node</th>
                                <th>profile</th>
                                <th>firmware</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><select id="my_nodes" size="15" multiple></select></td>
                                <td><select id="my_profiles" size="15"></select></td>
                                <td><select id="my_firmwares" size="15"></select></td>
                            </tr>
                            <tr>
                                <td><button id="btn_assoc" class="btn">Add Association</button></td>
                                <td></td>
                                <td><input type="file" id="files" name="files[]" multiple /></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    
                    <p>
                        <table style="width:700px" class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>node</th>
                                    <th>profile</th>
                                    <th>firmware</th>
                                </tr>
                            </thead>
                            <tbody id="my_assoc"></tbody>
                        </table>
                    </p>
                
            </form>
            
</div> <!-- span -->

    <div class="span3">
        <div id="help1" class="alert alert-info">
            <img src="img/help.png"> To create an new experiment you can: set a <b>name</b>, 
            set a <b>duration</b>, run it <b>as soon as possible</b> or <b>scheduled</b>.
        </div>
        
        <div id="help2" class="alert alert-info">
            <img src="img/help.png"> You can choose <b>physical</b> resources (with node name) or by <b>alias</b> (with properties). 
            <br/><br/>For <b>physical</b> resources you can chose your nodes on a map. 
            <br/><br/>Then click <b>Next</b> to go to the firmwares/profiles associations.
        </div>
        
        <div id="help3" class="alert alert-info">
            <img src="img/help.png"> Make association beetwen node, profile and firmware by selecting items and click <b>Add Association</b>.
            
            <br/><br/>Click to <b>upload</b> button to add a firmware to the list.
            
            <br/><br/>When finnish, click to <b>Submit</b> button to submit your experiment.
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
                
                //file upload event
                document.getElementById('files').addEventListener('change', handleFileSelect, false);

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
                        for(i = 0; i < my_profiles.length; i++) {
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


                //get sites resources
                $.ajax({
                    type: "GET",
                    dataType: "text",
                    contentType: "application/json; charset=utf-8",
                    url: "/rest/experiments?sites",
                    success: function (data_server) {
                        site_resources = JSON.parse(data_server);
                        
                        for(i = 0; i < site_resources.sites.length; i++) {
                            $("#lst_site").append(new Option(site_resources.sites[i].site, site_resources.sites[i].site));
                            
                            for(j = 0; j < site_resources.sites[i].nodes.length; j++) {
                                var find = false;
                                for(z = 0; z < $("#lst_archi option").length && !find; z++) {
                                    if($("#lst_archi option")[z].value == site_resources.sites[i].nodes[j].archi) {
                                        find = true;
                                    }
                                }
                                if(!find) {
                                    $("#lst_archi").append(new Option(site_resources.sites[i].nodes[j].archi, site_resources.sites[i].nodes[j].archi));
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
            $("#form_part1").bind("submit", function () {

                $("#form_part1").hide();
                $("#form_part2").show();
        
                $("#my_nodes").empty();
                
                $("#help1").hide();
                $("#help2").hide();
                $("#help3").show();
                
                //reset associations
                if($("input[name=resources_type]:checked").val() != exp_json.type) {
                    if(exp_json.profileassociations != null) {
                        exp_json.profileassociations = [];
                        exp_json.firmwareassociations = [];
                    }
                }
    
                if($("input[name=resources_type]:checked").val() == "alias") {
                    if(exp_json.profileassociations != null) {
                        exp_json.profileassociations = [];
                        exp_json.firmwareassociations = [];
                    }    
                }
                
                exp_json.type = $("input[name=resources_type]:checked").val();


                //type alias
                if($("input[name=resources_type]:checked").val() == "alias"){
                    
                    alias_nodes = [];
                    var alias_index = 0;
                    
                    for(i = 0; i<$("#resources_table tr").length; i++){
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
                            row_rs.properties.nbnodes = number;
                            row_rs.properties.archi = archi;
                            
                            if(site != "any")   
                                 row_rs.properties.site = site;
                            if(mobile)
                                row_rs.properties.mobile = 1;

                            alias_nodes.push(row_rs);

                            $("#my_nodes").append(new Option(archi+"/"+site+"/"+number+"/"+ntype,alias_index));

                            alias_index++;
                        }
                    }

                    exp_json.nodes = alias_nodes;
                }
                else {
                    //build nodes list
                    var devlille_nodes = [];
                    if ($("#devlille_list").val() != "") {
                        var devlille_list = parseNodebox($("#devlille_list").val());
                        for (i = 0; i < devlille_list.length; i++) {
                            if(!isNaN(devlille_list[i]))
                                devlille_nodes.push("node"+devlille_list[i]+".devlille.senslab.info");
                        }
                    }
                    
                    //set nodes list
                    exp_json.nodes = devlille_nodes;
                                    
                    for (i = 0; i < exp_json.nodes.length; i++) {
                        $("#my_nodes").append(new Option(exp_json.nodes[i], exp_json.nodes[i], false, false));
                    }

                    //check if selected nodes as already an association, if yes -> remove from the list
                    for (i = 0; i < exp_json.nodes.length; i++) {
                        if(exp_json.profileassociations != null) {
                            for (j = 0; j < exp_json.profileassociations.length; j++) {
                                for (k = 0; k < exp_json.profileassociations[j].nodes.length; k++) {
                                    if(exp_json.profileassociations[j].nodes[k] == exp_json.nodes[i]) {
                                        $('#my_nodes option[value="'+ exp_json.nodes[i]+'"]').remove();
                                    }
                                }
                            }
                        }
                    }
                    
                    //check associations for removed nodes
                    if(exp_json.profileassociations != null) {
                        for (i = 0; i < exp_json.profileassociations.length; i++) {
                            for (j = 0; j < exp_json.profileassociations[i].nodes.length; j++) {
                                var isHere = false;
                                for(k = 0; k < exp_json.nodes.length; k++) {
                                    if(exp_json.nodes[k] == exp_json.profileassociations[i].nodes[j])
                                        isHere = true;
                                }
                                
                                if(!isHere) {
                                    exp_json.profileassociations[i].nodes[j] = null;
                                }
                            }
                        }
                    }
                    
                    if(exp_json.firmwareassociations != null) {
                        for (i = 0; i < exp_json.firmwareassociations.length; i++) {
                            for (j = 0; j < exp_json.firmwareassociations[i].nodes.length; j++) {
                                var isHere = false;
                                for(k = 0; k < exp_json.nodes.length; k++) {
                                    if(exp_json.nodes[k] == exp_json.firmwareassociations[i].nodes[j])
                                        isHere = true;
                                }
                                
                                if(!isHere) {
                                    exp_json.firmwareassociations[i].nodes[j] = null;
                                }
                            }
                        }
                    }
                    
                    if(exp_json.profileassociations != null) {
                        for (i = 0; i < exp_json.profileassociations.length; i++) {
                            exp_json.profileassociations[i].nodes.clean(null);
                        }
                    }
                    
                    if(exp_json.firmwareassociations != null) {
                        for (i = 0; i < exp_json.firmwareassociations.length; i++) {
                            exp_json.firmwareassociations[i].nodes.clean(null);
                        }
                    }
                }
                
                displayAssociation();
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

                if (nodes_set == null || profil_set == null || firmware_set == null) {
                    alert("Please select nodes, profile and firmware");
                    return false;
                }
                $("#my_nodes option:selected").remove();

                //init some vars
                if (exp_json.profiles == null) {
                    exp_json.profileassociations = [];
                    exp_json.firmwareassociations = [];
                    exp_json.profiles = {}; 
                }
                
                //retrieve profile index
                var find = false;
                var index = -1;
                for (i = 0; i < my_profiles.length && index == -1; i++) {
                    if (my_profiles[i].profilename == profil_set) {
                        find = true;
                        index = i;
                        break;
                    }
                }
                
                //check if profile already in the exp json
                if(profil_set in exp_json.profiles){
                }
                else{
                    exp_json.profiles[profil_set] = my_profiles[index];
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
                
                displayAssociation();
                
                return false;
            });


            /* ************* */
            /* submit part 2 */
            /* ************ */
            $("#form_part2").bind('submit', function () {

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
                    
                    var d = new Date();
                    var offset = d.getTimezoneOffset();
                    exp_json.reservation = scheduled_timestamp - (offset*60);
                }

                console.log(JSON.stringify(exp_json));

                var mydata = JSON.stringify(exp_json);
                var datab = "";
                
                if (exp_json.profiles != null) {
                    var boundary = "AaB03x";

                    //JSON
                    datab += "--" + boundary + '\r\n';
                    datab += 'Content-Disposition: form-data; name="'+exp_json.name+'.json"; filename="'+exp_json.name+'.json"\r\n';
                    datab += 'Content-Type: application/json\r\n\r\n';
                    datab += mydata + '\r\n\r\n';
                    //datab += "--" + boundary + '\r\n';


                    for (i = 0; i < binary.length; i++) {
                        datab += "--" + boundary + '\r\n';
                        datab += 'Content-Disposition: form-data; name="' + binary[i].name + '"; filename="' + binary[i].name + '"\r\n';
                        datab += 'Content-Type: text/plain\r\n\r\n';
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
                        
                        //data: "data="+datab,
                        //url: "dump.php",
                        success: function (data_server) {
                            $("#expState").modal('show');
                            $("#expStateMsg").html("<h3>Your experiment was successfully submitted</h3>");
                            $("#expStateMsg").append(data_server);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#expState").modal('show');
                            $("#expStateMsg").html("<h3 style='color:red'>Error</h3>");
                            $("#expStateMsg").append(textStatus + ": " + errorThrows);
                        }
                    });
                }
                else
                {
                    $.ajax({
                        type: "POST",
                        dataType: "text",
                        data: mydata,
                        contentType: "application/json; charset=utf-8",
                        url: "/rest/experiment?body",
                        success: function (data_server) {
                            $("#expState").modal('show');
                            $("#expStateMsg").html("<h3>Your experiment was successfully submitted</h3>");
                            $("#expStateMsg").append(data_server);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#expState").modal('show');
                            $("#expStateMsg").html("<h3 style='color:red'>Error</h3>");
                            $("#expStateMsg").append(textStatus + ": " + errorThrows);
                        }
                    });
                    
                }

                return false;
            });



            /* ************ */
            /*   function   */
            /* ************ */

            // expand a list of nodes containing dash intervals
            // 1-3,5,9 -> 1,2,3,5,9
            function expand(factExp) {
                exp = [];
                for (i = 0; i < factExp.length; i++) {
                    dashExpression = factExp[i].split("-");
                    if (dashExpression.length == 2) {
                        for (j = parseInt(dashExpression[0]); j < (parseInt(dashExpression[1]) + 1); j++)
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
                return (a - b) //causes an array to be sorted numerically and ascending
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

                    reader.readAsText(f);
                }
            }
            
            
            //display nodes associations
            function displayAssociation() {
                $("#my_assoc").html("");
                
                json_tmp = [];

                //build a more simple json for parsing
                if(exp_json.profileassociations != null)
                {
                    for(i = 0; i < exp_json.profileassociations.length; i++) {
                        for(j = 0; j < exp_json.profileassociations[i].nodes.length;j++){
                            json_tmp.push({"node": exp_json.profileassociations[i].nodes[j],"profilename":exp_json.profileassociations[i].profilename});
                        }
                    }
                }
                
                if(exp_json.firmwareassociations != null) {
                    for(i = 0; i < exp_json.firmwareassociations.length; i++) {
                        for(j = 0; j < exp_json.firmwareassociations[i].nodes.length;j++){
                            
                            for(k = 0; k < json_tmp.length; k++) {
                                if(json_tmp[k].node == exp_json.firmwareassociations[i].nodes[j])
                                    json_tmp[k].firmwarename = exp_json.firmwareassociations[i].firmwarename;
                            }
                        }
                    }
                }
                
                //display
                for(k = 0; k < json_tmp.length; k++) {
                    if(exp_json.type == "physical") {
                        $("#my_assoc").append("<tr><td>"+json_tmp[k].node+"</td><td>"+json_tmp[k].profilename+"</td><td>"+json_tmp[k].firmwarename+"</td></tr>");
                    }
                    
                    else {
                        //$("#my_assoc").append("<tr><td>"+json_tmp[k].node+"</td><td>"+json_tmp[k].profilename+"</td><td>"+json_tmp[k].firmwarename+"</td></tr>");

                        for(z = 0; z < exp_json.nodes.length; z++) {
                            
                            if(json_tmp[k].node == exp_json.nodes[z].alias) {
                                var archi = exp_json.nodes[z].properties.archi;
                                
                                var site = "any";
                                if(exp_json.nodes[z].properties.site != null)
                                    site = exp_json.nodes[z].properties.site;
                                    
                                var nbnodes = exp_json.nodes[z].properties.nbnodes;
                                
                                var mobile = false;
                                if(exp_json.nodes[z].properties.mobile != null) {
                                    mobile = exp_json.nodes[z].properties.mobile;
                                }
                                
                                var ntype = "fixe";
                                if(mobile){
                                    ntype = "mobile";
                                }
                                
                                $("#my_assoc").append("<tr><td>"+archi+"/"+site+"/"+nbnodes+"/"+ntype+"</td><td>"+json_tmp[k].profilename+"</td><td>"+json_tmp[k].firmwarename+"</td></tr>");
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
            $("#cbScheduled").click(function(){
                if($(this).is(':checked')){
                    $("#dp1").removeAttr("disabled");
                    $("#tp1").removeAttr("disabled");
                    $("#div_scheduled").show();
                    scheduled = true;
                }
                else {
                    $("#dp1").attr("disabled","disabled");
                    $("#tp1").attr("disabled","disabled");
                    $("#div_scheduled").hide();
                    scheduled = false;
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

            //click on a map (open popup)
            $("#devlille_maps").click(function () {
                window.open('maps_lille.php', '', 'resizable=yes, location=no, width=500, height=500, menubar=no, status=no, scrollbars=no, menubar=no');
            });
            
            // Array Remove - By John Resig (MIT Licensed)
            Array.prototype.remove = function(from, to) {
                var rest = this.slice((to || from) + 1 || this.length);
                this.length = from < 0 ? this.length + from : from;
                return this.push.apply(this, rest);
            };
            
            // Array Clean
            Array.prototype.clean = function(deleteValue) { 
                for (var i = 0; i < this.length; i++) { 
                    if (this[i] == deleteValue) { 
                        this.splice(i, 1); 
                        i--; 
                    } 
                } 
                return this; 
            };  
            
            function redirectDashboard() {
                window.location.href = ".";
            }
            
        </script>
        
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/bootstrap-timepicker.js"></script>
       
        </body>
</html>
