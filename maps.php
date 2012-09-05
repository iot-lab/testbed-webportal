<!DOCTYPE html>
<html>
<head>
<title>Maps</title>
</head>
<?php 
session_start();
$site = $_GET['site'];
?>


<body>
<script src="js/jquery.js"></script>

<style type="text/css">
body {
  margin: 10px;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 13px;
  line-height: 18px;
  color: #333333;
  background-color: #ffffff;
}
</style>


    <div class="row" id="maps">
        <div class="span8 offset2">
            <div id='selectionbox' style="text-align:center;padding:2px">
            Selected Nodes <input type="text" placeholder="1-10,24,25" class="input-medium" id='nodebox'/>
            <button class="btn" id="btnSave" onClick="save()" value="Save"/>Save</button>
            </div>

            <div ID='div3d' style=" height:400px;background-color:#202020;z-index:-1" oncontextmenu="return false;"></div>

            <div ID='infobox' style="text-align:center"></div>
        </div>
</div>        

<script type="text/javascript" src="viewer3Djs/Three.js"></script>
<script type="text/javascript" src="viewer3Djs/viewer3D.js"></script>

<script type="text/javascript">

    var site = <?php echo '"'.$site.'"' ?>;

    $(document).ready(function(){
        $.ajax({
            url: "/rest/experiments?resources",
            type: "GET",
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            data: "",
            success:function(data){
                
                for(i=0; i<data.resources.length; i++) {
                    var n = [];
                    
                    if(data.resources[i].site == site && data.resources[i].mobile == 0) {
                        
                        var nn = data.resources[i].network_address;
                        var node_id = nn.substring(4,nn.indexOf("."));
                        
                        n.push(parseInt(node_id));
                        n.push(parseFloat(data.resources[i].x));
                        n.push(parseFloat(data.resources[i].y));
                        n.push(parseFloat(data.resources[i].z));
                        n.push(data.resources[i].uid);
                        n.push(data.resources[i].state);
                        nodes.push(n);
                    }
                }
                
                init();
                loadData();
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                alert(errorThrows);
            }
        });
    });


    function loadData() {
        var list = document.getElementById("nodebox");
        list.value = window.opener.document.getElementById(site+"_list").value;
        parseNodebox();
    }; 

    function save() {
        window.opener.document.getElementById(site+"_list").value = document.getElementById("nodebox").value;
        window.close();
    }


    <?php if (isset($_SESSION['basic_value'])) { ?>

        $.ajaxSetup({
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Basic <?php echo $_SESSION['basic_value']; ?>')
            }
        });

    <?php  } ?>

</script>

  </body>
</html>
