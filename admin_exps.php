<?php
session_start();

if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth'] || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("location: .");
    exit();
}

include("header.php");

$title = "";
$dashboard = "Admin";
$request_total = "/rest/admin/experiments?total";
$request_profiles = "/rest/admin/profiles";
$request_exps = "\"\"";
if (isset($_GET['user'])) {
    $title = " of user " . $_GET['user'];
    $dashboard = $_GET['user'];
    $request_total .= "&user=" . $_GET['user'];
    $request_profiles .= "?user=" . $_GET['user'];
    $request_exps = 'function ( aoData ) { aoData.push( { "name": "user", "value": "' . $_GET['user'] . '" } ); }';
}
?>

<div class="container">

    <div class="row">
        <div class="col-md-8">
            <h2>Experiment List <?php echo $title; ?></h2>

            <div class="alert alert-danger" id="div_msg" style="display:none"></div>
            <table id="tbl_exps" class="table table-striped table-condensed" style="display:none">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Owner</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Duration</th>
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
            <h2><?php echo $dashboard; ?> dashboard</h2>

            <p><span class="glyphicon glyphicon-cog"></span> Experiments: <span id="expTotal">&nbsp;</span></p>
            <ul>
                <li><span id="expRunning" class="badge badge-success">&nbsp;</span> running</li>
                <li><span id="expUpcoming" class="badge badge-info">&nbsp;</span> upcoming</li>
                <li><span id="expTerminated" class="badge">&nbsp;</span> terminated</li>
            </ul>

            <h2>Search for an experiment</h2>

            <div class="form-horizontal">
                <div class="col-md-6"><input type="text" class="form-control" id="expNum"/></div>
                <div class="col-md-1"><input class="btn btn-default" type="button" value="Show details"
                                             onClick='window.location.href="admin_exp_details.php?id="+document.getElementById("expNum").value;'/>
                </div>
            </div>
        </div>
    </div>


</div> <!-- container -->

<link href="css/datatable.css" rel="stylesheet">
<link href="css/datatable-custom.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>

<script type="text/javascript">

    var oTable;

    $(document).ready(function () {

        $("#admin").addClass("active");
        $("#admin_exps").addClass("active");


        $(document).ajaxStart(function () {
            $("#loader").show();
        });
        $(document).ajaxStop(function () {
            $("#loader").hide();
        });


        // Retrieve experiments total
        $.ajax({
            url: "<?php echo $request_total; ?>",
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
                $("#div_msg").removeClass("alert-success");
                $("#div_msg").addClass("alert-danger");
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving the experiment total");
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
            "sAjaxSource": "scripts/admin_exp_list.php",
            "fnServerParams": <?php echo $request_exps; ?>,
            "bPaginate": true,
            "aoColumns": [
                {"mDataProp": "id" },
                {"mDataProp": "owner" },
                {"mDataProp": "name" },
                {"mDataProp": "date",
                    "fnRender": function (obj) {
                        var date = obj.aData['date'];
                        myDate = new Date(date * 1000);
                        if (myDate == "Invalid Date")
                            myDate = "As soon as possible";
                        else myDate = myDate.toGMTString();
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
                        } else if (state == "Waiting" || state == "Launching" || state == "Suspended"
                            || state == "Hold" || state == "toAckReservation" || state == "toLaunch") { // upcomming
                            state = "<span class='label label-state label-info'>" + state + "</span>";
                        }
                        return state;
                    }
                }
            ],
            "sAjaxDataProp": "items",
            "sPaginationType": "bootstrap",
            "bLengthChange": true,
            "bFilter": true,
            "bSort": false,
            "bInfo": true,
            "bAutoWidth": false,
            "fnInitComplete": function (oSettings, json) {
                $('#tbl_exps tbody tr').each(function () {
                    this.setAttribute('title', 'Click to see details');
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
            window.location.href = "admin_exp_details.php?id=" + aData['id'];
        });

        $('#div_msg').hide();
        $('#tbl_exps').show();

        // filters by state for experiments list 
        $('.dataTables_filter').html('<label>Filter: <select id="filter_by_state" style="margin-top:7px;"><option value="All">All</option><option value="Running">Running</option><option value="Upcoming">Upcoming</option><option value="Terminated">Terminated</option></select></label>');
        $('#filter_by_state').change(function () {
            oTable.fnFilter($(this).val());
        });

        // search for an experiment input
        $('#expNum').bind('keyup', function (e) {
            if (e.keyCode == 13) window.location.href = "admin_exp_details.php?id=" + $(this).val();
        });

    });

</script>
<?php include('footer.php') ?>
