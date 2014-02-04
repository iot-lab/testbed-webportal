<?php
session_start();

if(!isset($_SESSION['is_auth']) || !$_SESSION['is_auth']) {
        header("location: .");
        exit();
}

include("header.php");
?>

<div class="container" text-align="top">

	<div class="row">
		<div class="col-md-12">
			<h2>Manage Profiles</h2>
		</div>
	</div>

	<div class="row">
		<?php include 'scripts/profiles.php'; ?>
	</div>

</div> <!-- container -->   
<script type="text/javascript">
    
    $(document).ready(function(){

        $("#profiles").addClass("active");
        $("#profiles2").addClass("active");
    });


</script>
<?php include('footer.php') ?>
