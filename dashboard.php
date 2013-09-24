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
        
        <div style="text-align:center;margin-bottom:20px"><a href="exp_new.php" class="btn btn-new btn input-large btn-large">New Experiment</a></div>        

        <div class="alert alert-error" id="div_msg" style="display:none"></div>
        <table id="tbl_exps" class="table table-bordered table-striped table-condensed" style="display:none">
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

    <div class="span4">
      <h2>Personal dashboard</h2>
      <p><i class="icon-cog"></i> Experiments: <span id="expTotal">&nbsp;</span></p>
        <ul>
            <li><span id="expRunning" class="badge badge-success">&nbsp;</span> running</li>
            <li><span id="expUpcoming" class="badge badge-info">&nbsp;</span> upcoming</li>
            <li><span id="expTerminated" class="badge">&nbsp;</span> terminated</li>
        </ul>
          <p><i class="icon-th"></i> Profiles: <span id="nb_profiles">&nbsp;</span></p>
          <!--<p><i class="icon-home"></i> Home's quota: 60% (600/1000Mo)
            <div class="progress" style="width:200px">
              <div class="bar" style="width: 60%;"></div>
            </div>
          </p>-->
          
        <div class="alert alert-info">
            <img src="img/help.png"/> Click on an experiment to manage it or click <b>New Experiment</b> to start a new one.
        </div>
          
      </div>

</div>
    
    <?php include('footer.php') ?>

<link href="css/datatable.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/datatable.js"></script>
<script type="text/javascript">

    var oTable;

var dateSrv = <?php echo time(); ?>*1000; // server date in milliseconds
    
    $(document).ready(function(){
       
        $(document).ajaxStart(function(){
            $("#loader").show();
        });
        $(document).ajaxStop(function(){
            $("#loader").hide();
        });
       
       
        // Retrieve experiments total 
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
                $("#div_msg").removeClass("alert-success");
                $("#div_msg").addClass("alert-error");
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
                $("#div_msg").addClass("alert-error");
                $("#div_msg").show();
                $("#div_msg").html("An error occurred while retrieving your profile list: " + errorThrows);
            }
        });

        // Manage experiment list 
        oTable = $('#tbl_exps').dataTable({
            "sDom": "<'row'<'span7'l><'span7'f>r>t<'row'<'span7'i><'span7'p>>",
            "bProcessing": false,
            "bServerSide": true,
            "sAjaxSource": "scripts/exp_list.php",
            "aoColumns": [
                {"mDataProp": "id" },
                {"mDataProp": "name" },
                {"mDataProp": "date",
                     "fnRender": function(obj) {
                            var date = obj.aData['date'];
                            myDate = new Date(date*1000);
                            if(myDate == "Invalid Date") 
                                myDate = "As soon as possible";
                            else myDate = myDate.toGMTString();
                            return myDate;
                     }
                },
                {"mDataProp": "duration",
                    "fnRender": function(obj) {
                        return Math.round(obj.aData['duration']/60);
                    }
                },
                {"mDataProp": "nb_resources" },
                {"mDataProp": "state",
                 "fnRender": function(obj) {
                        var state = obj.aData['state'];
                        if ( state == "Error" ) { // terminated error 
                            state = "<span class='label label-important'>"+state+"</span>";
                        } else if( state == "Terminated" ) { // terminated OK 
                            state = "<span class='label'>"+state+"</span>";
                        } else if( state == "Running" || state == "Finishing" || state == "Resuming" || state == "toError" ) { // running 
                            state = "<span class='label label-success'>"+state+"</span>";
			    var dateExp = new Date(obj.aData['date']).getTime(); // start date in milliseconds
			    var durationExp = obj.aData['duration']*60000; // experiment duration in milliseconds
                            setTimeout(function(){checkState(obj.aData['id'],1,dateExp,durationExp);},durationExp-(dateSrv-dateExp)); /* TODO verif */
			    console.log(obj.aData['id']+" is Running; refresh in "+(durationExp-(dateSrv-dateExp))/1000+" s.");
                        } else if( state == "Waiting" || state=="Launching" || state=="Suspended"
                            || state == "Hold" || state=="toAckReservation" || state=="toLaunch" ) { // upcomming 
                            state = "<span class='label label-info'>"+state+"</span>";
			    var dateExp = new Date(obj.aData['date']).getTime(); // start date in milliseconds
			    var durationExp = obj.aData['duration']*60000; // experiment duration in milliseconds
                            setTimeout(function(){checkState(obj.aData['id'],0,dateExp,durationExp);},dateExp-dateSrv); /* TODO verif */
			    console.log(obj.aData['id']+" is Waiting; refresh in "+(dateExp-dateSrv)/1000+" s.");
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
            "fnInitComplete": function(oSettings, json) {
                $('#tbl_exps tbody tr').each(function(){
                    this.setAttribute('title','Click to see details');
                    var aData = oTable.fnGetData( this );
                    this.setAttribute('id',aData['id']);
                });
                $('#tbl_exps tbody tr[title]').tooltip( {
                    "delay": 0,
                    "track": true,
                    "fade": 250,
                    "placement": 'right'
                });
            }
        });

        $('#tbl_exps tbody').on('click','tr', function () {
            var aData = oTable.fnGetData( this );
            window.location.href="exp_details.php?id="+aData['id'];
        });
        $('#div_msg').hide();
        $('#tbl_exps').show();

        // filters by state for experiments list 
		$('.dataTables_filter').html('<label>Filter: <select id="filter_by_state" style="margin-top:7px;"><option value="All">All</option><option value="Running">Running</option><option value="Upcoming">Upcoming</option><option value="Terminated">Terminated</option></select></label>');
		$('#filter_by_state').change(function() {
			oTable.fnFilter($(this).val());
		});

    });
    
	function checkState(id,currentState,date,duration) {
		// Retrieve exp state
		$.ajax({
			url: "/rest/experiments/"+id+"?state",
			type: "GET",
			dataType: "JSON",
			contentType: "application/json; charset=utf-8",
			success: function (data) {
				// state : 0 = upcomming ; 1 = running ; 2 = terminated
				var newState=2;
				var state = data['state'];
				if( state == "Waiting" || state=="Launching" || state=="Suspended" || state == "Hold" || state=="toAckReservation" || state=="toLaunch" ) newState=0;
				else if( state == "Running" || state == "Finishing" || state == "Resuming" || state == "toError" ) newState=1;
				
				if (currentState == newState) setTimeout(function(){checkState(id,currentState,date,duration);},2000); // no state change, still upcomming or running, refresh again
				else if (currentState == 0 && newState == 1) { // state change from upcomming to running, refresh again
					setTimeout(function(){checkState(id,1,date,duration);},duration-2000); /* TODO verif */
					console.log(id+" is now running; refresh in "+(duration-2000)/1000+" s.");
					$("#"+id+" td span").removeClass("label-info");
					$("#"+id+" td span").addClass("label-success");
					$("#"+id+" td span").html(state);
					/* change badges in Personal Dashboard */
					changeBadges("expUpcoming","expRunning");
				} else { // state change from upcomming or running to terminated, stop refreshing
					$("#"+id+" td span").removeClass("label-info");
					$("#"+id+" td span").removeClass("label-success");
					$("#"+id+" td span").removeClass("label-important");
					if(state == "Error")  $("#"+id+" td span").addClass("label-important");
					$("#"+id+" td span").html(state);
					/* change badges in Personal Dashboard */
					changeBadges((currentState==0?"expUpcoming":"expRunning"),"expTerminated");
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrows) {
				$("#div_msg").removeClass("alert-success");
				$("#div_msg").addClass("alert-error");
				$("#div_msg").show();
				$("#div_msg").html("An error occurred while refreshing experiment states: " + errorThrows);
			}
		});
	}

	function changeBadges(fromState,toState) { // remove 1 exp from fromState badge to toState badge
		var nbExp=$("#"+fromState+"").text();
		//nbExp--;
		$("#"+fromState+"").text(--nbExp);

		nbExp=$("#"+toState+"").text();
		//nbExp++;
		$("#"+toState+"").text(++nbExp);
	}

    
</script>

</body>
</html>
