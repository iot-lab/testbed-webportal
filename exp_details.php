<?php
session_start();

if (!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php");
?>

<link rel="stylesheet" href="css/datepicker.css" type="text/css"/>
<link rel="stylesheet" href="css/timepicker.css" type="text/css"/>
<link rel="stylesheet" href="css/datetimepicker-custom.css" type="text/css"/>

<div class="container" text-align="top">


    <div class="row">

        <h2>Experiment Details</h2>

        <div class="alert alert-danger" id="div_msg" style="display:none"></div>


        <!--  MODAL WINDOW FOR EXP STATE -->

        <div id="expState" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
             aria-labelledby="myModalLabel">
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
                        <a href="#" class="btn btn-default" data-dismiss="modal"
                           onClick="redirectDashboard();">Close</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <!--  MODAL WINDOW FOR EXP RELOAD -->

        <div id="expReload" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
             aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>Reload</h3>
                    </div>
                    <div class="modal-body" style="height:200px;">
                        <div class="form-group row">
                            <label class="col-md-3 control-label" for="txt_start"
                                   style="padding-top:8px;">Start:</label>

                            <div class="col-md-8">
                                <label class="radio inline"><input type="radio" id="radioStart" name="radioStart"
                                                                   value="asap" checked/>As soon as possible</label>
                                <label class="radio inline"><input type="radio" id="radioStart" name="radioStart"
                                                                   value="scheduled"/>Scheduled</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8 col-md-offset-3">
                                <div class="col-md-4" style="margin:0px"><input type="text" class="form-control"
                                                                                value="" id="dp1" name="dp1"
                                                                                disabled="disabled"
                                                                                style="display:none;z-index:1041;">
                                </div>
                                <div class="col-md-4"><input class="dropdown-timepicker form-control"
                                                             data-provide="timepicker" type="text" id="tp1" name="tp1"
                                                             disabled="disabled" style="display:none;z-index:1041;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label" for="txt_duration"
                                   style="padding-top:8px;padding-right:0px;">Duration (minutes):</label>

                            <div class="col-md-2">
                                <input id="txt_duration" name="duration" type="number" class="form-control" value="20"
                                       required="required" min="0">
                            </div>
                        </div>
                        <br/>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal" onClick="submitReload();">Reload</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <div id="detailsExp">
            <p id="detailsExpSummary"></p>

            <p id="expButtons">
                <button class="btn btn-danger" id="btnCancel" onclick="cancelExperiment()">Cancel</button>
                <a href="scripts/exp_download_data.php?id=<?php echo $_GET['id'] ?>" class="btn btn-default"
                   id="btnDownload">Download</a>
                <button class="btn btn-default" id="btnReload">Reload</button>
            </p>

            <table class="table table-striped table-bordered table-condensed" style="width:800px" id="tblNodes">
                <thead>
                <tr>
                    <th></th>
                    <th>Node</th>
                    <th>Profile</th>
                    <th>Firmware</th>
                    <th>Deployment</th>
                </tr>
                </thead>
                <tbody id="detailsExpRow">
                </tbody>
            </table>


            <form id="frmActions">
                <p><a href="javascript:selectAll();">Select All</a> - <a href="javascript:unSelectAll();">Unselect
                        All</a></p>

                <b>Actions on selected nodes: </b>

                <div class="row">
                    <div class="col-md-3">
                        <select id="action" class="form-control">
                            <option value="start">Start</option>
                            <option value="start" data-battery="battery">Start (battery)</option>
                            <option value="stop">Stop</option>
                            <option value="reset">Reset</option>
                            <option value="update">Update</option>
                        </select>

                        <div id="firmware" style="display:none"><label for="files">Firmware: <input type="file"
                                                                                                    id="files"
                                                                                                    name="files[]"/></label>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <button id="btn_send" class="btn btn-default" type="submit">Send</button>
                    </div>
                </div>

                <div id="stateSuccess" class="alert alert-success" style="display:none;margin-top:2px:"></div>
                <div id="stateFailure" class="alert alert-failure" style="display:none;margin-top:2px:"></div>

            </form>
            <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
            <br/>
        </div>

    </div>
    <!-- row -->

</div> <!-- container -->

<script type="text/javascript">

var json_exp = [];
var id = <?php echo $_GET['id']?>;
var expState = "";
var binary = [];
var boundary = "AaB03x";
var scheduled = false;
var exp_name = "";

$(document).ready(function () {
    $("#frmActions").hide();
    $("#expButtons").hide();
    $("#tblNodes").hide();

    $(document).ajaxStart(function () {
        $("#loader").show();
    });
    $(document).ajaxStop(function () {
        $("#loader").hide();
    });

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

    /* retrieve experiment details */
    $.ajax({
        url: "/rest/experiments/" + id,
        type: "GET",
        data: {},
        dataType: "json",
        success: function (data) {

            if (data.name != null)
                exp_name = data.name;

            expState = data.state;
            if (expState == "Running" || expState == "Waiting" || expState == "Launching") {
                $("#btnCancel").attr("disabled", false);
                $("#btnReload").attr("disabled", true);
                $("input[name=radioStart]").attr("disabled", true);
            }
            else {
                $("#btnCancel").attr("disabled", true);
                $("#btnReload").attr("disabled", false);
                $("input[name=radioStart]").attr("disabled", false);
            }


            $("#detailsExpSummary").html("<b>Experiment:</b> <a href=\"monika?job=" + id + "\">" + id + "</a><br/>");
            $("#detailsExpSummary").append("<b>State:</b> " + expState + "<br/>");
            $("#detailsExpSummary").append("<b>Name:</b> " + exp_name + "<br/>");
            $("#detailsExpSummary").append("<b>Duration (min):</b> " + data.duration + "<br/>");
            $("#detailsExpSummary").append("<b>Number of nodes:</b> ");

            $("#expButtons").show();

            json_exp = rebuildJson(data, true);

            //display
            $("#detailsExpRow").html("");

            var nbTotalNodes = 0;
            if (data.type == "physical") nbTotalNodes = data.nodes.length;
            var nodename = "";
            var checkbox = "";
            var state = "";

            for (var k = 0; k < json_exp.length; k++) {
                if (data.type == "physical") {
                    nodename = json_exp[k].node;
                    checkbox = "<input type=\"checkbox\" name=\"option1\" value=\"" + json_exp[k].node + "\">";
                    state = "<td style='" + displayVar(json_exp[k].style) + "'>" + displayVar(json_exp[k].state) + "</td></tr>";
                } else {
                    var archi = data.nodes[k].properties.archi;

                    var site = ((data.nodes[k].properties.site != null) ? data.nodes[k].properties.site : "any");
                    var ntype = ((data.nodes[k].properties.mobile != null && data.nodes[k].properties.mobile == "1") ? "mobile" : "fixe");
                    var nbnodes = data.nodes[k].nbnodes;
                    nbTotalNodes += nbnodes;

                    nodename = archi + "/" + site + "/" + nbnodes + "/" + ntype;
                    checkbox = "";
                    state = "<td>Not available</td>";
                }
                $("#detailsExpRow").append("<tr><td>" + checkbox + "</td><td>" + nodename + "</td><td>" + displayVar(json_exp[k].profilename) + "</td><td>" + displayVar(json_exp[k].firmwarename) + "</td>" + state + "</tr>");
            }
            $("#detailsExpSummary").append(nbTotalNodes + "<br/>");
            if (expState == "Running") {
                $("input[name=option1]").attr("disabled", false);
                $("#frmActions").show();
            } else {
                $("input[name=option1]").attr("disabled", true);
                $("#frmActions").hide();
            }
            $("#tblNodes").show();
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#detailsExpSummary").html("An error occurred while retrieving experiment #" + id + " details");
            $('#details_modal').modal('show');
        }
    });


    /* actions list change */
    $("#action").change(function () {
        if ($("#action").val() == "update") {
            $("#firmware").show();
        }
        else {
            $("#firmware").hide();
        }
    });


    /* actions on nodes */
    $("#frmActions").bind("submit", function (e) {
        e.preventDefault();

        $("#stateFailure").hide();
        $("#stateSuccess").hide();

        var command = $("#action option:selected").val();

        var battery = "";
        if ($("#action option:selected").attr("data-battery") == "battery") {
            battery = "&battery=true";
        }


        var lnodes = [];
        $("#tblNodes :checked").each(function () {
            lnodes.push($(this).val());
        });

        if ($("#action").val() == "update") {
            //JSON
            var datab = "";
            datab += "--" + boundary + '\r\n';
            datab += 'Content-Disposition: form-data; name="' + exp_name + '.json"; filename="' + exp_name + '.json"\r\n';
            datab += 'Content-Type: application/json\r\n\r\n';
            datab += JSON.stringify(lnodes) + '\r\n\r\n';
            //datab += "--" + boundary + '\r\n';

            //firmware
            datab += "--" + boundary + '\r\n';
            datab += 'Content-Disposition: form-data; name="' + binary[0].name + '"; filename="' + binary[0].name + '"\r\n';
            datab += 'Content-Type: application/octet-stream\r\n\r\n';
            datab += binary[0].bin + '\r\n';


            //add json
            datab += "--" + boundary + '--';

            $.ajax({
                type: "POST",
                dataType: "json",
                data: datab,
                url: "/rest/experiments/" + id + "/nodes?update",
                contentType: "multipart/form-data; boundary=" + boundary,

                success: function (data) {
                    if (data["0"]) {
                        $("#stateSuccess").html("<b>Update</b> successful for node(s): " + JSON.stringify(data["0"]));
                        $("#stateSuccess").show();
                    }
                    if (data["1"]) {
                        $("#stateFailure").html("<b>Update</b> failed for node(s): " + JSON.stringify(data["1"]));
                        $("#stateFailure").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#stateFailure").html(textStatus + " : " + errorThrows + " : " + XMLHttpRequest.responseText);
                    $("#stateFailure").show();
                }
            });
            binary = [];
        }
        else {
            $.ajax({
                type: "POST",
                dataType: "json",
                data: JSON.stringify(lnodes),
                contentType: "application/json; charset=utf-8",
                url: "/rest/experiments/" + id + "/nodes?" + command + "" + battery,
                success: function (data) {
                    if (data["0"]) {
                        $("#stateSuccess").html("<b>" + command + "</b> successful for node(s): " + JSON.stringify(data["0"]));
                        $("#stateSuccess").show();
                    }
                    if (data["1"]) {
                        $("#stateFailure").html("<b>" + command + "</b> failed for node(s): " + JSON.stringify(data["1"]));
                        $("#stateFailure").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#state").html(textStatus + " : " + errorThrows + " : " + XMLHttpRequest.responseText);
                    $("#state").removeClass("alert-success");
                    $("#state").addClass("alert-danger");
                    $("#state").show();
                }
            });
        }
        // return false;
    });

    //file upload event
    document.getElementById('files').addEventListener('change', handleFileSelect, false);


    $("#btnReload").click(function (e) {
        $("#expReload").modal("show");
    });

});


function cancelExperiment() {
    if (confirm("Cancel Experiment?")) {

        $.ajax({
            url: "/rest/experiments/" + id,
            type: "DELETE",
            contentType: "application/json",
            dataType: "text",

            success: function (data) {
                $("#btnCancel").attr("disabled", true);
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                alert("error: " + errorThrows);
            }
        });
    }
}

function selectAll() {
    $("#tblNodes :checkbox").each(function () {
        $(this).attr('checked', true);
    });
}

function unSelectAll() {
    $("#tblNodes :checkbox").each(function () {
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

                binary.push({
                    "name": theFile.name,
                    "bin": e.target.result
                });
            };
        })(f);
        if (f.name.indexOf(".elf", f.name.length - 4) != -1) reader.readAsDataURL(f);
        else reader.readAsText(f);
    }
}

function submitReload() {
    var exp_json = {};
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

    $.ajax({
        type: "POST",
        dataType: "text",
        data: JSON.stringify(exp_json),
        contentType: "application/json; charset=utf-8",
        url: "/rest/experiments/" + id + "?reload",
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
}


</script>

<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-timepicker.js"></script>

<?php include('footer.php') ?>
