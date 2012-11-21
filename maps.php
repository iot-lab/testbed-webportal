<!DOCTYPE html>
<html>
<head>
<title>Maps</title>
<?php 
session_start();
$site = $_GET['site'];
?>


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


<script type="text/javascript" src="viewer3Djs/Three.js"></script>
<script type="text/javascript" src="viewer3Djs/viewer3D.js"></script>

</head>


<body>
    <div class="row" id="maps">
        <div class="span8 offset2">
            <div id='selectionbox' style="text-align:center;padding:2px">
            Selected Nodes <input type="text" placeholder="1-10,24,25" class="input-medium" id='nodebox'/>
            <button class="btn" id="btnSave" onClick="save()" value="Save"/>Save</button>
            </div>

            <div ID='div3d' style=" height:500px;background-color:#202020;z-index:-1" oncontextmenu="return false;"></div>

            <div ID='infobox' style="text-align:center"></div>
        <div style="text-align:right"><img src="img/node_alive.png"> Alive - <img src="img/node_down.png"> Down - <img src="img/node_selected.png"> Selected - <img src="img/node_used.png"> Busy</div>
        </div>
</div>        


<script type="text/javascript">

    var site = <?php echo '"'.$site.'"' ?>;

    $(document).ready(function(){
       loadResources();
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


    function loadResources() {
        $.ajax({
            url: "/rest/experiments?resources",
            type: "GET",
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            data: "",
            success:function(data){
              
                for(i=0; i<data.length; i++) {
                    var n = [];
                    
                    if(data[i].site == site) {
                        
                        var nn = data[i].network_address;
                        var node_id = nn.substring(4,nn.indexOf("."));
                        
                        n.push(parseInt(node_id));
                        n.push(parseFloat(data[i].x));
                        n.push(parseFloat(data[i].y));
                        n.push(parseFloat(data[i].z));
                        n.push(data[i].uid);
                        n.push(data[i].state);
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
