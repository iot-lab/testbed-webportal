<?php 
session_start();
$site = $_GET['site'];
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo ucfirst($site); ?> map</title>
<link href="css/bootstrap.css" rel="stylesheet">

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
<script src="js/bootstrap.js"></script>

</head>


<body>
	<div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>
    <div id="maps" style="display:none">
            <div id='selectionbox' style="text-align:center;padding:2px;" class="form-horizontal row">
            	<div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 well" style="padding:10px;box-shadow:none;">
	            	<div class="col-sm-2 col-md-2"><h4>Selected Nodes</h4></div>
	            	<div class="col-sm-6 col-md-6"><div class="row" id="inputs_nodebox"></div></div>
		            <div class="col-sm-4 col-md-4">
		            	<button class="btn btn-primary" id="btnSave" onClick="save()" value="Save">Save</button>
		            	<button class="btn" id="btnAllFree" onClick="allFree()" value="Save">All Free Nodes</button>
		            </div>
	            </div>
            </div>

            <ul id="tab_trails" class="nav nav-tabs">
                <li><a href="#" data-toggle="tab" data-value="all">All nodes</a></li>
            </ul>
		
		    <div id="trails">
				<div id='div3d' style=" height:450px;background-color:#202020;z-index:-1" oncontextmenu="return false;"></div>
		    </div>

            <div ID='infobox' style="text-align:center"></div>
        	<div style="text-align:right"><img src="img/node_alive.png"> Alive - <img src="img/node_down.png"> Down - <img src="img/node_selected.png"> Selected - <img src="img/node_used.png"> Busy</div>
</div>        


<script type="text/javascript">

    var site = <?php echo '"'.$site.'"' ?>;

    var all_nodes;
    var bdd = [];

    $('#tab_trails a:first').tab('show');

    $(document).ready(function(){
       
        $(document).ajaxStart(function(){
            $("#loader").show();
        });
        $(document).ajaxStop(function(){
            $("#loader").hide();
        });
       loadResources();
   });

    function loadData() {
        var inputs = document.getElementsByName("nodebox");
        for (var i=0;i<inputs.length;i++) {
            var archi = inputs[i].id.substring(0,inputs[i].id.indexOf("_"));
        	inputs[i].value=window.opener.document.getElementById(site+"_"+archi+"_list").value;
        }
        parseNodebox();
    }; 

    function save() {
        //window.opener.document.getElementById(site+"_list").value = document.getElementById("nodebox").value;
        var inputs = document.getElementsByName("nodebox");
        for (var i=0;i<inputs.length;i++) {
            var archi = inputs[i].id.substring(0,inputs[i].id.indexOf("_"));
        	window.opener.document.getElementById(site+"_"+archi+"_list").value=inputs[i].value;
        }
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
              
                all_nodes = data;
				bdd['all'] = [];
                var archis= [];

                for(var i=0; i<data.items.length; i++) {
                    var n = [];
                    
                    if(data.items[i].site == site) {
                    	if(archis.indexOf(data.items[i].archi)==-1) { // unknown archi, adding it 
                            archis.push(data.items[i].archi);
                            bdd[data.items[i].archi] = [];
                            $("#tab_trails").append('<li><a href="#" data-toggle="tab" data-value="'+data.items[i].archi+'">'+data.items[i].archi+'</a></li>');
                            $("#inputs_nodebox").append('<div class="col-sm-4 col-md-4 text-right" style="padding:7px 12px;">'+data.items[i].archi+': </div><div class="col-sm-8 col-md-8"><input type="text" placeholder="1-10,24,25" class="form-control col-md-3" style="margin-bottom:4px;" id="'+data.items[i].archi+'_nodebox" name="nodebox"/></div>');
                    	}
                        
                        var nn = data.items[i].network_address;
                        var node_id = nn.substring(0,nn.indexOf("."));
                        
                        n.push(node_id);
                        n.push(parseFloat(data.items[i].x));
                        n.push(parseFloat(data.items[i].y));
                        n.push(parseFloat(data.items[i].z));
                        n.push(data.items[i].uid);
                        n.push(data.items[i].state);
                        n.push(data.items[i].archi);
                        nodes.push(n);

                        bdd[data.items[i].archi].push(n);
                        bdd['all'].push(n);

                    }
                }
        		nodes = bdd['all'];


        	    $('#tab_trails a').click(function (e) {
        	        e.preventDefault();
        	        $(this).tab('show');
        	        var type = $(this).data("value");

        	        nodes = bdd[type];
        	        $("#trails").html("<div id='div3d' style='height:450px;background-color:#202020;z-index:-1' oncontextmenu='return false;'></div>");
        	        init();
        	        parseNodebox();
        	    });

        		console.log(bdd);

                $("#maps").show();
                init();
                loadData();
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                alert(errorThrows);
            }
        });
    }    

    function allFree() {
       var free_list = "";
       for(var i=0; i<all_nodes.items.length; i++) {
           var n = [];
                    
           if(all_nodes.items[i].site == site && all_nodes.items[i].state == "Alive") {
               var nn = all_nodes.items[i].network_address;
               var node_id = nn.substring(4,nn.indexOf("."));
               free_list += node_id + ",";
             }
       }
       $("#nodebox").val(free_list);
       parseNodebox();
       myrender();

    }

    <?php if (isset($_SESSION['basic_value'])) { ?>

        $.ajaxSetup({
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Basic <?php echo $_SESSION['basic_value']; ?>');
            }
        });

    <?php  } ?>

</script>

  </body>
</html>
