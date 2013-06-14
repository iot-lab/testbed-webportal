<?php
session_start();

if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}


include("header.php") ?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      // google.setOnLoadCallback(drawChart);

      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows(graph);

        // Set chart options
        var options = {'title':'Users Country',
                       'width':400,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

    <div class="container">
      
    <div class="row">
        <div class="span12">
          <h2>Statistics</h2>
		  <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
		  <div class="alert alert-error" id="div_msg" style="display:none"></div>
        </div>
    </div>
      
    <div class="row">
        <div class="span6">
			<h3>Users</h3>
			
			<i class="icon-user"></i> <span id="usersTotal" class="label label-info"></span> users (with <span id="usersAdmin" class="label"></span> admins and <span id="usersPending" class="label label-warning"></span> pending)
			
			<div class="row">
				<h4>Country</h4>
				<div class="span5">
					 <table id="country" class="table table-condensed table-striped table-borderd"></table>
				</div>
			</div>
			
			<div id="chart_div"></div>
        </div>
        <div class="span6">
          <h3>Experiments</h3>
	      <i class="icon-cog"></i> <span id="expTotal" class="label label-info"></span> experiments (with <span id="expRunning" class="label">&nbsp;</span> running and <span id="expUpcoming" class="label"></span> upcoming)
		  <hr/>
			
          <h3>Nodes</h3>
	      <!-- <i class="icon-cog"></i> <span id="nodesTotal" class="label"></span> nodes (with <span id="nodesFree" class="label label-success">&nbsp;</span> free and <span id="nodesUnavailable" class="label label-info"></span> suspected or absent) -->
	      <div class="accordion" id="accordion2">
			<div class="accordion-group" style="border:0">
				<div class="accordion-heading">
					<i class="icon-download-alt"></i> <span id="nodesTotal" class="label label-info"></span> nodes (with <span id="nodesFree" class="label">&nbsp;</span> free and <span id="nodesUnavailable" class="label label-warning"></span> suspected or absent) 
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" style="color:#333;text-decoration:none"><button class="btn"><b class="caret"></b></button></a>
				</div>
				<div id="collapseOne" class="accordion-body collapse">
					<div class="accordion-inner" id="sitesNodesDetails"></div>
				</div>
			</div>
		  </div>
        </div>
    </div>

<?php include('footer.php') ?>

 
    <script type="text/javascript">

    var graph = [];

    $(document).ready(function()
    {

        $(document).ajaxStart(function(){
            $("#loader").show();
        });
        $(document).ajaxStop(function(){
            $("#loader").hide();
        });

        
        /* Retrieve users infos */
        $.ajax({
            url: "/rest/admin/users",
            type: "GET",
            dataType: "json",
            success:function(data){
				var admin = 0;
				var pending = 0;
				var country = {};
		
				for(var user in data) {
					if(data[user].admin) admin++;
					
					if(!data[user].validate) pending++;
					
					if(country[data[user].country] == undefined) country[data[user].country] = 1;
					else country[data[user].country] = country[data[user].country]+1;
					
				}
                $('#usersTotal').html(data.length);
				$('#usersAdmin').html(admin);
				$('#usersPending').html(pending);
		
				for(c in country) {
					var ct = [c,country[c]];
					graph.push(ct);
					$("#country").append("<tr><td>" + c + "</td><td>" + country[c]+"</td></tr>");
				}
		
				drawChart();

            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving user list");
            }
        });
       
       
        /* Retrieve experiments infos */
        $.ajax({
            url: "/rest/experiments?total",
            type: "GET",
            dataType: "json",
            success:function(data){
                var total = data.running+data.upcoming+data.terminated;
                $("#expRunning").text(data.running);
                $("#expUpcoming").text(data.upcoming);
                $("#expTerminated").text(data.terminated);
                $("#expTotal").text(total);
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving experiment list");
            }
        });

        // get nodes list 
        $.ajax({
            url: "/rest/admin/resourcesproperties",
            type: "GET",
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            data: "",
            success:function(data){

                var sites = new Object();

                var total = 0;
                var free = 0;
                var unavailable = 0;
                
                for(var i in data) {
                    if(!sites[data[i].site]) {
                        sites[data[i].site] = new Object();
                        sites[data[i].site]["total"]=0;
                        sites[data[i].site]["free"]=0;
                        sites[data[i].site]["unavailable"]=0;
                    }
                    console.log(sites);
                    total++;
                    sites[data[i].site]["total"]++;
                    if(data[i].state=="Alive") {
                        free++;
                        sites[data[i].site]["free"]++;
                    } else if (data[i].state=="Suspected" || data[i].state=="Absent") {
                        unavailable++;
                        sites[data[i].site]["unavailable"]++;
                    }
                }
                $("#nodesTotal").text(total);
                $("#nodesFree").text(free);
                $("#nodesUnavailable").text(unavailable);

                for(var j in sites) {
                	$("#sitesNodesDetails").append('<div style="width:250px;display:inline"><i class="icon-download-alt"></i> '+j+'</div> <span class="label label-info">'+sites[j]["total"]+'</span> nodes (with <span class="label">'+sites[j]["free"]+'</span> free and <span class="label label-warning">'+sites[j]["unavailable"]+'</span> suspected or absent)<br/>');
            	}
                
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $("#div_msg").html("An error occurred while retrieving nodes list");
                $("#div_msg").show();
            }
        });
    });
    
    
    
    </script>

  </body>
</html>
