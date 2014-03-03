<?php
session_start();

if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth'] || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("location: .");
    exit();
}

include("header.php");
?>

<div class="container">


    <div class="row">

        <h2>Admin Experiment Details</h2>

        <div id="detailsExp">
            <div class="alert alert-danger" id="div_msg" style="display:none"></div>
            <p id="detailsExpSummary"></p>

            <p id="expButtons">
                <button class="btn btn-danger" id="btnCancel" onclick="cancelExperiment()">Cancel</button>
            </p>

            <table class="table table-striped table-bordered table-condensed" style="width:500px" id="tblNodes">
                <thead>
                <tr>
                    <th>Node</th>
                    <th>Profile</th>
                    <th>Firmware</th>
                    <th>Deployment</th>
                </tr>
                </thead>
                <tbody id="detailsExpRow">
                </tbody>
            </table>
            <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
        </div>
    </div>


</div> <!-- container -->

<script type="text/javascript">

    var json_exp = [];
    var withAssoc = false;
    var id = <?php echo $_GET['id']?>;
    var state = "";

    $(document).ready(function () {

        $("#admin").addClass("active");

        $("#expButtons").hide();
        $("#tblNodes").hide();


        $(document).ajaxStart(function () {
            $("#loader").show();
        });
        $(document).ajaxStop(function () {
            $("#loader").hide();
        });

        /* Retrieve experiment details */
        $.ajax({
            url: "/rest/admin/experiments/" + id,
            type: "GET",
            data: {},
            dataType: "json",
            success: function (data) {

                var exp_name = "";
                if (data.name != null)
                    exp_name = data.name;

                state = data.state;
                if (state == "Running" || state == "Waiting") {
                    $("#btnCancel").attr("disabled", false);
                }
                else {
                    $("#btnCancel").attr("disabled", true);
                }


                $("#detailsExpSummary").html("<b>Experiment:</b> " + id + "<br/>");
                $("#detailsExpSummary").append("<b>Owner:</b> " + data.owner + "<br/>");
                $("#detailsExpSummary").append("<b>Name:</b> " + exp_name + "<br/>");
                $("#detailsExpSummary").append("<b>Duration:</b> " + data.duration + "<br/>");
                $("#detailsExpSummary").append("<b>Number of Nodes:</b> ");

                $("#expButtons").show();

                json_exp = rebuildJson(data, true);

                //display
                $("#detailsExpRow").html("");

                var nbTotalNodes = 0;
                if (data.type == "physical") nbTotalNodes = data.nodes.length;
                var nodename = "";
                var state = "";

                for (var k = 0; k < json_exp.length; k++) {

                    if (data.type == "physical") {
                        nodename = json_exp[k].node;
                        state = "<td style='" + displayVar(json_exp[k].style) + "'>" + displayVar(json_exp[k].state) + "</td></tr>";
                    } else {

                        var archi = data.nodes[k].properties.archi;

                        var site = ((data.nodes[k].properties.site != null) ? data.nodes[k].properties.site : "any");
                        var ntype = ((data.nodes[k].properties.mobile != null && data.nodes[k].properties.mobile == "1") ? "mobile" : "fixe");
                        var nbnodes = data.nodes[k].nbnodes;
                        nbTotalNodes += nbnodes;

                        nodename = archi + "/" + site + "/" + nbnodes + "/" + ntype;
                        state = "<td>Not available</td>";
                    }
                    $("#detailsExpRow").append("<tr><td>" + nodename + "</td><td>" + displayVar(json_exp[k].profilename) + "</td><td>" + displayVar(json_exp[k].firmwarename) + "</td>" + state + "</tr>");
                }
                $("#detailsExpSummary").append(nbTotalNodes + "<br/>");
                $("#tblNodes").show();
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                $("#div_msg").html("An error occurred while retrieving experiment #" + id + " details");
                $('#div_msg').show();
            }
        });
    });

    function cancelExperiment() {
        if (confirm("Cancel Experiment?")) {

            $.ajax({
                url: "/rest/admin/experiments/" + id,
                type: "DELETE",
                contentType: "application/json",
                dataType: "text",

                success: function (data) {
                    $("#btnCancel").attr("disabled", true);
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    alert("error: " + errorThrows)
                }
            });
        }
    }

</script>
<?php include('footer.php') ?>
