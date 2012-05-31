<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

include("header.php") ?>

    <div class="container" text-align="top">
    
   
	<div class="row">
        <div class="span9">
          <h2>Experiment List</h2>
        </div>
        <div class="span5" style="text-align:left;padding-bottom:5px;padding-left:5px;">
          <a href="new_experiment.php" class="btn btn-new">New Experiment</a>&nbsp;<a href="#" class="btn btn-clear" onClick="refreshExpList()">Refresh List</a>
        </div>
		<div class="span9">
		
                <div class="loading" id="div_msg" style="display:none"><b>Loading ...</b></div>
                <table id="tbl_exps" class="table table-bordered table-striped table-condensed" style="display:none">
					<thead>
                    	<tr>
                        	<th>ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Node(s)</th>
                            <th>State</th>
                            <th>Options</th>
                        </tr>
                   	</thead>
                    <tbody>
						<tr>
							<td colspan="5" class="dataTables_empty">Loading ...</td>
						</tr>
                    </tbody>
                    <tfoot>
                    </tfoot>
             	</table>
             	
        </div>
          
        <div class="span4">
          <h2>Personal dashboard</h2>
          <p><i class="icon-cog"></i> Experiments: <span id="expTotal">&nbsp;</span></p>
            <ul>
				<li><span id="expRunning" class="badge badge-success">&nbsp;</span> running</li>
				<li><span id="expUpcoming" class="badge badge-info">&nbsp;</span> upcoming</li>
				<li><span id="expPast" class="badge">&nbsp;</span> past</li>
            </ul>
          <p><i class="icon-th"></i> Profiles: 2 <p>
          <p><i class="icon-home"></i> Home's quota: 60% (600/1000Mo)
            <div class="progress" style="width:200px">
              <div class="bar"
                   style="width: 60%;"></div>
            </div>
            </p>
           
          </div>
      </div>      
        
        <div id="details_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">×</a>
              <h3>Experiment Details</h3>
            </div>
           <div class="modal-body">

		<div id="detailsExp">...</div>

            </div>
        </div>

        
        <?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>
    <script type="text/javascript">

    	var oTable;
        
        $(document).ready(function(){

    		/* Hide modal windows */
            $('#exp_details').modal('hide');		
	        
			/* Retrieve experiments total */
			$.ajax({
				url: "/rest/experiments?total",
				type: "GET",
				dataType: "json",
				success:function(data){
					var total = data.running+data.upcoming+data.terminated;
					$("#expRunning").text(data.running);
					$("#expUpcoming").text(data.upcoming);
					$("#expPast").text(data.terminated);
					$("#expTotal").text(total);
				},
				error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#div_msg").removeClass("loading");
                    $("#div_msg").addClass("alert");
                    $("#div_msg").addClass("alert-error");
                    $("#div_msg").show();
					$("#div_msg").html("An error occurred while retrieving your experiment list");
				}
			});

    		/* Retrieve experiment list */
            //getExpList();
			oTable = $('#tbl_exps').dataTable({
				"sDom": "<'row'<'span7'l><'span7'f>r>t<'row'<'span7'i><'span7'p>>",
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "/rest/experiments",
				"bPaginate": true,
				"sPaginationType": "bootstrap",
				"bLengthChange": true,
				"bFilter": true,
				"bSort": true,
				"bInfo": true,
				"bAutoWidth": false,
				"aaSorting": [[ 0, "desc" ]]
			});
			$('#div_msg').hide();
			$('#tbl_exps').show();
            
        });



		function detailsExp(id) {

			/* Retrieve experiment details */
			$.ajax({
				url: "/rest/experiments/"+id,
				type: "GET",
				data: {},
				dataType: "json",
				success:function(data){
					//console.log(data);

					$("#detailsExp").html("Experiment #" + id);
					$("#detailsExp").append("<br/>Number of Nodes: " + data.nodes.length);
		
					$("#detailsExp").append('<ul>');
					$.each(data.nodes, function(key,val) {
						$("#detailsExp").append('<li>'+val+'</li>');
					});
					$("#detailsExp").append('</ul>');

					$("#detailsExp").append('<ul>');
					$.each(data.firmwareassociations, function(key,val) {
						var liste = "";
						var liste_nodes = "";
						$.each(val, function(key2,val2) {
							if(key2 == "firmwarename") liste = '<ul><li>'+val2+'</li><ul>'+liste_nodes.replace(/,/g,'<br/>')+'</ul></lu>';
							else liste_nodes += '<li><b>'+val2+'</b></li>';
							$("#detailsExp").append(liste);
						});
					});

					$.each(data.profileassociations, function(key,val) {
						var liste = "";
						var liste_nodes = "";
						$.each(val, function(key2,val2) {
							if(key2 == "profilename") liste = '<ul><li>'+val2+'</li><ul>'+liste_nodes.replace(/,/g,'<br/>')+'</ul></lu>';
							else liste_nodes += '<li><b>'+val2+'</b></li>';
							$("#detailsExp").append(liste);
						});
					});

					$("#detailsExp").append('</ul>');

					$('#details_modal').modal('show');
				},
				error:function(XMLHttpRequest, textStatus, errorThrows){
					$("#detailsExp").html("An error occurred while retrieving experiment #" + id + " details");
					$('#details_modal').modal('show');
				}
			});
		}

		function getExpList() {
			$("#div_msg").removeClass("alert-error");
			$("#div_msg").removeClass("alert");
			$("#div_msg").addClass("loading");
			$("#div_msg").html("<b>Loading ...</b>");
			$("#div_msg").show();
		
	        
			/* Retrieve experiment list */
			$.ajax({
				url: "/rest/experiments",
				type: "GET",
				dataType: "json",
				success:function(data){
					exps = data.items;
					var i = 0;
					var expRunning=0;
					var expUpcoming=0;
					var expPast=0;
					$.each(data.items, function(key,val) {
	
						var date=new Date();
						date.setTime(val.date+"000");
	
						var buttonAction='<a href="#" class="btn btn-valid" data="'+val.id+'" onClick="detailsExp('+val.id+')">Details</a>';
	                	
						switch(val.state) {
							case "Running":
								buttonAction+='<a href="#" class="btn btn-danger" data="'+val.id+'" onClick="stopExp('+val.id+')">Stop</a>';
								expRunning++;
								break;
							case "Upcoming":
								buttonAction+='<a href="#" class="btn btn-danger" data="'+val.id+'" onClick="cancelExp('+val.id+')">Cancel</a>';
								expUpcoming++;
								break;
	        				case "Terminated":
	    					case "Error":
								expPast++;
								break;
						}

						$("#tbl_exps tbody").append(
								'<tr data=' + val.id + '>'+
								'<td>' + val.id + '</td>'+
								'<td>' + val.name + '</td>'+
								'<td>'+ date + '</td>'+
								'<td>' + Math.floor(val.duration/60) + ' minute(s)</a></td>'+
								'<td>' + val.nb_resources + ' node(s)</td>'+
								'<td>' + val.state + '</td>'+
								'<td>' + buttonAction +'</td>'+
								'</tr>');
						i++;
					});
					oTable = $('#tbl_exps').dataTable({
						"sDom": "<'row'<'span7'l><'span7'f>r>t<'row'<'span7'i><'span7'p>>",
						"bProcessing": true,
						"bServerSide": true,
						"sAjaxSource": "/rest/experiments",
						"bPaginate": true,
						"sPaginationType": "bootstrap",
						"bLengthChange": true,
						"bFilter": true,
						"bSort": true,
						"bInfo": true,
						"bAutoWidth": false,
						"aaSorting": [[ 0, "desc" ]]
					});
					$('#div_msg').hide();
					$('#tbl_exps').show();
				},
				error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#div_msg").removeClass("loading");
                    $("#div_msg").addClass("alert");
                    $("#div_msg").addClass("alert-error");
                    $("#div_msg").show();
					$("#div_msg").html("An error occurred while retrieving your experiment list");
				}
			});
		}

		function refreshExpList() {
			$('#tbl_exps').hide();
			oTable.fnClearTable(true);
			oTable.fnDestroy();
			getExpList();
		}
   
        
    </script>

  </body>
</html>
