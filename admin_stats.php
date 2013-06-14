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
          <h2>Dashboard</h2>
        </div>
    </div>
  

<h3>Users</h3>

<i class="icon-user"></i> <span id="nb_users" class="label label-success"></span> users (with <span id="nb_admin" class="label"></span> admins and <span id="nb_pending" class="label label-warning"></span> pending)

<div class="row">
	<h4>Country</h4>
	<div class="span5">
		 <table id="country" class="table table-condensed table-striped table-borderd"></table>
	</div>
</div>

<div id="chart_div"></div>

<?php include('footer.php') ?>

 
    <script type="text/javascript">

    var graph = [];

    $(document).ready(function()
    {

        /* Load data in the table */
        $.ajax({
            url: "/rest/admin/users",
            type: "GET",
            dataType: "json",
            success:function(data){
                $('#nb_users').html(data.length);
		var admin = 0;
		var pending = 0;
		var country = {};

		for(var user in data) {
			console.log(data[user].admin);
			if(data[user].admin) {
				admin++;
			}
			if(!data[user].validate) {
				pending++;
			}
			
			if(country[data[user].country] == undefined)
			{
				country[data[user].country] = 0;
			}
			else {
				country[data[user].country] = country[data[user].country]+1;
			}
			
		}
		$('#nb_admin').html(admin);
		$('#nb_pending').html(pending);

		for(c in country) {
			var ct = [c,country[c]];
			graph.push(ct);
			$("#country").append("<tr><td>" + c + "</td><td>" + country[c]+"</td></tr>");
		}

		drawChart();

            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                $('#tbl_exps_processing').hide();
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving user list");
            }
        });
    });
    
    
    
    </script>

  </body>
</html>
