<?php
session_start();

if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth'] || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("location: .");
    exit();
}

include("header.php");
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('visualization', '1.0', {'packages': ['corechart']});
    google.load('visualization', '1', {'packages': ['geochart']});
    //google.load('visualization', '1.0', {'packages':['gauge']});
    // Set a callback to run when the Google Visualization API is loaded.
    // google.setOnLoadCallback(drawChart);
</script>

<div class="container">

<div class="row">
    <div class="col-md-12">
        <h2>Statistics</h2>

        <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
        <div class="alert alert-danger" id="div_msg" style="display:none"></div>
    </div>
</div>

<div class="row" id="div_stats" style="display:none">
    <div class="col-md-6">
        <h3>Users</h3>

        <span class="glyphicon glyphicon-user"></span> <span id="usersTotal" class="badge badge-info"></span> users
        (with <span id="usersAdmin" class="badge"></span> admins and <span id="usersPending"
                                                                           class="badge badge-warning"></span> pending)
        <a href="scripts/download_users.php" class="btn btn-default">Download CSV</a>

        <div class="row">
            <h4>Country</h4>

            <div class="col-md-5">
                <table id="country" class="table table-condensed table-striped table-bordered"></table>
            </div>
        </div>

        <div id="chart_div"></div>
        <div id="chart_divGeo"></div>
    </div>

    <div class="col-md-6">
        <h3>Experiments</h3>
        <span class="glyphicon glyphicon-cog"></span> <span id="expTotal" class="badge badge-info"></span> experiments
        (with <span id="expRunning" class="badge">&nbsp;</span> running and <span id="expUpcoming" class="badge"></span>
        upcoming)
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-md-6">
                <select id="lst_exp" size="10" class="form-control">
                    <optgroup label="Running" id="Running"></optgroup>
                    <optgroup label="Upcoming" id="Upcoming"></optgroup>
                </select>
            </div>
            <div class="col-md-3">
                <br/><br/>
                <button id="btnDetails" class="btn btn-primary">Details</button>
                <br/><br/>
                <button id="btnCancel" class="btn btn-danger">Cancel</button>
            </div>
        </div>
        <hr/>

        <h3>Nodes</h3>
        <!-- <i class="icon-cog"></i> <span id="nodesTotal" class="label"></span> nodes (with <span id="nodesFree" class="label label-success">&nbsp;</span> alive and <span id="nodesUnavailable" class="label label-info"></span> suspected or absent) -->
        <div class="accordion" id="accordion2">
            <div class="accordion-group" style="border:0">
                <div class="accordion-heading">
                    <span class="glyphicon glyphicon-alt"></span> <span id="nodesTotal" class="badge badge-info"></span>
                    nodes (with <span id="nodesFree" class="badge">&nbsp;</span> alive, <span id="nodesBusy"
                                                                                              class="badge"></span> busy
                    and <span id="nodesUnavailable" class="badge badge-warning"></span> unavailable)
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"
                       style="color:#333;text-decoration:none">
                        <button class="btn btn-default"><b class="caret"></b></button>
                    </a>
                </div>
                <div id="collapseOne" class="accordion-body collapse">
                    <div class="accordion-inner" id="sitesNodesDetails"></div>
                </div>
            </div>
        </div>

        <hr/>

        <!--<h3>System</h3>
        <h4>Used on /senslab/experiments</h4>

        <div id="chart_divGauge"></div>
        -->
    </div>
</div>


<script type="text/javascript">

var graph = [];
var sitesNodesBusy = {};

$(document).ready(function () {

    $("#admin").addClass("active");
    $("#admin_stats").addClass("active");

    $(document).ajaxStart(function () {
        $("#loader").show();
    });
    $(document).ajaxStop(function () {
        $("#loader").hide();
    });


    /* Retrieve users infos */
    $.ajax({
        url: "/rest/admin/users",
        type: "GET",
        dataType: "json",
        success: function (data) {
            var admin = 0;
            var pending = 0;
            var country = {};

            var i = 0;
            for (i = 0; i < data.length; i++) {
                if (data[i].admin) admin++;

                if (!data[i].validate) pending++;

                if (country[data[i].country] == undefined) country[data[i].country] = 1;
                else country[data[i].country] = country[data[i].country] + 1;

            }
            $('#usersTotal').html(data.length);
            $('#usersAdmin').html(admin);
            $('#usersPending').html(pending);

            for (c in country) {
                var ct = [c, country[c]];
                graph.push(ct);
                $("#country").append("<tr><td>" + c + "</td><td>" + country[c] + "</td></tr>");
            }

            drawChart();

        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_msg").show();
            $("#div_msg").html("An error occurred while retrieving user list");
        }
    });


    /* Retrieve experiments infos */
    $.ajax({
        url: "/rest/admin/experiments?total",
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
            $("#div_msg").show();
            $("#div_msg").html("An error occurred while retrieving experiment list");
        }
    });


    $.when( //a callback for complete busy nodes after 2 ajax requests
            $.ajax({ // get nodes list
                url: "/rest/admin/resourcesproperties",
                type: "GET",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                data: "",
                success: function (data) {

                    var sites = new Object();

                    var total = 0;
                    var free = 0;
                    var unavailable = 0;

                    for (var i in data) {
                        if (!sites[data[i].site]) {
                            sites[data[i].site] = new Object();
                            sites[data[i].site]["total"] = 0;
                            sites[data[i].site]["free"] = 0;
                            sites[data[i].site]["unavailable"] = 0;
                        }
                        total++;
                        sites[data[i].site]["total"]++;
                        if (data[i].state == "Alive") {
                            free++;
                            sites[data[i].site]["free"]++;
                        } else if (data[i].state == "Suspected" || data[i].state == "Absent") {
                            unavailable++;
                            sites[data[i].site]["unavailable"]++;
                        }
                    }
                    $("#nodesTotal").text(total);
                    $("#nodesFree").text(free);
                    $("#nodesUnavailable").text(unavailable);

                    for (var j in sites) {
                        $("#sitesNodesDetails").append('<span class="glyphicon glyphicon-ok"></span> <b>' + j + '</b> <span class="badge badge-info">' + sites[j]["total"] + '</span> nodes (with <span class="badge">' + sites[j]["free"] + '</span> alive, <span class="badge" id="' + j + 'Busy">0</span> busy and <span class="badge badge-warning">' + sites[j]["unavailable"] + '</span> unavailable)<br/>');
                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#div_msg").html("An error occurred while retrieving nodes list");
                    $("#div_msg").show();
                }
            }),
            $.ajax({    //get running exps
                url: "/rest/admin/experiments?state=Running,Upcoming,Launching,Waiting&limit=20&offset=0",
                type: "GET",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                data: "",
                success: function (data) {

                    var nodesBusy = 0;

                    for (var i = 0; i < data.items.length; i++) {

                        if (data.items[i].state == "Running") {
                            $("#Running").append(new Option(data.items[i].id + "/" + data.items[i].owner, data.items[i].id, true, true));
                        }
                        else {
                            $("#Upcoming").append(new Option(data.items[i].id + "/" + data.items[i].owner, data.items[i].id, true, true));
                        }

                        if (data.items[i].state == "Running") {
                            nodesBusy = nodesBusy + data.items[i].resources.length;

                            //check the plateform resources busy
                            for (var res = 0; res < data.items[i].resources.length; res++) {
                                var regex = /(\w*)\.(\w*)\.iot-lab\.info/;
                                match = regex.exec(data.items[i].resources[res]);

                                var sitename = match[2];

                                if (sitesNodesBusy[sitename] == undefined) {
                                    sitesNodesBusy[sitename] = 1;
                                }
                                else {
                                    sitesNodesBusy[sitename]++;
                                }
                            }
                        }
                    }
                    $("#nodesBusy").text(nodesBusy);

                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#div_msg").html("An error occurred while retrieving experiment list");
                    $("#div_msg").show();
                }
            })
        ).then(function () {

            $("#div_stats").show();
            for (site in sitesNodesBusy) {
                $("#" + site + "Busy").text(sitesNodesBusy[site]);
            }
        });

    $("#btnCancel").click(function () {

        var id = $('#lst_exp').find(":selected").val();

        if (confirm("Cancel Experiment?")) {
            $.ajax({
                url: "/rest/admin/experiments/" + id,
                type: "DELETE",
                contentType: "application/json",
                dataType: "text",

                success: function (data) {
                    alert("Cancel ok");
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    alert("error: " + errorThrows)
                }
            });
        }
    });


    $("#btnDetails").click(function () {

        var id = $('#lst_exp').find(":selected").val();
        window.location.href = "admin_exp_details.php?id=" + id;
    });


});

function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Country');
    data.addColumn('number', 'Users');
    data.addRows(graph);

    // Set chart options
    var options = {'title': 'Users Country',
        'width': 400,
        'height': 300};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);

    //Geo graph
    //var dataGeo = google.visualization.arrayToDataTable(graph);
    var chartGeo = new google.visualization.GeoChart(document.getElementById('chart_divGeo'));
    chartGeo.draw(data, options);

    //Gauge graph
    /*
     var optionsGauge = {
     width: 400, height: 120,
     redFrom: 90, redTo: 100,
     yellowFrom:75, yellowTo: 90,
     minorTicks: 5
     };

     var data = google.visualization.arrayToDataTable([
     ['Label', 'Value'],
     ['Disk',
    <?php echo (int)(100-(disk_free_space("/senslab/experiments")*100 / disk_total_space("/senslab/experiments"))) ?>],
     ]);
     var chartGauge = new google.visualization.Gauge(document.getElementById('chart_divGauge'));
     chartGauge.draw(data, optionsGauge);*/
}

</script>

<?php include('footer.php') ?>
