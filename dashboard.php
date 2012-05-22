<?php 
session_start();
if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}

?>

<?php include("header.php") ?>

    <div class="container">
    
    <div class="row">
        <div class="span12">
          <h2>Experiment List</h2>
        </div>
	</div>
  
    <div class="row">
        <div class="span5" style="text-align:left;padding-bottom:5px;padding-left:5px;">
          <a href="new_experiment.php" class="btn btn-new">New Experiment</a>&nbsp;<a href="#" class="btn btn-clear" onClick="refreshExpList()">Refresh List</a>
        </div>
    </div>
    
	<div class="row">
		<div class="span9">
		
                <div class="loading" id="loading"><b>Loading ...</b></div>
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
                    </tbody>
             	</table>
             	
        </div>
          
        <div class="span4">
          <h2>Personal dashboard</h2>
          <p><i class="icon-cog"></i> Experiments:</p>
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
            <!-- <p><i class="icon-user"></i> VM's Status: <button class="btn btn-success">ON</button></p> -->

            <hr/>
 
 
            <p><a data-toggle="modal" href="#password_modal">Modify Password</a></p>
            <p><a data-toggle="modal" href="#ssh_modal">Modify SSH Key</a></p>

            <hr/>

            <p><a href="/monika">View nodes status</a></p>
            <p><a href="/drawgantt">View Gantt chart</a></p>
           
          </div>
      </div>
        
        
        <div id="ssh_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">×</a>
              <h3>Modify SSH Key</h3>
            </div>
           <div class="modal-body">
               <div class="alert alert-error" id="div_error" style="display:none"></div>
               
                <form class="well form-horizontal" id="form_modify">

              <div class="control-group">
                <label class="control-label" for="txt_ssh">SSH Key:</label>
                <div class="controls">
                    <textarea id="txt_ssh" class="input-xlarge" rows="3" required="required"></textarea>
                </div>
              </div>

                <button id="btn_modify" class="btn btn-primary" type="submit">Modify</button>
                </form>
            </div>
        </div>
        
        
        <div id="password_modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">×</a>
              <h3>Modify Password</h3>
            </div>
           <div class="modal-body">
               <div class="alert alert-error" id="div_error_password" style="display:none"></div>
               
                <form class="well form-horizontal" id="form_modify_password">

              <div class="control-group">
                <label class="control-label" for="txt_current_password">Current password:</label>
                <div class="controls">
                    <input id="txt_current_password" class="input-xlarge" type="password" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_new_password">New password:</label>
                <div class="controls">
                    <input id="txt_new_password" class="input-xlarge" type="password" required="required"/>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_cnew_password">Confirm New password:</label>
                <div class="controls">
                    <input id="txt_cnew_password" class="input-xlarge" type="password" required="required"/>
                </div>
              </div>
              
                <button id="btn_modify_password" class="btn btn-primary" type="submit">Modify</button>
                </form>
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

            getExpList();
            
            /* Retrieve current sshkey */
            $.ajax({
                url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
                type: "GET",
                //contentType: "application/json",
                //data: JSON.stringify({"login":"<?php echo $_SESSION['login'] ?>"}),
                data: {},
                dataType: "text",
                success:function(data){
                    $("#txt_ssh").val(data);
            },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error" + textStatus);
                }
            });
            
            $('#ssh_modal').modal('hide');
            $('#password_modal').modal('hide');
        
            /* Modify SSH Key */
            $('#form_modify').bind('submit', function(){
                var user = {
                    "sshkeys":$("#txt_ssh").val(),
                };
                
                $.ajax({
                    url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/sshkeys",
                    type: "PUT",
                    dataType: "text",
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(user),
                    success:function(data){
                        $("#div_error").show();
                        $("#div_error").removeClass("alert-error");
                        $("#div_error").addClass("alert-success");
                        $("#div_error").html("Your SSH Key was modify");
                        $("#ssh_modal").modal("hide");
                },
                    error:function(XMLHttpRequest, textStatus, errorThrows){
                        $("#div_error").show();
                        $("#div_error").removeClass("alert-success");
                        $("#div_error").addClass("alert-error");
                        $("#div_error").html("Error");
                    }
                });
                
            return false;
            
            });
            
            
            /* Modify Password Key */
            $('#form_modify_password').bind('submit', function(){
            
                if($("#txt_new_password").val() != $("#txt_cnew_password").val()) 
                {
                    $("#div_error_password").show();
                    $("#div_error_password").removeClass("alert-success");
                    $("#div_error_password").addClass("alert-error");
                    $("#div_error_password").html("Please confirm password");
                    return false;
                }
            
            
                var user = {
                    "newPassword":$("#txt_new_password").val(),
                    "oldPassword":$("#txt_current_password").val(),
                };
                
                $.ajax({
                    url: "/rest/users/"+<?php echo '"'.$_SESSION['login'].'"'?>+"/password",
                    type: "PUT",
                    dataType: "text",
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(user),
                    success:function(data){
                        $("#div_error_password").show();
                        $("#div_error_password").removeClass("alert-error");
                        $("#div_error_password").addClass("alert-success");
                        $("#div_error_password").html("Your Password was modify");
                        $("#password_modal").modal("hide");
                },
                    error:function(XMLHttpRequest, textStatus, errorThrows){
                        $("#div_error_password").show();
                        $("#div_error_password").removeClass("alert-success");
                        $("#div_error_password").addClass("alert-error");
                        $("#div_error_password").html("Error");
                    }
                });
                
            return false;
            
            });

            $('#exp_details').modal('hide');
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
					if(key2 == "firmwarename"){
						liste = '<ul><li>'+val2+'</li><ul>'+liste_nodes.replace(/,/g,'<br/>')+'</ul></lu>';
					}
					else{
						liste_nodes += '<li><b>'+val2+'</b></li>';
					}
					$("#detailsExp").append(liste);
				});

                        });


                        $.each(data.profileassociations, function(key,val) {
                                var liste = "";
                                var liste_nodes = "";
                                $.each(val, function(key2,val2) {
                                        if(key2 == "profilename"){
                                                liste = '<ul><li>'+val2+'</li><ul>'+liste_nodes.replace(/,/g,'<br/>')+'</ul></lu>';
                                        }
                                        else{
                                                liste_nodes += '<li><b>'+val2+'</b></li>';
                                        }
                                        $("#detailsExp").append(liste);
                                });

                        });



                        $("#detailsExp").append('</ul>');


			$('#details_modal').modal('show');
            	},
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error" + textStatus);
                }
            });

	}

	function getExpList() {
        
		/* Retrieve experiment list */
		$.ajax({
			url: "/rest/experiments?limite",
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
                            '<td>' + buttonAction +'</td>'
                            +'</tr>');
                    i++;
                });
                oTable = $('#tbl_exps').dataTable({
                	"sDom": "<'row'<'span7'l><'span7'f>r>t<'row'<'span7'i><'span7'p>>",
                        "bPaginate": true,
                        "sPaginationType": "bootstrap",
                        "bLengthChange": true,
                        "bFilter": true,
                        "bSort": true,
                        "bInfo": true,
                        "bAutoWidth": false,
                        "aaSorting": [[ 0, "desc" ]]
                } );
                $('#loading').hide();
                $('#tbl_exps').show();
                $("#expRunning").text(expRunning);
                $("#expUpcoming").text(expUpcoming);
                $("#expPast").text(expPast);
        },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                alert("error" + textStatus);
            }
        });

	}

	function refreshExpList() {
		$('#loading').show();
		$('#tbl_exps').hide();
		oTable.fnClearTable(true);
		oTable.fnDestroy();
		getExpList();
	}
   
        
    </script>

  </body>
</html>
