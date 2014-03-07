<?php
session_start();

if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php");
?>

<link rel="stylesheet" href="css/datepicker.css" type="text/css"/>
<link rel="stylesheet" href="css/timepicker.css" type="text/css"/>

<div class="container">

<h2>New experiment</h2>

<div class="row">
<div class="col-md-9">

    <div class="alert" id="txt_notif">
        <button class="close" data-dismiss="alert">×</button>
        <p id="txt_notif_msg"></p>
    </div>


    <!--  MODAL WINDOW FOR MANAGING PROFILES -->

    <div id="profiles_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" style="width:920px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            onClick="refreshProfiles();">&times;</button>
                    <h3>Manage Profiles</h3>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="div_error_profiles" style="display:none"></div>
                    <div class="row" id="profiles_modal-body"></div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!--  MODAL WINDOW FOR EXP STATE -->

    <div id="expState" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            onClick="redirectDashboard();">&times;</button>
                    <h3>Experiment state</h3>
                </div>
                <div class="modal-body">
                    <p id="expStateMsg"></p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal" onClick="redirectDashboard();">Close</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <form class="well form-horizontal form-inline" id="form_part1">

        <h3>Configure your experiment</h3>

        <div class="form-group" style="width:100%;padding-bottom:10px">
            <label class="col-md-3 control-label" for="txt_name">Name:</label>

            <div class="col-md-4">
                <input id="txt_name" name="name" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group" style="width:100%;padding-bottom:10px">
            <label class="col-md-3 control-label" for="txt_duration">Duration (minutes):</label>

            <div class="col-md-2">
                <input id="txt_duration" name="duration" type="number" class="form-control" value="20"
                       required="required" min="0">
            </div>
        </div>

        <div class="form-group" style="width:100%;padding-bottom:10px;min-height:75px">
            <label class="col-md-3 control-label" for="txt_start">Start:</label>

            <div class="col-md-9">
                <div class="row-fluid">
                    <label class="radio"><input type="radio" id="radioStart" name="radioStart" value="asap" checked/> As
                        soon as possible</label><br/>
                </div>
                <div class="row-fluid">
                    <label class="col-md-3 radio"><input type="radio" id="radioStart" name="radioStart"
                                                         value="scheduled"/> Scheduled</label>

                    <div class="col-md-8">
                        <div class="col-md-5" style="margin:0px"><input type="text" class="form-control" value=""
                                                                        id="dp1" name="dp1" disabled="disabled"
                                                                        style="display:none"></div>
                        <div class="col-md-4"><input class="dropdown-timepicker form-control" data-provide="timepicker"
                                                     type="text" id="tp1" name="tp1" disabled="disabled"
                                                     style="display:none"></div>
                    </div>
                </div>
            </div>
        </div>

        <h3>Choose your nodes</h3>

        <div class="form-group" style="width:100%;padding-bottom:10px">
            <label class="col-lg-3 control-label">Resources:</label>

            <div class="col-lg-9">
                <label class="radio"><input type="radio" name="resources_type" id="optionsRadiosMaps" value="physical"
                                            checked> from maps</label>&nbsp;&nbsp;
                <label class="radio"><input type="radio" name="resources_type" id="optionsRadiosType" value="alias"> by
                    type</label>

                <div class="col-md-12">
                    <div class="col-md-12" style="margin:0px;padding:0px;padding-top:3px">
                        <div class="" id="div_resources_map">
                            <table style="width:100%">
                                <thead style="text-align:left">
                                <tr>
                                    <th>Sites</th>
                                    <th style="padding-left:30px;">Architectures and IDs</th>
                                </tr>
                                </thead>
                                <tbody id="div_resources_map_tbl">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="col-md-12" style="margin:0px;padding:0px;padding-top:8px;">


                        <!-- by alias -->
                        <div class="" id="div_resources_type">

                            <table style="width:100%;text-align:center">
                                <thead>
                                <tr>
                                    <th>
                                        <button id="btn_add" class="btn btn-default" style="width:50px">+</button>
                                    </th>
                                    <th>Archi</th>
                                    <th>Site</th>
                                    <th>Number</th>
                                    <th>Mobile</th>
                                </tr>
                                </thead>
                                <tbody id="resources_table">
                                <tr id="resources_row">
                                    <td>
                                        <button class="btn btn-default" style="width:50px"
                                                onclick="removeRow(this);return false;">-
                                        </button>
                                    </td>
                                    <td>
                                        <select id="lst_archi" class="form-control archi">
                                        </select>
                                    </td>
                                    <td>
                                        <select id="lst_site" class="form-control site">
                                        </select>
                                    </td>
                                    <td width="75px;">
                                        <input id="txt_fixe" type="number" class="form-control number" value="1"
                                               min="0">
                                    </td>
                                    <td align="center">
                                        <input id="txt_mobile" type="checkbox" class="form-control mobile">
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


        <button id="btn_previous" class="btn btn-default">Previous</button>

        <h3>Manage associations</h3>

        <div class="row form-group">
            <div class=col-md-4>
                <label class="control-label">Node(s)</label>
                <select class="form-control" id="my_nodes" size="15" multiple style="margin-bottom:5px;">
                    <optgroup label="WSN430" id="wsn430Nodes"></optgroup>
                    <optgroup label="M3" id="m3Nodes"></optgroup>
                    <optgroup label="A8" id="a8Nodes"></optgroup>
                </select>
            </div>
            <div class=col-md-4>
                <label class="control-label">Profile(s)</label>
                <select class="form-control" id="my_profiles" size="15" style="margin-bottom:5px;">
                    <optgroup label="WSN430" id="wsn430Profiles"></optgroup>
                    <optgroup label="M3" id="m3Profiles"></optgroup>
                    <optgroup label="A8" id="a8Profiles"></optgroup>
                </select>
                <button id='profilesModalLink' class="btn btn-default" data-toggle="modal"
                        data-target="#profiles_modal">Manage Profiles
                </button>
            </div>
            <div class=col-md-4>
                <label class="control-label">Firmware(s)</label>
                <select class="form-control" id="my_firmwares" size="15" style="margin-bottom:5px;">
                    <optgroup label="WSN430" id="wsn430Firmwares"></optgroup>
                    <optgroup label="M3" id="m3Firmwares"></optgroup>
                    <optgroup label="A8" id="a8Firmwares"></optgroup>
                </select>
                <input type="file" id="files" name="files[]" multiple/>
            </div>
        </div>

        <div class="row form-group">
            <button id="btn_assoc" class="btn btn-default">Add Association</button>
        </div>


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

        <hr/>

        <button id="btn_submit" class="btn btn-primary" type="submit">Submit</button>

    </form>

</div>
<!-- span -->

<div class="col-md-3">
    <div id="help1" class="alert alert-info">
        <img src="img/help.png"> To create a new experiment you can: set a <b>name</b>,
        set a <b>duration</b>, run it <b>as soon as possible</b> or <b>scheduled</b>.
    </div>

    <div id="help2" class="alert alert-info">
        <img src="img/help.png"> You have two ways to choose the resources :
        <br/><br/>From the maps, or directly entering their numbers.
        <br/><br/>By type. Take care of building a consistent request (correct number of nodes...), otherwise it will be
        rejected. You can go to <i>Testbed activity &rarr; View nodes status</i> to check the properties of the nodes.
        If you select mobile nodes on train, every nodes of the train will be reserved.
        <br/><br/>Then click <b>Next</b> to go to the firmwares/profiles associations page.
    </div>

    <div id="help3" class="alert alert-info">
        <img src="img/help.png"> You can associate nodes with profiles and/or firmwares by selecting items and click <b>Add
            Association</b>.

        <br/><br/>Click <b>Browse</b> button to add a firmware to the list.

        <br/><br/>When it's done, click <b>Submit</b> to submit your experiment.
    </div>

</div>

</div>
<!-- row -->


</div> <!-- container -->

<script type="text/javascript">


/* ************ */
/*  global var  */
/* ************ */

//json
var exp_json = {
    "type": "physical",
    "duration": 20
};

//user profiles name and nodearch
var user_profiles = {};
//user firmwares name and nodearch
var user_firmwares = {};
//all nodes network address and nodearch
var sites_nodes = {};

//firmwares
var binary = [];

//scheduled exp
var scheduled = false;

var alias_nodes = [];

/* ************ */
/*   on ready   */
/* ************ */
$(document).ready(function () {
    // profiles things
    $('#profiles_modal').modal('hide');
    $('#profilesModalLink').click(function () {
        loadProfilesModal();
    });
    $('#profilesModalLink2').click(function () {
        loadProfilesModal();
    });

    // exp_new things
    $("#exp_new").addClass("active");
    $("#exp_new2").addClass("active");

    $("#txt_notif").hide();
    $("#expState").modal('hide');
    $("#form_part2").hide();
    $("#div_resources_type").hide();

    $("#help1").show();
    $("#help2").show();
    $("#help3").hide();

    //date picker
    var now = new Date();
    $("#dp1").val(now.getMonth() + 1 + "-" + now.getDate() + "-" + now.getFullYear());

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

    $("#expState").bind("hidden", redirectDashboard);

    //file upload event
    document.getElementById('files').addEventListener('change', handleFileSelect, false);

    //get user profiles
    getProfiles();

    //get sites nodes
    getNodes();
});


/* ************* */
/* submit part 1 */
/* ************ */
$("#form_part1").bind("submit", function (e) {

    e.preventDefault();

    var regExp = /^[0-9A-z]*$/;

    if (regExp.test($("#txt_name").val()) == false) {
        alert("You must set an experiment name with only alphanumeric characters [0-9A-Za-z_]");
        return false;
    }

    //var url = $(this).attr('action');
    //$("#form_part1").serializeArray();

    $("#my_nodes optgroup").empty();

    //reset
    exp_json = {};
    exp_json.type = $("input[name=resources_type]:checked").val();


    //type alias
    if ($("input[name=resources_type]:checked").val() == "alias") {

        alias_nodes = [];
        var alias_index = 0;

        for (var i = 0; i < $("#resources_table tr").length; i++) {
            var row_rs = {};

            row_rs.alias = alias_index;
            row_rs.properties = {};

            var row = $("#resources_table tr")[i];

            var site = $(row).find(".site").val();
            var archi = $(row).find(".archi").val();
            var number = $(row).find(".number").val();
            var mobile = $(row).find(".mobile").is(':checked');

            var ntype = mobile ? "mobile" : "fixe";


            if (number != 0) {
                row_rs.nbnodes = number;
                row_rs.properties.archi = archi;

                row_rs.properties.site = site;

                row_rs.properties.mobile = mobile;

                alias_nodes.push(row_rs);

                $("#" + archi.split(':')[0] + "Nodes").append(new Option(archi + "/" + site + "/" + number + "/" + ntype, alias_index));
                alias_index++;
            }
        }

        exp_json.nodes = alias_nodes;
    }
    else {
        exp_json.nodes = [];
        $("#div_resources_map_tbl input").each(function () {
            var site = $(this).attr("id").split('_')[0];
            var archi = $(this).attr("id").split('_')[1].split(":")[0];
            var val = $(this).val();
            if (val != "") {
                var snodes = expand(val.split("+"));
                for (i = 0; i < snodes.length; i++) {
                    var node_network_address;
                    if (!isNaN(snodes[i]) && ((node_network_address = archi + "-" + snodes[i] + "." + site + ".iot-lab.info") in sites_nodes)) {
                        exp_json.nodes.push(node_network_address);
                        $("#" + sites_nodes[node_network_address] + "Nodes").append(new Option(node_network_address, node_network_address, false, false));
                    }
                }
            }
        });
    }

    if (exp_json.nodes.length != 0) {
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
        $("#txt_notif").addClass("alert-danger");
    }
    return false;
});


/* ****************** */
/* set an association */
/* ****************** */
$("#btn_assoc").click(function () {

    //get selected item
    var nodes_set = $("#my_nodes").val();
    var profil_set = $("#my_profiles").val();
    var firmware_set = $("#my_firmwares").val();

    // check if at least a node and a firmware/profile are selected
    if ((nodes_set == null) || (profil_set == null && firmware_set == null)) {
        alert("Please select nodes and a profile and/or a firmware");
        return false;
    }

    // check if selected profile/firmware is OK for the selected node architecture
    // get profile and firmware architecture
    var profileNodearch = ((profil_set in user_profiles) ? user_profiles[profil_set] : null);
    var fwNodearch = ((firmware_set in user_firmwares) ? user_firmwares[firmware_set] : null);
    // profile and firware are compatible ?
    if (profileNodearch != null && fwNodearch != null && profileNodearch != fwNodearch) {
        alert("The profile and the firmware are not for the same architecture.");
        return false;
    }

    if (exp_json.type == "psysical") {
        // for each selected node, check the architecture
        for (var i = 0; i < nodes_set.length; i++) {
            if (profileNodearch != null && sites_nodes[nodes_set[i]].indexOf(profileNodearch) == -1) {
                alert("The profile is not compatible with some nodes architecture.");
                return false;
            }
            if (fwNodearch != null && sites_nodes[nodes_set[i]].indexOf(fwNodearch) == -1) {
                alert("The firmware is not compatible with some nodes architecture.");
                return false;
            }
        }
    }

    // everything is OK, let's do the job
    $("#my_profiles option:selected").removeAttr("selected");
    $("#my_firmwares option:selected").removeAttr("selected");
    $("#my_nodes option:selected").remove();


    if (profil_set != null) {

        //init some vars
        if (exp_json.profileassociations == null) exp_json.profileassociations = [];

        var find = false;
        //if profile already exist in the table
        for (i = 0; i < exp_json.profileassociations.length && !find; i++) {
            if (exp_json.profileassociations[i].profilename == profil_set) {
                exp_json.profileassociations[i].nodes = exp_json.profileassociations[i].nodes.concat(nodes_set);
                find = true;
            }
        }

        if (!find) exp_json.profileassociations.push({"profilename": profil_set, "nodes": nodes_set});
    }

    if (firmware_set != null) {

        //init some vars
        if (exp_json.firmwareassociations == null)  exp_json.firmwareassociations = [];


        var find = false;
        //if firmware already exist in the table
        for (i = 0; i < exp_json.firmwareassociations.length && !find; i++) {
            if (exp_json.firmwareassociations[i].firmwarename == firmware_set) {
                exp_json.firmwareassociations[i].nodes = exp_json.firmwareassociations[i].nodes.concat(nodes_set);
                find = true;
            }
        }

        if (!find) exp_json.firmwareassociations.push({"firmwarename": firmware_set, "nodes": nodes_set});
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

    if ($("#txt_name").val() != "") {
        exp_json.name = $("#txt_name").val();
    }

    exp_json.duration = parseInt($("#txt_duration").val());


    if (scheduled) {

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
        var scheduled_timestamp = schedule_date.getTime() / 1000;

        exp_json.reservation = scheduled_timestamp;
    }

    //console.log(JSON.stringify(exp_json));

    var mydata = JSON.stringify(exp_json);
    var datab = "";

    var boundary = "AaB03x";

    //JSON
    datab += "--" + boundary + '\r\n';
    datab += 'Content-Disposition: form-data; name="' + exp_json.name + '.json"; filename="' + exp_json.name + '.json"\r\n';
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
        url: "/rest/experiments",
        contentType: "multipart/form-data; boundary=" + boundary,

        success: function (data_server) {

            $("#expState").modal('show');
            var info = JSON.parse(data_server);

            if (info.id) {

                $("#expStateMsg").html("<h4>Your experiment has successfully been submitted</h4>");
                $("#expStateMsg").append("Experiment Id : " + info.id);
            }
            else {
                $("#expStateMsg").html("<h4 style='color:red'>Error</h4>");
                $("#expStateMsg").append(info.error);
            }

        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#expState").modal('show');
            $("#expStateMsg").html("<h4 style='color:red'>Error</h4>");
            $("#expStateMsg").append(textStatus + ": " + errorThrows + "<br/>" + XMLHttpRequest.responseText);
        }
    });

    return false;
});


/* ****************** */
/*   ajax functions   */
/* ****************** */
function getProfiles() { //get all user profiles
    $.ajax({
        type: "GET",
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        url: "/rest/profiles",
        success: function (data_server) {

            if (data_server == "") return false; // no profile

            //fill profiles list
            for (var i = 0; i < data_server.length; i++) {
                $("#" + data_server[i].nodearch + "Profiles").append(new Option(data_server[i].profilename, data_server[i].profilename));
                user_profiles[data_server[i].profilename] = data_server[i].nodearch;
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#txt_notif_msg").html(errorThrows);
            $("#txt_notif").show();
            $("#txt_notif").removeClass("alert-success");
            $("#txt_notif").addClass("alert-danger");
        }
    });
}

function getNodes() { // get all sites nodes
    $.ajax({
        type: "GET",
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        url: "/rest/admin/resourcesproperties",
        success: function (data_server) {
            var sites = [];
            var archis = [];

            for (var i in data_server) {
                if (sites.indexOf(data_server[i].site) == -1) { // unknown site, adding it
                    sites.push(data_server[i].site);
                    //$("#div_resources_map_tbl").append('<tr><td><a href="#" onclick="openMapPopup(\''+data_server[i].site+'\')" id="'+data_server[i].site+'_maps">'+data_server[i].site.charAt(0).toUpperCase() + data_server[i].site.slice(1)+' map</a></td><td><input type="text" id="'+data_server[i].site+'_list" value="" class="form-control" /></td></tr>');
                    // filling the "by type" form
                    $("#lst_site").append(new Option(data_server[i].site, data_server[i].site));
                    // filling the "from maps" form
                    $("#div_resources_map_tbl").append('<tr valign="top" style="border-top: 1px solid #CCCCCC;color:#555555"><td style="width:150px;"><a href="#" onclick="openMapPopup(\'' + data_server[i].site + '\')" id="' + data_server[i].site + '_maps">' + data_server[i].site.charAt(0).toUpperCase() + data_server[i].site.slice(1) + ' map</a></td><td id="' + data_server[i].site + '_archis" style="text-align:right;padding-bottom:20px;padding-top:20px"></td></tr>');
                }
                if (archis.indexOf(data_server[i].archi) == -1) { // unknown archi, adding it
                    archis.push(data_server[i].archi);
                    // filling the "by type" form
                    $("#lst_archi").append(new Option(data_server[i].archi, data_server[i].archi));
                    // filling the "from maps" form
                    $("#" + data_server[i].site + "_archis").append(data_server[i].archi + '&nbsp;&nbsp;<input type="text" id="' + data_server[i].site + "_" + data_server[i].archi + '_list" value="" class="form-control" style="width:70%" />');
                }
                sites_nodes[data_server[i].network_address] = data_server[i].archi.split(':')[0];
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#txt_notif_msg").html(errorThrows);
            $("#txt_notif").show();
            $("#txt_notif").removeClass("alert-success");
            $("#txt_notif").addClass("alert-danger");
        }
    });
}


/* ******************* */
/*   others function   */
/* ******************* */

//click on add row alias resources
$("#btn_add").click(function () {
    var t = $("#resources_row").clone();
    $(t).find(".number").val(1);
    var t = $("#resources_table").append(t);
    return false;
});


//click on remove row alias resources
function removeRow(btnDelete) {
    if ($("#resources_table tr").length != 1) $(btnDelete).parent().parent().remove();
}

//click on previous button
$("#btn_previous").click(function () {
    $("#form_part2").hide();
    $("#form_part1").show();

    $("#help1").show();
    $("#help2").show();
    $("#help3").hide();

    $("#my_nodes").val([]);
    $("#my_profiles").val([]);
    $("#my_firmware").val([]);

    return false;
});

//click on scheduled button
$("input[name=radioStart]").change(function () {
    if ($(this).val() == "asap") {
        $("#dp1").attr("disabled", "disabled");
        $("#dp1").hide();
        $("#tp1").attr("disabled", "disabled");
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
$("input[name=resources_type]").change(function () {
    if ($(this).val() == "physical") {
        $("#div_resources_type").hide();
        $("#div_resources_map").show();
    } else {
        $("#div_resources_type").show();
        $("#div_resources_map").hide();
    }
});

//click on a node in select  will remove non compatible profiles and firmwares
$("#my_nodes").change(function () {
    var nodearch = sites_nodes[$("#my_nodes").val()[0]];
    if (nodearch == null) nodearch = ($("#my_nodes option:selected").text()).split(':')[0];

    $("#my_profiles optgroup").attr("disabled", "disabled");
    $("#my_profiles option").css("font-style", "italic");
    $("#my_firmwares optgroup").attr("disabled", "disabled");
    $("#my_firmwares option").css("font-style", "italic");
    $("#" + nodearch + "Profiles").removeAttr("disabled");
    $("#" + nodearch + "Profiles option").css("font-style", "");
    $("#" + nodearch + "Firmwares").removeAttr("disabled");
    $("#" + nodearch + "Firmwares option").css("font-style", "");
    // remove selected profile or firmware if non compatible architecture
    var profil_set = $("#my_profiles").val();
    var firmware_set = $("#my_firmwares").val();
    var profileNodearch = ((profil_set in user_profiles) ? user_profiles[profil_set] : null);
    var fwNodearch = ((firmware_set in user_firmwares) ? user_firmwares[firmware_set] : null);
    if (nodearch != profileNodearch) $("#my_profiles option:selected").removeAttr("selected");
    if (nodearch != fwNodearch) $("#my_firmwares option:selected").removeAttr("selected");

    return false;
});

function enableOptions() {
    $("#my_profiles optgroup").removeAttr("disabled");
    $("#my_profiles option").css("font-style", "");
    $("#my_firmwares optgroup").removeAttr("disabled");
    $("#my_firmwares option").css("font-style", "");
}

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
                binary.push({"name": theFile.name, "bin": e.target.result});
                $("#" + user_firmwares[theFile.name] + "Firmwares").append(new Option(theFile.name, theFile.name, false, false));
            };
        })(f);
        if (f.name.indexOf(".elf", f.name.length - 4) != -1) {
            reader.readAsDataURL(f);
            user_firmwares[f.name] = "m3";
        } else {
            reader.readAsText(f);
            user_firmwares[f.name] = "wsn430";
        } // TODO A8
    }
}


//display nodes associations
function displayAssociation() {
    $("#my_assoc").html("");

    json_tmp = rebuildJson(exp_json);


    //display
    for (var k = 0; k < json_tmp.length; k++) {
        if (exp_json.type == "physical") {
            $("#my_assoc").append("<tr><td>" + json_tmp[k].node + "</td><td>" + displayVar(json_tmp[k].profilename) + "</td><td>" + displayVar(json_tmp[k].firmwarename) + "</td><td><a href='#' onClick='removeAssociation(" + k + ")'><img src='img/del.png'></img></a></td></tr>");
        }

        else {
            //$("#my_assoc").append("<tr><td>"+json_tmp[k].node+"</td><td>"+json_tmp[k].profilename+"</td><td>"+json_tmp[k].firmwarename+"</td></tr>");

            for (var z = 0; z < exp_json.nodes.length; z++) {

                if (json_tmp[k].node == exp_json.nodes[z].alias) {
                    var archi = exp_json.nodes[z].properties.archi;
                    var site = (exp_json.nodes[z].properties.site != null) ? exp_json.nodes[z].properties.site : "any";
                    var nbnodes = exp_json.nodes[z].nbnodes;
                    var mobile = (exp_json.nodes[z].properties.mobile != null) ? exp_json.nodes[z].properties.mobile : false;
                    var ntype = mobile ? "mobile" : "fixe";
                    $("#my_assoc").append("<tr><td>" + archi + "/" + site + "/" + nbnodes + "/" + ntype + "</td><td>" + json_tmp[k].profilename + "</td><td>" + json_tmp[k].firmwarename + "</td><td><a href='#' onClick='removeAssociation(" + k + ")'><img src='img/del.png'></img></a></td></tr>");
                }
            }
        }
    }

    // clear all disabled options
    enableOptions();

}

function removeAssociation(index) {
    json_tmp = rebuildJson(exp_json);

    // looking for profile in json_tmp[index]
    if (JSON.stringify(json_tmp[index].profilename) != undefined) {
        for (var i = 0; i < exp_json.profileassociations.length; i++) {
            if (exp_json.profileassociations[i].profilename == json_tmp[index].profilename) {
                for (var j = 0; j < exp_json.profileassociations[i].nodes.length; j++)
                    if (exp_json.profileassociations[i].nodes[j] == json_tmp[index].node)
                        exp_json.profileassociations[i].nodes.splice(j, 1);
                if (exp_json.profileassociations[i].nodes.length == 0)
                    exp_json.profileassociations.splice(i, 1);
            }
        }
        if (exp_json.profileassociations.length == 0)
            delete exp_json.profileassociations;

    }
    // looking for firmware in json_tmp[index]
    if (JSON.stringify(json_tmp[index].firmwarename) != undefined) {
        for (var i = 0; i < exp_json.firmwareassociations.length; i++) {
            if (exp_json.firmwareassociations[i].firmwarename == json_tmp[index].firmwarename) {
                for (var j = 0; j < exp_json.firmwareassociations[i].nodes.length; j++)
                    if (exp_json.firmwareassociations[i].nodes[j] == json_tmp[index].node)
                        exp_json.firmwareassociations[i].nodes.splice(j, 1);
                if (exp_json.firmwareassociations[i].nodes.length == 0)
                    exp_json.firmwareassociations.splice(i, 1);
            }
        }
        if (exp_json.firmwareassociations.length == 0)
            delete exp_json.firmwareassociations;
    }

    if (exp_json.type == "alias") {
        var node = exp_json.nodes[json_tmp[index].node];
        $("#" + node.properties.archi.split(':')[0] + "Nodes").append(new Option(node.properties.archi + "/" + node.properties.site + "/" + node.nbnodes + "/" + ((node.properties.mobile == 1) ? "mobile" : "fixe"), json_tmp[index].node)); // TODO
    } else {
        $("#" + sites_nodes[json_tmp[index].node] + "Nodes").append(new Option(json_tmp[index].node, json_tmp[index].node, false, false));
    }

    displayAssociation();
}


function openMapPopup(site) {
    window.open('maps.php?site=' + site, '', 'resizable=yes, location=no, width=800, height=700, menubar=no, status=no, scrollbars=no, menubar=no');
}


//refresh profile list
function refreshProfiles() {
    $("#my_profiles option").remove();
    getProfiles();
    return false;
}

// get modal form
function loadProfilesModal() {
    $.ajax({
        type: "GET",
        url: "scripts/profiles.php",
        success: function (html) {
            $("#profiles_modal-body").html(html);
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_error_ssh").removeClass("alert-success");
            $("#div_error_ssh").addClass("alert-danger");
            $("#div_error_ssh").html("An error occurred while loading profiles.");
            $("#div_error_ssh").show();
        }
    });
}

</script>

<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-timepicker.js"></script>

<?php include('footer.php') ?>
