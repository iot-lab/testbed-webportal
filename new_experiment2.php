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

	<h3>3. Configure your nodes</h3>

	<p>
	<select id="all_nodes" size="15" multiple></select>
        <select id="all_profils" size="15">
		<option value="profil1">profil1</option>
                <option value="profil2">profil2</option>
	</select>
        <select id="all_firmwares" size="15">
		<option value="firmware1">firmware1</option>
                <option value="firmware2">firmware2</option>
	</select>

	</p>

	<p>
	
	<button id="btn_assoc" class="btn">Associate</button>
        <!-- <button id="btn_clear" class="btn btn-danger">Clear (TODO)</button> -->
	<button id="btn_submit" class="btn btn-primary" type="submit">Submit</button>
	</p>

	<p>
		<table style="width:500px"class="table table-striped table-bordered table-condensed">
		<thead><tr><th>node</th><th>profil</th><th>firmware</th></tr></thead>
		<tbody id="my_assoc"></tbody>
		</table>
	</p>

    </form>


	<?php include('footer.php') ?>

    </div>


        

    <script type="text/javascript">
       
	var exp_json_tmp = localStorage.getItem("exp_json");
	var exp_json = JSON.parse(exp_json_tmp);

        var withAssoc = false;

        $(document).ready(function(){
		

	var selected_nodes = exp_json.nodes[0].split(",");
	for(i=0;i<selected_nodes.length;i++)
	{
		if(selected_nodes[i] != "")
			$("#all_nodes").append(new Option(selected_nodes[i],selected_nodes[i] , true, true));
	}


        $("#form_new_exp").bind('submit',function(){
	       console.log(JSON.stringify(exp_json));

	var url = "/rest/experiments?body";
	if(withAssoc)
		 url = "/rest/experiments";

        $.ajax({
            url: url,
            type: "POST",
            dataType: "text",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(exp_json),
            success:function(data){
                 alert("ok: " + data );
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
		 alert("error: " + errorThrows );
            }
        });

	       return false;
        });

	$("#btn_assoc").click(function(){

                if(!withAssoc) {
			exp_json.profileassociations = [];
        		exp_json.firmwareassociations = [];
			withAssoc = true;
		}

		var nodes_set = $("#all_nodes").val();
		var profil_set = $("#all_profils").val();
                var firmware_set = $("#all_firmwares").val();

		if(nodes_set == null || profil_set == null || firmware_set == null)
			return false;

		$("#all_nodes option:selected").remove();
	
		var nodes_str = ""
		for(i=0;i<nodes_set.length;i++)
		{
			nodes_str += nodes_set[i]+",";
			$("#my_assoc").append("<tr><td>" + nodes_set[i] + "</td><td>" + profil_set + "</td><td>" + firmware_set + "</td></tr>");
		}



		var find = false;
		//if profil already exist in the table
		if(exp_json.profileassociations != null)
		{
			for(i=0;i<exp_json.profileassociations.length;i++) {
				if(exp_json.profileassociations[i].profilename == profil_set)
				{
					exp_json.profileassociations[i].nodes += nodes_str;
					find = true;
				}
			}
		}		

		if(!find) {
			exp_json.profileassociations.push({"profilename":profil_set,"nodes":nodes_str});
		}
		

                find = false;
                //if firmware already exist in the table
                if(exp_json.firmwareassociations != null)
                {
                        for(i=0;i<exp_json.firmwareassociations.length;i++) {
                                if(exp_json.firmwareassociations[i].firmwarename == firmware_set)
                                {
                                        exp_json.firmwareassociations[i].nodes += nodes_str;
                                        find = true;
                                }
                        }
                }

                if(!find) {
                        exp_json.firmwareassociations.push({"firmwarename":firmware_set,"nodes":nodes_str});
                }


		console.log(JSON.stringify(exp_json)); 
		return false;
	});

	});
        
    </script>


  </body>
</html>
