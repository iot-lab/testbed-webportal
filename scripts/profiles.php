<!-- ------ -->
<!--  MAIN  -->
<!-- ------ -->


<div class="col-md-4">
	<div class="alert alert-info">
		<img src="img/help.png"> To <b>create</b> a new profile click the <b>New</b> button, fill the form and click <b>Save</b>. 
		<br/><br/>To <b>edit</b> a profile, click on the profile name on the list, edit settings, and click <b>Save</b>.
		<br/><br/>When finished, close the window by clicking <b>X</b>.
	</div>
	<select id="my_profiles_modal" size="15" class="form-control" style="margin-bottom:5px;"></select>
	<p>
		<button class="btn btn-default" id="btn_new">New</button>
		<button class="btn btn-danger" id="btn_delete">Delete</button>
	</p>
</div>

<div class="col-md-8">           
	<form class="well form-horizontal form-inline" id="form_part" role="form">

		<div class="form-group" style="width:100%;margin-bottom:10px">
			<label class="col-lg-4 control-label" for="txt_name">Name</label>
			<div class="col-lg-8"><input id="profiles_txt_name" type="text" class="form-control" required="required"></div>
		</div>

		<div class="form-group" style="width:100%;margin-bottom:10px">
			<label class="col-lg-4 control-label">Node architecture</label>
			<div class="col-lg-8" style="float:left">
				<label class="radio"><input type="radio" name="or_nodearch" id="or_nodearch_wsn430" value="wsn430" data-target="#wsn430panel" checked> WSN430</label>&nbsp;&nbsp;
				<label class="radio"><input type="radio" name="or_nodearch" id="or_nodearch_m3" value="m3" data-target="#m3panel"> M3</label>&nbsp;&nbsp;
				<!-- <label class="radio inline"><input type="radio" name="or_nodearch" id="or_nodearch_a8" value="a8" data-target="#a8panel"> A8</label> -->
			</div>
		</div>

				
		<div class="tab-content">

<!-- --------------------- -->
<!--  WSN430 PROFILE FORM  -->
<!-- --------------------- -->
                   
			<div id="wsn430panel" class="tab-pane active">  	
				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label">Power mode</label>
						<div class="col-lg-8">
							<label class="radio"><input type="radio" name="or_power_wsn430" id="or_power_dc_wsn430" value="dc" checked> DC</label>&nbsp;&nbsp;
							<label class="radio"><input type="radio" name="or_power_wsn430" id="or_power_battery_wsn430" value="battery"> battery</label>
						</div>
					</div>
				</div>
				
				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="inlineCheckboxes">Consumption</label>
						<div class="col-lg-8">
							<label class="checkbox inline"><input type="checkbox" id="cb_current_wsn430" value="current"> current</label>&nbsp;&nbsp;
							<label class="checkbox inline"><input type="checkbox" id="cb_voltage_wsn430" value="voltage"> voltage</label>&nbsp;&nbsp;
							<label class="checkbox inline"><input type="checkbox" id="cb_power_wsn430" value="power"> power</label>
						</div>
					</div>
	                 
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="consumption_frequency_wsn430">Frequency (ms)</label>
						<div class="col-lg-8">
							<select id="consumption_frequency_wsn430" class="form-control">
								<option value="70">70</option>
								<option value="100">100</option>
								<option value="500">500</option>
								<option value="1000">1000</option>
								<option value="5000">5000</option>
							</select>
						</div>
					</div>
				</div>


				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="inlineCheckboxes">Sensors</label>
						<div class="col-lg-8">
							<label class="checkbox"><input type="checkbox" id="cb_luminosity_wsn430" value="luminosity"> luminosity</label>&nbsp;&nbsp;
							<label class="checkbox"><input type="checkbox" id="cb_temperature_wsn430" value="temperature"> temperature</label>
						</div>
					</div>
	
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="sensor_frequency_wsn430">Frequency (ms)</label>
						<div class="col-lg-8">
							<select id="sensor_frequency_wsn430" class="form-control">
								<option value="5000">5000</option>
								<option value="10000">10000</option>
								<option value="30000">30000</option>
							</select>
						</div>
					</div>
				</div> 

				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="inlineCheckboxes">Radio</label>
						<div class="col-lg-8">
							<label class="checkbox inline"><input type="checkbox" id="cb_rssi_wsn430" value="luminosity"> rssi</label>
						</div>
					</div>
	
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="radio_frequency_wsn430">Frequency (ms)</label>
						<div class="col-lg-8">
							<select id="radio_frequency_wsn430" class="form-control">
								<option value="500">500</option>
								<option value="1000">1000</option>
								<option value="5000">5000</option>
								<option value="10000">10000</option>
							</select>
						</div>
					</div>
				</div>
			</div>
              

<!-- ----------------- -->
<!--  M3 PROFILE FORM  -->
<!-- ----------------- -->	
             
			<div class="tab-pane" id="m3panel">
			<!-- postfixer tous les id et les noms des input avec _m3 comme _wsn430 plus haut -->
				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label">Power mode</label>
						<div class="col-lg-8">
							<label class="radio"><input type="radio" name="or_power_m3" id="or_power_dc_m3" value="dc" checked> DC</label>&nbsp;&nbsp;
							<label class="radio"><input type="radio" name="or_power_m3" id="or_power_battery_m3" value="battery"> battery</label>
						</div>
					</div>
				</div>

				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="inlineCheckboxes">Consumption</label>
						<div class="col-lg-8">
							<label class="checkbox inline"><input type="checkbox" id="cb_current_m3" value="current"> current</label>&nbsp;&nbsp;
							<label class="checkbox inline"><input type="checkbox" id="cb_voltage_m3" value="voltage"> voltage</label>&nbsp;&nbsp;
							<label class="checkbox inline"><input type="checkbox" id="cb_power_m3" value="power"> power</label>
						</div>
					</div>
	                 
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="consumption_period_m3">Period (&micro;s)</label>
						<div class="col-lg-7">
							<select id="consumption_period_m3" class="form-control">
								<option value="140">140</option>
								<option value="204">204</option>
								<option value="332">332</option>
								<option value="588">588</option>
								<option value="1100">1100</option>
								<option value="2116">2116</option>
								<option value="4156">4156</option>
								<option value="8244">8244</option>
							</select>
						</div>
					</div>
	                 
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="consumption_average_m3">Average</label>
						<div class="col-lg-8">
							<select id="consumption_average_m3" class="form-control">
								<option value="1">1</option>
								<option value="4">4</option>
								<option value="16">16</option>
								<option value="64">64</option>
								<option value="128">428</option>
								<option value="256">256</option>
								<option value="512">512</option>
								<option value="1024">1024</option>
							</select>
						</div>
					</div>
				</div>

				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
					<div class="form-group" style="width:100%;margin-bottom:10px">
						<label class="col-lg-4 control-label" for="radio_mode_m3">Radio mode</label>
						<div class="col-lg-8">
							<label class="radio"><input type="radio" name="radio_mode_m3" id="radio_mode_none_m3" value="none" data-target="#m3RadioNonePanel" checked> none</label>&nbsp;&nbsp;
							<label class="radio"><input type="radio" name="radio_mode_m3" id="radio_mode_measure_m3" value="measure" data-target="#m3RadioMeasurePanel"> measure</label>
						</div>
					</div>
					
					<div class="tab-content">
						<div id="m3RadioNonePanel" class="tab-pane active">
						
						</div>
	
						<div id="m3RadioMeasurePanel" class="tab-pane">	
							<div class="form-group" style="width:100%;margin-bottom:10px">
								<label class="col-lg-4 control-label" for="radio_channel_m3">Channel</label>
								<div class="col-lg-8">
									<select id="radio_channel_m3" class="form-control">
										<option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option>							</select>
								</div>
							</div>
			
							<div class="form-group" style="width:100%;margin-bottom:10px">
								<label class="col-lg-4 control-label" for="radio_power_m3">Power (unit?)</label>
								<div class="col-lg-8">
									<select id="radio_power_m3" class="form-control">
										<option value="3">3</option>
										<option value="2.8">2.8</option>
										<option value="2.3">2.3</option>
										<option value="1.8">1.8</option>
										<option value="1.3">1.3</option>
										<option value="0.7">0.7</option>
										<option value="0">0</option>
										<option value="-1">-1</option>
										<option value="-2">-2</option>
										<option value="-3">-3</option>
										<option value="-4">-4</option>
										<option value="-5">-5</option>
										<option value="-7">-7</option>
										<option value="-9">-9</option>
										<option value="-12">-12</option>
										<option value="-17">-17</option>
									</select>
								</div>
							</div>
			
							<div class="form-group" style="width:100%;margin-bottom:10px">
								<label class="col-lg-4 control-label" for="radio_frequency_m3">Frequency (ms)</label>
								<div class="col-lg-8">
									<input type="text" id="radio_frequency_m3" class="form-control"/>
								</div>
							</div>
						</div>	
					</div>
				</div>

				
				
				
				
			</div>              

<!-- ----------------- -->
<!--  A8 PROFILE FORM  -->
<!-- ----------------- -->	
              
              
			<!-- <div class="tab-pane" id="a8panel">A8 - NYI</div>  -->
			
<!-- ---------- -->
<!--  MAIN  END -->
<!-- ---------- -->			
			
			
		</div>
		<button id="btn_submit" class="btn btn-primary" type="submit">Save</button>

	</form>
</div> 

                
<script type="text/javascript">



var my_profiles = [];
var profilesLoaded = false;
var edit = false;
var index = -1;



/* ************ */
/*   on ready   */
/* ************ */
$(document).ready(function(){

	// prepare form for the new profile
	$("#btn_new").click(function(){
	    $("#profiles_txt_name").val("new_profile");
	    
	    $("#my_profiles_modal option:selected").removeAttr("selected");
	    
	});
	
	// init tab for node architecture 
	$('input[name="or_nodearch"]').click(function () {
	    $(this).tab('show');
	});
	
	// init tab for m3 radio mode 
	$('input[name="radio_mode_m3"]').click(function () {
	    $(this).tab('show');
	});
	
	loadProfiles();


});



/* ************************************* */
/* load all profiles and fill the select */
/* ************************************* */
function loadProfiles() {

    if (!profilesLoaded) {
        $("#div_error_profiles").addClass("alert-success");
        $("#div_error_profiles").removeClass("alert-danger");
        $("#div_error_profiles").html("Loading your profiles ...");
        $("#div_error_profiles").show();
        $.ajax({
            type: "GET",
            cache: false,
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            url: "/rest/profiles",
            success: function (data_server) {
                
                if(data_server == "") {
                    //no profile
                    return false;
                }
                
                my_profiles = JSON.parse(data_server);
                
                //fill profiles list
                for(var i = 0; i < my_profiles.length; i++) {
                    $("#my_profiles_modal").append(new Option(my_profiles[i].profilename,my_profiles[i].profilename));
                }
                
                //bind onClick event
                $("#my_profiles_modal").change(loadProfile);
                $("#div_error_profiles").hide();
                profilesLoaded=true;
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                $("#div_error_profiles").html(errorThrows);
                $("#div_error_profiles").show();
                $("#div_error_profiles").removeClass("alert-success");
                $("#div_error_profiles").addClass("alert-danger");
            }
        });
    }
}

/* ******************************************* */
/* load the selected profile and fill the form */
/* ******************************************* */
function loadProfile() {
    
    var profilename = $(this).val();

    var index = -1;
    
    for(var i = 0; i < my_profiles.length && index == -1; i++) {
        if(my_profiles[i].profilename == profilename) {
            index = i;
            break;
        }
    }
    
    if(index != -1) {
        $("#profiles_txt_name").val(my_profiles[index].profilename);

        /* search for nodearch and show right profile form */
        var nodearch = my_profiles[i].nodearch;
        if (!nodearch) nodearch="wsn430";
        $("input[name='or_nodearch']").val([nodearch]);
        $("#or_nodearch_"+nodearch+"").tab('show');
        
        if(nodearch=="wsn430") {
	        $('#consumption_frequency_wsn430').val(my_profiles[i].consumption.frequency);
	        $('#sensor_frequency_wsn430').val(my_profiles[i].sensor.frequency);
	        $('#radio_frequency_wsn430').val(my_profiles[i].radio.frequency);
	        
	        $("input[name='or_power_wsn430']").val([my_profiles[i].power]);
	        $("#cb_current_wsn430").prop("checked",my_profiles[i].consumption.current);
	        $("#cb_voltage_wsn430").prop("checked",my_profiles[i].consumption.voltage);
	        $("#cb_power_wsn430").prop("checked",my_profiles[i].consumption.power);
	        
	        $("#cb_temperature_wsn430").prop("checked",my_profiles[i].sensor.temperature);
	        $("#cb_luminosity_wsn430").prop("checked",my_profiles[i].sensor.luminosity);
	        
	        $("#cb_rssi_wsn430").prop("checked",my_profiles[i].radio.rssi);
        } else if (nodearch=="m3") {
	        $('#consumption_period_m3').val(my_profiles[i].consumption.period);
	        $('#consumption_average_m3').val(my_profiles[i].consumption.average);
	        
	        $("input[name='or_power_m3']").val([my_profiles[i].power]);
	        $("#cb_current_m3").prop("checked",my_profiles[i].consumption.current);
	        $("#cb_voltage_m3").prop("checked",my_profiles[i].consumption.voltage);
	        $("#cb_power_m3").prop("checked",my_profiles[i].consumption.power);

	        if(my_profiles[i].radio!=null) {
	        	$("input[name='radio_mode_m3']").val([my_profiles[i].radio.mode]);
			$("input[name='radio_mode_m3']").tab('show');
	        	$("#radio_channel_m3").val(my_profiles[i].radio.channel);
	        	$("#radio_power_m3").val(my_profiles[i].radio.power);
	        	$("#radio_frequency_m3").val(my_profiles[i].radio.frequency);
	        } else {
	        	$("#radio_mode_none_m3").prop("checked",true);
			$("#radio_mode_none_m3").tab('show');
	        }
        //TODO } else if (nodearch=="a8") { 
        }
    }
    
}

/* ************************* */
/*  delete selected profile  */
/* ************************* */
$("#btn_delete").click(function(){
    var profile_name = $("#my_profiles_modal").val();
    if(confirm("Delete "+profile_name+" profile?")) {
	    $.ajax({
	        type: "DELETE",
	        dataType: "text",
	        contentType: "application/json; charset=utf-8",
	        url: "/rest/profiles/"+profile_name,
	        success: function (data_server) {
	            
	            $("#my_profiles_modal option:selected").remove();
	            
	            $("#div_error_profiles").html("Profile deleted.");
	            $("#div_error_profiles").show();
	            $("#div_error_profiles").removeClass("alert-danger");
	            $("#div_error_profiles").addClass("alert-success");
	            setTimeout( "$('#div_error_profiles').hide()", 2000); 
	        },
	        error: function (XMLHttpRequest, textStatus, errorThrows) {
	            $("#div_error_profiles").html(errorThrows);
	            $("#div_error_profiles").show();
	            $("#div_error_profiles").removeClass("alert-success");
	            $("#div_error_profiles").addClass("alert-danger");
	        }
	    });
    }
});

/* ****************************** */
/* submit a new or edited profile */
/* ****************************** */
$("#form_part").bind("submit", function (e) {

    e.preventDefault();

    // check profile name 
    var regExp = /^[0-9A-z]*$/; ;
    if(regExp.test($("#profiles_txt_name").val()) == false){
       alert("You must set a profile name with only word characters [0-9A-Za-z_]");
       return false;
    }

    //check profile existence 
    for(var i = 0; i < $("#my_profiles_modal option").length; i++) {
        if($($("#my_profiles_modal option")[i]).val() == $("#profiles_txt_name").val()) {
            edit = true;
            index = i;
            break;
        }
    }

    // construct JSON 
    var nodearch = "wsn430";
    if($('#or_nodearch_m3').prop('checked')) nodearch="m3";
    else if($('#or_nodearch_a8').prop('checked')) nodearch="a8";

    var profile_json = "";

    if(nodearch == "wsn430") {

	    consumption = {
	        "current":$('#cb_current_wsn430').prop('checked'),
	        "voltage":$('#cb_voltage_wsn430').prop('checked'),
	        "power":$('#cb_power_wsn430').prop('checked'),
	        "frequency":$('#consumption_frequency_wsn430').val()
	    };
	
	    sensor = {
	        "luminosity":$('#cb_luminosity_wsn430').prop('checked'),
	        "temperature":$('#cb_temperature_wsn430').prop('checked'),
	        "frequency":$('#sensor_frequency_wsn430').val()
	    };
	
	    radio = {
	        "rssi":$('#cb_rssi_wsn430').prop('checked'),
	        "frequency":$('#radio_frequency_wsn430').val()
	    };
	
	    profile_json = {
	        "profilename":$("#profiles_txt_name").val(),
	        "nodearch":nodearch,
	        "power":$("input[name=or_power_wsn430]:checked").val(),
	        "consumption":consumption,
	        "sensor":sensor,
	        "radio":radio
	    };
    } else if (nodearch == "m3") {

	    consumption = {
	        "current":$('#cb_current_m3').prop('checked'),
	        "voltage":$('#cb_voltage_m3').prop('checked'),
	        "power":$('#cb_power_m3').prop('checked'),
	        "period":$('#consumption_period_m3').val(),
	        "average":$('#consumption_average_m3').val()
	    };

	    radio_mode = $("input[name=radio_mode_m3]:checked").val();
	    frequency = $('#radio_frequency_m3').val();
	    if(radio_mode == "measure" && (frequency<2 || frequency>499)){
			alert("You must set a valid Radio frequency : range 2..499");
			return false;
	    }
	
	    radio = {
	    	"mode":radio_mode,
	        "channel":$('#radio_channel_m3').val(),
	        "power":$('#radio_power_m3').val(),
	        "frequency":$('#radio_frequency_m3').val()
	    };
	
	    profile_json = {
	        "profilename":$("#profiles_txt_name").val(),
	        "nodearch":nodearch,
	        "power":$("input[name=or_power_m3]:checked").val(),
	        "consumption":consumption
	    };
	    if(radio_mode!="none") profile_json.radio=radio;

    } else {
	
	    profile_json = {
	        "profilename":$("#profiles_txt_name").val(),
	        "nodearch":nodearch
	    };

    }
    if(edit) {
        url = "/rest/profiles/"+profile_json.profilename;
        type = "PUT";
    } else {
        url = "/rest/profiles";
        type = "POST";
    }    

    //send edit or create request
    $.ajax({
        type: type,
        dataType: "text",
        data: JSON.stringify(profile_json),
        contentType: "application/json; charset=utf-8",
        url: url,
        success: function (data_server) {

            var result_msg="";
            var edit = false;
            var index = -1;
            //check if profile already exist
            for(var i = 0; i < $("#my_profiles_modal option").length; i++) {
                if($($("#my_profiles_modal option")[i]).val() == profile_json.profilename) {
                    edit = true;
                    index = i;
                    break;
                }
            }
            
            if(!edit) {                
            	result_msg="Profile created.";
                $("#my_profiles_modal").append(new Option(profile_json.profilename,profile_json.profilename));
                my_profiles.push(profile_json);
            } else {
            	result_msg="Profile edited.";
                my_profiles[index] = profile_json;
            }
            $("#div_error_profiles").removeClass("alert-danger");
            $("#div_error_profiles").addClass("alert-success");
            $("#div_error_profiles").html(result_msg);
            $("#div_error_profiles").show();
            setTimeout( "$('#div_error_profiles').hide()", 2000); 
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_error_profiles").removeClass("alert-success");
            $("#div_error_profiles").addClass("alert-danger");
            $("#div_error_profiles").html(errorThrows);
            $("#div_error_profiles").show();
        }
    });

    return false;
});

</script>
