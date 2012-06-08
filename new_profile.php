<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}
?>

<?php include("header.php") ?>

        <div class="container">
            <h2>New profile</h2>
            
            <div class="alert" id="txt_notif">
                <button class="close" data-dismiss="alert">×</button>
                <p id="txt_notif_msg"></p>
            </div>
            
            
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
                <option>100</option>
                <option>500</option>
                <option>1000</option>
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
                <option>100</option>
                <option>500</option>
                <option>1000</option>
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
                <option>100</option>
                <option>500</option>
                <option>1000</option>
              </select>
            </div>
          </div>


                <button id="btn_submit" class="btn btn-primary" type="submit">Create</button>

            </form>
            
            
        <?php include('footer.php') ?>
        
        </div>
        
        
        <script type="text/javascript">

            /* ************ */
            /*   on ready   */
            /* ************ */
            $(document).ready(function () {

                $("#txt_notif").hide();


            });

            /* ************* */
            /* submit part */
            /* ************ */
            $("#form_part").bind("submit", function () {

                consemptium = {
                    "current":$('#cb_current').is(':checked'),
                    "voltage":$('#cb_voltage').is(':checked'),
                    "power":$('#cb_power').is(':checked'),
                    "frequency":$('#consumption_frequency').val(),
                };

                sensor = {
                    "luminosity":$('#cb_luminosity').is(':checked'),
                    "temperature":$('#cb_temperature').is(':checked'),
                    "frequency":$('#sensor_frequency').val(),
                };

                radio = {
                    "rssi":$('#cb_rssi').is(':checked'),
                    "frequency":$('#radio_frequency').val(),
                };

                var profile_json = {
                    "profilename":$("#txt_name").val(),
                    "power":$("input[name=or_power]:checked").val(),
                    "consemptium":consemptium,
                    "sensor":sensor,
                    "radio":radio
                };
                
                console.log(profile_json);

                $.ajax({
                    type: "POST",
                    dataType: "text",
                    data: profile_json,
                    contentType: "application/json; charset=utf-8",
                    url: "/rest/TODO",
                    success: function (data_server) {
                        $("#txt_notif_msg").html(data_server);
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

            
        </script>
       
        </body>
</html>
