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
                <h2>Manage profiles</h2>
                <div class="alert" id="txt_notif">
                    <button class="close" data-dismiss="alert">×</button>
                    <p id="txt_notif_msg"></p>
                </div>
                </div>
            </div>
            
            
            <div class="row">
                <div class="span3">
                    <select id="my_profiles" size="15"></select>
                    <p>
                    <button class="btn btn-danger" id="btn_delete" href="#">Delete</button>
                    </p>
                </div>
            
                <div class="span8">
             
                    <form class="well form-horizontal" id="form_part">

                        <div class="control-group">
                            <label class="control-label" for="txt_name">Name:</label>
                            <div class="controls">
                                <input id="txt_name" type="text" class="input-large" required="required">
                            </div>
                        </div>

                    <div class="control-group">
                        <label class="control-label">Power mode</label>
                        <div class="controls">
                          <label class="radio">
                            <input type="radio" name="or_power" id="or_power_dc" value="dc" checked="">
                            dc
                          </label>
                          <label class="radio">
                            <input type="radio" name="or_power" id="or_power_battery" value="battery">
                            battery
                          </label>
                        </div>
                      </div>


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
                      <select id="consumption_frequency">
                        <option value="70">70</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="5000">5000</option>
                      </select>
                    </div>
                  </div>



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
                      <select id="sensor_frequency">
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                        <option value="30000">30000</option>
                      </select>
                    </div>
                  </div>


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
                      <select id="radio_frequency">
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                      </select>
                    </div>
                  </div>


                   <button id="btn_submit" class="btn btn-primary" type="submit">Save</button>

                    </form>
                </div> 
            </div>
    
        
        <?php include('footer.php') ?>
        
</div>
        
        
        <script type="text/javascript">


            var my_profiles = [];

            /* ************ */
            /*   on ready   */
            /* ************ */
            $(document).ready(function () {

                $("#txt_notif").hide();


                //get all profiles
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
                        for(i = 0; i < my_profiles.length; i++) {
                            $("#my_profiles").append(new Option(my_profiles[i].profilename,my_profiles[i].profilename));
                        }
                        
                        //bind onClick event
                        $("#my_profiles").change(loadProfile);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrows) {
                        $("#txt_notif_msg").html(errorThrows);
                        $("#txt_notif").show();
                        $("#txt_notif").removeClass("alert-success");
                        $("#txt_notif").addClass("alert-error");
                    }
                });


                //delete selected profile
                $("#btn_delete").click(function(){
                    var profile_name = $("#my_profiles").val();
                    $.ajax({
                        type: "DELETE",
                        dataType: "text",
                        contentType: "application/json; charset=utf-8",
                        url: "/rest/profile/"+profile_name,
                        success: function (data_server) {
                            
                            $("#my_profiles option:selected").remove();
                            
                            $("#txt_notif_msg").html("Delete ok");
                            $("#txt_notif").show();
                            $("#txt_notif").removeClass("alert-error");
                            $("#txt_notif").addClass("alert-success");
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#txt_notif_msg").html(errorThrows);
                            $("#txt_notif").show();
                            $("#txt_notif").removeClass("alert-success");
                            $("#txt_notif").addClass("alert-error");
                        }
                    });
                    
                });
            });

            /* ************* */
            /* submit part */
            /* ************ */
            $("#form_part").bind("submit", function () {

               var regExp = /^[0-9A-z]*$/; ;
               if(regExp.test($("#txt_name").val()) == false){
                   alert("You must set a profile name with only word characters [0-9A-Za-z_]");
                   return false;
               }


                consemptium = {
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

                var profile_json = {
                    "profilename":$("#txt_name").val(),
                    "power":$("input[name=or_power]:checked").val(),
                    "consemptium":consemptium,
                    "sensor":sensor,
                    "radio":radio
                };
                

                //send edit or create request
                $.ajax({
                    type: "POST",
                    dataType: "text",
                    data: JSON.stringify(profile_json),
                    contentType: "application/json; charset=utf-8",
                    url: "/rest/profile",
                    success: function (data_server) {
                        
                        var edit = false;
                        var index = -1;
                        //check if profile already exist
                        for(i = 0; i < $("#my_profiles option").length; i++) {
                            if($($("#my_profiles option")[i]).val() == profile_json.profilename) {
                                edit = true;
                                index = i;
                                break;
                            }
                        }
                        
                        if(!edit) {
                            $("#txt_notif_msg").html("Profile created");
                            $("#my_profiles").append(new Option(profile_json.profilename,profile_json.profilename));
                            
                            my_profiles.push(profile_json);
                        }
                        else {
                             $("#txt_notif_msg").html("Profile edited");
                             my_profiles[index] = profile_json;
                             
                        }

                        $("#txt_notif").show();
                        $("#txt_notif").removeClass("alert-error");
                        $("#txt_notif").addClass("alert-success");
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrows) {
                        $("#txt_notif_msg").html(errorThrows);
                        $("#txt_notif").show();
                        $("#txt_notif").removeClass("alert-success");
                        $("#txt_notif").addClass("alert-error");
                    }
                });
            
                return false;
            });

            
            
            /* ********************** */
            /* fill form with profile */
            /* ********************** */
            function loadProfile() {
                
		var profilename = $(this).val();

                var index = -1;
                
                for(i = 0; i < my_profiles.length && index == -1; i++) {
                    if(my_profiles[i].profilename == profilename) {
                        index = i;
                        break;
                    }
                }
                
                if(index != -1) {
                    $("#txt_name").val(my_profiles[index].profilename);
                    $('#consumption_frequency').val(my_profiles[i].consemptium.frequency);
                    $('#sensor_frequency').val(my_profiles[i].sensor.frequency);
                    $('#radio_frequency').val(my_profiles[i].radio.frequency);
                    
                    $("input[name='or_power']").val([my_profiles[i].power]);
                    $("#cb_current").attr("checked",my_profiles[i].consemptium.current);
                    $("#cb_voltage").attr("checked",my_profiles[i].consemptium.votage);
                    $("#cb_power").attr("checked",my_profiles[i].consemptium.power);
                    
                    $("#cb_temperature").attr("checked",my_profiles[i].sensor.temperature);
                    $("#cb_luminosity").attr("checked",my_profiles[i].sensor.luminosity);
                    
                    $("#cb_rssi").attr("checked",my_profiles[i].radio.rssi);
                }
                
            }
            
            
        </script>
       
        </body>
</html>
