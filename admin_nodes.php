<?php 
session_start();

if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: .");
    exit();
}

include("header.php") ?>

    <div class="container" text-align="top">
    
   
<div class="row">

    <h2>Manage nodes</h2>

    <div id="allNodes">
        
        
        <table class="table table-striped table-bordered table-condensed" style="width:500px" id="tbl_nodes">
        <thead>
            <tr>
                <th></th>
                <th>node</th>
                <th>state</th>
            </tr>
        </thead>
        <tbody id="nodesRow">
        </tbody>
        </table>
        
        <p><a href="javascript:selectAll();">Select All</a> - <a href="javascript:unSelectAll();">Unselect All</a></p>
        
        
        <form id="frm_actions">

            <b>Actions on selected nodes: </b>
            
            <select id="part" class="input-medium">
                <option value="opennodes">Open Node</option>
                <option value="controlnodes">Control Node</option>
                <option value="gatewaynodes">Gateway</option>
            </select>
            
            <select id="action" class="input-small">
                <option value="start">Start</option>
                <!-- <option value="start" data-battery="battery">Start (battery)</option> -->
                <option value="stop">Stop</option>
                <option value="reset">Reset</option>
                <!-- <option value="update">Update</option> -->
            </select>
            
            <button id="btn_send" class="btn" type="submit">Send</button>
            
            <div id="firmware" style="display:none">firmware: <input type="file" id="files" name="files[]" multiple /></div>
            
            <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
            <div id="state" class="alert" style="display:none"></div>
            
        </form>
        
    </div>
</div>


    <?php include('footer.php') ?>

    <script type="text/javascript">
        
        var json_exp = [];
        var state = "";
        var binary = "";
        var boundary = "AaB03x";
        
        $(document).ready(function(){

            $("#loader").ajaxStart(function(){
                $(this).show();
            });
            $("#loader").ajaxStop(function(){
                $(this).hide();
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
            
            $("#nodesRow").html("");
            $.ajax({
                url: "/rest/experiments?resources",
                type: "GET",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                data: "",
                success:function(data){
                    
                    for(i=0; i<data.length; i++) {
                        $("#nodesRow").append("<tr><td>"+
                        "<input type=\"checkbox\" name=\"option1\" value=\""+data[i].network_address+"\"></td>"+
                        "<td>"+data[i].network_address+"</td>"+
                        "<td>"+data[i].state+"</td></tr>");
                    }
                    
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert(errorThrows);
                }
            });    
            
            
            
            /* actions on nodes */
            $("#frm_actions").bind("submit",function(e){
                e.preventDefault();
               
                $("#state").hide();
               
                var command = $("#action option:selected").val();
                
                var part = $("#part option:selected").val()
                
                var battery = "";
                if($("#action option:selected").attr("data-battery") == "battery") {
                    battery = "&battery=true";
                }
                
                
                var lnodes = [];
                $("#tbl_nodes :checked").each(function(){
                    lnodes.push($(this).val());
                });
                
                console.log(lnodes);
                
                if($("#action").val() == "update") {
                   /* //JSON
                    var datab = "";
                    datab += "--" + boundary + '\r\n';
                    datab += 'Content-Disposition: form-data; name="test.json"; filename="test.json"\r\n';
                    datab += 'Content-Type: application/json\r\n\r\n';
                    datab += JSON.stringify(lnodes) + '\r\n\r\n';
                    //datab += "--" + boundary + '\r\n';

                    //firmware
                    datab += "--" + boundary + '\r\n';
                    datab += 'Content-Disposition: form-data; name="test.hex"; filename="test.hex"\r\n';
                    datab += 'Content-Type: text/plain\r\n\r\n';
                    datab += binary + '\r\n';


                    //add json
                    datab += "--" + boundary + '--';

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: datab,
                        url: "/rest/experiment/nodes?update",
                        contentType: "multipart/form-data; boundary="+boundary,
                        
                        success: function (data) {
                            $("#state").html("OK: " + JSON.stringify(data.success));
                            $("#state").removeClass("alert-error");
                            $("#state").addClass("alert-success");
                            $("#state").show();
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#state").html(textStatus);
                            $("#state").removeClass("alert-success");
                            $("#state").addClass("alert-error");
                            $("#state").show();
                        }
                    });*/
                }
                else {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: JSON.stringify(lnodes),
                        contentType: "application/json; charset=utf-8",
                        url: "/rest/admin/"+part+"?"+command+""+battery,
                        success: function (data) {
                            $("#state").html("OK: " + JSON.stringify(data.success));
                            $("#state").removeClass("alert-error");
                            $("#state").addClass("alert-success");
                            $("#state").show();
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrows) {
                            $("#state").html(textStatus);
                            $("#state").removeClass("alert-success");
                            $("#state").addClass("alert-error");
                            $("#state").show();
                        }
                    });
                }
               // return false;
            });
            
            //file upload event
            document.getElementById('files').addEventListener('change', handleFileSelect, false);
    });

    
    function selectAll() {
        $("#tbl_nodes :checkbox").each(function(){
            $(this).attr('checked', true);
        });
    }
    
    function unSelectAll() {
        $("#tbl_nodes :checkbox").each(function(){
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
                    binary = e.target.result
                };
            })(f);

            reader.readAsText(f);
        }
    }

    </script>

  </body>
</html>
