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


	<select id="all_nodes" size="15" multiple></select>
        <select id="all_profils" size="15">
		<option value="profil1">profil1</option>
	</select>
        <select id="all_firmwares" size="15">
		<option value="firmware1">firmware1</option>
	</select>

	<button id="btn_assoc" class="btn">Associate</button>


	<h3>4. Validate</h3>

	<button id="btn_submit" class="btn btn-primary" type="submit">Submit</button>
	<p id="my_assoc"></p>

    </form>


	<?php include('footer.php') ?>

    </div>


        

    <script type="text/javascript">
        
        $(document).ready(function(){
		

	var exp_json_tmp = localStorage.getItem("exp_json");
	var exp_json = JSON.parse(exp_json_tmp);

	var selected_nodes = exp_json.nodes.split(",");
	for(i=0;i<selected_nodes.length;i++)
	{
		if(selected_nodes[i] != "")
			$("#all_nodes").append(new Option(selected_nodes[i],selected_nodes[i] , true, true));
	}


	$("#btn_assoc").click(function(){

		var nodes_set = $("#all_nodes").val();
		var profil_set = $("#all_profils").val();
                var firmware_set = $("#all_firmwares").val();

		if(nodes_set == null || profil_set == null || firmware_set == null)
			return false;

		$("#all_nodes option:selected").remove();

		for(i=0;i<nodes_set.length;i++)
		{
			$("#my_assoc").append("<li>" + nodes_set[i] + " > " + profil_set + " > " + firmware_set + "</li>");
		}


		return false;
	});


	});
        
    </script>


  </body>
</html>
