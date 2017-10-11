<?php
session_start();

if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php");
?>

<div class="container" text-align="top">


    <div class="row">
        <div class="col-md-8">

            <h2>Experiment List</h2>

            <div class="alert alert-danger" id="div_msg" style="display:none"></div>
            <table id="tbl_exps" class="table table-condensed table-striped" style="display:none">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Duration (min)</th>
                    <th>Node(s)</th>
                    <th>State</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


            <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>

        </div>

        <div class="col-md-4">
            <h2>Personal dashboard</h2>

            <p><span class="glyphicon glyphicon-cog"></span> Experiments: <span id="expTotal">&nbsp;</span></p>
            <ul>
                <li><span id="expRunning" class="badge badge-success">&nbsp;</span> running</li>
                <li><span id="expUpcoming" class="badge badge-info">&nbsp;</span> upcoming</li>
                <li><span id="expTerminated" class="badge">&nbsp;</span> terminated</li>
            </ul>
            <p><span class="glyphicon glyphicon-th"></span> Profiles: <span id="nb_profiles">&nbsp;</span></p>
            <!--<p><i class="icon-home"></i> Home's quota: 60% (600/1000Mo)
              <div class="progress" style="width:200px">
                <div class="bar" style="width: 60%;"></div>
              </div>
            </p>-->

            <div class="alert alert-info">
                <img src="img/help.png"/> Click on an experiment to manage it or click <b>New Experiment</b> to start a
                new one.
            </div>

        </div>

    </div>

</div> <!-- container -->

<link href="css/datatable.css" rel="stylesheet">
<link href="css/datatable-custom.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/datatable.js"></script>
<script type="text/javascript">

var oTable;
var dateSrv =
<?php echo time(); ?>*
1000; // server date in milliseconds
var isPageBeingRefreshed = false;

window.onbeforeunload = function () {
    isPageBeingRefreshed = true;
};

$(document).ready(function () {

    $(document).ajaxStart(function () {
        $("#loader").show();
    });
    $(document).ajaxStop(function () {
        $("#loader").hide();
    });


    $("#dashboard").addClass("active");
    $("#dashboard2").addClass("active");


    // Retrieve experiments total
    $.ajax({
        url: "/rest/experiments?total",
        type: "GET",
        dataType: "json",
        success: function (data) {
            var total = data.running + data.upcoming + data.terminated;
            $("#expRunning").text(data.running);
            $("#expUpcoming").text(data.upcoming);
            $("#expTerminated").text(data.terminated);
            $("#expTotal").text(total);
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {

            if (!XMLHttpRequest.getAllResponseHeaders()) {
                XMLHttpRequest.abort();
                if (isPageBeingRefreshed) {
                    return; // not an error
                }
            }

            $("#div_msg").removeClass("alert-success");
            $("#div_msg").addClass("alert-danger");
            $("#div_msg").show();
            $("#div_msg").html("An error occurred while retrieving your experiment list: " + errorThrows);
        }
    });

    // Retrieve profiles total
    $.ajax({
        url: "/rest/profiles?total",
        type: "GET",
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            $("#nb_profiles").text(data.total);
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_msg").removeClass("alert-success");
            $("#div_msg").addClass("alert-danger");
            $("#div_msg").show();
            $("#div_msg").html("An error occurred while retrieving your profile list: " + errorThrows);
        }
    });

    // Manage experiment list
    oTable = $('#tbl_exps').dataTable({
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "bProcessing": false,
        "bServerSide": true,
        "oLanguage": {
            "sInfoFiltered": ""
        },
        "sAjaxSource": "scripts/exp_list.php",
        "aoColumns": [
            {"mDataProp": "id" },
            {"mDataProp": "name" },
            {"mDataProp": "date",
                "fnRender": function (obj) {
                    var date = obj.aData['date'];
                    myDate = new Date(date * 1000);

                    if (myDate == "Invalid Date")
                        myDate = "As soon as possible";
                    else myDate = myDate.toLocaleString();

                    return myDate;
                }
            },
            {"mDataProp": "duration",
                "fnRender": function (obj) {
                    return Math.round(obj.aData['duration'] / 60);
                }
            },
            {"mDataProp": "nb_resources" },
            {"mDataProp": "state",
                "fnRender": function (obj) {
                    var state = obj.aData['state'];
                    if (state == "Error") { // terminated error
                        state = "<span class='label label-state label-danger'>" + state + "</span>";
                    } else if (state == "Terminated") { // terminated OK
                        state = "<span class='label label-state label-default'>" + state + "</span>";
                    } else if (state == "Running" || state == "Finishing" || state == "Resuming" || state == "toError") { // running
                        state = "<span class='label label-state label-success'>" + state + "</span>";
                       /* var dateExp = new Date(obj.aData['gmtdate']).getTime(); // start date in milliseconds
                        var durationExp = obj.aData['duration'] * 60000; // experiment duration in milliseconds
                        setTimeout(function () {
                            checkState(obj.aData['id'], 1, dateExp, durationExp);
                        }, durationExp - (dateSrv - dateExp));
                        console.log(obj.aData['id'] + " is Running; refresh in " + (durationExp - (dateSrv - dateExp)) / 1000 + " s.");*/
                    } else if (state == "Waiting" || state == "Launching" || state == "Suspended"
                        || state == "Hold" || state == "toAckReservation" || state == "toLaunch") { // upcomming
                        state = "<span class='label label-info'>" + state + "</span>";
                        /*var dateExp = new Date(obj.aData['gmtdate']).getTime(); // start date in milliseconds
                        var durationExp = obj.aData['duration'] * 60000; // experiment duration in milliseconds
                        setTimeout(function () {
                            checkState(obj.aData['id'], 0, dateExp, durationExp);
                        }, dateExp - dateSrv);
                        console.log(obj.aData['id'] + " is Waiting; refresh in " + (dateExp - dateSrv) / 1000 + " s.");*/
                    }
                    return state;
                }
            }
        ],
        "sAjaxDataProp": "items",
        "bPaginate": true,
        "sPaginationType": "bootstrap",
        "bLengthChange": true,
        "bFilter": true,
        "bSort": false,
        "bInfo": true,
        "bAutoWidth": false,
        "fnInitComplete": function (oSettings, json) {
            $('#tbl_exps tbody tr').each(function () {
                if(aData == undefined) return;
                this.setAttribute('title', 'Click to see details');
                var aData = oTable.fnGetData(this);
                this.setAttribute('id', aData['id']);
                
            });
            $('#tbl_exps tbody tr[title]').tooltip({
                "delay": 0,
                "track": true,
                "fade": 250,
                "placement": 'right',
                "container": 'body'
            });
        }
    });

    $('#tbl_exps tbody').on('click', 'tr', function () {
        var aData = oTable.fnGetData(this);
        window.location.href = "exp_details.php?id=" + aData['id'];
    });
    $('#div_msg').hide();
    $('#tbl_exps').show();

    // filters by state for experiments list
    $('.dataTables_filter').html('<label>Filter: <select id="filter_by_state" style="margin-top:7px;"><option value="All">All</option><option value="Running">Running</option><option value="Upcoming">Upcoming</option><option value="Terminated">Terminated</option></select></label>');
    $('#filter_by_state').change(function () {
        oTable.fnFilter($(this).val());
    });

});

function checkState(id, currentState, date, duration) {
    // Retrieve exp state
    $.ajax({
        url: "/rest/experiments/" + id + "?state",
        type: "GET",
        dataType: "JSON",
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            // state : 0 = upcomming ; 1 = running ; 2 = terminated
            var newState = 2;
            var state = data['state'];
            if (state == "Waiting" || state == "Launching" || state == "Suspended" || state == "Hold" || state == "toAckReservation" || state == "toLaunch") newState = 0;
            else if (state == "Running" || state == "Finishing" || state == "Resuming" || state == "toError") newState = 1;

            if (currentState == newState) setTimeout(function () {
                checkState(id, currentState, date, duration);
            }, 2000); // no state change, still upcomming or running, refresh again
            else if (currentState == 0 && newState == 1) { // state change from upcomming to running, refresh again
                setTimeout(function () {
                    checkState(id, 1, date, duration);
                }, duration - 2000);
                /* TODO verif */
                console.log(id + " is now running; refresh in " + (duration - 2000) / 1000 + " s.");
                $("#" + id + " td span").removeClass("label-info");
                $("#" + id + " td span").addClass("label-success");
                /* change badges in Personal Dashboard */
                changeBadges("expUpcoming", "expRunning");
            } else { // state change from upcomming or running to terminated or error, stop refreshing
                $("#" + id + " td span").removeClass("label-info");
                $("#" + id + " td span").removeClass("label-success");
                $("#" + id + " td span").removeClass("label-danger");
                if (state == "Terminated")  $("#" + id + " td span").addClass("label-default");
                else if (state == "Error")  $("#" + id + " td span").addClass("label-danger");
                /* change badges in Personal Dashboard */
                changeBadges((currentState == 0 ? "expUpcoming" : "expRunning"), "expTerminated");
            }
            $("#" + id + " td span").html(state);
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_msg").removeClass("alert-success");
            $("#div_msg").addClass("alert-danger");
            $("#div_msg").show();
            $("#div_msg").html("An error occurred while refreshing experiment states: " + errorThrows);
        }
    });
}

function changeBadges(fromState, toState) { // remove 1 exp from fromState badge to toState badge
    var nbExp = $("#" + fromState + "").text();
    //nbExp--;
    $("#" + fromState + "").text(--nbExp);

    nbExp = $("#" + toState + "").text();
    //nbExp++;
    $("#" + toState + "").text(++nbExp);
}


</script>
<?php include('footer.php') ?>
