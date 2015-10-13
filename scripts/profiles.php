<!-- ------ -->
<!--  MAIN  -->
<!-- ------ -->



<div class="col-md-8">


<div class="row">
<div class="col-lg-12">

<span><b>Select a profile:</b></span>
<select class="form-control" id="my_profiles_modal" style="margin-bottom:5px;display:inline-block;width:200px">
        <optgroup label="WSN430" id="wsn430Profiles_modal"></optgroup>
        <optgroup label="M3" id="m3Profiles_modal"></optgroup>
        <optgroup label="A8" id="a8Profiles_modal"></optgroup>
    </select>

<button id="btn_submit" class="btn btn-primary" type="submit">Save</button>
<button class="btn btn-danger" id="btn_delete">Delete</button>
|
<button class="btn btn-default" id="btn_new">New</button>


</div>
</div>


<form class="well form-horizontal form-inline" id="form_part" role="form">

<div class="form-group" style="width:100%;margin-bottom:10px">
    <label class="col-lg-4 control-label" for="txt_name">Name</label>

    <div class="col-lg-3"><input id="profiles_txt_name" type="text" class="form-control" required="required"></div>
</div>

<div class="form-group" style="width:100%;margin-bottom:10px">
    <label class="col-lg-4 control-label">Node architecture</label>

    <div class="col-lg-8" style="float:left">
        <label class="radio"><input type="radio" name="or_nodearch" id="or_nodearch_wsn430" value="wsn430"
                                    data-target="#wsn430panel" checked> WSN430</label>&nbsp;&nbsp;
        <label class="radio"><input type="radio" name="or_nodearch" id="or_nodearch_m3" value="m3"
                                    data-target="#m3panel"> M3</label>&nbsp;&nbsp;
        <label class="radio"><input type="radio" name="or_nodearch" id="or_nodearch_a8" value="a8"
                                    data-target="#m3panel"> A8</label>&nbsp;&nbsp;                           
    </div>
</div>


<div class="tab-content">

<!-- --------------------- -->
<!--  WSN430 PROFILE FORM  -->
<!-- --------------------- -->

<div id="wsn430panel" class="tab-pane active">
    <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label">Power mode</label>

            <div class="col-lg-8">
                <label class="radio"><input type="radio" name="or_power_wsn430" id="or_power_dc_wsn430" value="dc"
                                            checked> DC</label>&nbsp;&nbsp;
                <label class="radio"><input type="radio" name="or_power_wsn430" id="or_power_battery_wsn430"
                                            value="battery"> battery</label>
            </div>
        </div>
    </div>

    <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="inlineCheckboxes">Consumption</label>

            <div class="col-lg-8">
                <label class="checkbox inline"><input type="checkbox" id="cb_current_wsn430" value="current">
                    current</label>&nbsp;&nbsp;
                <label class="checkbox inline"><input type="checkbox" id="cb_voltage_wsn430" value="voltage">
                    voltage</label>&nbsp;&nbsp;
                <label class="checkbox inline"><input type="checkbox" id="cb_power_wsn430" value="power"> power</label>
            </div>
        </div>

        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="consumption_frequency_wsn430">Period (ms)</label>

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


    <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="inlineCheckboxes">Sensors</label>

            <div class="col-lg-8">
                <label class="checkbox"><input type="checkbox" id="cb_luminosity_wsn430" value="luminosity"> luminosity</label>&nbsp;&nbsp;
                <label class="checkbox"><input type="checkbox" id="cb_temperature_wsn430" value="temperature">
                    temperature</label>
            </div>
        </div>

        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="sensor_frequency_wsn430">Period (ms)</label>

            <div class="col-lg-8">
                <select id="sensor_frequency_wsn430" class="form-control">
                    <option value="5000">5000</option>
                    <option value="10000">10000</option>
                    <option value="30000">30000</option>
                </select>
            </div>
        </div>
    </div>

    <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="inlineCheckboxes">Radio</label>

            <div class="col-lg-8">
                <label class="checkbox inline"><input type="checkbox" id="cb_rssi_wsn430" value="luminosity">
                    rssi</label>
            </div>
        </div>

        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="radio_frequency_wsn430">Period (ms)</label>

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
    <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label">Power mode</label>

            <div class="col-lg-8">
                <label class="radio"><input type="radio" name="or_power_m3" id="or_power_dc_m3" value="dc" checked>
                    DC</label>&nbsp;&nbsp;
                <label class="radio"><input type="radio" name="or_power_m3" id="or_power_battery_m3" value="battery">
                    battery</label>
            </div>
        </div>
    </div>

    <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="inlineCheckboxes">Consumption</label>

            <div class="col-lg-8">
                <label class="checkbox inline"><input type="checkbox" id="cb_current_m3" value="current">
                    current</label>&nbsp;&nbsp;
                <label class="checkbox inline"><input type="checkbox" id="cb_voltage_m3" value="voltage">
                    voltage</label>&nbsp;&nbsp;
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
                    <option value="8244" selected="selected">8244</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="consumption_average_m3">Average</label>

            <div class="col-lg-8">
                <select id="consumption_average_m3" class="form-control">
                    <option value="1">1</option>
                    <option value="4" selected="selected">4</option>
                    <option value="16">16</option>
                    <option value="64">64</option>
                    <option value="256">256</option>
                    <option value="128">428</option>
                    <option value="512">512</option>
                    <option value="1024">1024</option>
                </select>
            </div>
        </div>
    </div>

    <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;" id="m3RadioMode">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="radio_mode_m3">Radio mode</label>

            <div class="col-lg-8">
                <label class="radio"><input type="radio" name="radio_mode_m3" id="radio_mode_none_m3" value="none"
                                            data-target="#m3RadioNonePanel" checked> none</label>&nbsp;&nbsp;
                <label class="radio"><input type="radio" name="radio_mode_m3" id="radio_mode_measure_m3" value="rssi"
                                            data-target="#m3RadioMeasurePanel" onchange="visibilityRadioNum();"> rssi</label>&nbsp;&nbsp;
                <label class="radio"><input type="radio" name="radio_mode_m3" id="radio_mode_sniffer_m3" value="sniffer"
                                            data-target="#m3RadioSnifferPanel">sniffer</label>
            </div>
        </div>

        <div class="tab-content">
            <div id="m3RadioNonePanel" class="tab-pane active">

            </div>
            <div id="m3RadioSnifferPanel" class="tab-pane">
            	<div class="form-group" style="width:100%;margin-bottom:10px">
                    <label class="col-lg-4 control-label" for="radio_channel_m3">Channels</label>
                    <div class="col-lg-8">
                        <select id="radio_sniffer_channel_m3" class="form-control">
                            <option value='11' selected="selected">11</option>
                            <option value='12'>12</option>
                            <option value='13'>13</option>
                            <option value='14'>14</option>
                            <option value='15'>15</option>
                            <option value='16'>16</option>
                            <option value='17'>17</option>
                            <option value='18'>18</option>
                            <option value='19'>19</option>
                            <option value='20'>20</option>
                            <option value='21'>21</option>
                            <option value='22'>22</option>
                            <option value='23'>23</option>
                            <option value='24'>24</option>
                            <option value='25'>25</option>
                            <option value='26'>26</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="m3RadioMeasurePanel" class="tab-pane">
                <div class="form-group" style="width:100%;margin-bottom:10px">
                    <label class="col-lg-4 control-label" for="radio_channel_m3">Channels</label>
                    <div class="col-lg-8">
                        <select multiple id="radio_channel_m3" class="form-control" onchange="visibilityRadioNum();">
                            <option value='11' selected="selected">11</option>
                            <option value='12'>12</option>
                            <option value='13'>13</option>
                            <option value='14'>14</option>
                            <option value='15'>15</option>
                            <option value='16'>16</option>
                            <option value='17'>17</option>
                            <option value='18'>18</option>
                            <option value='19'>19</option>
                            <option value='20'>20</option>
                            <option value='21'>21</option>
                            <option value='22'>22</option>
                            <option value='23'>23</option>
                            <option value='24'>24</option>
                            <option value='25'>25</option>
                            <option value='26'>26</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="width:100%;margin-bottom:10px">
                    <label class="col-lg-4 control-label" for="radio_period_m3">Period (ms)</label>

                    <div class="col-lg-2">
                        <!--<input type="text" id="radio_period_m3" class="form-control"/>-->
                        <input type="number" id="radio_period_m3" class="form-control" min="1" max="65535" step="1"
                               value="1000"/>
                    </div>
                </div>
                <div class="form-group" style="width:100%;margin-bottom:10px" id="m3RadioNumChannel">
                    <label class="col-lg-4 control-label" for="radio_num_per_channel_m3">Number of measure per
                        channel</label>

                    <div class="col-lg-8">
                        <!--<input type="text" id="radio_num_per_channel_m3" value="0" class="form-control"/>-->
                        <input type="range" id="radio_num_per_channel_m3" value="1" max="255" min="1" step="1"
                               onchange="updateRadioNum(this.value);"/>
                        <output for="radio_num_per_channel_m3" id="radio_num_m3"/>
                    </div>
                </div>
            </div>
       </div>




   <div style="border-top: 1px solid rgba(0, 0, 0, 0.05);border-radius:4px;margin:5px;" id="m3Mobility">
        <div class="form-group" style="width:100%;margin-bottom:10px">
            <label class="col-lg-4 control-label" for="mobile_mode_m3">Mobile</label>

            <div class="col-lg-8">
                <label class="radio"><input type="radio" name="mobile_mode_m3" id="mobile_mode_none_m3" value="none"
                                            data-target="#m3MobileNonePanel" checked> none</label>&nbsp;&nbsp;
                <label class="radio"><input type="radio" name="mobile_mode_m3" id="mobile_mode_predefined_m3" value="predefined"
                                            data-target="#m3MobilePredefinedPanel"> predefined</label>&nbsp;&nbsp;
				<!--<label class="radio"><input type="radio" name="mobile_mode_m3" id="mobile_mode_controlled_m3" value="controlled"
                                            data-target="#m3MobileControlledPanel"> controlled</label>-->
            </div>
        </div>

        <div class="tab-content">
            <div id="m3MobileNonePanel" class="tab-pane active">

            </div>

            <div id="m3MobilePredefinedPanel" class="tab-pane">
		<label class="col-lg-4 control-label">Site:</label>
                <div class="col-lg-8">
                <select id="mobile_site_m3" class="form-control">
                </select>
                </div>

                <label class="col-lg-4 control-label">Trajectory:</label>
                <div class="col-lg-8">
                <select id="mobile_trajectory_m3" class="form-control">
                </select>
                </div>
            </div>

	    <!--  <div id="m3MobileControlledPanel" class="tab-pane active">

            </div>-->

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

</form>


</div>


<div class="col-md-4">

    <div class="alert alert-info">
        <img src="img/help.png"> To <b>create</b> a new profile click the <b>New</b> button, fill the form and click <b>Save</b>.
        <br/><br/>To <b>edit</b> a profile, click on the profile name on the list, edit settings, and click <b>Save</b>.
    </div>


<div id="div_error_profiles" class="alert" style="display:none"></div>

</div>

<script type="text/javascript">


var my_profiles = [];
var profilesLoaded = false;
var edit = false;
var index = -1;
var radio_num = 0;
var profilename = "";


function updateRadioNum(num) {
    $("#radio_num_m3").val(num);
}

function visibilityRadioNum() {
	if ($("#radio_mode_measure_m3").is(':checked')) {
		$("#radio_mode_measure_m3").tab('show');
	    var count = $("#radio_channel_m3 :selected").length;
	    if (count > 1) {
	        $("#m3RadioNumChannel").show();
	        radio_num = $("#radio_num_per_channel_m3").val()
	        $("#radio_num_m3").val(radio_num)
	    }
	    else {
	        $("#m3RadioNumChannel").hide();
	        radio_num = 0;
	    }
	}
	else if ($("#radio_mode_sniffer_m3").is(':checked')) {
		$("#radio_mode_sniffer_m3").tab('show');
	} 
}

/* ************ */
/*   on ready   */
/* ************ */
$(document).ready(function () {

    // prepare form for the new profile
    $("#btn_new").click(function () {
        $("#form_part")[0].reset();
        $("#profiles_txt_name").val("<name>");

        $("#my_profiles_modal option:selected").removeAttr("selected");

        $("#m3panel").removeClass("active");
        $("#wsn430panel").addClass("active");
        
        $("#m3MobilePredefinedPanel").removeClass("active");
        $("#m3MobileControlledPanel").removeClass("active");
        $("#m3RadioMeasurePanel").removeClass("active");
        $("#m3RadioSnifferPanel").removeClass("active");
	loadMobilities();
    });

    // init tab for node architecture
    $('input[name="or_nodearch"]').click(function () {
        $(this).tab('show');
    });

    // init tab for m3 radio mode
    $('input[name="radio_mode_m3"]').click(function () {
        $(this).tab('show');
    });

    $('input[name="mobile_mode_m3"]').click(function () {
        $(this).tab('show');
    });

    visibilityRadioNum();
    loadProfiles();

    loadMobilities();
});

/* *************** */
/* load mobilities */
/* *************** */
function loadMobilities() {



        $.ajax({
            type: "GET",
            cache: false,
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            url: "/rest/robots/mobility",
            success: function (data_server) {

                if (data_server == "") {
                    //no mobility
                    return false;
                }

                var mobility = JSON.parse(data_server);

                //populate mobile site
                for(var i in mobility) {
                        $("#mobile_site_m3").append(new Option(i, i));
                }

                //populate circuits according to selected site
                $("#mobile_site_m3").on("change", function(){
                        $("#mobile_trajectory_m3").empty();
  
                        for(var i in mobility) {
                            if(i == $(this).val()) {
                                for (var j in mobility[i]) {
                                if(typeof mobility[i][j].trajectory_name != "undefined")
                                    $("#mobile_trajectory_m3").append(new Option(mobility[i][j].trajectory_name, mobility[i][j].trajectory_name));
                                }
                            }
                        }
                });
                $("#mobile_site_m3").trigger("change");

            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                $("#div_error_profiles").html(errorThrows);
                $("#div_error_profiles").show();
                $("#div_error_profiles").removeClass("alert-success");
                $("#div_error_profiles").addClass("alert-danger");
            }
        });
}


/* ************************************* */
/* load all profiles and fill the select */
/* ************************************* */
function loadProfiles() {

    $("#wsn430Profiles_modal").empty();
    $("#m3Profiles_modal").empty();
    $("#a8Profiles_modal").empty();

        $.ajax({
            type: "GET",
            cache: false,
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            url: "/rest/profiles",
            success: function (data_server) {

                if (data_server == "") {
                    //no profile
                    return false;
                }

                my_profiles = JSON.parse(data_server);
                //fill profiles list
                for (var i = 0; i < my_profiles.length; i++) {
                    $("#" + my_profiles[i].nodearch + "Profiles_modal").append(new Option(my_profiles[i].profilename, my_profiles[i].profilename));
                }

                //bind onClick event
                $("#my_profiles_modal").change(loadProfile);
                //$("#div_error_profiles").hide();
                profilesLoaded = true;

                if(profilename != "") {
                    $("#my_profiles_modal option[value="+profilename+"]").attr("selected","selected");
                }
                else {
                    loadProfile();
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                $("#div_error_profiles").html(errorThrows);
                $("#div_error_profiles").show();
                $("#div_error_profiles").removeClass("alert-success");
                $("#div_error_profiles").addClass("alert-danger");
            }
        });
}


/* ******************************************* */
/* load the selected profile and fill the form */
/* ******************************************* */
function loadProfile() {

    profilename = $("#my_profiles_modal").val();
    if(profilename == undefined) {
        return;
    }

    var index = -1;

    for (var i = 0; i < my_profiles.length && index == -1; i++) {
        if (my_profiles[i].profilename == profilename) {
            index = i;
            break;
        }
    }

    if (index != -1) {
        $("#profiles_txt_name").val(my_profiles[index].profilename);

        /* search for nodearch and show right profile form */
        var nodearch = my_profiles[i].nodearch;
        if (!nodearch) nodearch = "wsn430";
        $("input[name='or_nodearch']").val([nodearch]);
        $("#or_nodearch_" + nodearch + "").tab('show');

        if (nodearch == "wsn430") {
            $("input[name='or_power_wsn430']").val([my_profiles[i].power]);

            if (my_profiles[i].consumption != null) {
                $("#cb_current_wsn430").prop("checked", my_profiles[i].consumption.current);
                $("#cb_voltage_wsn430").prop("checked", my_profiles[i].consumption.voltage);
                $("#cb_power_wsn430").prop("checked", my_profiles[i].consumption.power);
                $('#consumption_frequency_wsn430').val(my_profiles[i].consumption.frequency);
            }
            if (my_profiles[i].sensor != null) {
                $("#cb_temperature_wsn430").prop("checked", my_profiles[i].sensor.temperature);
                $("#cb_luminosity_wsn430").prop("checked", my_profiles[i].sensor.luminosity);
                $('#sensor_frequency_wsn430').val(my_profiles[i].sensor.frequency);
            }
            if (my_profiles[i].radio != null) {
                $("#cb_rssi_wsn430").prop("checked", my_profiles[i].radio.rssi);
                $('#radio_frequency_wsn430').val(my_profiles[i].radio.frequency);
            }
        //} else if (nodearch == "m3") {
        // a8 and m3 nodearch = same profile form
        } else {
            $("input[name='or_power_m3']").val([my_profiles[i].power]);
            if (my_profiles[i].consumption != null) {
                $('#consumption_period_m3').val(my_profiles[i].consumption.period);
                $('#consumption_average_m3').val(my_profiles[i].consumption.average);
                $("#cb_current_m3").prop("checked", my_profiles[i].consumption.current);
                $("#cb_voltage_m3").prop("checked", my_profiles[i].consumption.voltage);
                $("#cb_power_m3").prop("checked", my_profiles[i].consumption.power);
            }
            if (my_profiles[i].radio != null) {
                $("input[name='radio_mode_m3']").val([my_profiles[i].radio.mode]);
                $("input[name='radio_mode_m3']").tab('show');
				var radio_mode = my_profiles[i].radio.mode
				if (radio_mode == "rssi") {
	                $("#radio_channel_m3").val(my_profiles[i].radio.channels);
	                $("#radio_period_m3").val(my_profiles[i].radio.period);
	                $("#radio_num_per_channel_m3").val(my_profiles[i].radio.num_per_channel);
	                $("#radio_num_m3").val(my_profiles[i].radio.num_per_channel);
				// radio_mode = sniffer
				} else {
					// one channel at the moment
		        	$("#radio_sniffer_channel_m3").val(my_profiles[i].radio.channels[0]);
		        }
				visibilityRadioNum();     
            } else {
                $("#radio_mode_none_m3").prop("checked", true);
                $("#radio_mode_none_m3").tab('show');
            }

            if (my_profiles[i].mobility.type == "predefined" || my_profiles[i].mobility.type == "controlled") {
                $("input[name='mobile_mode_m3']").tab('show');
                $("#mobile_mode_predefined_m3").prop("checked", true);
                $("#mobile_mode_predefined_m3").tab('show');
                $("#mobile_site_m3").val(my_profiles[i].mobility.site_name);
                $("#mobile_site_m3").trigger("change");
                $("#mobile_trajectory_m3").val(my_profiles[i].mobility.trajectory_name);
            }

	    	//else if (my_profiles[i].mobility.type == "controlled") {
            //    $("input[name='mobile_mode_m3']").tab('show');
            //    $("#mobile_mode_controlled_m3").prop("checked", true);
            //    $("#mobile_mode_controlled_m3").tab('show');
            //}
            else {
            	$("#mobile_mode_no_m3").prop("checked", true);
                $("#mobile_mode_no_m3").tab('show');
            } 
            //TODO } else if (nodearch=="a8") {
        }
    }

}

/* ************************* */
/*  delete selected profile  */
/* ************************* */
$("#btn_delete").click(function () {
    var profile_name = $("#my_profiles_modal").val();
    if (confirm("Delete " + profile_name + " profile?")) {
        $.ajax({
            type: "DELETE",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            url: "/rest/profiles/" + profile_name,
            success: function (data_server) {

                profilename = "";
                loadProfiles();

                $("#div_error_profiles").html("Profile deleted.");
                $("#div_error_profiles").show();
                $("#div_error_profiles").removeClass("alert-danger");
                $("#div_error_profiles").addClass("alert-success");
                setTimeout("$('#div_error_profiles').hide()", 2000);

                $("#form_part")[0].reset();
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
$("#btn_submit").on("click", function (e) {

  //  e.preventDefault();

    // check profile name 
    var regExp = /^[0-9A-z]*$/;

    if (regExp.test($("#profiles_txt_name").val()) == false) {
        alert("You must set a profile name with only word characters [0-9A-Za-z_]");
        return false;
    }

    //check profile existence 
    for (var i = 0; i < $("#my_profiles_modal option").length; i++) {
        if ($($("#my_profiles_modal option")[i]).val() == $("#profiles_txt_name").val()) {
            edit = true;
            index = i;
            break;
        }
    }

    // construct JSON 
    var nodearch = "wsn430";
    if ($('#or_nodearch_m3').prop('checked')) nodearch = "m3";
    else if ($('#or_nodearch_a8').prop('checked')) nodearch = "a8";

    var profile_json = "";

    if (nodearch == "wsn430") {

        consumption = {
            "current": $('#cb_current_wsn430').prop('checked'),
            "voltage": $('#cb_voltage_wsn430').prop('checked'),
            "power": $('#cb_power_wsn430').prop('checked'),
            "frequency": $('#consumption_frequency_wsn430').val()
        };

        sensor = {
            "luminosity": $('#cb_luminosity_wsn430').prop('checked'),
            "temperature": $('#cb_temperature_wsn430').prop('checked'),
            "frequency": $('#sensor_frequency_wsn430').val()
        };

        radio = {
            "rssi": $('#cb_rssi_wsn430').prop('checked'),
            "frequency": $('#radio_frequency_wsn430').val()
        };

        profile_json = {
            "profilename": $("#profiles_txt_name").val(),
            "nodearch": nodearch,
            "power": $("input[name=or_power_wsn430]:checked").val(),
            "consumption": consumption,
            "sensor": sensor,
            "radio": radio
        };
    //} else if (nodearch == "m3") {
    } else {

        consumption = {
            "current": $('#cb_current_m3').prop('checked'),
            "voltage": $('#cb_voltage_m3').prop('checked'),
            "power": $('#cb_power_m3').prop('checked'),
            "period": $('#consumption_period_m3').val(),
            "average": $('#consumption_average_m3').val()
        };

        radio_mode = $("input[name=radio_mode_m3]:checked").val();
        if (radio_mode == "rssi") {
	        period = $('#radio_period_m3').val();
	        if (radio_num == 0) {
	            num_per_channel = 0;
	        } else {
	            num_per_channel = $("#radio_num_per_channel_m3").val();
	        }
	        radio = {
	            "mode": radio_mode,
	            "channels": $('#radio_channel_m3').val(),
	            "period": period,
	            "num_per_channel": num_per_channel
	        };
        } else {
			channels = $('#radio_sniffer_channel_m3').val();
        	radio = {
    	        "mode": radio_mode,
    	        "channels": [channels]
    	    };
        }

        profile_json = {
            "profilename": $("#profiles_txt_name").val(),
            "nodearch": nodearch,
            "power": $("input[name=or_power_m3]:checked").val(),
            "consumption": consumption
        };
        if (radio_mode != "none") profile_json.radio = radio;

        mobile_mode_m3 = $("input[name=mobile_mode_m3]:checked").val()
        if (mobile_mode_m3 == "predefined") {
            profile_json.mobility = {
	       "type":"predefined",
               "site_name": $("#mobile_site_m3 option:selected").val(),
               "trajectory_name": $("#mobile_trajectory_m3 option:selected").val()
            };
        }
	else if (mobile_mode_m3 == "controlled") {
            profile_json.mobility = {
		"type":"controlled"
	    };
        }
        else {
            delete profile_json.mobility;
        }

    }
    /*} else {

        profile_json = {
            "profilename": $("#profiles_txt_name").val(),
            "nodearch": nodearch
        };

    }*/

    //send edit or create request
    $.ajax({
        type: "POST",
        dataType: "text",
        data: JSON.stringify(profile_json),
        contentType: "application/json; charset=utf-8",
        url: "/rest/profiles/" + profile_json.profilename,
        success: function (data_server) {
            profilename = profile_json.profilename;
            loadProfiles();

            $("#div_error_profiles").removeClass("alert-danger");
            $("#div_error_profiles").addClass("alert-success");
            $("#div_error_profiles").html("Profile saved.");
            $("#div_error_profiles").show();
            setTimeout("$('#div_error_profiles').hide()", 2000);
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            $("#div_error_profiles").show();
            $("#div_error_profiles").html(XMLHttpRequest.responseText);
            $("#div_error_profiles").removeClass("alert-success");
            $("#div_error_profiles").addClass("alert-danger");
        }

    });

    return false;
});

</script>
