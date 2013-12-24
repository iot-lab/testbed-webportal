<?php
session_start();

if(!isset($_SESSION['is_auth']) || !$_SESSION['is_auth'] || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
	header("location: .");
	exit();
}

include("header.php");
?>

    <div class="container" text-align="top">
    
   
<div class="row">
	<div class="col-md-8">

		<h2>Manage nodes</h2>
          	
		<a href="#" class="btn btn-default btn-add" id="refreshButton">Refresh Data Structure</a>

		<div class="alert alert-danger" id="div_msg" style="display:none"></div>
		<table class="table table-striped table-bordered table-condensed" id="tblNodes">
			<thead>
				<tr>
					<th></th>
					<th>Node</th>
					<th>Site</th>
					<th>Architecture</th>
					<th>Mobile</th>
					<th>State</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		        
		        
		<form id="frmActions" class="form-horizontal">
			<p><a href="javascript:selectAll();">Select All</a> - <a href="javascript:unSelectAll();">Unselect All</a></p>

			<b>Actions on selected nodes: </b>
            
            <div class="row">
            	<div class="col-md-3">
		            <select id="part" class="form-control">
						<option value="opennodes">Open Node</option>
						<option value="controlnodes">Control Node</option>
						<option value="gatewaynodes">Gateway</option>
					</select>
					<div id="firmware" style="display:none"><label for="files">Firmware: <input type="file" id="files" name="files[]" multiple /></label></div>
            	</div>
            	
            	<div class="col-md-3">
					<select id="action" class="form-control">
						<option value="start">Start</option>
						<!-- <option value="start" data-battery="battery">Start (battery)</option> -->
						<option value="stop">Stop</option>
						<option value="reset">Reset</option>
						<!-- <option value="update">Update</option> -->
					</select>
            	</div>
	            
            	<div class="col-md-1">
		            <button id="btn_send" class="btn btn-default" type="submit">Send</button>
            	</div>
            </div>
		            
		            
		            
		            
		            
            <div id="stateSuccess" class="alert alert-success" style="display:none"></div>
            <div id="stateFailure" class="alert alert-failure" style="display:none"></div>
		            
		</form>
		<div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
		<br/>
	</div>
          
	<div class="col-md-4" id="searchDiv">
		<h2>Search for nodes</h2>
        <div class="" id="div_resources_map"><table id="div_resources_map_tbl"></table></div>
	</div>
</div>


 
</div> <!-- container -->   

<link href="css/datatable.css" rel="stylesheet">
<link href="css/datatable-custom.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/datatable.js"></script>

    <script type="text/javascript">

    	var oTable;
        var exp_json = [];
        var state = "";
        var binary = [];
        var boundary = "AaB03x";
        var sites = [];
        
        $(document).ready(function(){

            $("#admin").addClass("active");
            $("#admin_nodes").addClass("active");

            
			$("#frmActions").hide();
			$("#tblNodes").hide();

            
       
        $(document).ajaxStart(function(){
            $("#loader").show();
        });
        $(document).ajaxStop(function(){
            $("#loader").hide();
        });


            /* actions list change */
            $("#action").change(function(){
               if($("#action").val() == "update") {
                   $("#firmware").show();
               } 
               else {
                   $("#firmware").hide();
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
                    
                    for(var i in data) {
                        $("#tblNodes tbody").append("<tr><td>"+
                                "<input type=\"checkbox\" name=\"option1\" value=\""+data[i].network_address+"\"></td>"+
                                "<td>"+data[i].network_address+"</td>"+
                                "<td>"+data[i].site+"</td>"+
                                "<td>"+data[i].archi+"</td>"+
                                "<td>"+data[i].mobile+"</td>"+
                                "<td>"+data[i].state+"</td></tr>");
                        if(sites.indexOf(data[i].site)==-1) sites.push(data[i].site);
                    }
                    
                    oTable = $('#tblNodes').dataTable({
                        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                        "bPaginate": true,
                        "sPaginationType": "bootstrap",
                        "bLengthChange": true,
                        "bFilter": true,
                        "bSort": true,
                        "bInfo": true,
                        "bAutoWidth": false
                    });
                    
                    $("#frmActions").show();
        			$("#tblNodes").show();
                                   
                    for(var j = 0; j < sites.length; j++)
                        $("#div_resources_map_tbl").append('<tr><td><a href="#" onclick="openMapPopup(\''+sites[j]+'\')" id="'+sites[j]+'_maps">'+sites[j].charAt(0).toUpperCase() + sites[j].slice(1)+' map</a></td><td><input type="text" id="'+sites[j]+'_list" value="" class="form-control" /></td></tr>');
                    $("#div_resources_map_tbl").append('<tr><td colspan="2"><button class="btn btn-default btn-add pull-right" id="searchButton" style="margin-left:5px;">Search</button><button class="btn btn-default btn-add pull-right" id="clearButton">Clear</button></td></tr>');
                    $("#searchButton").click(function() { oTable.fnDraw(); });
					$("#clearButton").click(function() {
						$("#div_resources_map input").each(function(){ $(this).val(""); });
						oTable.fnDraw();
					}); 
                    
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    $("#div_msg").html("An error occurred while retrieving nodes list");
                    $("#div_msg").show();
                }
            });

            //get sites resources 
            /*$.ajax({
                type: "GET",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                url: "/rest/experiments?sites",
                success: function (data_server) {
                    site_resources = JSON.parse(data_server);                    
                    for(var j = 0; j < site_resources.items.length; j++)
                        $("#div_resources_map_tbl").append('<tr><td><a href="#" onclick="openMapPopup(\''+site_resources.items[j].site+'\')" id="'+site_resources.items[j].site+'_maps">'+site_resources.items[j].site.charAt(0).toUpperCase() + site_resources.items[j].site.slice(1)+' map</a></td><td><input type="text" id="'+site_resources.items[j].site+'_list" value="" class="input-large" /></td></tr>');
                    $("#div_resources_map_tbl").append('<tr><td colspan="2"><button class="btn btn-add pull-right" id="searchButton" style="margin-left:5px;">Search</button><button class="btn btn-add pull-right" id="clearButton">Clear</button></td></tr>');
                    $("#searchButton").click(function() { oTable.fnDraw(); });
					$("#clearButton").click(function() {
						$("#div_resources_map input").each(function(){ $(this).val(""); });
						oTable.fnDraw();
					});                    
                },
                error: function (XMLHttpRequest, textStatus, errorThrows) {
                    $("#div_msg").html(errorThrows);
                    $("#div_msg").show();
                }
            });*/

            $("#refreshButton").click( function() {
                if(confirm("This can take a while. Are you sure?")) {
	                // refresh the server Resources Properties Static Structure 
					$("#frmActions").hide();
					$("#tblNodes_wrapper").hide();
					$("#searchDiv").hide();
					
	                $.ajax({
	                    type: "POST",
	                    dataType: "json",
	                    url: "/rest/admin/resourcesproperties",
	                    success: function (data_server) {
	                        window.location.href="admin_nodes.php";
	                    },
	                    error: function (XMLHttpRequest, textStatus, errorThrows) {
	                        $("#div_msg").html(errorThrows);
	                        $("#div_msg").show();
	                    }
	                });
                }
            });
            
            /* actions on nodes */
            $("#frmActions").bind("submit",function(e){
                e.preventDefault();
                
                $("#stateFailure").hide();
                $("#stateSuccess").hide();
               
                var command = $("#action option:selected").val();
                
                var part = $("#part option:selected").val();
                
                var battery = "";
                if($("#action option:selected").attr("data-battery") == "battery") {
                    battery = "&battery=true";
                }
                
                
                var lnodes = [];
                $("#tblNodes :checked").each(function(){
                    lnodes.push($(this).val());
                });
                
                //console.log(lnodes);
                
                if($("#action").val() == "update") {
                   /* //JSON
                    var datab = "";
                    datab += "--" + boundary + '\r\n';
                    datab += 'Content-Disposition: form-data; name="'+exp_name+'.json"; filename="'+exp_name+'.json"\r\n';
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
                        url: "/rest/experiment/nodes?update",
                        contentType: "multipart/form-data; boundary="+boundary,
                        success: function (data) {
                            if(data["0"]) {
                            	$("#stateSuccess").html("<b>Update</b> successful for node(s): " + JSON.stringify(data["0"]));
                            	$("#stateSuccess").show();
                            }
                            if(data["1"]) {
                            	$("#stateFailure").html("<b>Update</b> failed for node(s): " + JSON.stringify(data["1"]));
                            	$("#stateFailure").show();
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#stateFailure").html(textStatus + " : " + errorThrows +  " : " +  XMLHttpRequest.responseText);
                            $("#stateFailure").show();
                        }
                    });
                    binary = [];
                    */
                }
                else {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: JSON.stringify(lnodes),
                        contentType: "application/json; charset=utf-8",
                        url: "/rest/admin/"+part+"?"+command+""+battery,
                        success: function (data) {
                            if(data["0"]) {
                            	$("#stateSuccess").html("<b>Update</b> successful for node(s): " + JSON.stringify(data["0"]));
                            	$("#stateSuccess").show();
                            }
                            if(data["1"]) {
                            	$("#stateFailure").html("<b>Update</b> failed for node(s): " + JSON.stringify(data["1"]));
                            	$("#stateFailure").show();
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#stateFailure").html(textStatus + " : " + errorThrows +  " : " +  XMLHttpRequest.responseText);
                            $("#stateFailure").show();
                        }
                    });
                }
               // return false;
            });
            
            //file upload event
            document.getElementById('files').addEventListener('change', handleFileSelect, false);
    });

    
    function selectAll() {
        $("#tblNodes :checkbox").each(function(){
            $(this).attr('checked', true);
        });
    }
    
    function unSelectAll() {
        $("#tblNodes :checkbox").each(function(){
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
			if(f.name.indexOf(".elf",f.name.length -4)!=-1) reader.readAsDataURL(f);
			else reader.readAsText(f);
        }
    }

    // filtering function for map selection input (the "Search for nodes" part) 
	$.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {

		exp_json = [];
		$("#div_resources_map input").each(function(){
			var site = $(this).attr("id").split('_')[0];
			var val = $(this).val();
			if(val != "") {
				var snodes = expand(val.split(","));
				for (var i = 0; i < snodes.length; i++) if(!isNaN(snodes[i])) exp_json.push("node"+snodes[i]+"."+site+".senslab.info");
			}
		});
		if(exp_json.length==0) return true;

		var id = aData[1];
		if(exp_json.indexOf(id)!=-1) return true;
                
		return false;
	});

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
        //exp.sort(sortfunction);
        exp.sort(function (a,b) { return (a-b); });
        for (var i = 1; i < exp.length; i++) if (exp[i] == exp[i - 1])  exp.splice(i--, 1);
        return exp;
    }
    
    function openMapPopup(site) {
    	window.open('maps.php?site='+site, '', 'resizable=yes, location=no, width=800, height=600, menubar=no, status=no, scrollbars=no, menubar=no');
    }
    

    </script>
<?php include('footer.php') ?>
