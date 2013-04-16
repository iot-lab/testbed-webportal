<div class="span3">
	<div class="alert alert-info">
		<img src="img/help.png"> To <b>create</b> a new profile click the <b>New</b> button, fill the form and click <b>Save</b>. 
		<br/><br/>To <b>edit</b> a profile, click on the profile name on the list, edit settings, and click <b>Save</b>.
		<br/><br/>When finished, close the window by clicking <b>X</b>.
	</div>
	<select id="my_profiles_modal" size="15"></select>
	<p>
		<button class="btn btn" id="btn_new">New</button>
		<button class="btn btn-danger" id="btn_delete">Delete</button>
	</p>
</div>

<div class="span6">           
	<form class="well form-horizontal" id="form_part">

		<div class="control-group">
			<label class="control-label" for="txt_name">Name:</label>
			<div class="controls"><input id="profiles_txt_name" type="text" class="input-xlarge" required="required"></div>
		</div>

		<div class="control-group">
			<label class="control-label">Node architecture</label>
			<div class="controls">
				<label class="radio inline"><input type="radio" name="or_nodearch" id="or_nodearch_wsn430" value="wsn430" data-target="#wsn430panel" checked>WSN 430</label>
				<label class="radio inline"><input type="radio" name="or_nodearch" id="or_nodearch_m3" value="m3" data-target="#m3panel">M3</label>
				<label class="radio inline"><input type="radio" name="or_nodearch" id="or_nodearch_a8" value="a8" data-target="#a8panel">A8</label>
			</div>
		</div>
                   
		<div class="tab-content">
			<div id="wsn430panel" class="tab-pane active">  	
				<div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
					<div class="control-group">
						<label class="control-label">Power mode</label>
						<div class="controls">
							<label class="radio"><input type="radio" name="or_power" id="or_power_dc" value="dc" checked>dc</label>
							<label class="radio"><input type="radio" name="or_power" id="or_power_battery" value="battery">battery</label>
						</div>
					</div>
				</div>

				
				
				
				
				
				
                <div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
                <div class="control-group">
                    <label class="control-label" for="inlineCheckboxes">Consumption</label>
                    <div class="controls">
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_current" value="current"> current
                      </label>
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_voltage" value="voltage"> voltage
                      </label>
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_power" value="power"> power
                      </label>
                    </div>
                  </div>
                 
                <div class="control-group">
                    <label class="control-label" for="consumption_frequency">Frequency (ms)</label>
                    <div class="controls">
                      <select id="consumption_frequency" class="input-small">
                        <option value="70">70</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="5000">5000</option>
                      </select>
                    </div>
                  </div>
                </div>


                <div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
                <div class="control-group">
                    <label class="control-label" for="inlineCheckboxes">Sensors</label>
                    <div class="controls">
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_luminosity" value="luminosity"> luminosity
                      </label>
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_temperature" value="temperature"> temperature
                      </label>
                    </div>
                  </div>

                <div class="control-group">
                    <label class="control-label" for="sensor_frequency">Frequency (ms)</label>
                    <div class="controls">
                      <select id="sensor_frequency" class="input-small">
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                        <option value="30000">30000</option>
                      </select>
                    </div>
                  </div>
                </div> 

                <div style="border: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;padding:5px 0px 0px 0px;margin:5px;">
                <div class="control-group">
                    <label class="control-label" for="inlineCheckboxes">Radio</label>
                    <div class="controls">
                      <label class="checkbox inline">
                        <input type="checkbox" id="cb_rssi" value="luminosity"> rssi
                      </label>
                    </div>
                  </div>

                <div class="control-group">
                    <label class="control-label" for="radio_frequency">Frequency (ms)</label>
                    <div class="controls">
                      <select id="radio_frequency" class="input-small">
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
             
              <div class="tab-pane" id="m3panel">M3 - NYI</div>
              <div class="tab-pane" id="a8panel">A8 - NYI</div>
            </div>
             

		<button id="btn_submit" class="btn btn-primary" type="submit">Save</button>

	</form>
</div> 

                
<script type="text/javascript">



var my_profiles = [];
var profilesLoaded = false;



/* ************ */
/*   on ready   */
/* ************ */
$(document).ready(function(){


	//prepare form for the new profile
	$("#btn_new").click(function(){
	    $("#profiles_txt_name").val("new_profile");
	    
	    $("#my_profiles_modal option:selected").removeAttr("selected");
	    
	});
	
	// init tab for node architecture 
	$('input[name="or_nodearch"]').click(function () {
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
        $("#div_error_profiles").removeClass("alert-error");
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
                $("#div_error_profiles").addClass("alert-error");
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
	        $('#consumption_frequency').val(my_profiles[i].consumption.frequency);
	        $('#sensor_frequency').val(my_profiles[i].sensor.frequency);
	        $('#radio_frequency').val(my_profiles[i].radio.frequency);
	        
	        $("input[name='or_power']").val([my_profiles[i].power]);
	        $("#cb_current").attr("checked",my_profiles[i].consumption.current);
	        $("#cb_voltage").attr("checked",my_profiles[i].consumption.votage);
	        $("#cb_power").attr("checked",my_profiles[i].consumption.power);
	        
	        $("#cb_temperature").attr("checked",my_profiles[i].sensor.temperature);
	        $("#cb_luminosity").attr("checked",my_profiles[i].sensor.luminosity);
	        
	        $("#cb_rssi").attr("checked",my_profiles[i].radio.rssi);
        } else if (nodearch=="m3") {
        } else if (nodearch=="a8") {
        } else {
        }
    }
    
}

/* ************************* */
/*  delete selected profile  */
/* ************************* */
$("#btn_delete").click(function(){
    var profile_name = $("#my_profiles_modal").val();
    $.ajax({
        type: "DELETE",
        dataType: "text",
        contentType: "application/json; charset=utf-8",
        url: "/rest/profile/"+profile_name,
        success: function (data_server) {
            
            $("#my_profiles_modal option:selected").remove();
            
            $("#div_error_profiles").html("Delete ok");
            $("#div_error_profiles").show();
            $("#div_error_profiles").removeClass("alert-error");
            $("#div_error_profiles").addClass("alert-success");
            setTimeout( "$('#div_error_profiles').hide()", 2000); 
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_error_profiles").html(errorThrows);
            $("#div_error_profiles").show();
            $("#div_error_profiles").removeClass("alert-success");
            $("#div_error_profiles").addClass("alert-error");
        }
    });
    
});

/* ****************************** */
/* submit a new or edited profile */
/* ****************************** */
$("#form_part").bind("submit", function (e) {

    e.preventDefault();

    var regExp = /^[0-9A-z]*$/; ;
    if(regExp.test($("#profiles_txt_name").val()) == false){
       alert("You must set a profile name with only word characters [0-9A-Za-z_]");
       return false;
    }

    var nodearch = "wsn430";
    if($('#or_nodearch_m3').is(':checked')) nodearch="m3";
    else if($('#or_nodearch_a8').is(':checked')) nodearch="a8";

    var profile_json = "";

    if(nodearch == "wsn430") {

	    consumption = {
	        "current":$('#cb_current').is(':checked'),
	        "voltage":$('#cb_voltage').is(':checked'),
	        "power":$('#cb_power').is(':checked'),
	        "frequency":$('#consumption_frequency').val()
	    };
	
	    sensor = {
	        "luminosity":$('#cb_luminosity').is(':checked'),
	        "temperature":$('#cb_temperature').is(':checked'),
	        "frequency":$('#sensor_frequency').val()
	    };
	
	    radio = {
	        "rssi":$('#cb_rssi').is(':checked'),
	        "frequency":$('#radio_frequency').val()
	    };
	
	    profile_json = {
	        "profilename":$("#profiles_txt_name").val(),
	        "nodearch":nodearch,
	        "power":$("input[name=or_power]:checked").val(),
	        "consumption":consumption,
	        "sensor":sensor,
	        "radio":radio
	    };
    } else if (nodearch == "m3") {
	
	    profile_json = {
	        "profilename":$("#profiles_txt_name").val(),
	        "nodearch":nodearch
	    };

    } else {
	
	    profile_json = {
	        "profilename":$("#profiles_txt_name").val(),
	        "nodearch":nodearch
	    };

    }
    

    //send edit or create request
    $.ajax({
        type: "POST",
        dataType: "text",
        data: JSON.stringify(profile_json),
        contentType: "application/json; charset=utf-8",
        url: "/rest/profile",
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
            }
            else {
            	result_msg="Profile edited.";
                my_profiles[index] = profile_json;
                 
            }
            $("#div_error_profiles").removeClass("alert-error");
            $("#div_error_profiles").addClass("alert-success");
            $("#div_error_profiles").html(result_msg);
            $("#div_error_profiles").show();
            setTimeout( "$('#div_error_profiles').hide()", 2000); 
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_error_profiles").removeClass("alert-success");
            $("#div_error_profiles").addClass("alert-error");
            $("#div_error_profiles").html(errorThrows);
            $("#div_error_profiles").show();
        }
    });

    return false;
});

</script>