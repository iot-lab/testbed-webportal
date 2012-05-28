<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}
?>

<?php include("header.php") ?>

    <div class="container">


<h2>New experiment</h2>


	<form class="well form-horizontal" id="form_new_exp">

		<h3>1. Configure your experiment</h3>

              <div class="control-group">
                <label class="control-label" for="txt_name">Name:</label>
                <div class="controls">
                    <input id="txt_name" type="text" class="input-large" required="required">
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_duration">Duration (minutes):</label>
                <div class="controls">
                    <input id="txt_duration" type="number" class="input-large" required="required">
                </div>
              </div>


	<div class="control-group">
            <label class="control-label">Execution</label>
            <div class="controls">
              <label class="radio">
                <input type="radio" name="ExecutionType" id="optionsRadiosAsap" value="asap" checked="">
                as soon as possible
              </label>
              <label class="radio">
                <input type="radio" name="ExecutionType" id="optionsRadiosScheduled" value="scheduled">
                scheduled
              </label>
            </div>
          </div>


	<h3>2. Select your nodes</h3>

        <div class="control-group">
            <label class="control-label">Resources</label>
            <div class="controls">
              <label class="radio">
                <input type="radio" name="ResourcesType" id="optionsRadiosType" value="type" checked="">
                by type
              </label>

	<!-- by type -->
              <div class="" id="divResourcesType">
                <label class="control-label" for="txt_fixed">Fixed:</label>
                <div class="controls">
                    <input id="txt_fixed" type="text" class="input-large">
                </div>
              </div>



              <label class="radio">
                <input type="radio" name="ResourcesType" id="optionsRadiosMaps" value="physical">
                physical
              </label>

	        <div class="" id="divResourcesMap">
                	
                        <p><a href="#" id="str_maps">Strasbourg Maps</a> <input id="str_list" value=""/></p>
                        <p><a href="#" id="gre_maps">Grenoble Maps</a> <input id="gre_list" value=""/></p>
        	</div>

          </div>
	</div>


	<button id="btn_submit" class="btn btn-primary" type="submit">Next</button>

    </form>


	<?php include('footer.php') ?>

    </div>


        

    <script type="text/javascript">
        
        $(document).ready(function(){
		

		$("#divResourcesMap").hide();
		$("input[name=ResourcesType]").change(function () {
			if($(this).val() == "physical") {
				$("#divResourcesType").hide();
				$("#divResourcesMap").show();
			}
			else {
				$("#divResourcesType").show();
				$("#divResourcesMap").hide();
			}

		});

		$("#str_maps").click(function(){
			window.open('str_maps.php', '', 'resizable=no, location=no, width=500, height=500, menubar=no, status=no, scrollbars=no, menubar=no');
		});

                $("#gre_maps").click(function(){
                        window.open('gre_maps.php', '', 'resizable=no, location=no, width=500, height=500, menubar=no, status=no, scrollbars=no, menubar=no');
                });

        });
 

	$("#form_new_exp").bind("submit",function(){

                var exp_json = {
                    "type":$("input[name=ResourcesType]:checked").val(),
                    "name":$("#txt_name").val(),
                    "duration":parseInt($("#txt_duration").val())
                };
	
		//var my_nodes = new Array();
		var my_nodes = "";

		if($("#str_list").val() != "")
		{

			var str_all = parseNodebox($("#str_list").val());
			for(i=0;i<str_all.length;i++) {
				my_nodes += "node"+str_all[i]+".devstras.senslab.info,";
			}
		}

		if($("#gre_list").val() != "")
                {
                	var gre_all = parseNodebox($("#gre_list").val());
                	for(i=0;i<gre_all.length;i++) {
				my_nodes += "node"+gre_all[i]+".devgrenoble.senslab.info,";
                	}
		}

                exp_json.nodes = [];
		exp_json.nodes.push(my_nodes);
		console.log(exp_json);

		if(typeof localStorage!='undefined') {
			localStorage.setItem("exp_json",JSON.stringify(exp_json));
		} else {
		}

		window.location.href="new_experiment2.php";

		return false;
	})



// expand a list of nodes containing dash intervals
// 1-3,5,9 -> 1,2,3,5,9
function expand(factExp) {
    exp = [];
    for (i = 0; i < factExp.length; i++) {
        dashExpression = factExp[i].split("-");
        if (dashExpression.length == 2) {
            for (j = parseInt(dashExpression[0]); j < (parseInt(dashExpression[1]) + 1); j++)
                exp.push(j);
        } else exp.push(parseInt(factExp[i]));
    }
    exp.sort(sortfunction);
    for (var i = 1; i < exp.length; i++) { if (exp[i] == exp[i - 1]) { exp.splice(i--, 1); } }
    return exp;
}

function parseNodebox(input) {
    return expand(input.split(","));
}

function sortfunction(a, b) {
    return (a - b) //causes an array to be sorted numerically and ascending
}


  
        
    </script>


  </body>
</html>
