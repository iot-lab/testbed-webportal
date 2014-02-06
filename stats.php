<?php
session_start();

include("header.php") ?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load('visualization', '1.0', {'packages':['corechart']});
  google.load('visualization', '1', {'packages':['geochart']});
</script>

<div class="container">

<div class="row">
    <div class="col-md-12">
      <h2>Statistics</h2>
      <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
      <div class="alert alert-error" id="div_msg" style="display:none"></div>
    </div>
</div>


<div class="row" id="statsExps" style="display:none;border-bottom:1px solid #CCCCCC;padding-bottom:10px;">
	<div class="col-md-12">
		<h3>Experiments</h3>
		<div class="accordion" id="accordionExps">
			<div class="accordion-group" style="border:0">
				<div class="accordion-heading">
					<span class="glyphicon glyphicon-cog"></span> <span id="expTotal" class="badge badge-info"></span> experiments (with <span id="expRunning" class="badge">&nbsp;</span> running and <span id="expUpcoming" class="badge"></span> upcoming)
					<a data-toggle="collapse" data-parent="#accordionExps" href="#collapseExps" style="color:#333;text-decoration:none"><button class="btn btn-default"><b class="caret"></b></button></a>
				</div>
				<div id="collapseExps" class="accordion-body collapse">
					<select id="lst_exp" size="10" disabled style="width:250px">
						<optgroup label="Running" id="Running"></optgroup>
						<optgroup label="Upcoming" id="Upcoming"></optgroup>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row" id="statsNodes" style="display:none;border-bottom:1px solid #CCCCCC;padding-bottom:10px;">
	<div class="col-md-12">
		<h3>Nodes</h3>
		<div class="accordion" id="accordionNodes">
			<div class="accordion-group" style="border:0">
				<div class="accordion-heading">
					<span class="glyphicon glyphicon-download-alt"></span> <span id="nodesTotal" class="badge badge-info"></span> nodes (with <span id="nodesFree" class="badge">&nbsp;</span> free, <span id="nodesBusy" class="badge"></span> busy and <span id="nodesUnavailable" class="badge badge-warning"></span> unavailable)
					<a data-toggle="collapse" data-parent="#accordionNodes" href="#collapseNodes" style="color:#333;text-decoration:none"><button class="btn btn-default"><b class="caret"></b></button></a>
				</div>
				<div id="collapseNodes" class="accordion-body collapse">
					<div class="accordion-inner" id="sitesNodesDetails"></div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row" id="statsUsers" style="display:none;padding-bottom:10px;">
	<div class="col-md-12">
		<h3>Users</h3>
		<div class="accordion" id="accordionUsers">
			<div class="accordion-group" style="border:0">
				<div class="accordion-heading">
					<span class="glyphicon glyphicon-user"></span> <span id="usersTotal" class="badge badge-info"></span> users (with <span id="usersAdmin" class="badge"></span> admins and <span id="usersPending" class="badge badge-warning"></span> pending)
					<a data-toggle="collapse" data-parent="#accordionUsers" href="#collapseUsers" style="color:#333;text-decoration:none"><button class="btn btn-default"><b class="caret"></b></button></a>
				</div>
				<div id="collapseUsers" class="accordion-body collapse">
					<div class="row">
						<div class="col-md-8">
							<table id="country" class="table table-condensed table-striped table-bordered">
								<thead><tr><th>Country name</th><th>Nb user</th></thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="col-md-4">
							<div id="pie_chart_div"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<div id="chart_divGeo"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>


<?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<link href="css/datatable-custom.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>

<style>

.nomargin {
        margin-left:20px;
}

</style>

    <script type="text/javascript">

    $("#stats").addClass("active");

    var oTable;
    var graph = [];
    var sitesNodesBusy = {};

    $(document).ready(function() {

        $(document).ajaxStart(function(){
            $("#loader").show();
        });
        $(document).ajaxStop(function(){
            $("#loader").hide();
        });


        /* Retrieve users infos */
        $.ajax({
            url: "/testbed/scripts/admin_stats.php",
            type: "GET",
            dataType: "json",
            success:function(data){

                // users
                $('#usersTotal').html(data.users.nb_users);
                $('#usersAdmin').html(data.users.admin);
                $('#usersPending').html(data.users.pending);

                for(c in data.users.countries) {
                    var ct = [c,data.users.countries[c]];
                    graph.push(ct);
                    $("#country tbody").append("<tr><td>" + c + "</td><td>" + data.users.countries[c]+"</td></tr>");
                }


                oTable = $('#country').dataTable({
                    "sDom": "<'row'<'col-md-6'l><'col-md-8'f>>t<'row'<'col-md-4'i><'col-md-8'p>>",
                        "bPaginate": true,
                        "sPaginationType": "bootstrap",
                        "bLengthChange": true,
                        "bFilter": true,
                        "bSort": true,
                        "bInfo": true,
                        "bAutoWidth": false
                } );


                // experiments
                var total = data.exps.running+data.exps.upcoming+data.exps.terminated;
                $("#expRunning").text(data.exps.running);
                $("#expUpcoming").text(data.exps.upcoming);
                $("#expTerminated").text(data.exps.terminated);
                $("#expTotal").text(total);
                if(data.exps.infosRunning!=0) {
                        for(var i in data.exps.infosRunning) {
                               $("#Running").append(new Option(i+"/"+data.exps.infosRunning[i], i, false, false));
                        }
                }
                if(data.exps.infosUpco!=0) {
                        for(var i in data.exps.infosUpco) {
                               $("#Upcoming").append(new Option(i+"/"+data.exps.infosUpco[i], i, false, false));
                        }
                }


                // nodes
                total = data.nodes.Alive+data.nodes.Unavailable+data.nodes.Busy;
                $("#nodesTotal").text(total);
                $("#nodesFree").text(data.nodes.Alive);
                $("#nodesUnavailable").text(data.nodes.Unavailable);
                $("#nodesBusy").text(data.nodes.Busy);

                var sites = new Object();
                for(var i in data.nodes.BySite) {
                    total = data.nodes.BySite[i].Alive+data.nodes.BySite[i].Unavailable+data.nodes.BySite[i].Busy;
                    alive =  data.nodes.BySite[i].Alive;
                    busy = data.nodes.BySite[i].Busy;
                    unavailable = data.nodes.BySite[i].Unavailable;
                    $("#sitesNodesDetails").append('<div class="row"><div class="col-md-2 col-xs-2" style="text-align:right"><b>'+i.charAt(0).toUpperCase()+i.slice(1)+'</b></div><div class="col-md-10 col-xs-10"><span class="badge badge-info">'+total+'</span> nodes (with <span class="badge">'+alive+'</span> free, <span class="badge">'+busy+'</span> busy and <span class="badge badge-warning">'+unavailable+'</span> unavailable)</div></div>');
                }



                // show stats
				drawChart();
                $("#statsExps").show();
                $("#statsNodes").show();
                $("#statsUsers").show();
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving statistics");
            }
        });
   });



  function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Country');
    data.addColumn('number', 'Users');
    data.addRows(graph);

    
    // Instantiate and draw our chart, passing in some options.
    var options = {'title':'Users Country','width':400,'height':300};
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);

    //Geo graph
    options = {'title':'Users Country','width':600,'height':400};
    var chartGeo = new google.visualization.GeoChart(document.getElementById('chart_divGeo'));
    chartGeo.draw(data, options);

  }

    </script>

  </body>
</html>
 
